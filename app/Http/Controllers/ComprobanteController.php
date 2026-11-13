<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use App\Services\NumeroALetras;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Boleta / factura en PDF
 *   /comprobante/B001-00000010             -> se abre en el navegador (para ver o imprimir)
 *   /comprobante/B001-00000010?descargar=1 -> se descarga el archivo
 */
class ComprobanteController extends Controller
{
    public function pdf(Request $request, string $numero): Response
    {
        $venta = Venta::with(['cliente', 'empleado', 'detalles.producto', 'pagos'])
            ->where('numero_comprobante', $numero)
            ->firstOrFail();

        $usuario  = $request->user();
        $personal = $usuario->esPersonal();

        // Solo el dueño del pedido o el personal
        abort_unless($personal || $venta->cliente?->user_id === $usuario->id, 404);

        // El cliente recibe su comprobante cuando el pago ya fue confirmado
        if (! $personal && in_array($venta->estado_pedido, ['por_confirmar', 'anulado'], true)) {
            return redirect()->route('pedido.ver', $numero)
                ->with('carrito_info', 'Tu comprobante estará disponible cuando confirmemos tu pago.');
        }

        // Montos con IGV (los precios de la tienda ya incluyen el 18 %)
        $total    = (float) $venta->total;
        $gravada  = round($total / 1.18, 2);
        $igv      = round($total - $gravada, 2);
        $enLetras = NumeroALetras::soles($total);
        $datos    = config('libreria');

        $pdf = Pdf::loadView('venta.comprobante', compact('venta', 'gravada', 'igv', 'enLetras', 'datos'))
            ->setPaper('a4');

        $archivo = $venta->numero_comprobante . '.pdf';

        return $request->boolean('descargar')
            ? $pdf->download($archivo)
            : $pdf->stream($archivo);
    }
}
