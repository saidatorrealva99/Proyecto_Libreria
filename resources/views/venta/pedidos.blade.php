{{-- resources/views/venta/pedidos.blade.php  (archivo NUEVO, dentro de la carpeta venta que ya tienes)
     Lista de pedidos hechos en la tienda web:  /pedidos-web --}}
@extends('layouts.app')

@section('content')
@php
    $pestanas = \App\Http\Controllers\PedidoWebController::PESTANAS;
    [$tituloPestana] = $pestanas[$estado];
@endphp
<div class="container-fluid px-4 py-3">

    <div class="card border-0 shadow rounded-4 overflow-hidden">

        {{-- ========== ENCABEZADO ========== --}}
        <div class="card-header bg-dark bg-gradient text-white border-0 p-4 position-relative overflow-hidden">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 position-relative">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-info bg-opacity-25 text-info rounded-3 shadow-sm p-3 lh-1">
                        <i class="fas fa-globe fa-2x fa-fw"></i>
                    </div>
                    <div class="vr opacity-50"></div>
                    <div>
                        <h3 class="fw-bold mb-1">Pedidos web
                            <span class="badge bg-info text-dark rounded-pill fs-6 align-middle ms-1">{{ count($pedidos) }}</span>
                        </h3>
                        <p class="mb-0 opacity-75">Compras hechas por los clientes en la tienda en línea</p>
                    </div>
                </div>
                <a href="{{ route('ventas.index') }}" class="btn btn-outline-light rounded-pill px-4 fw-semibold">
                    <i class="fas fa-cash-register me-2"></i>Todas las ventas
                </a>
            </div>
            <i class="fas fa-globe fa-6x opacity-25 position-absolute top-50 end-0 translate-middle-y me-5 d-none d-xl-block"></i>
        </div>

        {{-- ========== PESTAÑAS POR ESTADO ========== --}}
        <div class="bg-white border-bottom px-4 pt-3">
            <ul class="nav nav-tabs border-0 flex-nowrap overflow-auto">
                @foreach ($pestanas as $clave => [$texto, $color, $icono])
                    <li class="nav-item">
                        <a class="nav-link text-nowrap {{ $estado === $clave ? 'active fw-semibold text-' . $color . '-emphasis' : 'text-secondary' }}"
                           href="{{ route('pedidos-web.index', ['estado' => $clave]) }}">
                            <i class="fas {{ $icono }} me-1"></i>{{ $texto }}
                            <span class="badge rounded-pill {{ ($conteo[$clave] ?? 0) > 0 ? 'bg-' . $color . ($color === 'warning' || $color === 'info' ? ' text-dark' : '') : 'bg-secondary-subtle text-secondary' }} ms-1">
                                {{ $conteo[$clave] ?? 0 }}
                            </span>
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>

        {{-- ========== MENSAJES ========== --}}
        @if ($message = Session::get('success'))
            <div class="alert alert-success border-0 rounded-0 d-flex align-items-center gap-2 mb-0">
                <i class="fas fa-check-circle fs-5"></i><span>{{ $message }}</span>
            </div>
        @endif

        {{-- ========== TABLA ========== --}}
        <div class="card-body bg-white p-4">
            @if ($estado === 'por_confirmar' || ($estado === 'pendientes' && ($conteo['por_confirmar'] ?? 0) > 0))
                <div class="alert alert-warning d-flex align-items-center gap-2 small">
                    <i class="fas fa-circle-info"></i>
                    <span>Antes de confirmar, revisa en tu app de <strong>Yape</strong>, <strong>Plin</strong> o en el banco que el <strong>número de operación</strong> y el <strong>monto</strong> coincidan.
                        Los pedidos sin confirmar se anulan solos a las <strong>{{ config('libreria.horas_para_pagar', 24) }} horas</strong>.</span>
                </div>
            @endif

            <div class="table-responsive">
                <table id="example" data-titulo="Pedidos web - {{ $tituloPestana }}" class="table table-hover align-middle w-100">
                    <thead class="table-light text-uppercase small text-secondary">
                        <tr>
                            <th class="ps-4">#</th>
                            <th>Pedido</th>
                            <th>Fecha</th>
                            <th>Cliente</th>
                            <th>Entrega</th>
                            <th class="text-end">Total</th>
                            <th class="text-end">Pagado</th>
                            <th class="text-end">Saldo</th>
                            <th>Estado</th>
                            <th class="text-end pe-4">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pedidos as $pedido)
                            @php
                                [$textoEstado, $colorEstado, $iconoEstado] = \App\Models\Venta::ESTADOS_PEDIDO[$pedido->estado_pedido] ?? ['—', 'secondary', 'fa-circle'];
                                $esperando = $pedido->estado_pedido === 'por_confirmar';
                            @endphp
                            <tr class="{{ $esperando ? 'table-warning' : '' }}">
                                <td class="ps-4 text-muted">{{ ++$i }}</td>
                                <td>
                                    <span class="badge {{ $pedido->tipo_comprobante === 'factura' ? 'bg-dark' : 'bg-primary' }} me-1">{{ ucfirst($pedido->tipo_comprobante) }}</span>
                                    <span class="font-monospace">{{ $pedido->numero_comprobante }}</span>
                                </td>
                                <td class="small text-nowrap">
                                    <i class="far fa-calendar-alt text-muted me-1"></i>{{ $pedido->fecha_venta?->format('d/m/Y H:i') }}
                                    <div class="text-muted">{{ $pedido->fecha_venta?->diffForHumans() }}</div>
                                </td>
                                <td>
                                    <div class="fw-semibold text-dark">{{ $pedido->cliente?->nombres }} {{ $pedido->cliente?->apellidos }}</div>
                                    <small class="text-muted"><i class="fas fa-mobile-screen me-1"></i>{{ $pedido->telefono_contacto }}</small>
                                </td>
                                <td class="text-nowrap">
                                    @if ($pedido->entrega === 'delivery')
                                        <span class="badge bg-info-subtle text-info-emphasis border border-info-subtle"><i class="fas fa-truck-fast me-1"></i>Delivery</span>
                                    @else
                                        <span class="badge bg-success-subtle text-success-emphasis border border-success-subtle"><i class="fas fa-store me-1"></i>Recojo</span>
                                    @endif
                                </td>
                                <td class="text-end fw-bold text-dark text-nowrap">S/ {{ number_format($pedido->total, 2) }}</td>
                                <td class="text-end text-success text-nowrap">S/ {{ number_format($pedido->pagado, 2) }}</td>
                                <td class="text-end text-nowrap {{ $pedido->saldo > 0 ? 'text-warning-emphasis fw-semibold' : 'text-muted' }}">
                                    {{ $pedido->saldo > 0 ? 'S/ ' . number_format($pedido->saldo, 2) : '—' }}
                                </td>
                                <td>
                                    <span class="badge rounded-pill bg-{{ $colorEstado }}-subtle text-{{ $colorEstado }}-emphasis border border-{{ $colorEstado }}-subtle text-nowrap">
                                        <i class="fas {{ $iconoEstado }} me-1"></i>{{ $textoEstado }}
                                    </span>
                                    @if ($pedido->vence_en)
                                        @php $horasRestantes = now()->diffInHours($pedido->vence_en, false); @endphp
                                        <div class="small mt-1 {{ $horasRestantes < 3 ? 'text-danger fw-semibold' : 'text-muted' }}">
                                            <i class="fas fa-stopwatch me-1"></i>Se anula {{ $pedido->vence_en->diffForHumans() }}
                                        </div>
                                    @endif
                                </td>
                                <td class="text-end pe-4">
                                    <a href="{{ route('pedidos-web.show', $pedido->id) }}"
                                       class="btn btn-sm {{ $esperando ? 'btn-warning' : 'btn-outline-primary' }} rounded-pill px-3 fw-semibold text-nowrap">
                                        <i class="fas {{ $esperando ? 'fa-hand-pointer' : 'fa-eye' }} me-1"></i>{{ $esperando ? 'Atender' : 'Ver' }}
                                    </a>
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
