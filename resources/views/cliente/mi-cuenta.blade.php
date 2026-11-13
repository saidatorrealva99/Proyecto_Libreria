{{-- resources/views/cliente/mi-cuenta.blade.php  (archivo NUEVO: crea la carpeta "cliente") --}}
@extends('layouts.app')

@section('content')
<div class="container py-3">

    {{-- ========== ENCABEZADO ========== --}}
    <div class="card border-0 shadow rounded-4 overflow-hidden mb-4">
        <div class="card-header bg-primary bg-gradient text-white border-0 p-4 position-relative overflow-hidden">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 position-relative">
                <div class="d-flex align-items-center gap-3">
                    <span class="bg-white text-primary fw-bold fs-3 rounded-circle shadow d-inline-flex align-items-center justify-content-center px-3 py-2">
                        {{ mb_strtoupper(mb_substr($usuario->name, 0, 1)) }}
                    </span>
                    <div class="vr opacity-50"></div>
                    <div>
                        <h3 class="fw-bold mb-1">Hola, {{ $usuario->name }}</h3>
                        <p class="mb-0 opacity-75">Bienvenido a tu cuenta. Aquí verás tus pedidos y tus datos.</p>
                    </div>
                </div>
                <a href="{{ route('catalogo') }}" class="btn btn-light btn-lg rounded-pill px-4 fw-semibold text-primary shadow-sm">
                    <i class="fas fa-store me-2"></i>Ir a la tienda
                </a>
            </div>
            <i class="fas fa-user fa-6x opacity-25 position-absolute top-50 end-0 translate-middle-y me-5 d-none d-xl-block"></i>
        </div>
    </div>

    <div class="row g-4">
        {{-- ========== MIS DATOS ========== --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3"><i class="fas fa-id-card text-primary me-2"></i>Mis datos</h5>
                    <ul class="list-unstyled mb-0">
                        <li class="mb-3">
                            <small class="text-muted text-uppercase fw-bold d-block">Nombre</small>
                            <span class="fw-semibold">{{ $usuario->name }}</span>
                        </li>
                        <li class="mb-3">
                            <small class="text-muted text-uppercase fw-bold d-block">Correo</small>
                            <span class="fw-semibold">{{ $usuario->email }}</span>
                        </li>
                        <li class="mb-3">
                            <small class="text-muted text-uppercase fw-bold d-block">Tipo de cuenta</small>
                            <span class="badge rounded-pill bg-success-subtle text-success-emphasis">{{ ucfirst($usuario->rol) }}</span>
                        </li>
                        <li>
                            <small class="text-muted text-uppercase fw-bold d-block">Cliente desde</small>
                            <span class="fw-semibold">{{ $usuario->created_at?->format('d/m/Y') }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        {{-- ========== MIS PEDIDOS ========== --}}
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold mb-0"><i class="fas fa-box text-primary me-2"></i>Mis pedidos</h5>
                        <span class="badge rounded-pill bg-primary-subtle text-primary-emphasis">{{ $pedidos->count() }}</span>
                    </div>

                    @if ($pedidos->isEmpty())
                        <div class="text-center bg-light rounded-4 py-5">
                            <i class="fas fa-cart-shopping fa-3x text-primary mb-3"></i>
                            <h6 class="fw-bold">Aún no tienes pedidos</h6>
                            <p class="text-muted small mb-3">Cuando compres en la tienda, aquí verás el estado de cada pedido.</p>
                            <a href="{{ route('catalogo') }}" class="btn btn-primary rounded-pill px-4">Ver catálogo</a>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light small text-uppercase text-secondary">
                                    <tr>
                                        <th>Pedido</th>
                                        <th>Fecha</th>
                                        <th>Entrega</th>
                                        <th class="text-end">Total</th>
                                        <th class="text-end">Saldo</th>
                                        <th>Estado</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($pedidos as $pedido)
                                        @php
                                            [$textoEstado, $colorEstado, $iconoEstado] = \App\Models\Venta::ESTADOS_PEDIDO[$pedido->estado_pedido ?? 'entregado']
                                                ?? ['—', 'secondary', 'fa-circle'];
                                        @endphp
                                        <tr>
                                            <td>
                                                <span class="font-monospace fw-semibold">{{ $pedido->numero_comprobante }}</span>
                                                <div class="small text-muted">{{ $pedido->detalles_count }} producto(s)</div>
                                            </td>
                                            <td class="small">{{ $pedido->fecha_venta?->format('d/m/Y') }}</td>
                                            <td class="small">
                                                @if ($pedido->entrega === 'delivery')
                                                    <i class="fas fa-truck-fast text-info me-1"></i>Envío
                                                @elseif ($pedido->entrega === 'recojo')
                                                    <i class="fas fa-store text-success me-1"></i>Recojo
                                                @else
                                                    <i class="fas fa-shop text-secondary me-1"></i>En tienda
                                                @endif
                                            </td>
                                            <td class="text-end fw-semibold">S/ {{ number_format($pedido->total, 2) }}</td>
                                            <td class="text-end {{ $pedido->saldo > 0 ? 'text-warning-emphasis' : 'text-success' }}">S/ {{ number_format($pedido->saldo, 2) }}</td>
                                            <td>
                                                <span class="badge rounded-pill bg-{{ $colorEstado }}-subtle text-{{ $colorEstado }}-emphasis">
                                                    <i class="fas {{ $iconoEstado }} me-1"></i>{{ $textoEstado }}
                                                </span>
                                            </td>
                                            <td class="text-end">
                                                <a href="{{ route('pedido.ver', $pedido->numero_comprobante) }}" class="btn btn-sm btn-outline-primary rounded-pill">Ver</a>
                                                @unless (in_array($pedido->estado_pedido, ['por_confirmar', 'anulado'], true))
                                                    <a href="{{ route('comprobante.pdf', ['numero' => $pedido->numero_comprobante, 'descargar' => 1]) }}"
                                                       class="btn btn-sm btn-outline-danger rounded-circle" title="Descargar comprobante PDF">
                                                        <i class="fas fa-file-pdf fa-fw"></i>
                                                    </a>
                                                @endunless
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif

                    {{-- Fichas de comprobante guardadas --}}
                    @if ($fichas->isNotEmpty())
                        <h6 class="fw-bold mt-4 mb-2"><i class="fas fa-id-card text-primary me-2"></i>Datos para mis comprobantes</h6>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach ($fichas as $f)
                                <span class="badge rounded-pill bg-light text-dark border fw-normal px-3 py-2">
                                    <strong>{{ $f->tipo_documento }}</strong> {{ $f->numero_documento }} · {{ $f->nombres }} {{ $f->apellidos }}
                                </span>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
