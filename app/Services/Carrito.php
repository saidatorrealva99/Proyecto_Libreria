<?php

namespace App\Services;

use App\Models\Producto;
use Illuminate\Support\Collection;

/**
 * Carrito de compras guardado en la SESIÓN del visitante.
 * En la sesión solo se guarda:  [producto_id => cantidad]
 * Los precios y el stock se leen siempre de la base de datos (nunca de la sesión).
 */
class Carrito
{
    private const CLAVE = 'carrito';

    // ---------------------------------------------------------------
    //  Lectura
    // ---------------------------------------------------------------

    /**
     * Productos del carrito con su cantidad y subtotal.
     * Quita los que ya no existen o están descontinuados y ajusta
     * la cantidad si el stock bajó.
     */
    public function items(): Collection
    {
        $contenido = session(self::CLAVE, []);
        if (empty($contenido)) {
            return collect();
        }

        $productos = Producto::with(['categoria', 'autor'])
            ->whereIn('id', array_keys($contenido))
            ->where('estado', 1)
            ->get()
            ->keyBy('id');

        $limpio = [];
        $items  = collect();

        foreach ($contenido as $id => $cantidad) {
            $producto = $productos->get($id);
            if (! $producto || $producto->stock < 1) {
                continue;                                   // ya no se puede vender
            }
            $cantidad = min((int) $cantidad, $producto->stock);
            $limpio[$id] = $cantidad;

            $items->push((object) [
                'producto' => $producto,
                'cantidad' => $cantidad,
                'subtotal' => round($cantidad * $producto->precio_venta, 2),
            ]);
        }

        session([self::CLAVE => $limpio]);                  // guarda la versión corregida

        return $items;
    }

    /** Contenido tal cual: [producto_id => cantidad] (ya corregido por stock) */
    public function contenido(): array
    {
        $this->items();                                     // limpia productos agotados o descontinuados
        return session(self::CLAVE, []);
    }

    public function cantidad(): int
    {
        return (int) array_sum(session(self::CLAVE, []));
    }

    public function cantidadDe(int $productoId): int
    {
        return (int) (session(self::CLAVE, [])[$productoId] ?? 0);
    }

    public function estaVacio(): bool
    {
        return $this->cantidad() === 0;
    }

    public function subtotal(): float
    {
        return round($this->items()->sum('subtotal'), 2);
    }

    /** Costo de envío a domicilio según el subtotal (gratis desde el monto configurado) */
    public static function costoEnvio(float $subtotal): float
    {
        return $subtotal >= config('libreria.envio_gratis_desde') ? 0.0 : (float) config('libreria.costo_envio');
    }

    // ---------------------------------------------------------------
    //  Cambios (devuelven un mensaje de error o null si todo salió bien)
    // ---------------------------------------------------------------

    public function agregar(Producto $producto, int $cantidad = 1): ?string
    {
        if (! $producto->estado) {
            return 'Este producto ya no está a la venta.';
        }
        if ($producto->stock < 1) {
            return 'Lo sentimos, "' . $producto->nombre . '" está agotado.';
        }

        $contenido = session(self::CLAVE, []);
        $nueva = ($contenido[$producto->id] ?? 0) + max(1, $cantidad);

        if ($nueva > $producto->stock) {
            $contenido[$producto->id] = $producto->stock;
            session([self::CLAVE => $contenido]);
            return 'Solo hay ' . $producto->stock . ' unidad(es) de "' . $producto->nombre . '". Agregamos el máximo disponible.';
        }

        $contenido[$producto->id] = $nueva;
        session([self::CLAVE => $contenido]);

        return null;
    }

    public function actualizar(Producto $producto, int $cantidad): ?string
    {
        if ($cantidad < 1) {
            $this->quitar($producto);
            return null;
        }

        $contenido = session(self::CLAVE, []);
        $mensaje = null;

        if ($cantidad > $producto->stock) {
            $cantidad = $producto->stock;
            $mensaje  = 'Solo hay ' . $producto->stock . ' unidad(es) de "' . $producto->nombre . '".';
        }

        $contenido[$producto->id] = $cantidad;
        session([self::CLAVE => $contenido]);

        return $mensaje;
    }

    public function quitar(Producto $producto): void
    {
        $contenido = session(self::CLAVE, []);
        unset($contenido[$producto->id]);
        session([self::CLAVE => $contenido]);
    }

    public function vaciar(): void
    {
        session()->forget(self::CLAVE);
    }
}
