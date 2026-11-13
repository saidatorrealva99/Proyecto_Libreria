{{-- resources/views/reclamacion/show.blade.php  (archivo NUEVO)
     Ver y responder una hoja de reclamación:  /reclamos/5 --}}
@extends('layouts.app')

@section('content')
@php
    $r      = $reclamacion;
    $dias   = $r->dias_restantes;
    $tienda = config('libreria');
    $asunto = 'Respuesta a su hoja de reclamación N.° ' . $r->codigo . ' - ' . $tienda['nombre'];
    $cuerpo = "Estimado(a) {$r->nombre}:\n\nEn atención a su " . $r->tipo . " registrado(a) el " . $r->created_at->format('d/m/Y')
            . " con la hoja N.° {$r->codigo}, le informamos lo siguiente:\n\n" . ($r->respuesta ?? '')
            . "\n\nPuede ver su hoja completa aquí: {$enlaceCliente}\n\nAtentamente,\n{$tienda['nombre']}\nRUC {$tienda['ruc']}";
    $telefono = preg_replace('/\D/', '', (string) $r->telefono);
@endphp
<div class="container py-3">

    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3">
        <div>
            <a href="{{ route('reclamos.index') }}" class="text-decoration-none small"><i class="fas fa-arrow-left me-1"></i>Volver al Libro de Reclamaciones</a>
            <h3 class="fw-bold mt-1 mb-0">
                <span class="badge {{ $r->tipo === 'reclamo' ? 'bg-danger' : 'bg-info text-dark' }} fs-6 align-middle me-1">{{ ucfirst($r->tipo) }}</span>
                Hoja N.° <span class="font-monospace text-danger">{{ $r->codigo }}</span>
            </h3>
            <small class="text-muted"><i class="far fa-calendar me-1"></i>{{ $r->created_at->format('d/m/Y H:i') }}</small>
        </div>
        <a href="{{ $enlaceCliente }}" target="_blank" class="btn btn-outline-secondary rounded-pill btn-sm px-3">
            <i class="fas fa-print me-1"></i>Hoja para imprimir
        </a>
    </div>

    @if ($message = Session::get('success'))
        <div class="alert alert-success d-flex align-items-center gap-2"><i class="fas fa-check-circle"></i><span>{{ $message }}</span></div>
    @endif
    @if ($message = Session::get('error'))
        <div class="alert alert-danger d-flex align-items-center gap-2"><i class="fas fa-exclamation-circle"></i><span>{{ $message }}</span></div>
    @endif

    {{-- Plazo --}}
    @if ($r->estado === 'pendiente')
        <div class="alert {{ $dias < 0 ? 'alert-danger' : ($dias <= 3 ? 'alert-warning' : 'alert-info') }} d-flex align-items-center gap-3">
            <i class="fas fa-stopwatch fa-2x"></i>
            <div>
                @if ($dias < 0)
                    <strong>¡Plazo vencido!</strong> Debió responderse hasta el {{ $r->fecha_limite->format('d/m/Y') }}. Respóndelo cuanto antes.
                @else
                    Quedan <strong>{{ $dias }} día(s) hábil(es)</strong> para responder (hasta el <strong>{{ $r->fecha_limite->translatedFormat('l d \d\e F') }}</strong>).
                @endif
            </div>
        </div>
    @endif

    <div class="row g-4">
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body">
                    <h6 class="fw-bold mb-3"><i class="fas fa-user text-primary me-2"></i>Consumidor</h6>
                    <div class="row small g-2">
                        <div class="col-md-6"><span class="text-muted">Nombre:</span> <strong>{{ $r->nombre }}</strong></div>
                        <div class="col-md-6"><span class="text-muted">{{ $r->tipo_documento }}:</span> {{ $r->numero_documento }}</div>
                        <div class="col-md-12"><span class="text-muted">Domicilio:</span> {{ $r->domicilio }}</div>
                        <div class="col-md-6"><span class="text-muted">Celular:</span> {{ $r->telefono }}</div>
                        <div class="col-md-6 text-break"><span class="text-muted">Correo:</span> {{ $r->email }}</div>
                        @if ($r->menor_edad)
                            <div class="col-md-12"><span class="badge bg-warning text-dark">Menor de edad</span> Apoderado: {{ $r->apoderado }}</div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body">
                    <h6 class="fw-bold mb-3"><i class="fas fa-box text-primary me-2"></i>Bien contratado</h6>
                    <div class="row small g-2">
                        <div class="col-md-4"><span class="text-muted">Tipo:</span> {{ ucfirst($r->tipo_bien) }}</div>
                        <div class="col-md-4"><span class="text-muted">Pedido:</span>
                            @if ($r->numero_pedido)
                                <a href="{{ route('comprobante.pdf', $r->numero_pedido) }}" target="_blank" class="font-monospace">{{ $r->numero_pedido }}</a>
                            @else
                                —
                            @endif
                        </div>
                        <div class="col-md-4"><span class="text-muted">Monto:</span> {{ $r->monto !== null ? 'S/ ' . number_format($r->monto, 2) : '—' }}</div>
                        <div class="col-md-12"><span class="text-muted">Descripción:</span> {{ $r->descripcion_bien }}</div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body">
                    <h6 class="fw-bold mb-3"><i class="fas fa-comment-dots text-primary me-2"></i>Detalle de la {{ $r->tipo }}</h6>
                    <p class="small">{!! nl2br(e($r->detalle)) !!}</p>
                    <div class="bg-light rounded-3 p-3 small"><strong>Pedido del consumidor:</strong> {{ $r->pedido_consumidor }}</div>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            @if ($r->estado === 'pendiente')
                <div class="card border-danger border-2 shadow rounded-4">
                    <div class="card-body">
                        <h6 class="fw-bold mb-3"><i class="fas fa-reply text-danger me-2"></i>Respuesta del proveedor</h6>
                        <form action="{{ route('reclamos.responder', $r->id) }}" method="POST"
                              onsubmit="return confirm('¿Registrar esta respuesta? Ya no se podrá cambiar.');">
                            @csrf
                            @method('PATCH')
                            <textarea name="respuesta" rows="9" maxlength="3000" class="form-control mb-2 @error('respuesta') is-invalid @enderror"
                                      placeholder="Explica qué se revisó y qué solución se ofrece (cambio, devolución, etc.).">{{ old('respuesta') }}</textarea>
                            @error('respuesta')<div class="invalid-feedback d-block mb-2">{{ $message }}</div>@enderror
                            <button type="submit" class="btn btn-danger w-100 rounded-pill fw-bold py-2">
                                <i class="fas fa-paper-plane me-2"></i>Registrar respuesta
                            </button>
                        </form>
                        <div class="form-text">Después podrás enviarla al consumidor por correo con un clic.</div>
                    </div>
                </div>
            @else
                <div class="card border-success border-2 shadow-sm rounded-4">
                    <div class="card-body">
                        <h6 class="fw-bold mb-3"><i class="fas fa-check text-success me-2"></i>Respuesta registrada</h6>
                        <p class="small">{!! nl2br(e($r->respuesta)) !!}</p>
                        <div class="small text-muted mb-3">
                            {{ $r->respondido_at?->format('d/m/Y H:i') }} · por {{ $r->responsable?->name ?? '—' }}
                        </div>
                        <div class="d-grid gap-2">
                            <a href="mailto:{{ $r->email }}?subject={{ rawurlencode($asunto) }}&body={{ rawurlencode($cuerpo) }}" class="btn btn-primary rounded-pill fw-semibold">
                                <i class="fas fa-envelope me-2"></i>Enviar por correo
                            </a>
                            <a href="https://wa.me/51{{ $telefono }}?text={{ rawurlencode('Hola ' . $r->nombre . ', respondimos su hoja de reclamación N.° ' . $r->codigo . '. Puede verla aquí: ' . $enlaceCliente) }}"
                               target="_blank" rel="noopener" class="btn btn-success rounded-pill fw-semibold">
                                <i class="fab fa-whatsapp me-2"></i>Avisar por WhatsApp
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
