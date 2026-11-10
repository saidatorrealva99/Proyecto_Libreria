{{-- resources/views/detalle-venta/show.blade.php  (reemplaza TODO el archivo) --}}
@extends('layouts.app')

@section('content')
<div class="container py-3">

    <div class="card border-0 shadow rounded-4 overflow-hidden">

        {{-- ========== ENCABEZADO ========== --}}
        <div class="card-header bg-primary bg-gradient text-white border-0 p-4 position-relative overflow-hidden">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 position-relative">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-white bg-opacity-25 rounded-3 shadow-sm p-3 lh-1">
                        <i class="fas fa-list-ul fa-2x fa-fw"></i>
                    </div>
                    <div class="vr opacity-50"></div>
                    <div>
                        <h3 class="fw-bold mb-1">Detalle del Producto Vendido</h3>
                        <p class="mb-0 opacity-75">{{ $detalleVenta->venta?->numero_comprobante }} · {{ $detalleVenta->producto?->nombre }}</p>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('detalle-ventas.index') }}" class="btn btn-outline-light rounded-pill px-4">
                        <i class="fas fa-arrow-left me-2"></i>Volver
                    </a>
                    <a href="{{ route('detalle-ventas.edit', $detalleVenta->id) }}" class="btn btn-light text-primary fw-semibold rounded-pill px-4 shadow-sm">
                        <i class="fas fa-pen me-2"></i>Editar
                    </a>
                </div>
            </div>
        </div>

        {{-- ========== DATOS ========== --}}
        <div class="card-body bg-light p-4">
            <div class="row g-2">
                <div class="col-md-6">
                    <div class="bg-white border border-primary-subtle rounded-3 shadow-sm px-3 py-2 h-100">
                        <small class="text-uppercase text-secondary fw-bold d-block small"><i class="fas fa-file-invoice fa-fw text-primary me-1"></i>Comprobante</small>
                        <div class="fw-semibold text-dark"><span class="font-monospace">{{ $detalleVenta->venta?->numero_comprobante }}</span></div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="bg-white border border-primary-subtle rounded-3 shadow-sm px-3 py-2 h-100">
                        <small class="text-uppercase text-secondary fw-bold d-block small"><i class="fas fa-book fa-fw text-primary me-1"></i>Producto</small>
                        <div class="fw-semibold text-dark">{{ $detalleVenta->producto?->codigo }} - {{ $detalleVenta->producto?->nombre }}</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="bg-white border border-primary-subtle rounded-3 shadow-sm px-3 py-2 h-100">
                        <small class="text-uppercase text-secondary fw-bold d-block small"><i class="fas fa-sort-numeric-up fa-fw text-primary me-1"></i>Cantidad</small>
                        <div class="fw-semibold text-dark">{{ $detalleVenta->cantidad }} <small class="text-muted">unidades</small></div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="bg-white border border-primary-subtle rounded-3 shadow-sm px-3 py-2 h-100">
                        <small class="text-uppercase text-secondary fw-bold d-block small"><i class="fas fa-tag fa-fw text-primary me-1"></i>Precio unitario</small>
                        <div class="fw-semibold text-dark">S/ {{ number_format($detalleVenta->precio_unitario, 2) }}</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="bg-white border border-primary-subtle rounded-3 shadow-sm px-3 py-2 h-100">
                        <small class="text-uppercase text-secondary fw-bold d-block small"><i class="fas fa-money-check-alt fa-fw text-primary me-1"></i>Subtotal</small>
                        <div class="fw-semibold text-dark"><span class="fs-5 text-primary">S/ {{ number_format($detalleVenta->subtotal, 2) }}</span></div>
                    </div>
                </div>
            </div>

        </div>

        {{-- ========== PIE ========== --}}
        <div class="card-footer bg-white border-top px-4 py-3 small text-muted d-flex flex-wrap justify-content-between gap-2">
            <span><i class="far fa-calendar-plus me-1"></i>Registrado: {{ $detalleVenta->created_at ? \Illuminate\Support\Carbon::parse($detalleVenta->created_at)->format('d/m/Y H:i') : '—' }}</span>
            <span><i class="far fa-edit me-1"></i>Última modificación: {{ $detalleVenta->updated_at ? \Illuminate\Support\Carbon::parse($detalleVenta->updated_at)->format('d/m/Y H:i') : '—' }}</span>
        </div>
    </div>
</div>
@endsection
