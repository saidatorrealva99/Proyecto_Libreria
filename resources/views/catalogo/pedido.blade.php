{{-- resources/views/catalogo/pedido.blade.php  (archivo NUEVO)
     Detalle de un pedido:  /mi-cuenta/pedido/B001-00000010
     Justo después de comprar muestra además el mensaje "¡Gracias por tu compra!" --}}
@extends('layouts.tienda')

@section('titulo', 'Pedido ' . $venta->numero_comprobante)

@section('content')
@php
    [$textoEstado, $colorEstado, $iconoEstado] = \App\Models\Venta::ESTADOS_PEDIDO[$venta->estado_pedido ?? 'confirmado'] ?? ['—', 'secondary', 'fa-circle'];
    $pagado = $venta->pagado;
    $saldo  = $venta->saldo;
    $ultimoPago = $venta->pagos->sortByDesc('fecha_pago')->first();
    $mensajeComprobante = 'Hola, soy ' . $venta->cliente?->nombres . '. Les envío la captura del pago de mi pedido '
                        . $venta->numero_comprobante . ' por S/ ' . number_format($pagado, 2)
                        . ($ultimoPago?->numero_operacion ? ' (operación ' . $ultimoPago->numero_operacion . ')' : '') . '.';
    // Pasos del pedido para la línea de avance
    $pasos = $venta->entrega === 'delivery'
        ? ['por_confirmar' => 'Pago enviado', 'confirmado' => 'Pago confirmado', 'en_camino' => 'En camino', 'entregado' => 'Entregado']
        : ['por_confirmar' => 'Adelanto enviado', 'confirmado' => 'Pago confirmado', 'listo_recojo' => 'Listo para recoger', 'entregado' => 'Entregado'];
    $orden = array_keys($pasos);
    $posicion = array_search($venta->estado_pedido, $orden, true);
@endphp

    <section class="bg-black py-5">
        <div class="container">

            {{-- ========== GRACIAS (solo justo después de comprar) ========== --}}
            @if (session('compra_ok'))
                <div class="text-center bg-success-subtle border border-success rounded-4 shadow-lg p-4 p-lg-5 mb-5">
                    <i class="fas fa-circle-check fa-4x text-success mb-3 fa-bounce"></i>
                    <h1 class="fw-bold">¡Gracias por tu compra!</h1>
                    <p class="lead text-success-emphasis mb-1">Tu pedido <strong class="font-monospace">{{ $venta->numero_comprobante }}</strong> fue registrado.</p>
                    <p class="text-white-50 mb-4">Vamos a verificar tu pago y te avisaremos por WhatsApp o al celular {{ $venta->telefono_contacto }}.</p>
                    <a href="{{ $wa($mensajeComprobante) }}" target="_blank" rel="noopener" class="btn btn-success btn-lg rounded-pill px-4 fw-semibold shadow" data-animar="fa-beat">
                        <i class="fas fa-comment-dots me-2"></i>Enviar captura del pago por WhatsApp
                    </a>
                    <p class="small text-white-50 mt-2 mb-0">Así confirmamos tu pedido más rápido.</p>
                </div>
            @endif

            {{-- ========== ENCABEZADO DEL PEDIDO ========== --}}
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
                <div>
                    <a href="{{ Auth::user()->esPersonal() ? route('ventas.index') : route('mi-cuenta') }}" class="link-warning text-decoration-none small">
                        <i class="fas fa-arrow-left me-1"></i>{{ Auth::user()->esPersonal() ? 'Volver a Ventas' : 'Volver a Mi cuenta' }}
                    </a>
                    <h2 class="fw-bold mt-2 mb-1">
                        {{ ucfirst($venta->tipo_comprobante) }} <span class="font-monospace text-warning">{{ $venta->numero_comprobante }}</span>
                    </h2>
                    <small class="text-white-50"><i class="far fa-calendar me-1"></i>{{ $venta->fecha_venta?->format('d/m/Y H:i') }}</small>
                </div>
                <span class="badge rounded-pill bg-{{ $colorEstado }}-subtle text-{{ $colorEstado }}-emphasis border border-{{ $colorEstado }} fs-6 px-4 py-2">
                    <i class="fas {{ $iconoEstado }} me-2"></i>{{ $textoEstado }}
                </span>
            </div>

            {{-- ========== PLAZO PARA CONFIRMAR EL PAGO ========== --}}
            @if ($venta->vence_en)
                <div class="alert alert-warning d-flex align-items-center gap-3 rounded-4 mb-4">
                    <i class="fas fa-clock fa-2x"></i>
                    <div>
                        Confirmaremos tu pago antes del <strong>{{ $venta->vence_en->translatedFormat('l d \d\e F, h:i a') }}</strong>.
                        Si para entonces no recibimos el pago, el pedido se <strong>anulará automáticamente</strong> y los productos volverán a la tienda.
                        <div class="small mt-1">¿Ya pagaste? Envíanos la captura por WhatsApp para confirmarlo más rápido.</div>
                    </div>
                </div>
            @elseif ($venta->estado_pedido === 'anulado' && $venta->observaciones)
                <div class="alert alert-danger d-flex align-items-center gap-3 rounded-4 mb-4">
                    <i class="fas fa-ban fa-2x"></i>
                    <div>
                        {{ \Illuminate\Support\Str::startsWith($venta->observaciones, 'Anulado automáticamente') ? $venta->observaciones : 'Este pedido fue anulado.' }}
                        <div class="small mt-1">Si realizaste el pago, escríbenos por WhatsApp para coordinar la devolución.</div>
                    </div>
                </div>
            @endif

            {{-- ========== AVANCE DEL PEDIDO ========== --}}
            @if ($venta->estado_pedido !== 'anulado' && $posicion !== false)
                <div class="row g-2 text-center mb-5">
                    @foreach ($pasos as $clave => $texto)
                        @php $hecho = $loop->index <= $posicion; @endphp
                        <div class="col-3">
                            <div class="border-top border-4 {{ $hecho ? 'border-warning' : 'border-secondary border-opacity-25' }} pt-2">
                                <i class="fas {{ $hecho ? 'fa-circle-check text-warning' : 'fa-circle text-secondary' }} mb-1"></i>
                                <div class="small {{ $hecho ? 'fw-semibold' : 'text-white-50' }}">{{ $texto }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            <div class="row g-4">
                {{-- Productos --}}
                <div class="col-lg-8">
                    <div class="bg-primary-subtle border border-primary border-opacity-25 rounded-4 overflow-hidden">
                        <div class="px-4 py-3 border-bottom border-primary border-opacity-25 fw-bold"><i class="fas fa-box-open text-warning me-2"></i>Productos</div>
                        <div class="table-responsive">
                            <table class="table table-borderless align-middle mb-0">
                                <thead class="small text-white-50 text-uppercase">
                                    <tr><th class="ps-4">Producto</th><th class="text-center">Cant.</th><th class="text-end">Precio</th><th class="text-end pe-4">Subtotal</th></tr>
                                </thead>
                                <tbody>
                                    @foreach ($venta->detalles as $d)
                                        <tr>
                                            <td class="ps-4">
                                                <a href="{{ route('producto.ver', $d->producto?->codigo) }}" class="link-light text-decoration-none fw-semibold">{{ $d->producto?->nombre }}</a>
                                                <div class="small text-white-50 font-monospace">{{ $d->producto?->codigo }}</div>
                                            </td>
                                            <td class="text-center">{{ $d->cantidad }}</td>
                                            <td class="text-end">S/ {{ number_format($d->precio_unitario, 2) }}</td>
                                            <td class="text-end pe-4 fw-semibold">S/ {{ number_format($d->subtotal, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- Resumen, entrega y pagos --}}
                <div class="col-lg-4">
                    <div class="bg-primary-subtle border border-warning border-opacity-50 rounded-4 p-4 mb-4">
                        <div class="d-flex justify-content-between small mb-1"><span class="text-white-50">Subtotal</span><span>S/ {{ number_format($venta->subtotal, 2) }}</span></div>
                        @if ($venta->descuento > 0)
                            <div class="d-flex justify-content-between small mb-1 text-danger"><span>Descuento</span><span>- S/ {{ number_format($venta->descuento, 2) }}</span></div>
                        @endif
                        <div class="d-flex justify-content-between small mb-2"><span class="text-white-50">Envío</span><span>{{ $venta->costo_envio > 0 ? 'S/ ' . number_format($venta->costo_envio, 2) : 'Gratis' }}</span></div>
                        <div class="d-flex justify-content-between align-items-center border-top border-warning border-opacity-25 pt-2 mb-3">
                            <span class="fw-bold">Total</span><span class="fs-4 fw-bold text-warning">S/ {{ number_format($venta->total, 2) }}</span>
                        </div>
                        <div class="bg-black bg-opacity-50 rounded-3 p-3">
                            <div class="d-flex justify-content-between"><span>Pagado</span><strong class="text-success">S/ {{ number_format($pagado, 2) }}</strong></div>
                            <div class="d-flex justify-content-between"><span>Saldo</span><strong class="{{ $saldo > 0 ? 'text-warning' : 'text-success' }}">S/ {{ number_format($saldo, 2) }}</strong></div>
                            @if ($saldo > 0 && $venta->entrega === 'recojo')
                                <small class="text-white-50 d-block mt-1">Pagas el saldo al recoger en tienda.</small>
                            @endif
                        </div>
                    </div>

                    <div class="bg-primary-subtle border border-primary border-opacity-25 rounded-4 p-4 mb-4 small">
                        <div class="fw-bold mb-2">
                            <i class="fas {{ $venta->entrega === 'delivery' ? 'fa-truck-fast text-info' : 'fa-store text-success' }} me-2"></i>
                            {{ $venta->entrega === 'delivery' ? 'Envío a domicilio' : 'Recojo en tienda' }}
                        </div>
                        @if ($venta->entrega === 'delivery')
                            <div>{{ $venta->direccion_envio }}</div>
                            @if ($venta->referencia_envio)<div class="text-white-50">Ref.: {{ $venta->referencia_envio }}</div>@endif
                        @else
                            <div>{{ $datos['direccion'] }}</div>
                            <div class="text-white-50">{{ $datos['horario'] }}</div>
                        @endif
                        <div class="mt-2"><i class="fas fa-mobile-screen text-warning me-1"></i>{{ $venta->telefono_contacto }}</div>
                        <div class="mt-2"><i class="fas fa-id-card text-warning me-1"></i>{{ $venta->cliente?->tipo_documento }} {{ $venta->cliente?->numero_documento }} · {{ $venta->cliente?->nombres }} {{ $venta->cliente?->apellidos }}</div>
                    </div>

                    <div class="bg-primary-subtle border border-primary border-opacity-25 rounded-4 p-4 small">
                        <div class="fw-bold mb-2"><i class="fas fa-wallet text-warning me-2"></i>Pagos</div>
                        @foreach ($venta->pagos as $pg)
                            <div class="d-flex justify-content-between">
                                <span>{{ ucfirst($pg->metodo_pago) }} <span class="text-white-50 font-monospace">{{ $pg->numero_operacion }}</span></span>
                                <span>S/ {{ number_format($pg->monto, 2) }}</span>
                            </div>
                        @endforeach
                    </div>

                    {{-- Comprobante en PDF --}}
                    @if (! in_array($venta->estado_pedido, ['por_confirmar', 'anulado'], true))
                        <div class="d-grid gap-2 mt-4">
                            <a href="{{ route('comprobante.pdf', ['numero' => $venta->numero_comprobante, 'descargar' => 1]) }}"
                               class="btn btn-warning rounded-pill fw-semibold" data-animar="fa-bounce">
                                <i class="fas fa-file-arrow-down me-2"></i>Descargar {{ $venta->tipo_comprobante }} (PDF)
                            </a>
                            <a href="{{ route('comprobante.pdf', $venta->numero_comprobante) }}" target="_blank"
                               class="btn btn-outline-light rounded-pill btn-sm">
                                <i class="fas fa-print me-2"></i>Ver o imprimir
                            </a>
                        </div>
                    @elseif ($venta->estado_pedido === 'por_confirmar')
                        <p class="small text-white-50 text-center mt-3 mb-0">
                            <i class="fas fa-file-invoice me-1"></i>Podrás descargar tu {{ $venta->tipo_comprobante }} cuando confirmemos el pago.
                        </p>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection
