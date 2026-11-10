{{-- resources/views/venta/show.blade.php  (reemplaza TODO el archivo) --}}
@extends('layouts.app')

@section('content')
<div class="container py-3">

    <div class="card border-0 shadow rounded-4 overflow-hidden">

        {{-- ========== ENCABEZADO ========== --}}
        <div class="card-header bg-primary bg-gradient text-white border-0 p-4 position-relative overflow-hidden">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 position-relative">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-white bg-opacity-25 rounded-3 shadow-sm p-3 lh-1">
                        <i class="fas fa-receipt fa-2x fa-fw"></i>
                    </div>
                    <div class="vr opacity-50"></div>
                    <div>
                        <h3 class="fw-bold mb-1">Detalle de la Venta</h3>
                        <p class="mb-0 opacity-75">{{ ucfirst($venta->tipo_comprobante) }} {{ $venta->numero_comprobante }}</p>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('ventas.index') }}" class="btn btn-outline-light rounded-pill px-4">
                        <i class="fas fa-arrow-left me-2"></i>Volver
                    </a>
                    <a href="{{ route('ventas.edit', $venta->id) }}" class="btn btn-light text-primary fw-semibold rounded-pill px-4 shadow-sm">
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
                        <small class="text-uppercase text-secondary fw-bold d-block small"><i class="fas fa-file-invoice fa-fw text-primary me-1"></i>Comprobante</small>
                        <div class="fw-semibold text-dark">{{ ucfirst($venta->tipo_comprobante) }} <span class="font-monospace">{{ $venta->numero_comprobante }}</span></div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="bg-white border border-primary-subtle rounded-3 shadow-sm px-3 py-2 h-100">
                        <small class="text-uppercase text-secondary fw-bold d-block small"><i class="fas fa-calendar-alt fa-fw text-primary me-1"></i>Fecha y hora</small>
                        <div class="fw-semibold text-dark">{{ $venta->fecha_venta ? \Illuminate\Support\Carbon::parse($venta->fecha_venta)->format('d/m/Y H:i') : '—' }}</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="bg-white border border-primary-subtle rounded-3 shadow-sm px-3 py-2 h-100">
                        <small class="text-uppercase text-secondary fw-bold d-block small"><i class="fas fa-flag fa-fw text-primary me-1"></i>Estado</small>
                        <div class="fw-semibold text-dark">@php $c = ['pagada' => 'success', 'pendiente' => 'warning', 'anulada' => 'danger'][$venta->estado] ?? 'secondary'; @endphp<span class="badge rounded-pill bg-{{ $c }}-subtle text-{{ $c }}-emphasis">{{ ucfirst($venta->estado) }}</span></div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="bg-white border border-primary-subtle rounded-3 shadow-sm px-3 py-2 h-100">
                        <small class="text-uppercase text-secondary fw-bold d-block small"><i class="fas fa-user fa-fw text-primary me-1"></i>Cliente</small>
                        <div class="fw-semibold text-dark">{{ $venta->cliente?->nombres }} {{ $venta->cliente?->apellidos }} <small class="text-muted d-block">{{ $venta->cliente?->tipo_documento }} {{ $venta->cliente?->numero_documento }}</small></div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="bg-white border border-primary-subtle rounded-3 shadow-sm px-3 py-2 h-100">
                        <small class="text-uppercase text-secondary fw-bold d-block small"><i class="fas fa-id-badge fa-fw text-primary me-1"></i>Vendedor</small>
                        <div class="fw-semibold text-dark">{{ $venta->empleado?->nombres }} {{ $venta->empleado?->apellidos }} <small class="text-muted d-block">{{ ucfirst($venta->empleado?->cargo) }}</small></div>
                    </div>
                </div>
            </div>

            <h6 class="text-primary fw-bold text-uppercase small border-bottom border-primary-subtle pb-2 mt-4 mb-2">
                <i class="fas fa-shopping-cart me-2"></i>Productos vendidos
                <span class="badge rounded-pill bg-primary-subtle text-primary-emphasis ms-1">{{ $venta->detalles->count() }}</span>
            </h6>
            <div class="table-responsive bg-white border border-primary-subtle rounded-3 shadow-sm">
                <table class="table table-sm table-hover align-middle mb-0">
                    <thead class="table-light text-uppercase small text-secondary"><tr><th>Código</th><th>Producto</th><th class="text-center">Cant.</th><th class="text-end">P. unit.</th><th class="text-end">Subtotal</th></tr></thead>
                    <tbody>
                        @forelse ($venta->detalles as $d)
                        <tr>
                            <td><span class="font-monospace">{{ $d->producto?->codigo }}</span></td>
                            <td class="fw-semibold">{{ $d->producto?->nombre }}</td>
                            <td class="text-center">{{ $d->cantidad }}</td>
                            <td class="text-end">S/ {{ number_format($d->precio_unitario, 2) }}</td>
                            <td class="text-end fw-semibold">S/ {{ number_format($d->subtotal, 2) }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="text-center text-muted py-3">Esta venta aún no tiene productos.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <h6 class="text-primary fw-bold text-uppercase small border-bottom border-primary-subtle pb-2 mt-4 mb-2">
                <i class="fas fa-wallet me-2"></i>Pagos recibidos
                <span class="badge rounded-pill bg-primary-subtle text-primary-emphasis ms-1">{{ $venta->pagos->count() }}</span>
            </h6>
            <div class="table-responsive bg-white border border-primary-subtle rounded-3 shadow-sm">
                <table class="table table-sm table-hover align-middle mb-0">
                    <thead class="table-light text-uppercase small text-secondary"><tr><th>Método</th><th>N° operación</th><th>Fecha</th><th class="text-end">Monto</th></tr></thead>
                    <tbody>
                        @forelse ($venta->pagos as $pg)
                        <tr>
                            <td>{{ ucfirst($pg->metodo_pago) }}</td>
                            <td><span class="font-monospace small">{{ $pg->numero_operacion ?? '—' }}</span></td>
                            <td class="small">{{ \Illuminate\Support\Carbon::parse($pg->fecha_pago)->format('d/m/Y H:i') }}</td>
                            <td class="text-end fw-semibold">S/ {{ number_format($pg->monto, 2) }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center text-muted py-3">Esta venta aún no tiene pagos.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="row justify-content-end mt-3">
                <div class="col-md-5">
                    <ul class="list-group list-group-flush border border-primary-subtle rounded-3 shadow-sm">
                        <li class="list-group-item d-flex justify-content-between"><span>Subtotal</span><span>S/ {{ number_format($venta->subtotal, 2) }}</span></li>
                        <li class="list-group-item d-flex justify-content-between text-danger"><span>Descuento</span><span>- S/ {{ number_format($venta->descuento, 2) }}</span></li>
                        <li class="list-group-item d-flex justify-content-between bg-primary-subtle fw-bold fs-5"><span>Total</span><span>S/ {{ number_format($venta->total, 2) }}</span></li>
                        <li class="list-group-item d-flex justify-content-between small"><span>Pagado</span><span>S/ {{ number_format($venta->pagos->sum('monto'), 2) }}</span></li>
                        <li class="list-group-item d-flex justify-content-between small fw-semibold"><span>Saldo pendiente</span><span>S/ {{ number_format($venta->total - $venta->pagos->sum('monto'), 2) }}</span></li>
                    </ul>
                </div>
            </div>
        </div>

        {{-- ========== PIE ========== --}}
        <div class="card-footer bg-white border-top px-4 py-3 small text-muted d-flex flex-wrap justify-content-between gap-2">
            <span><i class="far fa-calendar-plus me-1"></i>Registrado: {{ $venta->created_at ? \Illuminate\Support\Carbon::parse($venta->created_at)->format('d/m/Y H:i') : '—' }}</span>
            <span><i class="far fa-edit me-1"></i>Última modificación: {{ $venta->updated_at ? \Illuminate\Support\Carbon::parse($venta->updated_at)->format('d/m/Y H:i') : '—' }}</span>
        </div>
    </div>
</div>
@endsection
