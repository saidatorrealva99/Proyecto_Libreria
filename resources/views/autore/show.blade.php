{{-- resources/views/autore/show.blade.php  (reemplaza TODO el archivo) --}}
@extends('layouts.app')

@section('content')
<div class="container py-3">

    <div class="card border-0 shadow rounded-4 overflow-hidden">

        {{-- ========== ENCABEZADO ========== --}}
        <div class="card-header bg-primary bg-gradient text-white border-0 p-4 position-relative overflow-hidden">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 position-relative">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-white bg-opacity-25 rounded-3 shadow-sm p-3 lh-1">
                        <i class="fas fa-feather-alt fa-2x fa-fw"></i>
                    </div>
                    <div class="vr opacity-50"></div>
                    <div>
                        <h3 class="fw-bold mb-1">Detalle del Autor</h3>
                        <p class="mb-0 opacity-75">{{ $autore->nombres }} {{ $autore->apellidos }}</p>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('autores.index') }}" class="btn btn-outline-light rounded-pill px-4">
                        <i class="fas fa-arrow-left me-2"></i>Volver
                    </a>
                    <a href="{{ route('autores.edit', $autore->id) }}" class="btn btn-light text-primary fw-semibold rounded-pill px-4 shadow-sm">
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
                        <small class="text-uppercase text-secondary fw-bold d-block small"><i class="fas fa-user fa-fw text-primary me-1"></i>Nombres</small>
                        <div class="fw-semibold text-dark">{{ $autore->nombres }}</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="bg-white border border-primary-subtle rounded-3 shadow-sm px-3 py-2 h-100">
                        <small class="text-uppercase text-secondary fw-bold d-block small"><i class="fas fa-user-tag fa-fw text-primary me-1"></i>Apellidos</small>
                        <div class="fw-semibold text-dark">{{ $autore->apellidos }}</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="bg-white border border-primary-subtle rounded-3 shadow-sm px-3 py-2 h-100">
                        <small class="text-uppercase text-secondary fw-bold d-block small"><i class="fas fa-flag fa-fw text-primary me-1"></i>Nacionalidad</small>
                        <div class="fw-semibold text-dark">{{ $autore->nacionalidad ?? '—' }}</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="bg-white border border-primary-subtle rounded-3 shadow-sm px-3 py-2 h-100">
                        <small class="text-uppercase text-secondary fw-bold d-block small"><i class="fas fa-birthday-cake fa-fw text-primary me-1"></i>Fecha de nacimiento</small>
                        <div class="fw-semibold text-dark">{{ $autore->fecha_nacimiento ? \Illuminate\Support\Carbon::parse($autore->fecha_nacimiento)->format('d/m/Y') : '—' }}</div>
                    </div>
                </div>
            </div>

            <h6 class="text-primary fw-bold text-uppercase small border-bottom border-primary-subtle pb-2 mt-4 mb-2">
                <i class="fas fa-book me-2"></i>Libros del autor en la librería
                <span class="badge rounded-pill bg-primary-subtle text-primary-emphasis ms-1">{{ $autore->productos->count() }}</span>
            </h6>
            <div class="table-responsive bg-white border border-primary-subtle rounded-3 shadow-sm">
                <table class="table table-sm table-hover align-middle mb-0">
                    <thead class="table-light text-uppercase small text-secondary"><tr><th>Código</th><th>Título</th><th>Editorial</th><th class="text-end">Precio</th><th class="text-center">Stock</th></tr></thead>
                    <tbody>
                        @forelse ($autore->productos as $p)
                        <tr>
                            <td><span class="font-monospace">{{ $p->codigo }}</span></td>
                            <td class="fw-semibold">{{ $p->nombre }}</td>
                            <td>{{ $p->marca_editorial ?? '—' }}</td>
                            <td class="text-end">S/ {{ number_format($p->precio_venta, 2) }}</td>
                            <td class="text-center">{{ $p->stock }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="text-center text-muted py-3">No hay libros de este autor.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- ========== PIE ========== --}}
        <div class="card-footer bg-white border-top px-4 py-3 small text-muted d-flex flex-wrap justify-content-between gap-2">
            <span><i class="far fa-calendar-plus me-1"></i>Registrado: {{ $autore->created_at ? \Illuminate\Support\Carbon::parse($autore->created_at)->format('d/m/Y H:i') : '—' }}</span>
            <span><i class="far fa-edit me-1"></i>Última modificación: {{ $autore->updated_at ? \Illuminate\Support\Carbon::parse($autore->updated_at)->format('d/m/Y H:i') : '—' }}</span>
        </div>
    </div>
</div>
@endsection
