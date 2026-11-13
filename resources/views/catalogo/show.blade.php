{{-- resources/views/catalogo/show.blade.php  (archivo NUEVO)
     Página propia de un producto:  /producto/LIB-0001 --}}
@extends('layouts.tienda')

@section('titulo', $producto->nombre)

@section('content')
@php
    $esLibro = ! is_null($producto->autor_id);
    [$color, $icono] = $estilo($producto->categoria?->nombre);
    $enlaceProducto = route('producto.ver', $producto->codigo);
    $mensajeCompra  = 'Hola, me interesa: ' . $producto->nombre . ' (' . $producto->codigo . ') a S/ '
                    . number_format($producto->precio_venta, 2) . '. ¿Está disponible?';
    $mensajeCompartir = '¡Mira este producto de ' . $datos['nombre'] . '! ' . $producto->nombre . ' - ' . $enlaceProducto;
@endphp

    {{-- ========== RUTA (migas de pan) ========== --}}
    <div class="bg-primary-subtle border-bottom border-primary border-opacity-25">
        <div class="container py-3">
            <nav aria-label="Ruta">
                <ol class="breadcrumb small mb-0">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}" class="link-warning text-decoration-none"><i class="fas fa-house me-1"></i>Inicio</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('catalogo') }}" class="link-warning text-decoration-none">Catálogo</a></li>
                    @if ($producto->categoria)
                        <li class="breadcrumb-item"><a href="{{ route('catalogo', ['categoria' => $producto->categoria_id]) }}" class="link-warning text-decoration-none">{{ $producto->categoria->nombre }}</a></li>
                    @endif
                    <li class="breadcrumb-item active text-truncate" aria-current="page">{{ $producto->nombre }}</li>
                </ol>
            </nav>
        </div>
    </div>

    {{-- ========== PRODUCTO ========== --}}
    <section class="bg-black py-5">
        <div class="container">
            <div class="row g-5">

                {{-- Foto --}}
                <div class="col-lg-5">
                    <div class="bg-{{ $color }} bg-opacity-25 border border-{{ $color }} border-opacity-50 rounded-4 shadow-lg overflow-hidden text-center position-relative">
                        <span class="badge rounded-pill bg-black bg-opacity-75 text-{{ $color }}-emphasis position-absolute top-0 start-0 m-3 px-3 py-2">
                            <i class="fas {{ $icono }} me-1"></i>{{ $producto->categoria?->nombre }}
                        </span>
                        @if ($producto->imagen)
                            <img src="{{ $producto->url_imagen }}" alt="{{ $producto->nombre }}" class="img-fluid w-100">
                        @else
                            <div class="py-5 my-5">
                                <i class="fas {{ $icono }} fa-10x text-{{ $color }}-emphasis opacity-75"></i>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Datos y compra --}}
                <div class="col-lg-7">
                    <small class="font-monospace text-white-50">{{ $producto->codigo }}</small>
                    <h1 class="fw-bold display-6 mb-2">{{ $producto->nombre }}</h1>

                    @if ($esLibro)
                        <p class="fs-5 mb-3">
                            <i class="fas fa-feather-pointed text-warning me-2"></i>
                            <a href="{{ route('catalogo', ['autor' => $producto->autor_id]) }}" class="link-light link-opacity-75 link-opacity-100-hover"
                               data-bs-toggle="tooltip" data-bs-title="Ver todos sus libros">
                                {{ $producto->autor?->nombres }} {{ $producto->autor?->apellidos }}
                            </a>
                            @if ($producto->autor?->nacionalidad)
                                <span class="badge rounded-pill bg-info-subtle text-info-emphasis ms-2 fs-6 fw-normal">{{ $producto->autor->nacionalidad }}</span>
                            @endif
                        </p>
                    @endif

                    <div class="d-flex flex-wrap align-items-center gap-3 mb-4">
                        <span class="display-5 fw-bold text-warning">S/ {{ number_format($producto->precio_venta, 2) }}</span>
                        @if ($producto->stock > $producto->stock_minimo)
                            <span class="badge rounded-pill bg-success-subtle text-success-emphasis border border-success-subtle fs-6 px-3 py-2"><i class="fas fa-circle-check me-1"></i>Disponible · {{ $producto->stock }} unidades</span>
                        @elseif ($producto->stock > 0)
                            <span class="badge rounded-pill bg-warning-subtle text-warning-emphasis border border-warning-subtle fs-6 px-3 py-2"><i class="fas fa-hourglass-half me-1"></i>¡Últimas {{ $producto->stock }} unidades!</span>
                        @else
                            <span class="badge rounded-pill bg-danger-subtle text-danger-emphasis border border-danger-subtle fs-6 px-3 py-2"><i class="fas fa-circle-xmark me-1"></i>Agotado por ahora</span>
                        @endif
                    </div>

                    @if ($producto->descripcion)
                        <p class="lead text-white-50 mb-4">{{ $producto->descripcion }}</p>
                    @endif

                    {{-- Ficha técnica --}}
                    <div class="bg-primary-subtle border border-primary border-opacity-25 rounded-4 overflow-hidden mb-4">
                        <table class="table table-sm table-borderless align-middle mb-0">
                            <tbody>
                                <tr><th class="ps-4 py-2 text-white-50 fw-normal w-50"><i class="fas fa-barcode fa-fw text-warning me-2"></i>Código</th><td class="font-monospace">{{ $producto->codigo }}</td></tr>
                                @if ($producto->isbn)
                                    <tr><th class="ps-4 py-2 text-white-50 fw-normal"><i class="fas fa-hashtag fa-fw text-warning me-2"></i>ISBN</th><td class="font-monospace">{{ $producto->isbn }}</td></tr>
                                @endif
                                <tr><th class="ps-4 py-2 text-white-50 fw-normal"><i class="fas fa-building fa-fw text-warning me-2"></i>{{ $esLibro ? 'Editorial' : 'Marca' }}</th><td>{{ $producto->marca_editorial ?? '—' }}</td></tr>
                                <tr><th class="ps-4 py-2 text-white-50 fw-normal"><i class="fas fa-tags fa-fw text-warning me-2"></i>Categoría</th><td>{{ $producto->categoria?->nombre }}</td></tr>
                            </tbody>
                        </table>
                    </div>

                    {{-- Comprar: cantidad + agregar al carrito --}}
                    @if ($producto->stock > 0)
                        <form action="{{ route('carrito.agregar', $producto->id) }}" method="POST"
                              class="d-flex flex-wrap align-items-center gap-3 bg-primary-subtle border border-warning border-opacity-50 rounded-4 p-3 mb-3">
                            @csrf
                            <div class="input-group input-group-lg w-auto">
                                <button class="btn btn-outline-warning" type="button" data-cantidad="-1" data-objetivo="cantidad" aria-label="Menos">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <input type="number" name="cantidad" id="cantidad" value="1" min="1" max="{{ $producto->stock }}"
                                       class="form-control bg-black border-warning text-center fw-bold" aria-label="Cantidad">
                                <button class="btn btn-outline-warning" type="button" data-cantidad="1" data-objetivo="cantidad" aria-label="Más">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                            <button type="submit" class="btn btn-warning btn-lg rounded-pill px-4 fw-bold shadow flex-grow-1" data-animar="fa-bounce">
                                <i class="fas fa-cart-plus me-2"></i>Agregar al carrito
                            </button>
                            @if ($carritoCantidad > 0)
                                <a href="{{ route('carrito') }}" class="small link-warning text-decoration-none w-100 text-center">
                                    <i class="fas fa-cart-shopping me-1"></i>Tienes {{ $carritoCantidad }} producto(s) en tu carrito · Ver carrito
                                </a>
                            @endif
                        </form>
                    @endif

                    {{-- Otros botones --}}
                    <div class="d-flex flex-wrap gap-2 mb-4">
                        <a href="{{ $wa($mensajeCompra) }}" target="_blank" rel="noopener"
                           class="btn btn-success btn-lg rounded-pill px-4 fw-semibold shadow {{ $producto->stock > 0 ? '' : 'disabled' }}" data-animar="fa-beat">
                            <i class="fas fa-comment-dots me-2"></i>Consultar
                        </a>
                        <a href="{{ $wa($mensajeCompartir) }}" target="_blank" rel="noopener"
                           class="btn btn-outline-light btn-lg rounded-pill px-4" data-animar="fa-bounce"
                           data-bs-toggle="tooltip" data-bs-title="Enviar este producto a un amigo">
                            <i class="fas fa-share-nodes me-2"></i>Compartir
                        </a>
                        <button type="button" class="btn btn-outline-warning btn-lg rounded-pill px-4" data-copiar="{{ $enlaceProducto }}"
                                data-bs-toggle="tooltip" data-bs-title="Copiar el enlace de este producto">
                            <i class="fas fa-link me-2"></i>Copiar enlace
                        </button>
                    </div>

                    {{-- Envío, recojo y pagos --}}
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="h-100 bg-black border border-info border-opacity-25 rounded-4 p-3">
                                <i class="fas fa-truck-fast fa-lg text-info mb-2"></i>
                                <div class="fw-bold small">Envío a domicilio</div>
                                <small class="text-white-50">S/ {{ number_format($datos['costo_envio'], 2) }} · gratis desde S/ {{ number_format($datos['envio_gratis_desde'], 0) }}</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="h-100 bg-black border border-success border-opacity-25 rounded-4 p-3">
                                <i class="fas fa-store fa-lg text-success mb-2"></i>
                                <div class="fw-bold small">Recojo en tienda</div>
                                <small class="text-white-50">Separa con el {{ $datos['adelanto_recojo'] * 100 }} % y paga el resto al recoger</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <a href="{{ url('/') }}#pagos" class="d-block h-100 bg-black border border-warning border-opacity-25 rounded-4 p-3 text-decoration-none text-white">
                                <i class="fas fa-mobile-screen fa-lg text-warning mb-2"></i>
                                <div class="fw-bold small">Yape · Plin · Tarjeta</div>
                                <small class="text-white-50">Ver métodos de pago</small>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ========== RELACIONADOS ========== --}}
    @if ($relacionados->isNotEmpty())
        <section class="bg-primary-subtle bg-gradient border-top border-primary border-opacity-25 py-5">
            <div class="container">
                <div class="d-flex justify-content-between align-items-end mb-4">
                    <div>
                        <span class="text-warning fw-bold small text-uppercase"><i class="fas fa-wand-magic-sparkles me-1"></i>También te puede gustar</span>
                        <h2 class="fw-bold mt-2 mb-0">Productos relacionados</h2>
                    </div>
                    <a href="{{ route('catalogo', ['categoria' => $producto->categoria_id]) }}" class="btn btn-outline-warning rounded-pill px-4 d-none d-md-inline-block">
                        Ver más de {{ $producto->categoria?->nombre }} <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
                <div class="row g-4">
                    @foreach ($relacionados as $p)
                        <div class="col-sm-6 col-lg-3">
                            @include('catalogo._tarjeta', ['p' => $p])
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
@endsection
