{{-- resources/views/venta/comprobante.blade.php  (archivo NUEVO)
     Plantilla del PDF de la boleta / factura.
     OJO: el PDF lo arma DomPDF, que NO puede usar Bootstrap ni FontAwesome (no lee los CDN
     y no entiende flex/grid). Por eso SOLO aquí se usan estilos simples y tablas. --}}
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>{{ ucfirst($venta->tipo_comprobante) }} {{ $venta->numero_comprobante }}</title>
    <style>
        @page { margin: 28px 34px; }
        body  { font-family: DejaVu Sans, sans-serif; font-size: 10.5px; color: #212529; }
        table { width: 100%; border-collapse: collapse; }
        td, th { vertical-align: top; }
        .marca   { font-size: 22px; font-weight: bold; color: #0d6efd; }
        .gris    { color: #6c757d; }
        .caja    { border: 2px solid #0d6efd; border-radius: 8px; text-align: center; padding: 10px; }
        .caja .tipo { font-size: 13px; font-weight: bold; color: #0d6efd; margin: 4px 0; }
        .caja .num  { font-size: 14px; font-weight: bold; }
        .seccion { border: 1px solid #dee2e6; border-radius: 6px; padding: 8px 10px; margin-top: 14px; }
        .etiqueta { color: #6c757d; width: 95px; }
        .items   { margin-top: 14px; }
        .items th { background: #0d6efd; color: #fff; padding: 6px; font-size: 9.5px; text-transform: uppercase; }
        .items td { padding: 6px; border-bottom: 1px solid #dee2e6; }
        .derecha { text-align: right; }
        .centro  { text-align: center; }
        .totales td { padding: 4px 6px; }
        .total   { font-size: 13px; font-weight: bold; color: #0d6efd; border-top: 2px solid #0d6efd; }
        .letras  { background: #f1f5ff; border-radius: 6px; padding: 7px 10px; margin-top: 10px; font-weight: bold; }
        .pie     { margin-top: 22px; text-align: center; font-size: 9px; color: #6c757d; border-top: 1px dashed #adb5bd; padding-top: 8px; }
        .sello   { position: fixed; top: 300px; left: 90px; font-size: 90px; font-weight: bold; color: #dc3545;
                   opacity: 0.15; transform: rotate(-30deg); }
    </style>
</head>
<body>

    @if ($venta->estado === 'anulada')
        <div class="sello">ANULADO</div>
    @endif

    {{-- ========== CABECERA ========== --}}
    <table>
        <tr>
            <td style="width: 62%;">
                <div class="marca">{{ $datos['nombre'] }}</div>
                <div class="gris">{{ $datos['eslogan'] }}</div>
                <div style="margin-top: 8px;">
                    {{ $datos['direccion'] }}<br>
                    Tel.: {{ $datos['telefono'] }} · {{ $datos['correo'] }}<br>
                    {{ $datos['horario'] }}
                </div>
            </td>
            <td>
                <div class="caja">
                    <div>R.U.C. {{ $datos['ruc'] }}</div>
                    <div class="tipo">{{ $venta->tipo_comprobante === 'factura' ? 'FACTURA DE VENTA' : 'BOLETA DE VENTA' }}</div>
                    <div class="num">{{ $venta->numero_comprobante }}</div>
                </div>
            </td>
        </tr>
    </table>

    {{-- ========== CLIENTE Y VENTA ========== --}}
    <div class="seccion">
        <table>
            <tr>
                <td class="etiqueta">{{ $venta->tipo_comprobante === 'factura' ? 'Razón social' : 'Cliente' }}</td>
                <td><strong>{{ $venta->cliente?->nombres }} {{ $venta->cliente?->apellidos }}</strong></td>
                <td class="etiqueta">Fecha</td>
                <td>{{ $venta->fecha_venta?->format('d/m/Y H:i') }}</td>
            </tr>
            <tr>
                <td class="etiqueta">{{ $venta->cliente?->tipo_documento ?? 'Documento' }}</td>
                <td>{{ $venta->cliente?->numero_documento }}</td>
                <td class="etiqueta">Moneda</td>
                <td>Soles (PEN)</td>
            </tr>
            <tr>
                <td class="etiqueta">Dirección</td>
                <td>{{ $venta->direccion_envio ?? $venta->cliente?->direccion ?? '—' }}</td>
                <td class="etiqueta">Canal</td>
                <td>
                    @if ($venta->canal === 'web')
                        Tienda web · {{ $venta->entrega === 'delivery' ? 'Envío a domicilio' : 'Recojo en tienda' }}
                    @else
                        Venta en tienda
                    @endif
                </td>
            </tr>
            @if ($venta->empleado)
                <tr>
                    <td class="etiqueta">Atendido por</td>
                    <td colspan="3">{{ $venta->empleado->nombres }} {{ $venta->empleado->apellidos }}</td>
                </tr>
            @endif
        </table>
    </div>

    {{-- ========== PRODUCTOS ========== --}}
    <table class="items">
        <thead>
            <tr>
                <th class="centro" style="width: 40px;">Cant.</th>
                <th style="width: 80px;">Código</th>
                <th>Descripción</th>
                <th class="derecha" style="width: 75px;">P. Unit.</th>
                <th class="derecha" style="width: 80px;">Importe</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($venta->detalles as $d)
                <tr>
                    <td class="centro">{{ $d->cantidad }}</td>
                    <td>{{ $d->producto?->codigo }}</td>
                    <td>{{ $d->producto?->nombre }}</td>
                    <td class="derecha">{{ number_format($d->precio_unitario, 2) }}</td>
                    <td class="derecha">{{ number_format($d->subtotal, 2) }}</td>
                </tr>
            @endforeach
            @if ($venta->costo_envio > 0)
                <tr>
                    <td class="centro">1</td>
                    <td>ENVIO</td>
                    <td>Servicio de envío a domicilio</td>
                    <td class="derecha">{{ number_format($venta->costo_envio, 2) }}</td>
                    <td class="derecha">{{ number_format($venta->costo_envio, 2) }}</td>
                </tr>
            @endif
        </tbody>
    </table>

    {{-- ========== TOTALES Y PAGOS ========== --}}
    <table style="margin-top: 12px;">
        <tr>
            <td style="width: 55%; padding-right: 14px;">
                <div class="seccion" style="margin-top: 0;">
                    <strong>Pagos</strong>
                    <table style="margin-top: 4px;">
                        @foreach ($venta->pagos->sortBy('fecha_pago') as $pg)
                            <tr>
                                <td>{{ ucfirst($pg->metodo_pago) }}
                                    @if ($pg->numero_operacion)<span class="gris">· Op. {{ $pg->numero_operacion }}</span>@endif
                                    <br><span class="gris">{{ $pg->fecha_pago?->format('d/m/Y H:i') }}</span>
                                </td>
                                <td class="derecha">S/ {{ number_format($pg->monto, 2) }}</td>
                            </tr>
                        @endforeach
                        <tr>
                            <td><strong>Saldo pendiente</strong></td>
                            <td class="derecha"><strong>S/ {{ number_format($venta->saldo, 2) }}</strong></td>
                        </tr>
                    </table>
                </div>
            </td>
            <td>
                <table class="totales">
                    @if ($venta->descuento > 0)
                        <tr><td class="gris">Descuento</td><td class="derecha">- S/ {{ number_format($venta->descuento, 2) }}</td></tr>
                    @endif
                    <tr><td class="gris">Op. gravada</td><td class="derecha">S/ {{ number_format($gravada, 2) }}</td></tr>
                    <tr><td class="gris">I.G.V. (18 %)</td><td class="derecha">S/ {{ number_format($igv, 2) }}</td></tr>
                    <tr class="total"><td>TOTAL</td><td class="derecha">S/ {{ number_format($venta->total, 2) }}</td></tr>
                </table>
            </td>
        </tr>
    </table>

    <div class="letras">SON: {{ $enLetras }}</div>

    @if ($venta->estado === 'anulada' && $venta->observaciones)
        <div class="seccion" style="color: #dc3545;">{{ $venta->observaciones }}</div>
    @endif

    {{-- ========== PIE ========== --}}
    <div class="pie">
        ¡Gracias por tu compra en {{ $datos['nombre'] }}!<br>
        Documento generado por el sistema de la librería el {{ now()->format('d/m/Y H:i') }}.
        Proyecto académico: no reemplaza al comprobante electrónico de SUNAT.
    </div>

</body>
</html>
