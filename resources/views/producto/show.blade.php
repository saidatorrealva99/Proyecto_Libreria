{{-- resources/views/producto/show.blade.php  (reemplaza TODO el archivo) --}}
@extends('layouts.app')

@section('content')
<div class="container py-3">

    <div class="card border-0 shadow rounded-4 overflow-hidden">

        {{-- ========== ENCABEZADO ========== --}}
        <div class="card-header bg-primary bg-gradient text-white border-0 p-4 position-relative overflow-hidden">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 position-relative">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-white bg-opacity-25 rounded-3 shadow-sm p-3 lh-1">
                        <i class="fas fa-book fa-2x fa-fw"></i>
                    </div>
                    <div class="vr opacity-50"></div>
                    <div>
                        <h3 class="fw-bold mb-1">Detalle del Producto</h3>
                        <p class="mb-0 opacity-75">{{ $producto->codigo }} · {{ $producto->nombre }}</p>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('productos.index') }}" class="btn btn-outline-light rounded-pill px-4">
                        <i class="fas fa-arrow-left me-2"></i>Volver
                    </a>
                    <a href="{{ route('productos.edit', $producto->id) }}" class="btn btn-light text-primary fw-semibold rounded-pill px-4 shadow-sm">
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
                        <small class="text-uppercase text-secondary fw-bold d-block small"><i class="fas fa-barcode fa-fw text-primary me-1"></i>Código</small>
                        <div class="fw-semibold text-dark"><span class="font-monospace">{{ $producto->codigo }}</span></div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="bg-white border border-primary-subtle rounded-3 shadow-sm px-3 py-2 h-100">
                        <small class="text-uppercase text-secondary fw-bold d-block small"><i class="fas fa-book fa-fw text-primary me-1"></i>Nombre</small>
                        <div class="fw-semibold text-dark">{{ $producto->nombre }}</div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="bg-white border border-primary-subtle rounded-3 shadow-sm px-3 py-2 h-100">
                        <small class="text-uppercase text-secondary fw-bold d-block small"><i class="fas fa-align-left fa-fw text-primary me-1"></i>Descripción</small>
                        <div class="fw-semibold text-dark">{{ $producto->descripcion ?? '—' }}</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="bg-white border border-primary-subtle rounded-3 shadow-sm px-3 py-2 h-100">
                        <small class="text-uppercase text-secondary fw-bold d-block small"><i class="fas fa-tags fa-fw text-primary me-1"></i>Categoría</small>
                        <div class="fw-semibold text-dark">{{ $producto->categoria?->nombre ?? '—' }}</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="bg-white border border-primary-subtle rounded-3 shadow-sm px-3 py-2 h-100">
                        <small class="text-uppercase text-secondary fw-bold d-block small"><i class="fas fa-feather-alt fa-fw text-primary me-1"></i>Autor</small>
                        <div class="fw-semibold text-dark">{{ $producto->autor ? $producto->autor->nombres . ' ' . $producto->autor->apellidos : 'Sin autor (útil / papelería)' }}</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="bg-white border border-primary-subtle rounded-3 shadow-sm px-3 py-2 h-100">
                        <small class="text-uppercase text-secondary fw-bold d-block small"><i class="fas fa-hashtag fa-fw text-primary me-1"></i>ISBN</small>
                        <div class="fw-semibold text-dark"><span class="font-monospace">{{ $producto->isbn ?? '—' }}</span></div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="bg-white border border-primary-subtle rounded-3 shadow-sm px-3 py-2 h-100">
                        <small class="text-uppercase text-secondary fw-bold d-block small"><i class="fas fa-building fa-fw text-primary me-1"></i>Editorial / Marca</small>
                        <div class="fw-semibold text-dark">{{ $producto->marca_editorial ?? '—' }}</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="bg-white border border-primary-subtle rounded-3 shadow-sm px-3 py-2 h-100">
                        <small class="text-uppercase text-secondary fw-bold d-block small"><i class="fas fa-money-bill-wave fa-fw text-primary me-1"></i>Precio de venta</small>
                        <div class="fw-semibold text-dark"><span class="fs-5 text-primary">S/ {{ number_format($producto->precio_venta, 2) }}</span></div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="bg-white border border-primary-subtle rounded-3 shadow-sm px-3 py-2 h-100">
                        <small class="text-uppercase text-secondary fw-bold d-block small"><i class="fas fa-boxes fa-fw text-primary me-1"></i>Stock actual</small>
                        <div class="fw-semibold text-dark"><span class="fs-5">{{ $producto->stock }}</span> <small class="text-muted">unidades</small></div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="bg-white border border-primary-subtle rounded-3 shadow-sm px-3 py-2 h-100">
                        <small class="text-uppercase text-secondary fw-bold d-block small"><i class="fas fa-exclamation-triangle fa-fw text-primary me-1"></i>Stock mínimo</small>
                        <div class="fw-semibold text-dark">{{ $producto->stock_minimo }} <small class="text-muted">unidades</small></div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="bg-white border border-primary-subtle rounded-3 shadow-sm px-3 py-2 h-100">
                        <small class="text-uppercase text-secondary fw-bold d-block small"><i class="fas fa-toggle-on fa-fw text-primary me-1"></i>Estado</small>
                        <div class="fw-semibold text-dark">@if (! $producto->estado)<span class="badge rounded-pill bg-secondary-subtle text-secondary-emphasis"><i class="fas fa-ban me-1"></i>Descontinuado</span>@elseif ($producto->stock == 0)<span class="badge rounded-pill bg-danger-subtle text-danger-emphasis"><i class="fas fa-times-circle me-1"></i>Agotado</span>@elseif ($producto->stock <= $producto->stock_minimo)<span class="badge rounded-pill bg-warning-subtle text-warning-emphasis"><i class="fas fa-exclamation-triangle me-1"></i>Stock bajo</span>@else<span class="badge rounded-pill bg-success-subtle text-success-emphasis"><i class="fas fa-check-circle me-1"></i>Disponible</span>@endif</div>
                    </div>
                </div>
            </div>

        </div>

        {{-- ========== PIE ========== --}}
        <div class="card-footer bg-white border-top px-4 py-3 small text-muted d-flex flex-wrap justify-content-between gap-2">
            <span><i class="far fa-calendar-plus me-1"></i>Registrado: {{ $producto->created_at ? \Illuminate\Support\Carbon::parse($producto->created_at)->format('d/m/Y H:i') : '—' }}</span>
            <span><i class="far fa-edit me-1"></i>Última modificación: {{ $producto->updated_at ? \Illuminate\Support\Carbon::parse($producto->updated_at)->format('d/m/Y H:i') : '—' }}</span>
        </div>
    </div>
</div>
@endsection
