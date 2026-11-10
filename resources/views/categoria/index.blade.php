{{-- resources/views/categoria/index.blade.php  (reemplaza TODO el archivo) --}}
@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-3">

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
                        <h3 class="fw-bold mb-1">Gestión de Categorías
                            <span class="badge bg-white text-primary rounded-pill fs-6 align-middle ms-1">{{ count($categorias) }}</span>
                        </h3>
                        <p class="mb-0 opacity-75">Géneros literarios, áreas académicas y tipos de útiles</p>
                    </div>
                </div>
                <a href="{{ route('categorias.create') }}" class="btn btn-light btn-lg rounded-pill px-4 fw-semibold text-primary shadow-sm">
                    <i class="fas fa-plus-circle me-2"></i>Nueva Categoría
                </a>
            </div>
            <i class="fas fa-tags fa-6x opacity-25 position-absolute top-50 end-0 translate-middle-y me-5 d-none d-xl-block"></i>
        </div>

        {{-- ========== MENSAJE DE ÉXITO ========== --}}
        @if ($message = Session::get('success'))
            <div class="alert alert-success border-0 rounded-0 d-flex align-items-center gap-2 mb-0">
                <i class="fas fa-check-circle fs-5"></i><span>{{ $message }}</span>
            </div>
        @endif
        @if ($message = Session::get('error'))
            <div class="alert alert-danger border-0 rounded-0 d-flex align-items-center gap-2 mb-0">
                <i class="fas fa-exclamation-circle fs-5"></i><span>{{ $message }}</span>
            </div>
        @endif

        {{-- ========== TABLA ========== --}}
        <div class="card-body bg-white p-4">
            <div class="table-responsive">
                <table id="example" data-titulo="Gestión de Categorías" class="table table-hover align-middle w-100">
                    <thead class="table-light text-uppercase small text-secondary">
                        <tr>
                            <th class="ps-4">#</th>
                            <th>Categoría</th>
                            <th>Descripción</th>
                            <th class="text-center">Productos</th>
                            <th>Estado</th>
                            <th class="text-end pe-4">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($categorias as $categoria)
                        <tr>
                            <td class="ps-4 text-muted">{{ ++$i }}</td>
                            <td class="fw-semibold text-dark"><i class="fas fa-tag text-primary me-2"></i>{{ $categoria->nombre }}</td>
                            <td class="text-muted">{{ $categoria->descripcion ?? '—' }}</td>
                            <td class="text-center"><span class="badge rounded-pill bg-primary-subtle text-primary-emphasis">{{ $categoria->productos()->count() }}</span></td>
                            <td>@if ($categoria->estado)<span class="badge rounded-pill bg-success-subtle text-success-emphasis"><i class="fas fa-check-circle me-1"></i>Activa</span>@else<span class="badge rounded-pill bg-secondary-subtle text-secondary-emphasis"><i class="fas fa-pause-circle me-1"></i>Inactiva</span>@endif</td>
                            <td class="text-end pe-4">
                                <form action="{{ route('categorias.destroy', $categoria->id) }}" method="POST" class="d-inline-flex gap-1">
                                    <a href="{{ route('categorias.show', $categoria->id) }}" class="btn btn-sm btn-outline-info rounded-circle" title="Ver detalle">
                                        <i class="fas fa-eye fa-fw"></i>
                                    </a>
                                    <a href="{{ route('categorias.edit', $categoria->id) }}" class="btn btn-sm btn-outline-success rounded-circle" title="Editar">
                                        <i class="fas fa-pen fa-fw"></i>
                                    </a>
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger rounded-circle" title="Eliminar"
                                            onclick="event.preventDefault(); confirm('¿Seguro que deseas eliminar este registro?') ? this.closest('form').submit() : false;">
                                        <i class="fas fa-trash fa-fw"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
@endsection
