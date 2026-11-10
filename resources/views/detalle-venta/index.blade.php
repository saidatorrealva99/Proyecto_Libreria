{{-- resources/views/detalle-venta/index.blade.php  (reemplaza TODO el archivo) --}}
@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-3">

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
                        <h3 class="fw-bold mb-1">Detalle de Ventas
                            <span class="badge bg-white text-primary rounded-pill fs-6 align-middle ms-1">{{ count($detalleVentas) }}</span>
                        </h3>
                        <p class="mb-0 opacity-75">Productos vendidos en cada comprobante</p>
                    </div>
                </div>
                <a href="{{ route('detalle-ventas.create') }}" class="btn btn-light btn-lg rounded-pill px-4 fw-semibold text-primary shadow-sm">
                    <i class="fas fa-plus-circle me-2"></i>Nuevo Detalle
                </a>
            </div>
            <i class="fas fa-list-ul fa-6x opacity-25 position-absolute top-50 end-0 translate-middle-y me-5 d-none d-xl-block"></i>
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
                <table id="example" data-titulo="Detalle de Ventas" class="table table-hover align-middle w-100">
                    <thead class="table-light text-uppercase small text-secondary">
                        <tr>
                            <th class="ps-4">#</th>
                            <th>Comprobante</th>
                            <th>Producto</th>
                            <th class="text-center">Cantidad</th>
                            <th class="text-end">Precio unit.</th>
                            <th class="text-end">Subtotal</th>
                            <th class="text-end pe-4">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($detalleVentas as $detalleVenta)
                        <tr>
                            <td class="ps-4 text-muted">{{ ++$i }}</td>
                            <td><span class="font-monospace badge bg-light text-dark border">{{ $detalleVenta->venta?->numero_comprobante }}</span></td>
                            <td><div class="fw-semibold text-dark">{{ $detalleVenta->producto?->nombre }}</div><small class="text-muted">{{ $detalleVenta->producto?->codigo }}</small></td>
                            <td class="text-center"><span class="badge rounded-pill bg-primary-subtle text-primary-emphasis fs-6">{{ $detalleVenta->cantidad }}</span></td>
                            <td class="text-end">S/ {{ number_format($detalleVenta->precio_unitario, 2) }}</td>
                            <td class="text-end fw-bold text-dark">S/ {{ number_format($detalleVenta->subtotal, 2) }}</td>
                            <td class="text-end pe-4">
                                <form action="{{ route('detalle-ventas.destroy', $detalleVenta->id) }}" method="POST" class="d-inline-flex gap-1">
                                    <a href="{{ route('detalle-ventas.show', $detalleVenta->id) }}" class="btn btn-sm btn-outline-info rounded-circle" title="Ver detalle">
                                        <i class="fas fa-eye fa-fw"></i>
                                    </a>
                                    <a href="{{ route('detalle-ventas.edit', $detalleVenta->id) }}" class="btn btn-sm btn-outline-success rounded-circle" title="Editar">
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
