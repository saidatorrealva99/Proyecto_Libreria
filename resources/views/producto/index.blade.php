{{-- resources/views/producto/index.blade.php  (reemplaza TODO el archivo) --}}
@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-3">

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
                        <h3 class="fw-bold mb-1">Gestión de Productos
                            <span class="badge bg-white text-primary rounded-pill fs-6 align-middle ms-1">{{ count($productos) }}</span>
                        </h3>
                        <p class="mb-0 opacity-75">Libros, útiles escolares y papelería de la librería</p>
                    </div>
                </div>
                <a href="{{ route('productos.create') }}" class="btn btn-light btn-lg rounded-pill px-4 fw-semibold text-primary shadow-sm">
                    <i class="fas fa-plus-circle me-2"></i>Nuevo Producto
                </a>
            </div>
            <i class="fas fa-book fa-6x opacity-25 position-absolute top-50 end-0 translate-middle-y me-5 d-none d-xl-block"></i>
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
                <table id="example" data-titulo="Gestión de Productos" class="table table-hover align-middle w-100">
                    <thead class="table-light text-uppercase small text-secondary">
                        <tr>
                            <th class="ps-4">#</th>
                            <th>Código</th>
                            <th>Producto</th>
                            <th>Categoría</th>
                            <th>Autor</th>
                            <th>Editorial / Marca</th>
                            <th class="text-end">Precio</th>
                            <th class="text-center">Stock</th>
                            <th>Estado</th>
                            <th class="text-end pe-4">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($productos as $producto)
                        <tr>
                            <td class="ps-4 text-muted">{{ ++$i }}</td>
                            <td><span class="badge bg-light text-dark border font-monospace">{{ $producto->codigo }}</span></td>
                            <td><div class="fw-semibold text-dark">{{ $producto->nombre }}</div>
                                @if ($producto->isbn)<small class="text-muted"><i class="fas fa-barcode me-1"></i>ISBN {{ $producto->isbn }}</small>@endif</td>
                            <td><span class="badge rounded-pill bg-primary-subtle text-primary-emphasis">{{ $producto->categoria?->nombre ?? '—' }}</span></td>
                            <td>@if ($producto->autor)<i class="fas fa-feather-alt text-muted me-1"></i>{{ $producto->autor->nombres }} {{ $producto->autor->apellidos }}@else<span class="text-muted">—</span>@endif</td>
                            <td>{{ $producto->marca_editorial ?? '—' }}</td>
                            <td class="text-end fw-semibold">S/ {{ number_format($producto->precio_venta, 2) }}</td>
                            <td class="text-center">{{ $producto->stock }}<small class="text-muted"> / mín. {{ $producto->stock_minimo }}</small></td>
                            <td>
                                @if (! $producto->estado)
                                    <span class="badge rounded-pill bg-secondary-subtle text-secondary-emphasis"><i class="fas fa-ban me-1"></i>Descontinuado</span>
                                @elseif ($producto->stock == 0)
                                    <span class="badge rounded-pill bg-danger-subtle text-danger-emphasis"><i class="fas fa-times-circle me-1"></i>Agotado</span>
                                @elseif ($producto->stock <= $producto->stock_minimo)
                                    <span class="badge rounded-pill bg-warning-subtle text-warning-emphasis"><i class="fas fa-exclamation-triangle me-1"></i>Stock bajo</span>
                                @else
                                    <span class="badge rounded-pill bg-success-subtle text-success-emphasis"><i class="fas fa-check-circle me-1"></i>Disponible</span>
                                @endif
                            </td>
                            <td class="text-end pe-4">
                                <form action="{{ route('productos.destroy', $producto->id) }}" method="POST" class="d-inline-flex gap-1">
                                    <a href="{{ route('productos.show', $producto->id) }}" class="btn btn-sm btn-outline-info rounded-circle" title="Ver detalle">
                                        <i class="fas fa-eye fa-fw"></i>
                                    </a>
                                    <a href="{{ route('productos.edit', $producto->id) }}" class="btn btn-sm btn-outline-success rounded-circle" title="Editar">
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
