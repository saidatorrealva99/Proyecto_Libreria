{{-- resources/views/reclamacion/constancia.blade.php  (archivo NUEVO)
     Hoja de reclamación registrada (constancia para el consumidor) --}}
@extends('layouts.tienda')

@section('titulo', 'Hoja de reclamación ' . $reclamacion->codigo)

@section('content')
@php
    $tienda = config('libreria');
    $r = $reclamacion;
@endphp
<section class="bg-black py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-9">

                @if (session('reclamo_ok'))
                    <div class="text-center bg-success-subtle border border-success rounded-4 p-4 mb-4 d-print-none">
                        <i class="fas fa-circle-check fa-3x text-success mb-2"></i>
                        <h2 class="fw-bold mb-1">Hoja de reclamación registrada</h2>
                        <p class="mb-0 text-success-emphasis">Guarda este número: <strong class="font-monospace fs-5">{{ $r->codigo }}</strong>.
                            Te responderemos al correo <strong>{{ $r->email }}</strong> en un plazo máximo de 15 días hábiles.</p>
                    </div>
                @endif

                <div class="d-flex flex-wrap justify-content-end gap-2 mb-3 d-print-none">
                    <button type="button" onclick="window.print()" class="btn btn-warning rounded-pill px-4 fw-semibold">
                        <i class="fas fa-print me-2"></i>Imprimir o guardar en PDF
                    </button>
                    <button type="button" class="btn btn-outline-light rounded-pill px-4" data-copiar="{{ url()->full() }}">
                        <i class="fas fa-link me-2"></i>Copiar enlace
                    </button>
                </div>

                <div class="bg-primary-subtle border border-warning border-opacity-50 rounded-4 p-4 p-lg-5">
                    {{-- Cabecera --}}
                    <div class="d-flex flex-wrap justify-content-between gap-3 border-bottom border-warning border-opacity-25 pb-3 mb-4">
                        <div>
                            <h4 class="fw-bold mb-1"><i class="fas fa-book text-warning me-2"></i>Libro de Reclamaciones</h4>
                            <div class="small text-white-50">{{ $tienda['nombre'] }} ({{ $tienda['titular'] }}) · RUC {{ $tienda['ruc'] }}<br>{{ $tienda['direccion'] }}</div>
                        </div>
                        <div class="text-end">
                            <div class="small text-white-50">Hoja de reclamación N.°</div>
                            <div class="fs-4 fw-bold font-monospace text-warning">{{ $r->codigo }}</div>
                            <div class="small">{{ $r->created_at->format('d/m/Y H:i') }}</div>
                            @if ($r->estado === 'respondido')
                                <span class="badge bg-success mt-1"><i class="fas fa-check me-1"></i>Respondido</span>
                            @else
                                <span class="badge bg-warning text-dark mt-1"><i class="fas fa-hourglass-half me-1"></i>En revisión</span>
                            @endif
                        </div>
                    </div>

                    {{-- 1. Consumidor --}}
                    <h6 class="fw-bold text-warning">1. Consumidor reclamante</h6>
                    <div class="row small g-2 mb-4">
                        <div class="col-md-6"><span class="text-white-50">Nombre:</span> {{ $r->nombre }}</div>
                        <div class="col-md-6"><span class="text-white-50">{{ $r->tipo_documento }}:</span> {{ $r->numero_documento }}</div>
                        <div class="col-md-6"><span class="text-white-50">Domicilio:</span> {{ $r->domicilio }}</div>
                        <div class="col-md-6"><span class="text-white-50">Celular:</span> {{ $r->telefono }} · <span class="text-white-50">Correo:</span> {{ $r->email }}</div>
                        @if ($r->menor_edad)
                            <div class="col-md-12"><span class="text-white-50">Menor de edad. Padre, madre o tutor:</span> {{ $r->apoderado }}</div>
                        @endif
                    </div>

                    {{-- 2. Bien --}}
                    <h6 class="fw-bold text-warning">2. Bien contratado</h6>
                    <div class="row small g-2 mb-4">
                        <div class="col-md-4"><span class="text-white-50">Tipo:</span> {{ ucfirst($r->tipo_bien) }}</div>
                        <div class="col-md-4"><span class="text-white-50">Pedido:</span> {{ $r->numero_pedido ?? '—' }}</div>
                        <div class="col-md-4"><span class="text-white-50">Monto:</span> {{ $r->monto !== null ? 'S/ ' . number_format($r->monto, 2) : '—' }}</div>
                        <div class="col-md-12"><span class="text-white-50">Descripción:</span> {{ $r->descripcion_bien }}</div>
                    </div>

                    {{-- 3. Detalle --}}
                    <h6 class="fw-bold text-warning">3. Detalle de la {{ $r->tipo }}</h6>
                    <p class="small mb-2">{!! nl2br(e($r->detalle)) !!}</p>
                    <p class="small mb-4"><span class="text-white-50">Pedido del consumidor:</span> {{ $r->pedido_consumidor }}</p>

                    {{-- 4. Respuesta --}}
                    <h6 class="fw-bold text-warning">4. Respuesta del proveedor</h6>
                    @if ($r->estado === 'respondido')
                        <div class="bg-black bg-opacity-50 rounded-3 p-3 small">
                            <p class="mb-2">{!! nl2br(e($r->respuesta)) !!}</p>
                            <div class="text-white-50">Respondido el {{ $r->respondido_at?->format('d/m/Y H:i') }}</div>
                        </div>
                    @else
                        <p class="small text-white-50 mb-0">
                            Aún en revisión. Responderemos a más tardar el <strong class="text-white">{{ $r->fecha_limite->translatedFormat('d \d\e F \d\e Y') }}</strong>.
                        </p>
                    @endif

                    <hr class="border-warning border-opacity-25">
                    <p class="small text-white-50 mb-0">
                        La formulación del reclamo no impide acudir a otras vías de solución de controversias ni es requisito previo para interponer una denuncia ante el INDECOPI.
                        El proveedor deberá dar respuesta al reclamo en un plazo no mayor a quince (15) días hábiles improrrogables.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
