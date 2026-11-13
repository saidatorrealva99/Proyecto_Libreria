<?php

namespace App\Http\Controllers;

use App\Models\Empleado;
use App\Models\Pago;
use App\Models\Producto;
use App\Models\Venta;
use App\Services\AnularPedidosVencidos;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

/**
 * Pedidos de la tienda web (lado del PERSONAL: admin y empleado)
 *
 *   por_confirmar ──► confirmado ──► listo_recojo ──► entregado   (recojo en tienda)
 *                               └──► en_camino    ──► entregado   (envío a domicilio)
 *   (cualquiera, menos entregado) ──► anulado  (devuelve el stock)
 */
class PedidoWebController extends Controller
{
    // Pestañas de la lista: clave => [texto, color, icono]
    public const PESTANAS = [
        'pendientes'    => ['Por atender',        'warning', 'fa-bell'],
        'por_confirmar' => ['Por confirmar pago', 'warning', 'fa-hourglass-half'],
        'confirmado'    => ['Pago confirmado',    'primary', 'fa-circle-check'],
        'listo_recojo'  => ['Listo para recoger', 'info',    'fa-store'],
        'en_camino'     => ['En camino',          'info',    'fa-truck-fast'],
        'entregado'     => ['Entregados',         'success', 'fa-box-open'],
        'anulado'       => ['Anulados',           'danger',  'fa-ban'],
        'todos'         => ['Todos',              'secondary', 'fa-list'],
    ];

    /**
     * Lista de pedidos web  (/pedidos-web?estado=por_confirmar)
     */
    public function index(Request $request): View
    {
        // Anula los pedidos web que vencieron sin pago (devuelve el stock)
        AnularPedidosVencidos::revisar();

        $estado = array_key_exists($request->estado, self::PESTANAS) ? $request->estado : 'pendientes';

        $pedidos = Venta::with(['cliente', 'empleado', 'pagos'])
            ->where('canal', 'web')
            ->when($estado === 'pendientes', fn ($q) => $q->whereNotIn('estado_pedido', ['entregado', 'anulado']))
            ->when(! in_array($estado, ['pendientes', 'todos']), fn ($q) => $q->where('estado_pedido', $estado))
            ->orderByDesc('fecha_venta')
            ->get();

        // Cantidad de pedidos en cada pestaña (para los numeritos)
        $porEstado = Venta::where('canal', 'web')
            ->selectRaw('estado_pedido, COUNT(*) AS cantidad')
            ->groupBy('estado_pedido')
            ->pluck('cantidad', 'estado_pedido');

        $conteo = $porEstado->all() + [
            'pendientes' => $porEstado->except(['entregado', 'anulado'])->sum(),
            'todos'      => $porEstado->sum(),
        ];

        return view('venta.pedidos', compact('pedidos', 'estado', 'conteo'))
            ->with('i', 0);
    }

    /**
     * Ficha del pedido con los botones para atenderlo
     */
    public function show(Request $request, Venta $venta): View
    {
        abort_unless($venta->canal === 'web', 404);

        AnularPedidosVencidos::revisar();
        $venta->refresh();

        $venta->load(['cliente', 'empleado', 'detalles.producto', 'pagos']);

        $empleados = Empleado::where('estado', 1)->orderBy('nombres')->get();

        // Se propone al empleado que tiene el mismo correo que el usuario conectado
        $empleadoSugerido = $venta->empleado_id
            ?? $empleados->firstWhere('email', $request->user()->email)?->id;

        return view('venta.pedido', compact('venta', 'empleados', 'empleadoSugerido'));
    }

    /**
     * 1) Confirmar el pago (después de revisar Yape / Plin / el banco)
     */
    public function confirmar(Request $request, Venta $venta): RedirectResponse
    {
        $this->soloWeb($venta);

        $request->validate([
            'empleado_id' => ['required', 'exists:empleados,id'],
        ], [], ['empleado_id' => 'empleado que atiende']);

        if ($venta->estado_pedido !== 'por_confirmar') {
            return back()->with('error', 'Este pedido ya no está esperando confirmación.');
        }

        $venta->load('pagos');
        $venta->update([
            'empleado_id'   => $request->empleado_id,
            'estado_pedido' => 'confirmado',
            'estado'        => $venta->saldo <= 0 ? 'pagada' : 'pendiente',
        ]);

        return back()->with('success', 'Pago confirmado. Ahora prepara el pedido.');
    }

    /**
     * 2) Avanzar el pedido: listo para recoger / en camino / entregado
     */
    public function estado(Request $request, Venta $venta): RedirectResponse
    {
        $this->soloWeb($venta);

        $request->validate([
            'estado_pedido' => ['required', 'in:listo_recojo,en_camino,entregado'],
        ]);

        $siguiente = $request->estado_pedido;
        $permitidos = $this->siguientesEstados($venta);

        if (! in_array($siguiente, $permitidos, true)) {
            return back()->with('error', 'Ese cambio de estado no corresponde a este pedido.');
        }

        $venta->load('pagos');
        if ($siguiente === 'entregado' && $venta->saldo > 0) {
            return back()->with('error', 'Primero registra el saldo de S/ ' . number_format($venta->saldo, 2) . ' antes de entregar.');
        }

        $venta->update(['estado_pedido' => $siguiente]);

        $textos = [
            'listo_recojo' => 'El pedido está listo para que el cliente lo recoja.',
            'en_camino'    => 'El pedido salió a reparto.',
            'entregado'    => '¡Pedido entregado! Venta completada.',
        ];

        return back()->with('success', $textos[$siguiente]);
    }

    /**
     * 3) Registrar el saldo (el 50 % restante cuando recoge en tienda)
     */
    public function saldo(Request $request, Venta $venta): RedirectResponse
    {
        $this->soloWeb($venta);

        $request->validate([
            'metodo_pago'      => ['required', 'in:efectivo,tarjeta,yape,plin,transferencia'],
            'numero_operacion' => ['nullable', 'required_unless:metodo_pago,efectivo', 'string', 'min:4', 'max:30'],
            'entregar'         => ['nullable', 'boolean'],
        ], [
            'numero_operacion.required_unless' => 'Escribe el número de operación (solo el efectivo no lo necesita).',
        ], [
            'metodo_pago'      => 'método de pago',
            'numero_operacion' => 'número de operación',
        ]);

        if (! in_array($venta->estado_pedido, ['confirmado', 'listo_recojo', 'en_camino'], true)) {
            return back()->with('error', 'Primero confirma el pago del adelanto.');
        }

        DB::transaction(function () use ($request, $venta) {
            $venta->load('pagos');
            $saldo = $venta->saldo;
            if ($saldo <= 0) {
                return;
            }

            Pago::create([
                'venta_id'         => $venta->id,
                'metodo_pago'      => $request->metodo_pago,
                'monto'            => $saldo,
                'numero_operacion' => $request->metodo_pago === 'efectivo' ? null : $request->numero_operacion,
                'fecha_pago'       => now(),
            ]);

            $cambios = ['estado' => 'pagada'];
            if ($request->boolean('entregar')) {
                $cambios['estado_pedido'] = 'entregado';
            }
            $venta->update($cambios);
        });

        return back()->with('success', $request->boolean('entregar')
            ? 'Saldo cobrado y pedido entregado. ¡Venta completada!'
            : 'Saldo registrado. La venta quedó pagada.');
    }

    /**
     * 4) Anular el pedido y devolver los productos al stock
     */
    public function anular(Request $request, Venta $venta): RedirectResponse
    {
        $this->soloWeb($venta);

        $request->validate([
            'motivo' => ['required', 'string', 'min:5', 'max:200'],
        ], [], ['motivo' => 'motivo de anulación']);

        if (in_array($venta->estado_pedido, ['entregado', 'anulado'], true)) {
            return back()->with('error', 'Un pedido entregado o ya anulado no se puede anular.');
        }

        DB::transaction(function () use ($request, $venta) {
            $venta->load('detalles');

            // Devolver cada producto a su stock
            $productos = Producto::whereIn('id', $venta->detalles->pluck('producto_id'))
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            foreach ($venta->detalles as $detalle) {
                $productos->get($detalle->producto_id)?->increment('stock', $detalle->cantidad);
            }

            $venta->update([
                'estado'        => 'anulada',
                'estado_pedido' => 'anulado',
                'observaciones' => 'Anulado por ' . $request->user()->name . ': ' . $request->motivo,
            ]);
        });

        $venta->load('pagos');
        $aviso = $venta->pagado > 0
            ? ' Recuerda devolver S/ ' . number_format($venta->pagado, 2) . ' al cliente.'
            : '';

        return back()->with('success', 'Pedido anulado y stock devuelto.' . $aviso);
    }

    /**
     * Estados a los que puede pasar el pedido desde donde está
     */
    public static function siguientesEstados(Venta $venta): array
    {
        return match ($venta->estado_pedido) {
            'confirmado'   => [$venta->entrega === 'delivery' ? 'en_camino' : 'listo_recojo'],
            'listo_recojo',
            'en_camino'    => ['entregado'],
            default        => [],
        };
    }

    private function soloWeb(Venta $venta): void
    {
        abort_unless($venta->canal === 'web', 404);
    }
}
