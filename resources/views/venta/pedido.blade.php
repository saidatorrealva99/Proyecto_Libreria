{{-- resources/views/venta/pedido.blade.php  (archivo NUEVO, dentro de la carpeta venta que ya tienes)
     Ficha de un pedido web para ATENDERLO:  /pedidos-web/15 --}}
@extends('layouts.app')

@section('content')
@php
    [$textoEstado, $colorEstado, $iconoEstado] = \App\Models\Venta::ESTADOS_PEDIDO[$venta->estado_pedido] ?? ['—', 'secondary', 'fa-circle'];
    $pagado     = $venta->pagado;
    $saldo      = $venta->saldo;
    $esDelivery = $venta->entrega === 'delivery';
    $siguientes = \App\Http\Controllers\PedidoWebController::siguientesEstados($venta);
    $cerrado    = in_array($venta->estado_pedido, ['entregado', 'anulado'], true);
    $tienda     = config('libreria');

    // Línea de avance
    $pasos = $esDelivery
        ? ['por_confirmar' => 'Pago enviado', 'confirmado' => 'Pago confirmado', 'en_camino' => 'En camino', 'entregado' => 'Entregado']
        : ['por_confirmar' => 'Adelanto enviado', 'confirmado' => 'Pago confirmado', 'listo_recojo' => 'Listo para recoger', 'entregado' => 'Entregado'];
    $posicion = array_search($venta->estado_pedido, array_keys($pasos), true);

    // Botones para avanzar: estado => [texto, color, icono]
    $botones = [
        'listo_recojo' => ['Marcar listo para recoger', 'info',    'fa-store'],
        'en_camino'    => ['Enviar: en camino',         'info',    'fa-truck-fast'],
        'entregado'    => ['Marcar como entregado',     'success', 'fa-box-open'],
    ];

    // Mensaje de WhatsApp para avisar al cliente según el estado
    $nombreCliente = $venta->cliente?->nombres;
    $numero        = $venta->numero_comprobante;
    $mensajes = [
        'por_confirmar' => "Hola {$nombreCliente}, recibimos tu pedido {$numero} en {$tienda['nombre']}. Estamos verificando tu pago.",
        'confirmado'    => "Hola {$nombreCliente}, confirmamos el pago de tu pedido {$numero}. Ya lo estamos preparando.",
        'listo_recojo'  => "Hola {$nombreCliente}, tu pedido {$numero} ya está listo para recoger en {$tienda['direccion']} ({$tienda['horario']})."
                         . ($saldo > 0 ? ' Recuerda que tienes un saldo de S/ ' . number_format($saldo, 2) . '.' : ''),
        'en_camino'     => "Hola {$nombreCliente}, tu pedido {$numero} ya salió a reparto. Llegará a: {$venta->direccion_envio}.",
        'entregado'     => "Hola {$nombreCliente}, tu pedido {$numero} fue entregado. ¡Gracias por comprar en {$tienda['nombre']}!",
        'anulado'       => "Hola {$nombreCliente}, tu pedido {$numero} fue anulado. Escríbenos para coordinar la devolución de tu pago.",
    ];
    $telefono      = preg_replace('/\D/', '', (string) $venta->telefono_contacto);
    $enlaceCliente = 'https://wa.me/51' . $telefono . '?text=' . rawurlencode($mensajes[$venta->estado_pedido] ?? '');
@endphp

<div class="container py-3">

    {{-- ========== ENCABEZADO ========== --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3">
        <div>
            <a href="{{ route('pedidos-web.index') }}" class="text-decoration-none small">
                <i class="fas fa-arrow-left me-1"></i>Volver a Pedidos web
            </a>
            <h3 class="fw-bold mt-1 mb-0">
                <i class="fas fa-globe text-info me-1"></i>
                {{ ucfirst($venta->tipo_comprobante) }} <span class="font-monospace text-primary">{{ $venta->numero_comprobante }}</span>
            </h3>
            <small class="text-muted">
                <i class="far fa-calendar me-1"></i>{{ $venta->fecha_venta?->format('d/m/Y H:i') }} · {{ $venta->fecha_venta?->diffForHumans() }}
            </small>
        </div>
        <div class="d-flex flex-wrap align-items-center gap-2">
            <span class="badge rounded-pill bg-{{ $colorEstado }}-subtle text-{{ $colorEstado }}-emphasis border border-{{ $colorEstado }} fs-6 px-4 py-2">
                <i class="fas {{ $iconoEstado }} me-2"></i>{{ $textoEstado }}
            </span>
            <a href="{{ route('comprobante.pdf', $venta->numero_comprobante) }}" target="_blank" class="btn btn-danger rounded-pill btn-sm px-3 fw-semibold" title="Ver o imprimir el comprobante">
                <i class="fas fa-file-pdf me-1"></i>Comprobante PDF
            </a>
            <a href="{{ route('pedido.ver', $venta->numero_comprobante) }}" target="_blank" class="btn btn-outline-secondary rounded-pill btn-sm px-3" title="Así lo ve el cliente">
                <i class="fas fa-up-right-from-square me-1"></i>Vista del cliente
            </a>
        </div>
    </div>

    {{-- ========== MENSAJES ========== --}}
    @if ($message = Session::get('success'))
        <div class="alert alert-success d-flex align-items-center gap-2 shadow-sm">
            <i class="fas fa-check-circle fs-5"></i><span>{{ $message }}</span>
        </div>
    @endif
    @if ($message = Session::get('error'))
        <div class="alert alert-danger d-flex align-items-center gap-2 shadow-sm">
            <i class="fas fa-exclamation-circle fs-5"></i><span>{{ $message }}</span>
        </div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger shadow-sm">
            <i class="fas fa-exclamation-circle me-1"></i>{{ $errors->first() }}
        </div>
    @endif

    {{-- ========== LÍNEA DE AVANCE ========== --}}
    @if ($venta->estado_pedido === 'anulado')
        <div class="alert alert-danger d-flex align-items-center gap-2">
            <i class="fas fa-ban fs-5"></i>
            <span>{{ $venta->observaciones ?? 'Pedido anulado.' }}</span>
        </div>
    @elseif ($posicion !== false)
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body">
                <div class="row g-2 text-center">
                    @foreach ($pasos as $clave => $texto)
                        @php $hecho = $loop->index <= $posicion; @endphp
                        <div class="col-3">
                            <div class="border-top border-4 {{ $hecho ? 'border-success' : 'border-secondary border-opacity-25' }} pt-2">
                                <i class="fas {{ $hecho ? 'fa-circle-check text-success' : 'fa-circle text-secondary text-opacity-50' }} mb-1"></i>
                                <div class="small {{ $hecho ? 'fw-semibold' : 'text-muted' }}">{{ $texto }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <div class="row g-4">

        {{-- =================== IZQUIERDA: datos del pedido =================== --}}
        <div class="col-lg-7">

            {{-- Productos --}}
            <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
                <div class="card-header bg-white fw-bold py-3"><i class="fas fa-box-open text-primary me-2"></i>Productos para preparar</div>
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead class="table-light small text-uppercase text-secondary">
                            <tr><th class="ps-4">Producto</th><th class="text-center">Cant.</th><th class="text-end">Precio</th><th class="text-end pe-4">Subtotal</th></tr>
                        </thead>
                        <tbody>
                            @foreach ($venta->detalles as $d)
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center gap-2">
                                            @if ($d->producto?->imagen)
                                                <img src="{{ $d->producto->url_imagen }}" alt="" width="40" height="40" class="rounded object-fit-cover border">
                                            @else
                                                <span class="bg-light border rounded d-inline-flex align-items-center justify-content-center p-2"><i class="fas fa-book text-secondary fa-fw"></i></span>
                                            @endif
                                            <div>
                                                <div class="fw-semibold">{{ $d->producto?->nombre }}</div>
                                                <small class="text-muted font-monospace">{{ $d->producto?->codigo }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center"><span class="badge bg-primary rounded-pill fs-6">{{ $d->cantidad }}</span></td>
                                    <td class="text-end text-nowrap">S/ {{ number_format($d->precio_unitario, 2) }}</td>
                                    <td class="text-end pe-4 fw-semibold text-nowrap">S/ {{ number_format($d->subtotal, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="small">
                            <tr><td colspan="3" class="text-end text-muted">Subtotal</td><td class="text-end pe-4">S/ {{ number_format($venta->subtotal, 2) }}</td></tr>
                            @if ($venta->descuento > 0)
                                <tr><td colspan="3" class="text-end text-danger">Descuento</td><td class="text-end pe-4 text-danger">- S/ {{ number_format($venta->descuento, 2) }}</td></tr>
                            @endif
                            <tr><td colspan="3" class="text-end text-muted">Envío</td><td class="text-end pe-4">{{ $venta->costo_envio > 0 ? 'S/ ' . number_format($venta->costo_envio, 2) : 'Gratis' }}</td></tr>
                            <tr class="fs-6"><td colspan="3" class="text-end fw-bold">Total</td><td class="text-end pe-4 fw-bold text-primary">S/ {{ number_format($venta->total, 2) }}</td></tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <div class="row g-4">
                {{-- Cliente --}}
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm rounded-4 h-100">
                        <div class="card-body">
                            <h6 class="fw-bold mb-3"><i class="fas fa-user text-primary me-2"></i>Cliente</h6>
                            <div class="fw-semibold">{{ $venta->cliente?->nombres }} {{ $venta->cliente?->apellidos }}</div>
                            <div class="small text-muted mb-2">{{ $venta->cliente?->tipo_documento }} {{ $venta->cliente?->numero_documento }}</div>
                            <div class="small"><i class="fas fa-mobile-screen text-muted me-2"></i>{{ $venta->telefono_contacto }}</div>
                            @if ($venta->cliente?->email)
                                <div class="small text-break"><i class="fas fa-envelope text-muted me-2"></i>{{ $venta->cliente->email }}</div>
                            @endif
                            <a href="{{ $enlaceCliente }}" target="_blank" rel="noopener" class="btn btn-success btn-sm rounded-pill px-3 mt-3 fw-semibold">
                                <i class="fab fa-whatsapp me-1"></i>Avisar al cliente
                            </a>
                            <div class="form-text">Abre WhatsApp con un mensaje según el estado actual.</div>
                        </div>
                    </div>
                </div>

                {{-- Entrega --}}
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm rounded-4 h-100">
                        <div class="card-body">
                            @if ($esDelivery)
                                <h6 class="fw-bold mb-3"><i class="fas fa-truck-fast text-info me-2"></i>Envío a domicilio</h6>
                                <div class="fw-semibold">{{ $venta->direccion_envio }}</div>
                                @if ($venta->referencia_envio)
                                    <div class="small text-muted">Ref.: {{ $venta->referencia_envio }}</div>
                                @endif
                                <a href="https://www.google.com/maps/search/?api=1&query={{ rawurlencode($venta->direccion_envio) }}" target="_blank" rel="noopener" class="btn btn-outline-info btn-sm rounded-pill px-3 mt-3">
                                    <i class="fas fa-map-location-dot me-1"></i>Ver en el mapa
                                </a>
                            @else
                                <h6 class="fw-bold mb-3"><i class="fas fa-store text-success me-2"></i>Recojo en tienda</h6>
                                <div class="small">El cliente pasa a recoger a <strong>{{ $tienda['direccion'] }}</strong>.</div>
                                @if ($saldo > 0)
                                    <div class="alert alert-warning small py-2 mt-3 mb-0">
                                        <i class="fas fa-coins me-1"></i>Cobrar <strong>S/ {{ number_format($saldo, 2) }}</strong> al entregar.
                                    </div>
                                @endif
                            @endif
                            @if ($venta->empleado)
                                <hr>
                                <div class="small text-muted"><i class="fas fa-id-badge me-1"></i>Atiende: <strong class="text-dark">{{ $venta->empleado->nombres }} {{ $venta->empleado->apellidos }}</strong></div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- =================== DERECHA: pagos y acciones =================== --}}
        <div class="col-lg-5">

            {{-- Pagos --}}
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body">
                    <h6 class="fw-bold mb-3"><i class="fas fa-receipt text-primary me-2"></i>Pagos recibidos</h6>
                    @forelse ($venta->pagos->sortBy('fecha_pago') as $pago)
                        <div class="d-flex justify-content-between align-items-center border rounded-3 p-2 px-3 mb-2">
                            <div>
                                <span class="badge bg-primary-subtle text-primary-emphasis text-uppercase">{{ $pago->metodo_pago }}</span>
                                @if ($pago->numero_operacion)
                                    <div class="small mt-1">Operación: <strong class="font-monospace user-select-all">{{ $pago->numero_operacion }}</strong></div>
                                @endif
                                <small class="text-muted">{{ $pago->fecha_pago?->format('d/m/Y H:i') }}</small>
                            </div>
                            <strong class="text-success text-nowrap">S/ {{ number_format($pago->monto, 2) }}</strong>
                        </div>
                    @empty
                        <p class="text-muted small">Sin pagos registrados.</p>
                    @endforelse

                    <div class="bg-light rounded-3 p-3 mt-3">
                        <div class="d-flex justify-content-between"><span>Total</span><strong>S/ {{ number_format($venta->total, 2) }}</strong></div>
                        <div class="d-flex justify-content-between"><span>Pagado</span><strong class="text-success">S/ {{ number_format($pagado, 2) }}</strong></div>
                        <div class="d-flex justify-content-between"><span>Saldo</span><strong class="{{ $saldo > 0 ? 'text-danger' : 'text-success' }}">S/ {{ number_format($saldo, 2) }}</strong></div>
                    </div>
                </div>
            </div>

            {{-- ---------- ACCIÓN SEGÚN EL ESTADO ---------- --}}
            @if ($venta->estado_pedido === 'por_confirmar')
                {{-- 1) Confirmar el pago --}}
                <div class="card border-warning border-2 shadow rounded-4 mb-4">
                    <div class="card-body">
                        <h6 class="fw-bold mb-2"><i class="fas fa-magnifying-glass-dollar text-warning me-2"></i>Paso 1: confirma el pago</h6>
                        @if ($venta->vence_en)
                            <div class="alert alert-warning small py-2">
                                <i class="fas fa-stopwatch me-1"></i>Si no lo confirmas, se anulará solo <strong>{{ $venta->vence_en->diffForHumans() }}</strong>
                                ({{ $venta->vence_en->format('d/m/Y h:i a') }}).
                            </div>
                        @endif
                        <ol class="small text-muted ps-3">
                            <li>Abre tu app de Yape / Plin o tu banca por internet.</li>
                            <li>Busca la operación <strong class="text-dark font-monospace">{{ $venta->pagos->first()?->numero_operacion }}</strong>.</li>
                            <li>Comprueba que el monto sea <strong class="text-dark">S/ {{ number_format($pagado, 2) }}</strong>.</li>
                        </ol>
                        <form action="{{ route('pedidos-web.confirmar', $venta->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <label for="empleado_id" class="form-label small fw-semibold">Empleado que atiende</label>
                            <select name="empleado_id" id="empleado_id" class="form-select mb-3 @error('empleado_id') is-invalid @enderror" required>
                                <option value="">— Elige —</option>
                                @foreach ($empleados as $emp)
                                    <option value="{{ $emp->id }}" @selected(old('empleado_id', $empleadoSugerido) == $emp->id)>{{ $emp->nombres }} {{ $emp->apellidos }} ({{ $emp->cargo }})</option>
                                @endforeach
                            </select>
                            <button type="submit" class="btn btn-warning w-100 rounded-pill fw-bold py-2">
                                <i class="fas fa-circle-check me-2"></i>Sí, el pago llegó: confirmar
                            </button>
                        </form>
                        <div class="form-text text-center mt-2">¿No llegó el pago? Usa <strong>Anular pedido</strong> más abajo.</div>
                    </div>
                </div>
            @endif

            @if (in_array($venta->estado_pedido, ['confirmado', 'listo_recojo', 'en_camino'], true) && $saldo > 0)
                {{-- 2) Cobrar el saldo --}}
                <div class="card border-success border-2 shadow rounded-4 mb-4">
                    <div class="card-body">
                        <h6 class="fw-bold mb-3"><i class="fas fa-coins text-success me-2"></i>Cobrar saldo: S/ {{ number_format($saldo, 2) }}</h6>
                        <form action="{{ route('pedidos-web.saldo', $venta->id) }}" method="POST">
                            @csrf
                            <div class="row g-2 mb-3">
                                @foreach (['efectivo' => ['fa-money-bill-wave', 'Efectivo'], 'tarjeta' => ['fa-credit-card', 'Tarjeta'], 'yape' => ['fa-mobile-screen', 'Yape'], 'plin' => ['fa-mobile-screen-button', 'Plin'], 'transferencia' => ['fa-building-columns', 'Transf.']] as $valor => [$icono, $texto])
                                    <div class="col">
                                        <input type="radio" class="btn-check" name="metodo_pago" id="metodo-{{ $valor }}" value="{{ $valor }}" @checked(old('metodo_pago', 'efectivo') === $valor)>
                                        <label class="btn btn-outline-success w-100 py-2 small" for="metodo-{{ $valor }}">
                                            <i class="fas {{ $icono }} d-block mb-1"></i><span class="small">{{ $texto }}</span>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                            <div id="campo-operacion" class="mb-3">
                                <label for="numero_operacion" class="form-label small fw-semibold">N.° de operación / voucher</label>
                                <input type="text" name="numero_operacion" id="numero_operacion" value="{{ old('numero_operacion') }}" maxlength="30"
                                       class="form-control @error('numero_operacion') is-invalid @enderror" placeholder="Ej.: 12345678">
                                @error('numero_operacion')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" role="switch" name="entregar" id="entregar" value="1" @checked($venta->estado_pedido !== 'confirmado')>
                                <label class="form-check-label small" for="entregar">Entregar el pedido en este momento</label>
                            </div>
                            <button type="submit" class="btn btn-success w-100 rounded-pill fw-bold py-2">
                                <i class="fas fa-cash-register me-2"></i>Registrar cobro
                            </button>
                        </form>
                    </div>
                </div>
            @endif

            @if (count($siguientes))
                {{-- 3) Avanzar el pedido --}}
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-body">
                        <h6 class="fw-bold mb-3"><i class="fas fa-forward text-primary me-2"></i>Siguiente paso</h6>
                        @foreach ($siguientes as $siguiente)
                            @php [$texto, $color, $icono] = $botones[$siguiente]; @endphp
                            <form action="{{ route('pedidos-web.estado', $venta->id) }}" method="POST" class="mb-2">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="estado_pedido" value="{{ $siguiente }}">
                                <button type="submit" class="btn btn-{{ $color }} w-100 rounded-pill fw-bold py-2"
                                        @disabled($siguiente === 'entregado' && $saldo > 0)>
                                    <i class="fas {{ $icono }} me-2"></i>{{ $texto }}
                                </button>
                            </form>
                            @if ($siguiente === 'entregado' && $saldo > 0)
                                <div class="form-text text-center">Primero registra el cobro del saldo.</div>
                            @endif
                        @endforeach
                        <div class="form-text text-center">Después de cada paso, usa <strong>Avisar al cliente</strong>.</div>
                    </div>
                </div>
            @endif

            @if ($venta->estado_pedido === 'entregado')
                <div class="alert alert-success d-flex align-items-center gap-2 rounded-4">
                    <i class="fas fa-circle-check fa-2x"></i>
                    <div><strong>Venta completada.</strong><br><small>El pedido fue entregado y está totalmente pagado.</small></div>
                </div>
            @endif

            {{-- 4) Anular --}}
            @unless ($cerrado)
                <div class="card border-danger border-opacity-25 rounded-4">
                    <div class="card-body">
                        <button class="btn btn-outline-danger btn-sm rounded-pill px-3" type="button" data-bs-toggle="collapse" data-bs-target="#zonaAnular">
                            <i class="fas fa-ban me-1"></i>Anular pedido
                        </button>
                        <div class="collapse {{ $errors->has('motivo') ? 'show' : '' }}" id="zonaAnular">
                            <form action="{{ route('pedidos-web.anular', $venta->id) }}" method="POST" class="mt-3"
                                  onsubmit="return confirm('¿Anular el pedido {{ $venta->numero_comprobante }}? Los productos vuelven al stock.');">
                                @csrf
                                @method('PATCH')
                                <label for="motivo" class="form-label small fw-semibold">Motivo</label>
                                <input type="text" name="motivo" id="motivo" maxlength="200" value="{{ old('motivo') }}"
                                       class="form-control form-control-sm @error('motivo') is-invalid @enderror"
                                       list="motivos" placeholder="Ej.: El pago no llegó" required>
                                <datalist id="motivos">
                                    <option value="El pago no llegó">
                                    <option value="El monto pagado no coincide">
                                    <option value="El cliente pidió cancelar">
                                    <option value="El cliente no recogió el pedido">
                                </datalist>
                                @error('motivo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                @if ($pagado > 0)
                                    <div class="form-text text-danger"><i class="fas fa-triangle-exclamation me-1"></i>Si el pago sí llegó, tendrás que devolver S/ {{ number_format($pagado, 2) }} al cliente.</div>
                                @endif
                                <button type="submit" class="btn btn-danger btn-sm rounded-pill px-3 mt-2">
                                    <i class="fas fa-ban me-1"></i>Confirmar anulación
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endunless
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
    // El número de operación solo se pide si NO es efectivo
    document.addEventListener('DOMContentLoaded', function () {
        const campo = document.getElementById('campo-operacion');
        if (!campo) return;
        const radios = document.querySelectorAll('input[name="metodo_pago"]');
        const actualizar = () => {
            const elegido = document.querySelector('input[name="metodo_pago"]:checked');
            campo.classList.toggle('d-none', !elegido || elegido.value === 'efectivo');
        };
        radios.forEach(r => r.addEventListener('change', actualizar));
        actualizar();
    });
</script>
@endpush
