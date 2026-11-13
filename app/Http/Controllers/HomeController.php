<?php

namespace App\Http\Controllers;

use App\Services\AnularPedidosVencidos;
use App\Models\Autore;
use App\Models\Categoria;
use App\Models\Cliente;
use App\Models\DetalleVenta;
use App\Models\Pago;
use App\Models\Producto;
use App\Models\Venta;
use Illuminate\Contracts\Support\Renderable;

class HomeController extends Controller
{
    /**
     * Panel de Control de la librería
     * (la ruta /home ya está protegida con middleware('auth') en routes/web.php)
     */
    public function index(): Renderable
    {
        // Anula los pedidos web que vencieron sin pago (devuelve el stock)
        AnularPedidosVencidos::revisar();

        // ---------- Tarjetas de resumen (KPI) ----------
        $totalProductos  = Producto::count();
        $totalClientes   = Cliente::count();
        $totalAutores    = Autore::count();
        $totalCategorias = Categoria::count();

        $ventasValidas   = Venta::where('estado', '!=', 'anulada');
        $ingresosTotales = (clone $ventasValidas)->sum('total');
        $cantidadVentas  = (clone $ventasValidas)->count();
        $ticketPromedio  = $cantidadVentas > 0 ? $ingresosTotales / $cantidadVentas : 0;

        $ventasMes = (clone $ventasValidas)
            ->whereYear('fecha_venta', now()->year)
            ->whereMonth('fecha_venta', now()->month)
            ->sum('total');

        // ---------- Inventario ----------
        $stockBajo = Producto::where('estado', 1)
            ->whereColumn('stock', '<=', 'stock_minimo')
            ->orderBy('stock')
            ->get();

        $unidadesEnStock = Producto::where('estado', 1)->sum('stock');

        // ---------- Producto más vendido ----------
        $masVendido = DetalleVenta::selectRaw('producto_id, SUM(cantidad) AS unidades, SUM(subtotal) AS ingresos')
            ->groupBy('producto_id')
            ->orderByDesc('unidades')
            ->with('producto')
            ->first();

        // ---------- Novedades: últimos productos registrados (para el slider) ----------
        $novedades = Producto::with(['categoria', 'autor'])
            ->where('estado', 1)
            ->orderByDesc('id')
            ->take(9)
            ->get();

        // ---------- Pagos agrupados por método ----------
        $pagosPorMetodo = Pago::selectRaw('metodo_pago, SUM(monto) AS total, COUNT(*) AS cantidad')
            ->groupBy('metodo_pago')
            ->orderByDesc('total')
            ->get();
        $totalPagado = $pagosPorMetodo->sum('total');

        // ---------- Mejores vendedores ----------
        $topVendedores = Venta::selectRaw('empleado_id, COUNT(*) AS ventas, SUM(total) AS monto')
            ->where('estado', '!=', 'anulada')
            ->whereNotNull('empleado_id')            // los pedidos web sin atender aún no tienen vendedor
            ->groupBy('empleado_id')
            ->orderByDesc('monto')
            ->with('empleado')
            ->take(4)
            ->get();

        // ---------- Últimas ventas ----------
        $ultimasVentas = Venta::with(['cliente', 'empleado'])
            ->latest('fecha_venta')
            ->take(5)
            ->get();

        // ---------- Pedidos de la tienda web ----------
        $pedidosWeb = Venta::where('canal', 'web')
            ->selectRaw('estado_pedido, COUNT(*) AS cantidad')
            ->groupBy('estado_pedido')
            ->pluck('cantidad', 'estado_pedido');

        $ultimosPorConfirmar = Venta::with('cliente')
            ->where('estado_pedido', 'por_confirmar')
            ->oldest('fecha_venta')
            ->take(3)
            ->get();

        return view('home', compact(
            'pedidosWeb', 'ultimosPorConfirmar',
            'totalProductos', 'totalClientes', 'totalAutores', 'totalCategorias',
            'ingresosTotales', 'cantidadVentas', 'ticketPromedio', 'ventasMes',
            'stockBajo', 'unidadesEnStock', 'masVendido', 'novedades',
            'pagosPorMetodo', 'totalPagado', 'topVendedores', 'ultimasVentas'
        ));
    }
}
