{{-- resources/views/cliente/show.blade.php  (reemplaza TODO el archivo) --}}
@extends('layouts.app')

@section('content')
<div class="container py-3">

    <div class="card border-0 shadow rounded-4 overflow-hidden">

        {{-- ========== ENCABEZADO ========== --}}
        <div class="card-header bg-primary bg-gradient text-white border-0 p-4 position-relative overflow-hidden">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 position-relative">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-white bg-opacity-25 rounded-3 shadow-sm p-3 lh-1">
                        <i class="fas fa-user fa-2x fa-fw"></i>
                    </div>
                    <div class="vr opacity-50"></div>
                    <div>
                        <h3 class="fw-bold mb-1">Detalle del Cliente</h3>
                        <p class="mb-0 opacity-75">{{ $cliente->tipo_documento }} {{ $cliente->numero_documento }} · {{ $cliente->nombres }} {{ $cliente->apellidos }}</p>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('clientes.index') }}" class="btn btn-outline-light rounded-pill px-4">
                        <i class="fas fa-arrow-left me-2"></i>Volver
                    </a>
                    <a href="{{ route('clientes.edit', $cliente->id) }}" class="btn btn-light text-primary fw-semibold rounded-pill px-4 shadow-sm">
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
                        <small class="text-uppercase text-secondary fw-bold d-block small"><i class="fas fa-id-card fa-fw text-primary me-1"></i>Tipo de documento</small>
                        <div class="fw-semibold text-dark">{{ $cliente->tipo_documento }}</div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="bg-white border border-primary-subtle rounded-3 shadow-sm px-3 py-2 h-100">
                        <small class="text-uppercase text-secondary fw-bold d-block small"><i class="fas fa-hashtag fa-fw text-primary me-1"></i>Número de documento</small>
                        <div class="fw-semibold text-dark"><span class="font-monospace">{{ $cliente->numero_documento }}</span></div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="bg-white border border-primary-subtle rounded-3 shadow-sm px-3 py-2 h-100">
                        <small class="text-uppercase text-secondary fw-bold d-block small"><i class="fas fa-user fa-fw text-primary me-1"></i>Nombres / Razón social</small>
                        <div class="fw-semibold text-dark">{{ $cliente->nombres }}</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="bg-white border border-primary-subtle rounded-3 shadow-sm px-3 py-2 h-100">
                        <small class="text-uppercase text-secondary fw-bold d-block small"><i class="fas fa-user-tag fa-fw text-primary me-1"></i>Apellidos</small>
                        <div class="fw-semibold text-dark">{{ $cliente->apellidos ?? '—' }}</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="bg-white border border-primary-subtle rounded-3 shadow-sm px-3 py-2 h-100">
                        <small class="text-uppercase text-secondary fw-bold d-block small"><i class="fas fa-envelope fa-fw text-primary me-1"></i>Correo electrónico</small>
                        <div class="fw-semibold text-dark">{{ $cliente->email ?? '—' }}</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="bg-white border border-primary-subtle rounded-3 shadow-sm px-3 py-2 h-100">
                        <small class="text-uppercase text-secondary fw-bold d-block small"><i class="fas fa-phone fa-fw text-primary me-1"></i>Teléfono</small>
                        <div class="fw-semibold text-dark">{{ $cliente->telefono ?? '—' }}</div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="bg-white border border-primary-subtle rounded-3 shadow-sm px-3 py-2 h-100">
                        <small class="text-uppercase text-secondary fw-bold d-block small"><i class="fas fa-map-marker-alt fa-fw text-primary me-1"></i>Dirección</small>
                        <div class="fw-semibold text-dark">{{ $cliente->direccion ?? '—' }}</div>
                    </div>
                </div>
            </div>

            <h6 class="text-primary fw-bold text-uppercase small border-bottom border-primary-subtle pb-2 mt-4 mb-2">
                <i class="fas fa-shopping-bag me-2"></i>Historial de compras
                <span class="badge rounded-pill bg-primary-subtle text-primary-emphasis ms-1">{{ $cliente->ventas->count() }}</span>
            </h6>
            <div class="table-responsive bg-white border border-primary-subtle rounded-3 shadow-sm">
                <table class="table table-sm table-hover align-middle mb-0">
                    <thead class="table-light text-uppercase small text-secondary"><tr><th>Comprobante</th><th>Fecha</th><th class="text-end">Total</th><th>Estado</th></tr></thead>
                    <tbody>
                        @forelse ($cliente->ventas as $v)
                        <tr>
                            <td><span class="font-monospace">{{ $v->numero_comprobante }}</span></td>
                            <td class="small">{{ \Illuminate\Support\Carbon::parse($v->fecha_venta)->format('d/m/Y H:i') }}</td>
                            <td class="text-end fw-semibold">S/ {{ number_format($v->total, 2) }}</td>
                            <td>{{ ucfirst($v->estado) }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center text-muted py-3">Este cliente aún no tiene compras.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- ========== PIE ========== --}}
        <div class="card-footer bg-white border-top px-4 py-3 small text-muted d-flex flex-wrap justify-content-between gap-2">
            <span><i class="far fa-calendar-plus me-1"></i>Registrado: {{ $cliente->created_at ? \Illuminate\Support\Carbon::parse($cliente->created_at)->format('d/m/Y H:i') : '—' }}</span>
            <span><i class="far fa-edit me-1"></i>Última modificación: {{ $cliente->updated_at ? \Illuminate\Support\Carbon::parse($cliente->updated_at)->format('d/m/Y H:i') : '—' }}</span>
        </div>
    </div>
</div>
@endsection
