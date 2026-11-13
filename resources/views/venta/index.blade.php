{{-- resources/views/venta/index.blade.php  (reemplaza TODO el archivo) --}}
@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-3">

    <div class="card border-0 shadow rounded-4 overflow-hidden">

        {{-- ========== ENCABEZADO ========== --}}
        <div class="card-header bg-primary bg-gradient text-white border-0 p-4 position-relative overflow-hidden">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 position-relative">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-white bg-opacity-25 rounded-3 shadow-sm p-3 lh-1">
                        <i class="fas fa-cash-register fa-2x fa-fw"></i>
                    </div>
                    <div class="vr opacity-50"></div>
                    <div>
                        <h3 class="fw-bold mb-1">Gestión de Ventas
                            <span class="badge bg-white text-primary rounded-pill fs-6 align-middle ms-1">{{ count($ventas) }}</span>
                        </h3>
                        <p class="mb-0 opacity-75">Boletas y facturas emitidas por la librería</p>
                    </div>
                </div>
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('pedidos-web.index') }}" class="btn btn-outline-light btn-lg rounded-pill px-4 fw-semibold">
                        <i class="fas fa-globe me-2"></i>Pedidos web
                    </a>
                    <a href="{{ route('ventas.create') }}" class="btn btn-light btn-lg rounded-pill px-4 fw-semibold text-primary shadow-sm">
                        <i class="fas fa-plus-circle me-2"></i>Nueva Venta
                    </a>
                </div>
            </div>
            <i class="fas fa-cash-register fa-6x opacity-25 position-absolute top-50 end-0 translate-middle-y me-5 d-none d-xl-block"></i>
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
                <table id="example" data-titulo="Gestión de Ventas" class="table table-hover align-middle w-100">
                    <thead class="table-light text-uppercase small text-secondary">
                        <tr>
                            <th class="ps-4">#</th>
                            <th>Comprobante</th>
                            <th>Fecha</th>
                            <th>Cliente</th>
                            <th>Vendedor</th>
                            <th class="text-end">Subtotal</th>
                            <th class="text-end">Desc.</th>
                            <th class="text-end">Total</th>
                            <th>Estado</th>
                            <th class="text-end pe-4">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($ventas as $venta)
                        <tr>
                            <td class="ps-4 text-muted">{{ ++$i }}</td>
                            <td class="text-nowrap">
                                @if ($venta->canal === 'web')
                                    <span class="badge bg-info text-dark me-1" title="Pedido de la tienda web"><i class="fas fa-globe"></i></span>
                                @endif
                                <span class="badge {{ $venta->tipo_comprobante === 'factura' ? 'bg-dark' : 'bg-primary' }} me-1">{{ ucfirst($venta->tipo_comprobante) }}</span><span class="font-monospace">{{ $venta->numero_comprobante }}</span>
                            </td>
                            <td class="small"><i class="far fa-calendar-alt text-muted me-1"></i>{{ $venta->fecha_venta ? \Illuminate\Support\Carbon::parse($venta->fecha_venta)->format('d/m/Y H:i') : '—' }}</td>
                            <td class="fw-semibold text-dark">{{ $venta->cliente?->nombres }} {{ $venta->cliente?->apellidos }}</td>
                            <td>
                                @if ($venta->empleado)
                                    {{ $venta->empleado->nombres }} {{ $venta->empleado->apellidos }}
                                @else
                                    <span class="text-muted small fst-italic">Sin asignar</span>
                                @endif
                            </td>
                            <td class="text-end">S/ {{ number_format($venta->subtotal, 2) }}</td>
                            <td class="text-end text-danger">{{ $venta->descuento > 0 ? '- S/ ' . number_format($venta->descuento, 2) : '—' }}</td>
                            <td class="text-end fw-bold text-dark">S/ {{ number_format($venta->total, 2) }}</td>
                            <td>@php
                                    $estados = [
                                        'pagada'    => ['bg-success-subtle text-success-emphasis', 'fa-check-circle'],
                                        'pendiente' => ['bg-warning-subtle text-warning-emphasis', 'fa-clock'],
                                        'anulada'   => ['bg-danger-subtle text-danger-emphasis',   'fa-ban'],
                                    ];
                                    [$color, $icono] = $estados[$venta->estado] ?? ['bg-secondary', 'fa-question'];
                                @endphp
                                <span class="badge rounded-pill {{ $color }}"><i class="fas {{ $icono }} me-1"></i>{{ ucfirst($venta->estado) }}</span>
                                @if ($venta->canal === 'web' && $venta->estado_pedido)
                                    @php [$textoPedido, $colorPedido] = \App\Models\Venta::ESTADOS_PEDIDO[$venta->estado_pedido]; @endphp
                                    <div class="small text-{{ $colorPedido }}-emphasis mt-1">{{ $textoPedido }}</div>
                                @endif
                            </td>
                            <td class="text-end pe-4">
                                <form action="{{ route('ventas.destroy', $venta->id) }}" method="POST" class="d-inline-flex gap-1">
                                    @if ($venta->canal === 'web')
                                        <a href="{{ route('pedidos-web.show', $venta->id) }}" class="btn btn-sm btn-outline-info rounded-circle" title="Atender pedido web">
                                            <i class="fas fa-globe fa-fw"></i>
                                        </a>
                                    @else
                                        <a href="{{ route('ventas.show', $venta->id) }}" class="btn btn-sm btn-outline-info rounded-circle" title="Ver detalle">
                                            <i class="fas fa-eye fa-fw"></i>
                                        </a>
                                    @endif
                                    <a href="{{ route('comprobante.pdf', $venta->numero_comprobante) }}" target="_blank" class="btn btn-sm btn-outline-danger rounded-circle" title="Comprobante PDF">
                                        <i class="fas fa-file-pdf fa-fw"></i>
                                    </a>
                                    <a href="{{ route('ventas.edit', $venta->id) }}" class="btn btn-sm btn-outline-success rounded-circle" title="Editar">
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
