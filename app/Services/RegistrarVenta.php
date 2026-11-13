<?php

namespace App\Services;

use App\Models\Cliente;
use App\Models\DetalleVenta;
use App\Models\Pago;
use App\Models\Producto;
use App\Models\User;
use App\Models\Venta;
use Illuminate\Support\Facades\DB;
use RuntimeException;

/**
 * Registra una venta completa en UNA sola transacción:
 *   cliente -> venta -> detalle -> descuento de stock -> pago
 * Si algo falla (por ejemplo, alguien compró el último ejemplar un segundo antes),
 * no se guarda NADA y se lanza una RuntimeException con un mensaje para el cliente.
 *
 * Se usa para la compra web y se podrá reutilizar para la venta en tienda.
 */
class RegistrarVenta
{
    /**
     * @param  array  $carrito  [producto_id => cantidad]
     * @param  array  $datos    datos validados de CompraRequest
     */
    public function desdeTiendaWeb(User $usuario, array $carrito, array $datos): Venta
    {
        if (empty($carrito)) {
            throw new RuntimeException('Tu carrito está vacío.');
        }

        return DB::transaction(function () use ($usuario, $carrito, $datos) {

            // 1) Productos bloqueados mientras dura la compra (nadie más puede tomarlos)
            $productos = Producto::whereIn('id', array_keys($carrito))
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            $lineas   = [];
            $subtotal = 0;
            foreach ($carrito as $id => $cantidad) {
                $producto = $productos->get($id);
                $cantidad = (int) $cantidad;

                if (! $producto || ! $producto->estado) {
                    throw new RuntimeException('Un producto de tu carrito ya no está a la venta. Revisa tu carrito.');
                }
                if ($cantidad < 1 || $cantidad > $producto->stock) {
                    throw new RuntimeException('Solo quedan ' . $producto->stock . ' unidad(es) de "' . $producto->nombre . '". Ajusta la cantidad en tu carrito.');
                }

                $importe   = round($cantidad * $producto->precio_venta, 2);
                $subtotal += $importe;
                $lineas[]  = [$producto, $cantidad, $importe];
            }
            $subtotal = round($subtotal, 2);

            // 2) Montos (siempre calculados aquí, nunca recibidos del navegador)
            $esDelivery = $datos['entrega'] === 'delivery';
            $envio      = $esDelivery ? Carrito::costoEnvio($subtotal) : 0.0;
            $total      = round($subtotal + $envio, 2);
            $aPagarYa   = $esDelivery ? $total : round($total * config('libreria.adelanto_recojo'), 2);

            // 3) Cliente (se crea o se actualiza su ficha)
            $cliente = $this->obtenerCliente($usuario, $datos);

            // 4) Venta (cabecera)
            $venta = Venta::create([
                'tipo_comprobante'   => $datos['comprobante'],
                'numero_comprobante' => $this->siguienteNumero($datos['comprobante']),
                'cliente_id'         => $cliente->id,
                'empleado_id'        => null,                 // lo asigna el personal al atender el pedido
                'fecha_venta'        => now(),
                'subtotal'           => $subtotal,
                'descuento'          => 0,
                'costo_envio'        => $envio,
                'total'              => $total,
                'estado'             => 'pendiente',          // pasa a "pagada" cuando se cobra todo
                'canal'              => 'web',
                'entrega'            => $datos['entrega'],
                'direccion_envio'    => $esDelivery ? $datos['direccion'] : null,
                'referencia_envio'   => $esDelivery ? ($datos['referencia'] ?? null) : null,
                'telefono_contacto'  => $datos['telefono'],
                'estado_pedido'      => 'por_confirmar',
            ]);

            // 5) Detalle + descuento de stock
            foreach ($lineas as [$producto, $cantidad, $importe]) {
                DetalleVenta::create([
                    'venta_id'        => $venta->id,
                    'producto_id'     => $producto->id,
                    'cantidad'        => $cantidad,
                    'precio_unitario' => $producto->precio_venta,
                    'subtotal'        => $importe,
                ]);
                $producto->decrement('stock', $cantidad);
            }

            // 6) Pago (adelanto del 50 % si recoge en tienda, o el total si es envío)
            Pago::create([
                'venta_id'         => $venta->id,
                'metodo_pago'      => $datos['metodo_pago'],
                'monto'            => $aPagarYa,
                'numero_operacion' => $datos['numero_operacion'],
                'fecha_pago'       => now(),
            ]);

            return $venta;
        });
    }

    /**
     * Busca la ficha del cliente por su documento; si no existe, la crea.
     * La ficha queda enlazada a la cuenta del usuario (clientes.user_id).
     */
    private function obtenerCliente(User $usuario, array $datos): Cliente
    {
        $esFactura = $datos['comprobante'] === 'factura';

        $cliente = Cliente::where('tipo_documento', $datos['tipo_documento'])
            ->where('numero_documento', $datos['numero_documento'])
            ->lockForUpdate()
            ->first();

        if ($cliente && $cliente->user_id && $cliente->user_id !== $usuario->id) {
            throw new RuntimeException('Ese documento ya está registrado con otra cuenta. Si es tuyo, comunícate con la librería.');
        }

        $valores = [
            'user_id'   => $usuario->id,
            'nombres'   => $datos['nombres'],
            'apellidos' => $esFactura ? null : ($datos['apellidos'] ?? null),
            'telefono'  => $datos['telefono'],
        ];
        if ($datos['entrega'] === 'delivery') {
            $valores['direccion'] = $datos['direccion'];
        }

        // El correo de la cuenta se guarda solo si ninguna otra ficha lo usa (es UNIQUE)
        $correoLibre = ! Cliente::where('email', $usuario->email)
            ->when($cliente, fn ($q) => $q->where('id', '!=', $cliente->id))
            ->exists();
        if ($correoLibre) {
            $valores['email'] = $usuario->email;
        }

        if ($cliente) {
            $cliente->update($valores);
            return $cliente;
        }

        return Cliente::create($valores + [
            'tipo_documento'   => $datos['tipo_documento'],
            'numero_documento' => $datos['numero_documento'],
        ]);
    }

    /**
     * Siguiente número de comprobante:  B001-00000010  o  F001-00000002
     */
    private function siguienteNumero(string $tipo): string
    {
        $serie = $tipo === 'factura' ? 'F001' : 'B001';

        $ultimo = Venta::where('numero_comprobante', 'like', $serie . '-%')
            ->lockForUpdate()
            ->orderByDesc('numero_comprobante')
            ->value('numero_comprobante');

        $correlativo = $ultimo ? ((int) substr($ultimo, 5)) + 1 : 1;

        return sprintf('%s-%08d', $serie, $correlativo);
    }
}
