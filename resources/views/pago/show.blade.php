{{-- resources/views/pago/show.blade.php  (reemplaza TODO el archivo) --}}
@extends('layouts.app')

@section('content')
<div class="container py-3">

    <div class="card border-0 shadow rounded-4 overflow-hidden">

        {{-- ========== ENCABEZADO ========== --}}
        <div class="card-header bg-primary bg-gradient text-white border-0 p-4 position-relative overflow-hidden">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 position-relative">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-white bg-opacity-25 rounded-3 shadow-sm p-3 lh-1">
                        <i class="fas fa-credit-card fa-2x fa-fw"></i>
                    </div>
                    <div class="vr opacity-50"></div>
                    <div>
                        <h3 class="fw-bold mb-1">Detalle del Pago</h3>
                        <p class="mb-0 opacity-75">{{ $pago->venta?->numero_comprobante }} · S/ {{ number_format($pago->monto, 2) }}</p>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('pagos.index') }}" class="btn btn-outline-light rounded-pill px-4">
                        <i class="fas fa-arrow-left me-2"></i>Volver
                    </a>
                    <a href="{{ route('pagos.edit', $pago->id) }}" class="btn btn-light text-primary fw-semibold rounded-pill px-4 shadow-sm">
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
                        <div class="fw-semibold text-dark"><span class="font-monospace">{{ $pago->venta?->numero_comprobante }}</span> <small class="text-muted d-block">Total de la venta: S/ {{ number_format($pago->venta?->total, 2) }}</small></div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="bg-white border border-primary-subtle rounded-3 shadow-sm px-3 py-2 h-100">
                        <small class="text-uppercase text-secondary fw-bold d-block small"><i class="fas fa-wallet fa-fw text-primary me-1"></i>Método de pago</small>
                        <div class="fw-semibold text-dark">{{ ucfirst($pago->metodo_pago) }}</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="bg-white border border-primary-subtle rounded-3 shadow-sm px-3 py-2 h-100">
                        <small class="text-uppercase text-secondary fw-bold d-block small"><i class="fas fa-money-bill-wave fa-fw text-primary me-1"></i>Monto</small>
                        <div class="fw-semibold text-dark"><span class="fs-5 text-primary">S/ {{ number_format($pago->monto, 2) }}</span></div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="bg-white border border-primary-subtle rounded-3 shadow-sm px-3 py-2 h-100">
                        <small class="text-uppercase text-secondary fw-bold d-block small"><i class="fas fa-receipt fa-fw text-primary me-1"></i>N° de operación</small>
                        <div class="fw-semibold text-dark"><span class="font-monospace">{{ $pago->numero_operacion ?? '—' }}</span></div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="bg-white border border-primary-subtle rounded-3 shadow-sm px-3 py-2 h-100">
                        <small class="text-uppercase text-secondary fw-bold d-block small"><i class="fas fa-calendar-alt fa-fw text-primary me-1"></i>Fecha y hora</small>
                        <div class="fw-semibold text-dark">{{ $pago->fecha_pago ? \Illuminate\Support\Carbon::parse($pago->fecha_pago)->format('d/m/Y H:i') : '—' }}</div>
                    </div>
                </div>
            </div>

        </div>

        {{-- ========== PIE ========== --}}
        <div class="card-footer bg-white border-top px-4 py-3 small text-muted d-flex flex-wrap justify-content-between gap-2">
            <span><i class="far fa-calendar-plus me-1"></i>Registrado: {{ $pago->created_at ? \Illuminate\Support\Carbon::parse($pago->created_at)->format('d/m/Y H:i') : '—' }}</span>
            <span><i class="far fa-edit me-1"></i>Última modificación: {{ $pago->updated_at ? \Illuminate\Support\Carbon::parse($pago->updated_at)->format('d/m/Y H:i') : '—' }}</span>
        </div>
    </div>
</div>
@endsection
