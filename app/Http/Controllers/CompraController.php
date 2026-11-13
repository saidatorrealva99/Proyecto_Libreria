<?php

namespace App\Http\Controllers;

use App\Http\Requests\CompraRequest;
use App\Models\Venta;
use App\Services\Carrito;
use App\Services\RegistrarVenta;
use App\Services\AnularPedidosVencidos;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use RuntimeException;

class CompraController extends Controller
{
    public function __construct(private Carrito $carrito)
    {
    }

    /**
     * Página de compra: entrega, datos del comprobante y pago
     */
    public function create(Request $request): View|RedirectResponse
    {
        $items = $this->carrito->items();
        if ($items->isEmpty()) {
            return redirect()->route('carrito')->with('carrito_info', 'Tu carrito está vacío. Agrega productos para comprar.');
        }

        $subtotal     = round($items->sum('subtotal'), 2);
        $envio        = Carrito::costoEnvio($subtotal);
        $porcAdelanto = (float) config('libreria.adelanto_recojo');

        // Montos para cada forma de entrega (la página los cambia al elegir)
        $montos = [
            'recojo'   => ['envio' => 0.0,    'total' => $subtotal,                  'pagar' => round($subtotal * $porcAdelanto, 2)],
            'delivery' => ['envio' => $envio, 'total' => round($subtotal + $envio, 2), 'pagar' => round($subtotal + $envio, 2)],
        ];

        // Datos del último cliente que usó esta cuenta (para no volver a escribirlos)
        $anterior = $request->user()->clientes()->latest('updated_at')->first();

        return view('catalogo.compra', compact('items', 'subtotal', 'montos', 'anterior', 'porcAdelanto'));
    }

    /**
     * Confirmar la compra
     */
    public function store(CompraRequest $request, RegistrarVenta $registrar): RedirectResponse
    {
        try {
            $venta = $registrar->desdeTiendaWeb($request->user(), $this->carrito->contenido(), $request->validated());
        } catch (RuntimeException $e) {
            return back()->withInput()->with('compra_error', $e->getMessage());
        }

        $this->carrito->vaciar();

        return redirect()->route('pedido.ver', $venta->numero_comprobante)
            ->with('compra_ok', true);
    }

    /**
     * Detalle de un pedido del cliente (también es la página de "¡Gracias por tu compra!")
     *   /mi-cuenta/pedido/B001-00000010
     */
    public function pedido(Request $request, string $numero): View
    {
        AnularPedidosVencidos::revisar();

        $venta = Venta::with(['cliente', 'detalles.producto.categoria', 'pagos'])
            ->where('numero_comprobante', $numero)
            ->firstOrFail();

        // Solo el dueño del pedido (o el personal) puede verlo
        $usuario = $request->user();
        abort_unless($usuario->esPersonal() || $venta->cliente?->user_id === $usuario->id, 404);

        return view('catalogo.pedido', compact('venta'));
    }
}
