{{-- resources/views/home.blade.php  (reemplaza TODO el archivo) --}}
{{-- Solo Bootstrap 5 + FontAwesome (cargados en layouts/app.blade.php) --}}
@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-2">

    {{-- =====================================================================
         1. BARRA DE BIENVENIDA
    ====================================================================== --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4 d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div class="d-flex align-items-center gap-3">
                <div class="bg-primary bg-gradient text-white rounded-3 shadow p-3 lh-1">
                    <i class="fas fa-tachometer-alt fa-2x fa-fw"></i>
                </div>
                <div>
                    <h3 class="fw-bold text-dark mb-1">Panel de Control</h3>
                    <p class="text-muted mb-0">
                        Bienvenido de nuevo, <strong class="text-dark">{{ Auth::user()->name }}</strong>.
                        <span class="d-none d-md-inline">Este es el resumen de tu librería al
                            <span class="text-primary fw-semibold">{{ now()->translatedFormat('d \d\e F \d\e Y') }}</span>.</span>
                    </p>
                </div>
            </div>
            <div class="d-flex flex-wrap gap-2">
                <span class="badge rounded-pill bg-success-subtle text-success-emphasis border border-success-subtle d-flex align-items-center px-3">
                    <span class="spinner-grow spinner-grow-sm text-success me-2" role="status"></span>Sistema en línea
                </span>
                <a href="{{ route('productos.create') }}" class="btn btn-outline-primary rounded-pill px-4 fw-semibold">
                    <i class="fas fa-book-medical me-2"></i>Nuevo Producto
                </a>
                <a href="{{ route('ventas.create') }}" class="btn btn-primary rounded-pill px-4 fw-semibold shadow">
                    <i class="fas fa-cash-register me-2"></i>Nueva Venta
                </a>
            </div>
        </div>
    </div>

    {{-- =====================================================================
         PEDIDOS DE LA TIENDA WEB
    ====================================================================== --}}
    @php
        $porConfirmar = $pedidosWeb['por_confirmar'] ?? 0;
        $resumenWeb = [
            'por_confirmar' => ['Por confirmar',      'warning', 'fa-hourglass-half'],
            'confirmado'    => ['Por preparar',       'primary', 'fa-box'],
            'listo_recojo'  => ['Listos para recoger', 'info',   'fa-store'],
            'en_camino'     => ['En camino',          'info',    'fa-truck-fast'],
        ];
    @endphp
    <div class="card border-0 shadow-sm rounded-4 mb-4 {{ $porConfirmar > 0 ? 'border-start border-warning border-5' : '' }}">
        <div class="card-body p-4">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                <h5 class="fw-bold mb-0">
                    <i class="fas fa-globe text-info me-2 {{ $porConfirmar > 0 ? 'fa-beat-fade' : '' }}"></i>Pedidos de la tienda web
                    @if ($porConfirmar > 0)
                        <span class="badge bg-danger rounded-pill ms-1">{{ $porConfirmar }} nuevo{{ $porConfirmar > 1 ? 's' : '' }}</span>
                    @endif
                </h5>
                <a href="{{ route('pedidos-web.index') }}" class="btn btn-outline-primary btn-sm rounded-pill px-3 fw-semibold">
                    Ver todos<i class="fas fa-arrow-right ms-2"></i>
                </a>
            </div>

            <div class="row g-3">
                @foreach ($resumenWeb as $clave => [$texto, $color, $icono])
                    <div class="col-6 col-lg-3">
                        <a href="{{ route('pedidos-web.index', ['estado' => $clave]) }}" class="text-decoration-none">
                            <div class="bg-{{ $color }}-subtle border border-{{ $color }}-subtle rounded-4 p-3 h-100 d-flex align-items-center gap-3">
                                <i class="fas {{ $icono }} fa-2x fa-fw text-{{ $color }}-emphasis"></i>
                                <div>
                                    <div class="fs-3 fw-bold text-{{ $color }}-emphasis lh-1">{{ $pedidosWeb[$clave] ?? 0 }}</div>
                                    <small class="text-{{ $color }}-emphasis">{{ $texto }}</small>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>

            @if ($ultimosPorConfirmar->isNotEmpty())
                <div class="list-group list-group-flush mt-3">
                    @foreach ($ultimosPorConfirmar as $p)
                        <a href="{{ route('pedidos-web.show', $p->id) }}" class="list-group-item list-group-item-action d-flex flex-wrap justify-content-between align-items-center gap-2 px-0">
                            <span>
                                <i class="fas fa-hourglass-half text-warning me-2"></i>
                                <span class="font-monospace fw-semibold">{{ $p->numero_comprobante }}</span>
                                · {{ $p->cliente?->nombres }} {{ $p->cliente?->apellidos }}
                                <small class="text-muted ms-1">{{ $p->fecha_venta?->diffForHumans() }}</small>
                            </span>
                            <span class="fw-bold">S/ {{ number_format($p->total, 2) }}
                                <span class="btn btn-warning btn-sm rounded-pill px-3 ms-2 fw-semibold">Atender</span>
                            </span>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    {{-- =====================================================================
         2. CARRUSEL PRINCIPAL (se mueve solo cada 5 segundos)
    ====================================================================== --}}
    <div id="carruselPrincipal" class="carousel slide carousel-fade shadow rounded-4 overflow-hidden mb-4" data-bs-ride="carousel" data-bs-interval="5000">

        <div class="carousel-indicators mb-2">
            <button type="button" data-bs-target="#carruselPrincipal" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Ingresos"></button>
            <button type="button" data-bs-target="#carruselPrincipal" data-bs-slide-to="1" aria-label="Más vendido"></button>
            <button type="button" data-bs-target="#carruselPrincipal" data-bs-slide-to="2" aria-label="Inventario"></button>
            <button type="button" data-bs-target="#carruselPrincipal" data-bs-slide-to="3" aria-label="Catálogo"></button>
        </div>

        <div class="carousel-inner">

            {{-- Slide 1: Ingresos --}}
            <div class="carousel-item active bg-dark bg-gradient text-white">
                <div class="row align-items-center g-0 px-4 px-lg-5 pt-4 pb-5">
                    <div class="col-lg-9">
                        <span class="badge rounded-pill bg-info-subtle text-info-emphasis mb-2 px-3 py-2">
                            <span class="spinner-grow spinner-grow-sm me-1" role="status"></span> EN TIEMPO REAL
                        </span>
                        <h3 class="fw-bold mb-1">Ingresos de la librería</h3>
                        <p class="text-white-50 mb-3">Total vendido en comprobantes válidos (boletas y facturas).</p>
                        <div class="d-flex flex-wrap gap-3">
                            <div>
                                <div class="small text-white-50 text-uppercase">Total vendido</div>
                                <div class="fs-4 fw-bold text-info">S/ {{ number_format($ingresosTotales, 2) }}</div>
                            </div>
                            <div class="vr opacity-25"></div>
                            <div>
                                <div class="small text-white-50 text-uppercase">Comprobantes</div>
                                <div class="fs-4 fw-bold">{{ $cantidadVentas }}</div>
                            </div>
                            <div class="vr opacity-25"></div>
                            <div>
                                <div class="small text-white-50 text-uppercase">Ticket promedio</div>
                                <div class="fs-4 fw-bold">S/ {{ number_format($ticketPromedio, 2) }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 text-center d-none d-lg-block">
                        <i class="fas fa-chart-line fa-5x text-info opacity-75"></i>
                    </div>
                </div>
            </div>

            {{-- Slide 2: Producto más vendido --}}
            <div class="carousel-item bg-primary bg-gradient text-white">
                <div class="row align-items-center g-0 px-4 px-lg-5 pt-4 pb-5">
                    <div class="col-lg-9">
                        <span class="badge rounded-pill bg-warning text-dark mb-2 px-3 py-2">
                            <i class="fas fa-trophy me-1"></i> TOP VENTAS
                        </span>
                        <h3 class="fw-bold mb-1">{{ $masVendido?->producto?->nombre ?? 'Aún no hay ventas' }}</h3>
                        <p class="text-white-50 mb-3">El producto más vendido de la librería.</p>
                        <div class="d-flex flex-wrap gap-3">
                            <div>
                                <div class="small text-white-50 text-uppercase">Unidades vendidas</div>
                                <div class="fs-4 fw-bold">{{ $masVendido?->unidades ?? 0 }}</div>
                            </div>
                            <div class="vr opacity-25"></div>
                            <div>
                                <div class="small text-white-50 text-uppercase">Ingresos generados</div>
                                <div class="fs-4 fw-bold text-warning">S/ {{ number_format($masVendido?->ingresos ?? 0, 2) }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 text-center d-none d-lg-block">
                        <i class="fas fa-medal fa-5x text-warning opacity-75"></i>
                    </div>
                </div>
            </div>

            {{-- Slide 3: Inventario --}}
            <div class="carousel-item {{ $stockBajo->count() ? 'bg-danger' : 'bg-success' }} bg-gradient text-white">
                <div class="row align-items-center g-0 px-4 px-lg-5 pt-4 pb-5">
                    <div class="col-lg-9">
                        <span class="badge rounded-pill bg-white {{ $stockBajo->count() ? 'text-danger' : 'text-success' }} mb-2 px-3 py-2">
                            <i class="fas fa-warehouse me-1"></i> CONTROL DE INVENTARIO
                        </span>
                        @if ($stockBajo->count())
                            <h3 class="fw-bold mb-1">{{ $stockBajo->count() }} producto(s) por reponer</h3>
                            <p class="text-white-50 mb-3">Tienen stock igual o menor al mínimo. ¡Haz el pedido al proveedor!</p>
                        @else
                            <h3 class="fw-bold mb-1">Inventario en orden</h3>
                            <p class="text-white-50 mb-3">Ningún producto está por debajo de su stock mínimo.</p>
                        @endif
                        <div class="d-flex flex-wrap gap-3">
                            <div>
                                <div class="small text-white-50 text-uppercase">Unidades en almacén</div>
                                <div class="fs-4 fw-bold">{{ number_format($unidadesEnStock) }}</div>
                            </div>
                            <div class="vr opacity-25"></div>
                            <div>
                                <div class="small text-white-50 text-uppercase">Productos activos</div>
                                <div class="fs-4 fw-bold">{{ $totalProductos }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 text-center d-none d-lg-block">
                        <i class="fas {{ $stockBajo->count() ? 'fa-exclamation-triangle' : 'fa-check-double' }} fa-5x opacity-75"></i>
                    </div>
                </div>
            </div>

            {{-- Slide 4: Catálogo --}}
            <div class="carousel-item bg-info bg-gradient text-dark">
                <div class="row align-items-center g-0 px-4 px-lg-5 pt-4 pb-5">
                    <div class="col-lg-9">
                        <span class="badge rounded-pill bg-dark mb-2 px-3 py-2">
                            <i class="fas fa-layer-group me-1"></i> CATÁLOGO
                        </span>
                        <h3 class="fw-bold mb-1">Todo lo que necesitas para leer y estudiar</h3>
                        <p class="opacity-75 mb-3">Novelas, poesía, textos universitarios, útiles y papelería.</p>
                        <div class="d-flex flex-wrap gap-3">
                            <div>
                                <div class="small opacity-75 text-uppercase">Autores</div>
                                <div class="fs-4 fw-bold">{{ $totalAutores }}</div>
                            </div>
                            <div class="vr opacity-25"></div>
                            <div>
                                <div class="small opacity-75 text-uppercase">Categorías</div>
                                <div class="fs-4 fw-bold">{{ $totalCategorias }}</div>
                            </div>
                            <div class="vr opacity-25"></div>
                            <div>
                                <div class="small opacity-75 text-uppercase">Clientes</div>
                                <div class="fs-4 fw-bold">{{ $totalClientes }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 text-center d-none d-lg-block">
                        <i class="fas fa-book-reader fa-5x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="btn-group position-absolute top-0 end-0 m-3 z-3">
            <button class="btn btn-sm btn-light rounded-start-pill" type="button" data-bs-target="#carruselPrincipal" data-bs-slide="prev" title="Anterior">
                <i class="fas fa-chevron-left"></i>
            </button>
            <button class="btn btn-sm btn-light rounded-end-pill" type="button" data-bs-target="#carruselPrincipal" data-bs-slide="next" title="Siguiente">
                <i class="fas fa-chevron-right"></i>
            </button>
        </div>
    </div>

    {{-- =====================================================================
         3. TARJETAS DE RESUMEN (KPI)
    ====================================================================== --}}
    <div class="row g-3 mb-4">
        @php
            $kpis = [
                ['Productos',        $totalProductos,                              'fa-book',          'primary', 'productos.index', 'Ver catálogo'],
                ['Clientes',         $totalClientes,                               'fa-users',         'success', 'clientes.index',  'Ver clientes'],
                ['Ventas del mes',   'S/ ' . number_format($ventasMes, 2),         'fa-calendar-check','warning', 'ventas.index',    'Ver ventas'],
                ['Stock bajo',       $stockBajo->count(),                          'fa-exclamation-triangle', $stockBajo->count() ? 'danger' : 'info', 'productos.index', 'Revisar inventario'],
            ];
        @endphp
        @foreach ($kpis as $k)
            @php [$titulo, $valor, $icono, $color, $ruta, $enlace] = $k; @endphp
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 border-start border-4 border-{{ $color }} shadow-sm rounded-4 h-100">
                    <div class="card-body d-flex align-items-center py-3">
                        <div class="flex-grow-1">
                            <span class="d-block text-muted small text-uppercase fw-bold mb-1">{{ $titulo }}</span>
                            <div class="fs-3 fw-bold text-dark lh-sm">{{ $valor }}</div>
                        </div>
                        <div class="bg-{{ $color }} bg-opacity-10 text-{{ $color }} rounded-circle p-3 lh-1">
                            <i class="fas {{ $icono }} fa-lg fa-fw"></i>
                        </div>
                    </div>
                    <div class="card-footer bg-white border-0 pt-0 pb-2">
                        <a href="{{ route($ruta) }}" class="text-decoration-none small fw-bold text-{{ $color }}">
                            {{ $enlace }} <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="row g-4">

        {{-- =================================================================
             COLUMNA IZQUIERDA: Novedades + Últimas ventas
        ================================================================== --}}
        <div class="col-xl-8">

            {{-- 4. SLIDER DE NOVEDADES (3 productos por diapositiva) --}}
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white border-0 pt-3 px-4 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold text-dark mb-0">
                        <i class="fas fa-bolt text-warning me-2"></i>Novedades
                        <span class="badge rounded-pill bg-danger ms-1 small">NUEVO</span>
                    </h5>
                    <div class="btn-group">
                        <button class="btn btn-sm btn-outline-primary rounded-start-pill" type="button" data-bs-target="#sliderNovedades" data-bs-slide="prev">
                            <i class="fas fa-chevron-left"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-primary rounded-end-pill" type="button" data-bs-target="#sliderNovedades" data-bs-slide="next">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body px-4 pb-3 pt-2">
                    @if ($novedades->isEmpty())
                        <p class="text-muted text-center py-4 mb-0">Aún no hay productos registrados.</p>
                    @else
                        <div id="sliderNovedades" class="carousel slide" data-bs-ride="carousel" data-bs-interval="4000">
                            <div class="carousel-inner">
                                @foreach ($novedades->chunk(3) as $grupo)
                                    <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
                                        <div class="row g-3">
                                            @foreach ($grupo as $p)
                                                @php $esLibro = ! is_null($p->autor_id); @endphp
                                                <div class="col-md-4">
                                                    <div class="card h-100 border border-primary-subtle rounded-4 overflow-hidden">
                                                        <div class="{{ $esLibro ? 'bg-primary' : 'bg-success' }} bg-gradient text-white d-flex align-items-center justify-content-between px-3 py-2">
                                                            <span class="badge bg-white {{ $esLibro ? 'text-primary' : 'text-success' }}">{{ $p->categoria?->nombre }}</span>
                                                            <i class="fas {{ $esLibro ? 'fa-book-open' : 'fa-pencil-ruler' }} fa-lg"></i>
                                                        </div>
                                                        <div class="card-body p-3">
                                                            <small class="font-monospace text-muted">{{ $p->codigo }}</small>
                                                            <h6 class="fw-bold text-dark mb-1 text-truncate" title="{{ $p->nombre }}">{{ $p->nombre }}</h6>
                                                            <small class="text-muted d-block text-truncate mb-2">
                                                                @if ($esLibro)
                                                                    <i class="fas fa-feather-alt me-1"></i>{{ $p->autor?->nombres }} {{ $p->autor?->apellidos }}
                                                                @else
                                                                    <i class="fas fa-tag me-1"></i>{{ $p->marca_editorial }}
                                                                @endif
                                                            </small>
                                                            <div class="d-flex justify-content-between align-items-center">
                                                                <span class="fs-5 fw-bold text-primary">S/ {{ number_format($p->precio_venta, 2) }}</span>
                                                                <a href="{{ route('productos.show', $p->id) }}" class="btn btn-sm btn-outline-primary rounded-pill" title="Ver detalle">
                                                                    <i class="fas fa-box me-1"></i>{{ $p->stock }} <i class="fas fa-arrow-right ms-1"></i>
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- 6. ÚLTIMAS VENTAS --}}
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-header bg-white border-0 pt-3 px-4 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold text-dark mb-0"><i class="fas fa-history text-primary me-2"></i>Últimas ventas</h5>
                    <a href="{{ route('ventas.index') }}" class="btn btn-sm btn-outline-primary rounded-pill px-3 fw-semibold">Ver todas</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-sm table-hover align-middle mb-0 text-nowrap">
                        <thead class="table-light text-uppercase small text-secondary">
                            <tr>
                                <th class="ps-4">Comprobante</th>
                                <th>Cliente</th>
                                <th>Vendedor</th>
                                <th>Fecha</th>
                                <th class="text-end">Total</th>
                                <th>Estado</th>
                                <th class="text-end pe-4">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($ultimasVentas as $venta)
                                @php $c = ['pagada' => 'success', 'pendiente' => 'warning', 'anulada' => 'danger'][$venta->estado] ?? 'secondary'; @endphp
                                <tr>
                                    <td class="ps-4">
                                        <span class="badge {{ $venta->tipo_comprobante === 'factura' ? 'bg-dark' : 'bg-primary' }} me-1">{{ ucfirst($venta->tipo_comprobante) }}</span>
                                        <span class="font-monospace small">{{ $venta->numero_comprobante }}</span>
                                    </td>
                                    <td class="fw-semibold text-dark">{{ $venta->cliente?->nombres }} {{ $venta->cliente?->apellidos }}</td>
                                    <td class="small">{{ $venta->empleado?->nombres }}</td>
                                    <td class="small text-muted">{{ \Illuminate\Support\Carbon::parse($venta->fecha_venta)->format('d/m/Y H:i') }}</td>
                                    <td class="text-end fw-bold">S/ {{ number_format($venta->total, 2) }}</td>
                                    <td><span class="badge rounded-pill bg-{{ $c }}-subtle text-{{ $c }}-emphasis">{{ ucfirst($venta->estado) }}</span></td>
                                    <td class="text-end pe-4">
                                        <a href="{{ route('ventas.show', $venta->id) }}" class="btn btn-sm btn-outline-info rounded-circle" title="Ver detalle"><i class="fas fa-eye fa-fw"></i></a>
                                        <a href="{{ route('ventas.edit', $venta->id) }}" class="btn btn-sm btn-outline-success rounded-circle" title="Editar"><i class="fas fa-pen fa-fw"></i></a>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="7" class="text-center text-muted py-4">
                                    <i class="fas fa-receipt fa-2x d-block mb-2 opacity-50"></i>Aún no hay ventas registradas.
                                </td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- =================================================================
             COLUMNA DERECHA: Cobros + Vendedores + Alertas
        ================================================================== --}}
        <div class="col-xl-4">

            {{-- 5. COBROS POR MÉTODO --}}
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white border-0 pt-3 px-4">
                    <h5 class="fw-bold text-dark mb-0"><i class="fas fa-wallet text-primary me-2"></i>Cobros por método</h5>
                </div>
                <div class="card-body px-4 pt-2 pb-3">
                    @php
                        $metodos = [
                            'efectivo'      => ['bg-success', 'fa-money-bill-wave'],
                            'tarjeta'       => ['bg-primary', 'fa-credit-card'],
                            'yape'          => ['bg-info',    'fa-mobile-alt'],
                            'plin'          => ['bg-info',    'fa-mobile-alt'],
                            'transferencia' => ['bg-dark',    'fa-university'],
                        ];
                    @endphp
                    @forelse ($pagosPorMetodo as $m)
                        @php
                            [$color, $icono] = $metodos[$m->metodo_pago] ?? ['bg-secondary', 'fa-wallet'];
                            $porcentaje = $totalPagado > 0 ? round($m->total * 100 / $totalPagado) : 0;
                        @endphp
                        <div class="mb-2">
                            <div class="d-flex justify-content-between small mb-1">
                                <span class="fw-semibold text-dark"><i class="fas {{ $icono }} fa-fw text-muted me-1"></i>{{ ucfirst($m->metodo_pago) }}
                                    <span class="text-muted fw-normal">({{ $m->cantidad }})</span></span>
                                <span class="fw-bold">S/ {{ number_format($m->total, 2) }} <span class="text-muted fw-normal">· {{ $porcentaje }}%</span></span>
                            </div>
                            <div class="progress rounded-pill" role="progressbar" aria-valuenow="{{ $porcentaje }}" aria-valuemin="0" aria-valuemax="100">
                                <div class="progress-bar {{ $color }} progress-bar-striped progress-bar-animated" data-ancho="{{ $porcentaje }}"></div>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted small mb-0">Aún no hay pagos registrados.</p>
                    @endforelse
                </div>
            </div>

            {{-- MEJORES VENDEDORES --}}
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white border-0 pt-3 px-4">
                    <h5 class="fw-bold text-dark mb-0"><i class="fas fa-crown text-warning me-2"></i>Mejores vendedores</h5>
                </div>
                <ul class="list-group list-group-flush rounded-bottom-4">
                    @forelse ($topVendedores as $v)
                        <li class="list-group-item d-flex align-items-center gap-3 px-4 py-2">
                            <span class="badge rounded-circle {{ $loop->first ? 'bg-warning text-dark' : 'bg-light text-dark border' }} p-2 lh-1">
                                {{ $loop->iteration }}
                            </span>
                            <div class="flex-grow-1 lh-sm">
                                <div class="fw-semibold text-dark small">{{ $v->empleado?->nombres }} {{ $v->empleado?->apellidos }}</div>
                                <small class="text-muted">{{ $v->ventas }} venta(s)</small>
                            </div>
                            <span class="fw-bold text-primary small">S/ {{ number_format($v->monto, 2) }}</span>
                        </li>
                    @empty
                        <li class="list-group-item text-muted small px-4">Aún no hay ventas.</li>
                    @endforelse
                </ul>
            </div>

            {{-- 7. ALERTAS DE STOCK --}}
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-0 pt-3 px-4">
                    <h5 class="fw-bold text-dark mb-0"><i class="fas fa-bell text-danger me-2"></i>Alertas de stock</h5>
                </div>
                <div class="card-body px-4 pt-2 pb-3">
                    @forelse ($stockBajo as $p)
                        @php $nivel = $p->stock_minimo > 0 ? min(100, round($p->stock * 100 / ($p->stock_minimo * 2))) : 0; @endphp
                        <div class="border border-danger-subtle rounded-3 p-2 mb-2">
                            <div class="d-flex justify-content-between mb-1">
                                <span class="fw-semibold text-dark small text-truncate">{{ $p->nombre }}</span>
                                <span class="badge bg-danger">{{ $p->stock }} / {{ $p->stock_minimo }}</span>
                            </div>
                            <div class="progress rounded-pill">
                                <div class="progress-bar bg-danger" data-ancho="{{ $nivel }}"></div>
                            </div>
                        </div>
                    @empty
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-success bg-opacity-10 text-success rounded-circle p-3 lh-1">
                                <i class="fas fa-check-double fa-lg fa-fw"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold text-dark mb-0">¡Todo en orden!</h6>
                                <small class="text-muted">Ningún producto está por debajo de su stock mínimo.</small>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
