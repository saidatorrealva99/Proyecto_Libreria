{{-- resources/views/reclamacion/index.blade.php  (archivo NUEVO)
     Lista de reclamos y quejas para el personal:  /reclamos --}}
@extends('layouts.app')

@section('content')
@php
    $pestanas = [
        'pendiente'  => ['Por responder', 'warning', 'fa-hourglass-half'],
        'respondido' => ['Respondidos',   'success', 'fa-check'],
        'todos'      => ['Todos',         'secondary', 'fa-list'],
    ];
    $totales = ['pendiente' => $conteo['pendiente'] ?? 0, 'respondido' => $conteo['respondido'] ?? 0];
    $totales['todos'] = $totales['pendiente'] + $totales['respondido'];
@endphp
<div class="container-fluid px-4 py-3">
    <div class="card border-0 shadow rounded-4 overflow-hidden">

        {{-- ========== ENCABEZADO ========== --}}
        <div class="card-header bg-danger bg-gradient text-white border-0 p-4 position-relative overflow-hidden">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 position-relative">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-white bg-opacity-25 rounded-3 shadow-sm p-3 lh-1"><i class="fas fa-book fa-2x fa-fw"></i></div>
                    <div class="vr opacity-50"></div>
                    <div>
                        <h3 class="fw-bold mb-1">Libro de Reclamaciones
                            <span class="badge bg-white text-danger rounded-pill fs-6 align-middle ms-1">{{ count($reclamaciones) }}</span>
                        </h3>
                        <p class="mb-0 opacity-75">Plazo legal de respuesta: 15 días hábiles, improrrogables</p>
                    </div>
                </div>
                <a href="{{ route('reclamaciones.create') }}" target="_blank" class="btn btn-light rounded-pill px-4 fw-semibold text-danger shadow-sm">
                    <i class="fas fa-up-right-from-square me-2"></i>Ver formulario público
                </a>
            </div>
            <i class="fas fa-book fa-6x opacity-25 position-absolute top-50 end-0 translate-middle-y me-5 d-none d-xl-block"></i>
        </div>

        {{-- ========== PESTAÑAS ========== --}}
        <div class="bg-white border-bottom px-4 pt-3">
            <ul class="nav nav-tabs border-0">
                @foreach ($pestanas as $clave => [$texto, $color, $icono])
                    <li class="nav-item">
                        <a class="nav-link {{ $estado === $clave ? 'active fw-semibold text-' . $color . '-emphasis' : 'text-secondary' }}" href="{{ route('reclamos.index', ['estado' => $clave]) }}">
                            <i class="fas {{ $icono }} me-1"></i>{{ $texto }}
                            <span class="badge rounded-pill {{ $totales[$clave] > 0 ? 'bg-' . $color . ($color === 'warning' ? ' text-dark' : '') : 'bg-secondary-subtle text-secondary' }} ms-1">{{ $totales[$clave] }}</span>
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>

        {{-- ========== TABLA ========== --}}
        <div class="card-body bg-white p-4">
            <div class="table-responsive">
                <table id="example" data-titulo="Libro de Reclamaciones" class="table table-hover align-middle w-100">
                    <thead class="table-light text-uppercase small text-secondary">
                        <tr>
                            <th class="ps-4">#</th>
                            <th>Hoja N.°</th>
                            <th>Fecha</th>
                            <th>Consumidor</th>
                            <th>Tipo</th>
                            <th>Bien</th>
                            <th>Plazo</th>
                            <th>Estado</th>
                            <th class="text-end pe-4">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($reclamaciones as $r)
                            @php $dias = $r->dias_restantes; @endphp
                            <tr class="{{ $r->estado === 'pendiente' && $dias <= 3 ? 'table-danger' : '' }}">
                                <td class="ps-4 text-muted">{{ ++$i }}</td>
                                <td class="font-monospace fw-semibold">{{ $r->codigo }}</td>
                                <td class="small text-nowrap">{{ $r->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <div class="fw-semibold text-dark">{{ $r->nombre }}</div>
                                    <small class="text-muted">{{ $r->tipo_documento }} {{ $r->numero_documento }}</small>
                                </td>
                                <td>
                                    <span class="badge {{ $r->tipo === 'reclamo' ? 'bg-danger-subtle text-danger-emphasis' : 'bg-info-subtle text-info-emphasis' }}">{{ ucfirst($r->tipo) }}</span>
                                </td>
                                <td class="small">{{ \Illuminate\Support\Str::limit($r->descripcion_bien, 40) }}</td>
                                <td class="small text-nowrap">
                                    @if ($r->estado === 'respondido')
                                        <span class="text-success"><i class="fas fa-check me-1"></i>{{ $r->respondido_at?->format('d/m/Y') }}</span>
                                    @elseif ($dias < 0)
                                        <span class="text-danger fw-bold"><i class="fas fa-triangle-exclamation me-1"></i>Vencido</span>
                                    @else
                                        <span class="{{ $dias <= 3 ? 'text-danger fw-bold' : 'text-muted' }}">
                                            <i class="fas fa-stopwatch me-1"></i>{{ $dias }} día(s) hábil(es)
                                        </span>
                                        <div class="text-muted">hasta {{ $r->fecha_limite->format('d/m/Y') }}</div>
                                    @endif
                                </td>
                                <td>
                                    @if ($r->estado === 'respondido')
                                        <span class="badge rounded-pill bg-success-subtle text-success-emphasis"><i class="fas fa-check me-1"></i>Respondido</span>
                                    @else
                                        <span class="badge rounded-pill bg-warning-subtle text-warning-emphasis"><i class="fas fa-hourglass-half me-1"></i>Pendiente</span>
                                    @endif
                                </td>
                                <td class="text-end pe-4">
                                    <a href="{{ route('reclamos.show', $r->id) }}" class="btn btn-sm {{ $r->estado === 'pendiente' ? 'btn-danger' : 'btn-outline-secondary' }} rounded-pill px-3 fw-semibold text-nowrap">
                                        <i class="fas {{ $r->estado === 'pendiente' ? 'fa-reply' : 'fa-eye' }} me-1"></i>{{ $r->estado === 'pendiente' ? 'Responder' : 'Ver' }}
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <p class="small text-muted mb-0 mt-2"><i class="fas fa-circle-info me-1"></i>El plazo cuenta de lunes a viernes; revisa si hay feriados en medio.</p>
        </div>
    </div>
</div>
@endsection
