{{-- resources/views/catalogo/carrito.blade.php  (archivo NUEVO)
     Página del carrito:  /carrito --}}
@extends('layouts.tienda')

@section('titulo', 'Mi carrito')

@section('content')
@php
    $gratisDesde  = (float) $datos['envio_gratis_desde'];
    $falta        = max(0, $gratisDesde - $subtotal);
    $avanceGratis = $gratisDesde > 0 ? min(100, round($subtotal * 100 / $gratisDesde)) : 100;
    $adelanto     = round($subtotal * $datos['adelanto_recojo'], 2);
@endphp

    {{-- ========== ENCABEZADO ========== --}}
    <header class="bg-primary-subtle bg-gradient border-bottom border-primary border-opacity-25">
        <div class="container py-4">
            <nav aria-label="Ruta">
                <ol class="breadcrumb small mb-2">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}" class="link-warning text-decoration-none"><i class="fas fa-house me-1"></i>Inicio</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('catalogo') }}" class="link-warning text-decoration-none">Catálogo</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Mi carrito</li>
                </ol>
            </nav>
            <div class="d-flex flex-wrap justify-content-between align-items-end gap-3">
                <h1 class="fw-bold mb-0"><i class="fas fa-cart-shopping text-warning me-2"></i>Mi carrito</h1>

                {{-- Pasos de la compra --}}
                <div class="d-flex align-items-center gap-2 small">
                    <span class="badge rounded-pill bg-warning text-dark px-3 py-2"><i class="fas fa-cart-shopping me-1"></i>1. Carrito</span>
                    <i class="fas fa-chevron-right text-white-50"></i>
                    <span class="badge rounded-pill bg-black bg-opacity-50 border border-secondary text-white-50 px-3 py-2"><i class="fas fa-truck me-1"></i>2. Entrega y datos</span>
                    <i class="fas fa-chevron-right text-white-50"></i>
                    <span class="badge rounded-pill bg-black bg-opacity-50 border border-secondary text-white-50 px-3 py-2"><i class="fas fa-mobile-screen me-1"></i>3. Pago</span>
                </div>
            </div>
        </div>
    </header>

    <section class="bg-black py-5">
        <div class="container">

            @if ($items->isEmpty())
                {{-- ========== CARRITO VACÍO ========== --}}
                <div class="text-center bg-primary-subtle border border-primary border-opacity-25 rounded-4 py-5 px-3">
                    <i class="fas fa-cart-shopping fa-4x text-warning mb-3 fa-bounce"></i>
                    <h3 class="fw-bold">Tu carrito está vacío</h3>
                    <p class="text-white-50 mb-4">Explora el catálogo y agrega los libros y útiles que necesitas.</p>
                    <a href="{{ route('catalogo') }}" class="btn btn-warning btn-lg rounded-pill px-5 fw-semibold">
                        <i class="fas fa-book-open me-2"></i>Ir al catálogo
                    </a>
                </div>
            @else
                <div class="row g-4">

                    {{-- ========== PRODUCTOS ========== --}}
                    <div class="col-lg-8">
                        <div class="bg-primary-subtle border border-primary border-opacity-25 rounded-4 overflow-hidden">
                            <div class="d-flex justify-content-between align-items-center px-4 py-3 border-bottom border-primary border-opacity-25">
                                <span class="fw-bold">{{ $items->sum('cantidad') }} producto(s)</span>
                                <form action="{{ route('carrito.vaciar') }}" method="POST"
                                      onsubmit="return confirm('¿Seguro que quieres vaciar el carrito?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-3">
                                        <i class="fas fa-trash me-1"></i>Vaciar carrito
                                    </button>
                                </form>
                            </div>

                            @foreach ($items as $item)
                                @php
                                    $p = $item->producto;
                                    [$color, $icono] = $estilo($p->categoria?->nombre);
                                @endphp
                                <div class="d-flex flex-wrap flex-md-nowrap align-items-center gap-3 px-4 py-3 {{ $loop->last ? '' : 'border-bottom border-primary border-opacity-25' }}">

                                    {{-- Foto --}}
                                    <a href="{{ route('producto.ver', $p->codigo) }}" class="flex-shrink-0">
                                        @if ($p->imagen)
                                            <img src="{{ $p->url_imagen }}" alt="{{ $p->nombre }}" width="70" height="95" class="rounded-3 object-fit-cover border border-{{ $color }} border-opacity-50">
                                        @else
                                            <span class="d-inline-flex align-items-center justify-content-center bg-{{ $color }} bg-opacity-25 text-{{ $color }}-emphasis rounded-3 px-3 py-4">
                                                <i class="fas {{ $icono }} fa-xl fa-fw"></i>
                                            </span>
                                        @endif
                                    </a>

                                    {{-- Nombre y precio --}}
                                    <div class="flex-grow-1">
                                        <a href="{{ route('producto.ver', $p->codigo) }}" class="fw-bold link-light text-decoration-none">{{ $p->nombre }}</a>
                                        <div class="small text-white-50">
                                            {{ $p->categoria?->nombre }}
                                            @if ($p->autor) · {{ $p->autor->nombres }} {{ $p->autor->apellidos }} @endif
                                        </div>
                                        <div class="small text-warning">S/ {{ number_format($p->precio_venta, 2) }} c/u
                                            @if ($p->stock <= $p->stock_minimo)
                                                <span class="badge rounded-pill bg-warning-subtle text-warning-emphasis ms-1">Quedan {{ $p->stock }}</span>
                                            @endif
                                        </div>
                                    </div>

                                    {{-- Cantidad (se guarda al cambiar) --}}
                                    <form action="{{ route('carrito.actualizar', $p->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <div class="input-group input-group-sm">
                                            <button class="btn btn-outline-warning" type="button" data-cantidad="-1" data-objetivo="cant{{ $p->id }}" aria-label="Menos"
                                                    {{ $item->cantidad <= 1 ? 'disabled' : '' }}>
                                                <i class="fas fa-minus"></i>
                                            </button>
                                            <input type="number" name="cantidad" id="cant{{ $p->id }}" value="{{ $item->cantidad }}" min="1" max="{{ $p->stock }}"
                                                   class="form-control bg-black border-warning text-center fw-bold" aria-label="Cantidad"
                                                   data-autoenviar onchange="this.form.submit()">
                                            <button class="btn btn-outline-warning" type="button" data-cantidad="1" data-objetivo="cant{{ $p->id }}" aria-label="Más"
                                                    {{ $item->cantidad >= $p->stock ? 'disabled' : '' }}>
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        </div>
                                    </form>

                                    {{-- Subtotal y quitar --}}
                                    <div class="text-end">
                                        <div class="fw-bold fs-5">S/ {{ number_format($item->subtotal, 2) }}</div>
                                        <form action="{{ route('carrito.quitar', $p->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-link btn-sm link-danger text-decoration-none p-0">
                                                <i class="fas fa-xmark me-1"></i>Quitar
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <a href="{{ route('catalogo') }}" class="btn btn-outline-light rounded-pill px-4 mt-3">
                            <i class="fas fa-arrow-left me-2"></i>Seguir comprando
                        </a>
                    </div>

                    {{-- ========== RESUMEN ========== --}}
                    <div class="col-lg-4">
                        <div class="bg-primary-subtle border border-warning border-opacity-50 rounded-4 shadow-lg p-4 sticky-top">
                            <h5 class="fw-bold mb-4"><i class="fas fa-receipt text-warning me-2"></i>Resumen</h5>

                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-white-50">Subtotal</span>
                                <span class="fw-bold">S/ {{ number_format($subtotal, 2) }}</span>
                            </div>

                            {{-- Envío gratis: barra de avance --}}
                            <div class="bg-black bg-opacity-50 rounded-3 p-3 my-3">
                                @if ($falta > 0)
                                    <div class="small mb-2">
                                        <i class="fas fa-truck-fast text-info me-1"></i>
                                        Te faltan <strong class="text-warning">S/ {{ number_format($falta, 2) }}</strong> para el <strong>envío gratis</strong>
                                    </div>
                                @else
                                    <div class="small mb-2 text-success-emphasis">
                                        <i class="fas fa-circle-check text-success me-1"></i>¡Tienes <strong>envío gratis</strong> a domicilio!
                                    </div>
                                @endif
                                <div class="progress rounded-pill" role="progressbar" aria-valuenow="{{ $avanceGratis }}" aria-valuemin="0" aria-valuemax="100">
                                   <div class="progress-bar {{ $falta > 0 ? 'bg-info' : 'bg-success' }} progress-bar-striped progress-bar-animated" data-ancho="{{ $avanceGratis }}"></div>
                                </div>
                            </div>

                            {{-- Las dos formas de recibir --}}
                            <div class="small mb-4">
                                <div class="d-flex justify-content-between mb-2">
                                    <span><i class="fas fa-truck fa-fw text-info me-1"></i>Envío a domicilio</span>
                                    <span>{{ $envio > 0 ? 'S/ ' . number_format($envio, 2) : 'Gratis' }}</span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span><i class="fas fa-store fa-fw text-success me-1"></i>Recojo en tienda</span>
                                    <span>Gratis</span>
                                </div>
                            </div>

                            <div class="border-top border-warning border-opacity-25 pt-3 mb-1 d-flex justify-content-between align-items-center">
                                <span class="fw-bold">Total con envío</span>
                                <span class="fs-3 fw-bold text-warning">S/ {{ number_format($subtotal + $envio, 2) }}</span>
                            </div>
                            <p class="small text-white-50 mb-4">
                                Si recoges en tienda: pagas <strong class="text-white">S/ {{ number_format($adelanto, 2) }}</strong> de adelanto
                                ({{ $datos['adelanto_recojo'] * 100 }} %) y el resto al recoger.
                            </p>

                            <a href="{{ route('compra') }}" class="btn btn-warning btn-lg w-100 rounded-pill fw-bold shadow mb-2" data-animar="fa-bounce">
                                <i class="fas fa-lock me-2"></i>Continuar compra
                            </a>
                            @guest
                                <p class="small text-white-50 text-center mb-3">
                                    <i class="fas fa-circle-info me-1"></i>Te pediremos iniciar sesión o crear una cuenta. Tu carrito se conserva.
                                </p>
                            @endguest

                            {{-- Alternativa: pedido por WhatsApp con el detalle del carrito --}}
                            @php
                                $lineas = $items->map(fn ($it) => '• ' . $it->cantidad . ' x ' . $it->producto->nombre . ' (' . $it->producto->codigo . ') = S/ ' . number_format($it->subtotal, 2))->implode("\n");
                                $pedido = "Hola, quiero hacer este pedido:\n" . $lineas . "\nSubtotal: S/ " . number_format($subtotal, 2);
                            @endphp
                            <a href="{{ $wa($pedido) }}" target="_blank" rel="noopener" class="btn btn-outline-success w-100 rounded-pill" data-animar="fa-beat">
                                <i class="fas fa-comment-dots me-2"></i>O pedir por WhatsApp
                            </a>

                            <div class="d-flex justify-content-center gap-3 mt-4 text-white-50">
                                <i class="fas fa-mobile-screen fa-lg" data-bs-toggle="tooltip" data-bs-title="Yape"></i>
                                <i class="fas fa-mobile-screen-button fa-lg" data-bs-toggle="tooltip" data-bs-title="Plin"></i>
                                <i class="fas fa-building-columns fa-lg" data-bs-toggle="tooltip" data-bs-title="Transferencia"></i>
                                <i class="fas fa-shield-halved fa-lg" data-bs-toggle="tooltip" data-bs-title="Compra segura"></i>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </section>
@endsection
