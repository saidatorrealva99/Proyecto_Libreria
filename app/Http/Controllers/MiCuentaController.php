<?php

namespace App\Http\Controllers;

use App\Services\AnularPedidosVencidos;
use App\Models\Venta;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MiCuentaController extends Controller
{
    /**
     * Zona del cliente: sus datos y sus pedidos
     */
    public function index(Request $request): View
    {
        // Anula los pedidos web que vencieron sin pago (devuelve el stock)
        AnularPedidosVencidos::revisar();

        $usuario = $request->user();

        $pedidos = Venta::with(['pagos'])
            ->withCount('detalles')
            ->whereIn('cliente_id', $usuario->clientes()->pluck('id'))
            ->latest('fecha_venta')
            ->get();

        $fichas = $usuario->clientes()->orderBy('tipo_documento')->get();

        return view('cliente.mi-cuenta', compact('usuario', 'pedidos', 'fichas'));
    }
}
