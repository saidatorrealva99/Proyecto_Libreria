{{-- resources/views/empleado/show.blade.php  (reemplaza TODO el archivo) --}}
@extends('layouts.app')

@section('content')
<div class="container py-3">

    <div class="card border-0 shadow rounded-4 overflow-hidden">

        {{-- ========== ENCABEZADO ========== --}}
        <div class="card-header bg-primary bg-gradient text-white border-0 p-4 position-relative overflow-hidden">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 position-relative">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-white bg-opacity-25 rounded-3 shadow-sm p-3 lh-1">
                        <i class="fas fa-id-badge fa-2x fa-fw"></i>
                    </div>
                    <div class="vr opacity-50"></div>
                    <div>
                        <h3 class="fw-bold mb-1">Detalle del Empleado</h3>
                        <p class="mb-0 opacity-75">{{ $empleado->nombres }} {{ $empleado->apellidos }} · {{ ucfirst($empleado->cargo) }}</p>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('empleados.index') }}" class="btn btn-outline-light rounded-pill px-4">
                        <i class="fas fa-arrow-left me-2"></i>Volver
                    </a>
                    <a href="{{ route('empleados.edit', $empleado->id) }}" class="btn btn-light text-primary fw-semibold rounded-pill px-4 shadow-sm">
                        <i class="fas fa-pen me-2"></i>Editar
                    </a>
                </div>
            </div>
        </div>

        {{-- ========== DATOS ========== --}}
        <div class="card-body bg-light p-4">
            <div class="row g-2">
                <div class="col-md-4">
                    <div class="bg-white border border-primary-subtle rounded-3 shadow-sm px-3 py-2 h-100">
                        <small class="text-uppercase text-secondary fw-bold d-block small"><i class="fas fa-id-card fa-fw text-primary me-1"></i>DNI</small>
                        <div class="fw-semibold text-dark"><span class="font-monospace">{{ $empleado->dni }}</span></div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="bg-white border border-primary-subtle rounded-3 shadow-sm px-3 py-2 h-100">
                        <small class="text-uppercase text-secondary fw-bold d-block small"><i class="fas fa-user fa-fw text-primary me-1"></i>Nombres</small>
                        <div class="fw-semibold text-dark">{{ $empleado->nombres }}</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="bg-white border border-primary-subtle rounded-3 shadow-sm px-3 py-2 h-100">
                        <small class="text-uppercase text-secondary fw-bold d-block small"><i class="fas fa-user-tag fa-fw text-primary me-1"></i>Apellidos</small>
                        <div class="fw-semibold text-dark">{{ $empleado->apellidos }}</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="bg-white border border-primary-subtle rounded-3 shadow-sm px-3 py-2 h-100">
                        <small class="text-uppercase text-secondary fw-bold d-block small"><i class="fas fa-briefcase fa-fw text-primary me-1"></i>Cargo</small>
                        <div class="fw-semibold text-dark">{{ ucfirst($empleado->cargo) }}</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="bg-white border border-primary-subtle rounded-3 shadow-sm px-3 py-2 h-100">
                        <small class="text-uppercase text-secondary fw-bold d-block small"><i class="fas fa-envelope fa-fw text-primary me-1"></i>Correo electrónico</small>
                        <div class="fw-semibold text-dark">{{ $empleado->email }}</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="bg-white border border-primary-subtle rounded-3 shadow-sm px-3 py-2 h-100">
                        <small class="text-uppercase text-secondary fw-bold d-block small"><i class="fas fa-mobile-alt fa-fw text-primary me-1"></i>Celular</small>
                        <div class="fw-semibold text-dark">{{ $empleado->telefono ?? '—' }}</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="bg-white border border-primary-subtle rounded-3 shadow-sm px-3 py-2 h-100">
                        <small class="text-uppercase text-secondary fw-bold d-block small"><i class="fas fa-money-bill-wave fa-fw text-primary me-1"></i>Sueldo</small>
                        <div class="fw-semibold text-dark">S/ {{ number_format($empleado->sueldo, 2) }}</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="bg-white border border-primary-subtle rounded-3 shadow-sm px-3 py-2 h-100">
                        <small class="text-uppercase text-secondary fw-bold d-block small"><i class="fas fa-calendar-check fa-fw text-primary me-1"></i>Fecha de contratación</small>
                        <div class="fw-semibold text-dark">{{ $empleado->fecha_contratacion ? \Illuminate\Support\Carbon::parse($empleado->fecha_contratacion)->format('d/m/Y') : '—' }}</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="bg-white border border-primary-subtle rounded-3 shadow-sm px-3 py-2 h-100">
                        <small class="text-uppercase text-secondary fw-bold d-block small"><i class="fas fa-toggle-on fa-fw text-primary me-1"></i>Estado</small>
                        <div class="fw-semibold text-dark">@if ($empleado->estado)<span class="badge rounded-pill bg-success-subtle text-success-emphasis"><i class="fas fa-check-circle me-1"></i>Activo</span>@else<span class="badge rounded-pill bg-secondary-subtle text-secondary-emphasis"><i class="fas fa-ban me-1"></i>Cesado</span>@endif</div>
                    </div>
                </div>
            </div>

        </div>

        {{-- ========== PIE ========== --}}
        <div class="card-footer bg-white border-top px-4 py-3 small text-muted d-flex flex-wrap justify-content-between gap-2">
            <span><i class="far fa-calendar-plus me-1"></i>Registrado: {{ $empleado->created_at ? \Illuminate\Support\Carbon::parse($empleado->created_at)->format('d/m/Y H:i') : '—' }}</span>
            <span><i class="far fa-edit me-1"></i>Última modificación: {{ $empleado->updated_at ? \Illuminate\Support\Carbon::parse($empleado->updated_at)->format('d/m/Y H:i') : '—' }}</span>
        </div>
    </div>
</div>
@endsection
