<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Services\Carrito;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CarritoController extends Controller
{
    public function __construct(private Carrito $carrito)
    {
    }

    /** Página del carrito */
    public function index(): View
    {
        $items    = $this->carrito->items();
        $subtotal = round($items->sum('subtotal'), 2);
        $envio    = Carrito::costoEnvio($subtotal);

        return view('catalogo.carrito', compact('items', 'subtotal', 'envio'));
    }

    /** Agregar un producto (desde la tarjeta o la página del producto) */
    public function agregar(Request $request, Producto $producto): RedirectResponse
    {
        $cantidad = max(1, min(99, (int) $request->input('cantidad', 1)));
        $error = $this->carrito->agregar($producto, $cantidad);

        if ($error) {
            return back()->with('carrito_error', $error);
        }

        return back()->with('carrito_ok', '"' . $producto->nombre . '" se agregó a tu carrito.');
    }

    /** Cambiar la cantidad de un producto */
    public function actualizar(Request $request, Producto $producto): RedirectResponse
    {
        $cantidad = max(0, min(99, (int) $request->input('cantidad', 1)));
        $aviso = $this->carrito->actualizar($producto, $cantidad);

        return redirect()->route('carrito')->with($aviso ? 'carrito_error' : 'carrito_info', $aviso ?? 'Carrito actualizado.');
    }

    /** Quitar un producto */
    public function quitar(Producto $producto): RedirectResponse
    {
        $this->carrito->quitar($producto);

        return redirect()->route('carrito')->with('carrito_info', '"' . $producto->nombre . '" se quitó del carrito.');
    }

    /** Vaciar todo el carrito */
    public function vaciar(): RedirectResponse
    {
        $this->carrito->vaciar();

        return redirect()->route('carrito')->with('carrito_info', 'Tu carrito quedó vacío.');
    }
}
