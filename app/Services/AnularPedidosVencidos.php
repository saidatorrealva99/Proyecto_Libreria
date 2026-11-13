<?php

namespace App\Services;

use App\Models\Producto;
use App\Models\Venta;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

/**
 * Anula los pedidos web que pasaron más de X horas "por confirmar"
 * (config('libreria.horas_para_pagar')) y devuelve sus productos al stock.
 *
 * Se ejecuta:
 *   - solo, cuando alguien abre el panel, Pedidos web o "Mi cuenta"  -> revisar()
 *   - a mano:  php artisan pedidos:anular-vencidos                   -> ejecutar()
 */
class AnularPedidosVencidos
{
    /**
     * Revisión rápida: como máximo una vez cada 5 minutos
     * (así no se consulta la base de datos en cada página).
     */
    public static function revisar(): void
    {
        if (Cache::add('pedidos_vencidos_revisados', true, now()->addMinutes(5))) {
            self::ejecutar();
        }
    }

    /**
     * Anula todos los pedidos vencidos. Devuelve cuántos anuló.
     */
    public static function ejecutar(): int
    {
        $horas  = (int) config('libreria.horas_para_pagar', 24);
        $limite = now()->subHours($horas);

        $ids = Venta::where('canal', 'web')
            ->where('estado_pedido', 'por_confirmar')
            ->where('fecha_venta', '<', $limite)
            ->pluck('id');

        $anulados = 0;

        foreach ($ids as $id) {
            DB::transaction(function () use ($id, $horas, &$anulados) {
                // Se vuelve a leer bloqueado: quizá alguien lo confirmó hace un segundo
                $venta = Venta::with('detalles')->lockForUpdate()->find($id);
                if (! $venta || $venta->estado_pedido !== 'por_confirmar') {
                    return;
                }

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
                    'observaciones' => "Anulado automáticamente: el pago no se confirmó dentro de {$horas} horas.",
                ]);

                $anulados++;
            });
        }

        return $anulados;
    }
}
