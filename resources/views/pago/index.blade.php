{{-- resources/views/pago/index.blade.php  (reemplaza TODO el archivo) --}}
@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-3">

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
                        <h3 class="fw-bold mb-1">Gestión de Pagos
                            <span class="badge bg-white text-primary rounded-pill fs-6 align-middle ms-1">{{ count($pagos) }}</span>
                        </h3>
                        <p class="mb-0 opacity-75">Cobros recibidos por cada venta</p>
                    </div>
                </div>
                <a href="{{ route('pagos.create') }}" class="btn btn-light btn-lg rounded-pill px-4 fw-semibold text-primary shadow-sm">
                    <i class="fas fa-plus-circle me-2"></i>Nuevo Pago
                </a>
            </div>
            <i class="fas fa-credit-card fa-6x opacity-25 position-absolute top-50 end-0 translate-middle-y me-5 d-none d-xl-block"></i>
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
                <table id="example" data-titulo="Gestión de Pagos" class="table table-hover align-middle w-100">
                    <thead class="table-light text-uppercase small text-secondary">
                        <tr>
                            <th class="ps-4">#</th>
                            <th>Comprobante</th>
                            <th>Método</th>
                            <th>N° operación</th>
                            <th>Fecha</th>
                            <th class="text-end">Monto</th>
                            <th class="text-end pe-4">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pagos as $pago)
                        <tr>
                            <td class="ps-4 text-muted">{{ ++$i }}</td>
                            <td><span class="font-monospace badge bg-light text-dark border">{{ $pago->venta?->numero_comprobante }}</span></td>
                            <td>@php
                                    $metodos = [
                                        'efectivo'      => ['bg-success',   'fa-money-bill-wave'],
                                        'tarjeta'       => ['bg-primary',   'fa-credit-card'],
                                        'yape'          => ['bg-info text-dark', 'fa-mobile-alt'],
                                        'plin'          => ['bg-info text-dark', 'fa-mobile-alt'],
                                        'transferencia' => ['bg-dark',      'fa-university'],
                                    ];
                                    [$color, $icono] = $metodos[$pago->metodo_pago] ?? ['bg-secondary', 'fa-wallet'];
                                @endphp
                                <span class="badge rounded-pill {{ $color }}"><i class="fas {{ $icono }} me-1"></i>{{ ucfirst($pago->metodo_pago) }}</span></td>
                            <td class="font-monospace small">{{ $pago->numero_operacion ?? '—' }}</td>
                            <td class="small"><i class="far fa-calendar-alt text-muted me-1"></i>{{ $pago->fecha_pago ? \Illuminate\Support\Carbon::parse($pago->fecha_pago)->format('d/m/Y H:i') : '—' }}</td>
                            <td class="text-end fw-bold text-dark">S/ {{ number_format($pago->monto, 2) }}</td>
                            <td class="text-end pe-4">
                                <form action="{{ route('pagos.destroy', $pago->id) }}" method="POST" class="d-inline-flex gap-1">
                                    <a href="{{ route('pagos.show', $pago->id) }}" class="btn btn-sm btn-outline-info rounded-circle" title="Ver detalle">
                                        <i class="fas fa-eye fa-fw"></i>
                                    </a>
                                    <a href="{{ route('pagos.edit', $pago->id) }}" class="btn btn-sm btn-outline-success rounded-circle" title="Editar">
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
