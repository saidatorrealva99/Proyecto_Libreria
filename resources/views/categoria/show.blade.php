{{-- resources/views/categoria/show.blade.php  (reemplaza TODO el archivo) --}}
@extends('layouts.app')

@section('content')
<div class="container py-3">

    <div class="card border-0 shadow rounded-4 overflow-hidden">

        {{-- ========== ENCABEZADO ========== --}}
        <div class="card-header bg-primary bg-gradient text-white border-0 p-4 position-relative overflow-hidden">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 position-relative">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-white bg-opacity-25 rounded-3 shadow-sm p-3 lh-1">
                        <i class="fas fa-tags fa-2x fa-fw"></i>
                    </div>
                    <div class="vr opacity-50"></div>
                    <div>
                        <h3 class="fw-bold mb-1">Detalle de la Categoría</h3>
                        <p class="mb-0 opacity-75">{{ $categoria->nombre }}</p>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('categorias.index') }}" class="btn btn-outline-light rounded-pill px-4">
                        <i class="fas fa-arrow-left me-2"></i>Volver
                    </a>
                    <a href="{{ route('categorias.edit', $categoria->id) }}" class="btn btn-light text-primary fw-semibold rounded-pill px-4 shadow-sm">
                        <i class="fas fa-pen me-2"></i>Editar
                    </a>
                </div>
            </div>
        </div>

        {{-- ========== DATOS ========== --}}
        <div class="card-body bg-light p-4">
            <div class="row g-2">
                <div class="col-md-8">
                    <div class="bg-white border border-primary-subtle rounded-3 shadow-sm px-3 py-2 h-100">
                        <small class="text-uppercase text-secondary fw-bold d-block small"><i class="fas fa-tag fa-fw text-primary me-1"></i>Nombre</small>
                        <div class="fw-semibold text-dark">{{ $categoria->nombre }}</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="bg-white border border-primary-subtle rounded-3 shadow-sm px-3 py-2 h-100">
                        <small class="text-uppercase text-secondary fw-bold d-block small"><i class="fas fa-toggle-on fa-fw text-primary me-1"></i>Estado</small>
                        <div class="fw-semibold text-dark">@if ($categoria->estado)<span class="badge rounded-pill bg-success-subtle text-success-emphasis"><i class="fas fa-check-circle me-1"></i>Activa</span>@else<span class="badge rounded-pill bg-secondary-subtle text-secondary-emphasis"><i class="fas fa-ban me-1"></i>Inactiva</span>@endif</div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="bg-white border border-primary-subtle rounded-3 shadow-sm px-3 py-2 h-100">
                        <small class="text-uppercase text-secondary fw-bold d-block small"><i class="fas fa-align-left fa-fw text-primary me-1"></i>Descripción</small>
                        <div class="fw-semibold text-dark">{{ $categoria->descripcion ?? '—' }}</div>
                    </div>
                </div>
            </div>

            <h6 class="text-primary fw-bold text-uppercase small border-bottom border-primary-subtle pb-2 mt-4 mb-2">
                <i class="fas fa-book me-2"></i>Productos de esta categoría
                <span class="badge rounded-pill bg-primary-subtle text-primary-emphasis ms-1">{{ $categoria->productos->count() }}</span>
            </h6>
            <div class="table-responsive bg-white border border-primary-subtle rounded-3 shadow-sm">
                <table class="table table-sm table-hover align-middle mb-0">
                    <thead class="table-light text-uppercase small text-secondary"><tr><th>Código</th><th>Producto</th><th class="text-end">Precio</th><th class="text-center">Stock</th></tr></thead>
                    <tbody>
                        @forelse ($categoria->productos as $p)
                        <tr>
                            <td><span class="font-monospace">{{ $p->codigo }}</span></td>
                            <td class="fw-semibold">{{ $p->nombre }}</td>
                            <td class="text-end">S/ {{ number_format($p->precio_venta, 2) }}</td>
                            <td class="text-center">{{ $p->stock }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center text-muted py-3">Esta categoría aún no tiene productos.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- ========== PIE ========== --}}
        <div class="card-footer bg-white border-top px-4 py-3 small text-muted d-flex flex-wrap justify-content-between gap-2">
            <span><i class="far fa-calendar-plus me-1"></i>Registrado: {{ $categoria->created_at ? \Illuminate\Support\Carbon::parse($categoria->created_at)->format('d/m/Y H:i') : '—' }}</span>
            <span><i class="far fa-edit me-1"></i>Última modificación: {{ $categoria->updated_at ? \Illuminate\Support\Carbon::parse($categoria->updated_at)->format('d/m/Y H:i') : '—' }}</span>
        </div>
    </div>
</div>
@endsection
