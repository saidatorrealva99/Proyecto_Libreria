
@extends('layouts.tienda')

@section('titulo', 'Inicio')

@section('content')

    {{-- =====================================================================
         PORTADA
    ====================================================================== --}}
    <header id="inicio" class="bg-primary-subtle bg-gradient border-bottom border-primary border-opacity-25">

        {{-- Barra superior de útiles (accesos a categorías) --}}
        @include('catalogo._barra-utiles')

        <div class="container py-5">
            <div class="row align-items-center g-4 py-lg-3">

                {{-- Izquierda: pila de libros --}}
                <div class="col-lg-2 d-none d-lg-flex flex-column align-items-center">
                    <a href="{{ route('catalogo', ['tipo' => 'libros']) }}" class="text-decoration-none d-flex flex-column align-items-center"
                       data-animar="fa-bounce" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-title="Ver todos los libros">
                        <span class="badge rounded-pill bg-warning text-dark mb-3 px-3 py-2">
                            <i class="fas fa-fire me-1"></i>+{{ $totalLibros }} títulos
                        </span>
                        <span class="d-flex flex-column align-items-center lh-1">
                            <i class="fas fa-book fa-5x fa-rotate-90 text-primary"></i>
                            <i class="fas fa-book fa-5x fa-rotate-90 text-info"></i>
                            <i class="fas fa-book fa-5x fa-rotate-90 text-warning"></i>
                            <i class="fas fa-book fa-5x fa-rotate-90 text-danger"></i>
                        </span>
                    </a>
                    <a href="{{ route('catalogo', ['q' => 'Novela']) }}" class="mt-3" data-animar="fa-bounce"
                       data-bs-toggle="tooltip" data-bs-placement="right" data-bs-title="Novelas">
                        <i class="fas fa-book-open fa-2x text-primary-emphasis fa-beat-fade"></i>
                    </a>
                </div>

                {{-- Centro: mensaje, buscador y botones --}}
                <div class="col-lg-8 text-center">
                    <span class="badge rounded-pill border border-warning text-light bg-black bg-opacity-50 px-3 py-2 mb-4">
                        <span class="spinner-grow spinner-grow-sm text-warning me-1" role="status"></span>
                        LIBROS · ÚTILES · PAPELERÍA
                    </span>

                    <div class="d-flex justify-content-center align-items-start gap-3">
                        <div class="d-none d-md-flex flex-column gap-2 pt-3">
                            <i class="fas fa-star fa-xs text-warning fa-fade"></i>
                            <i class="fas fa-star text-warning"></i>
                        </div>
                        <h1 class="display-4 fw-bold lh-sm mb-3">
                            Tu próxima historia<br><span class="text-warning">empieza aquí</span>
                        </h1>
                        <div class="d-none d-md-flex flex-column gap-2 pt-3">
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star fa-xs text-warning fa-fade"></i>
                        </div>
                    </div>

                    <p class="lead text-white-50 mb-4">
                        Novelas, textos universitarios, útiles y papelería.<br class="d-none d-md-inline">
                        Todo para leer, estudiar y crear en un solo lugar.
                    </p>

                    {{-- Buscador (funciona: filtra los productos de la base de datos) --}}
                    <form action="{{ route('catalogo') }}" method="GET" class="col-lg-10 mx-auto mb-3">
                        <div class="input-group input-group-lg shadow">
                            <span class="input-group-text bg-black border-warning border-opacity-50 rounded-start-pill text-warning">
                                <i class="fas fa-magnifying-glass"></i>
                            </span>
                            <select name="tipo" class="form-select bg-black border-warning border-opacity-50 text-white-50 flex-grow-0 w-auto" aria-label="Tipo">
                                <option value="">Todo</option>
                                <option value="libros">Libros</option>
                                <option value="utiles">Útiles</option>
                            </select>
                            <input type="text" name="q" class="form-control bg-black border-warning border-opacity-50 text-white"
                                   placeholder="Busca un libro, autor o útil" aria-label="Buscar">
                            <button class="btn btn-warning rounded-end-pill fw-bold px-4" type="submit">Buscar</button>
                        </div>
                    </form>

                    {{-- Categorías rápidas --}}
                    <div class="d-flex flex-wrap justify-content-center gap-2 mb-4">
                        @foreach ($categorias->take(6) as $cat)
                            @php [$color, $icono] = $estilo($cat->nombre); @endphp
                            <a href="{{ route('catalogo', ['categoria' => $cat->id]) }}"
                               class="badge rounded-pill bg-black bg-opacity-50 border border-{{ $color }} border-opacity-50 text-{{ $color }}-emphasis fw-normal text-decoration-none px-3 py-2">
                                <i class="fas {{ $icono }} me-1"></i>{{ $cat->nombre }}
                            </a>
                        @endforeach
                    </div>

                    <div class="d-flex flex-wrap justify-content-center gap-3 mb-4">
                        <a href="{{ route('catalogo') }}" class="btn btn-warning btn-lg rounded-pill px-4 fw-semibold shadow">
                            <i class="fas fa-book-open me-2"></i>Ver catálogo
                        </a>
                        <a href="{{ $miZona }}" class="btn btn-outline-info btn-lg rounded-pill px-4 fw-semibold">
                            <i class="fas fa-right-to-bracket me-2"></i>{{ $miZonaTexto }}
                        </a>
                    </div>

                    <div class="d-flex flex-wrap justify-content-center align-items-center gap-3 small">
                        <a href="#pagos" class="link-light link-opacity-75 link-opacity-100-hover text-decoration-none" data-animar="fa-beat"
                           data-bs-toggle="tooltip" data-bs-title="Pagar con el QR de Yape o Plin">
                            <i class="fas fa-mobile-screen text-info me-1"></i>Yape · Plin
                        </a>
                        <span class="vr"></span>
                        <a href="#pagos" class="link-light link-opacity-75 link-opacity-100-hover text-decoration-none" data-animar="fa-beat"
                           data-bs-toggle="tooltip" data-bs-title="Visa y Mastercard">
                            <i class="fas fa-credit-card text-warning me-1"></i>Tarjeta
                        </a>
                        <span class="vr"></span>
                        <a href="#pagos" class="link-light link-opacity-75 link-opacity-100-hover text-decoration-none" data-animar="fa-beat"
                           data-bs-toggle="tooltip" data-bs-title="Datos para transferencia y factura">
                            <i class="fas fa-file-invoice text-success me-1"></i>Factura con RUC
                        </a>
                    </div>
                </div>

                {{-- Derecha: birrete, estante y avión de papel --}}
                <div class="col-lg-2 d-none d-lg-flex flex-column align-items-center">
                    <a href="{{ route('catalogo', ['q' => 'Informática']) }}" class="mb-4" data-animar="fa-bounce"
                       data-bs-toggle="tooltip" data-bs-placement="left" data-bs-title="Textos universitarios">
                        <i class="fas fa-graduation-cap fa-4x text-light"></i>
                    </a>

                    {{-- Estante: 3 repisas con libros parados --}}
                    <a href="{{ route('catalogo') }}" class="d-block w-100 px-2 mb-4 text-decoration-none"
                       data-bs-toggle="tooltip" data-bs-placement="left" data-bs-title="Ver el catálogo">
                        @foreach ([['primary', 'info', 'warning', 'danger'], ['success', 'light', 'primary', 'info'], ['warning', 'danger', 'info', 'success']] as $repisa)
                            <div class="d-flex justify-content-center align-items-end gap-1 opacity-75 pt-2">
                                @foreach ($repisa as $colorLibro)
                                    <i class="fas fa-book fa-2x text-{{ $colorLibro }}"></i>
                                @endforeach
                            </div>
                            <div class="border-top border-4 border-primary rounded-pill"></div>
                        @endforeach
                    </a>

                    <a href="#contacto" data-animar="fa-shake"
                       data-bs-toggle="tooltip" data-bs-placement="left" data-bs-title="Contáctanos">
                        <i class="far fa-paper-plane fa-3x text-light fa-bounce"></i>
                    </a>
                </div>
            </div>
        </div>

        {{-- Barra inferior de útiles --}}
        <div class="bg-black bg-opacity-25 border-top border-primary border-opacity-25">
            <div class="container d-flex flex-wrap justify-content-around align-items-center gap-2 py-3">
                @foreach ($barraInferior as $util)
                    @php [$icono, $color, $ayuda, $enlace] = $util; @endphp
                    <a href="{{ $enlace }}" class="p-2 lh-1" data-animar="fa-bounce"
                       @if (str_starts_with($enlace, 'https://wa.me')) target="_blank" rel="noopener" @endif
                       data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="{{ $ayuda }}">
                        <i class="fas {{ $icono }} fa-xl fa-fw text-{{ $color }}"></i>
                    </a>
                    @unless ($loop->last)
                        <span class="vr opacity-25 d-none d-md-inline-block"></span>
                    @endunless
                @endforeach
            </div>
        </div>
    </header>

    {{-- =====================================================================
         CIFRAS (datos reales)
    ====================================================================== --}}
    <section class="bg-black py-4">
        <div class="container">
            <div class="row g-3">
                @php
                    $cifras = [
                        [$totalProductos,  'Productos en catálogo', 'fa-book',            'primary', route('catalogo'),                      'Ver catálogo'],
                        [$totalAutores,    'Autores',               'fa-feather-pointed', 'success', route('catalogo', ['tipo' => 'libros']), 'Ver libros por autor'],
                        [$totalCategorias, 'Categorías',            'fa-tags',            'warning', url('/') . '#inicio',                  'Ver categorías'],
                        [$totalClientes,   'Clientes atendidos',    'fa-users',           'info',    url('/') . '#resenas',                 'Lee lo que opinan nuestros clientes'],
                    ];
                @endphp
                @foreach ($cifras as $c)
                    @php [$numero, $texto, $icono, $color, $enlace, $ayuda] = $c; @endphp
                    <div class="col-6 col-lg-3">
                        <a href="{{ $enlace }}" data-animar="fa-beat" data-bs-toggle="tooltip" data-bs-title="{{ $ayuda }}"
                           class="d-flex align-items-center gap-3 bg-primary-subtle border border-primary border-opacity-25 rounded-4 p-3 h-100 text-decoration-none text-white">
                            <span class="bg-{{ $color }} bg-opacity-25 text-{{ $color }}-emphasis rounded-circle p-3 lh-1">
                                <i class="fas {{ $icono }} fa-lg fa-fw"></i>
                            </span>
                            <div class="lh-sm">
                                <div class="fs-3 fw-bold">{{ $numero }}+</div>
                                <small class="text-white-50">{{ $texto }}</small>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- =====================================================================
         NOVEDADES (los 6 últimos productos) -> el catálogo completo está en /catalogo
    ====================================================================== --}}
    <section id="catalogo" class="bg-black pb-5 pt-4">
        <div class="container">
            <div class="d-flex flex-wrap justify-content-between align-items-end gap-3 mb-4">
                <div>
                    <span class="text-warning fw-bold small text-uppercase"><i class="fas fa-bolt me-1"></i>Novedades</span>
                    <h2 class="fw-bold mt-2 mb-0">Lo último en nuestra librería</h2>
                </div>
                <a href="{{ route('catalogo') }}" class="btn btn-outline-warning rounded-pill px-4" data-animar="fa-bounce">
                    Ver todo el catálogo <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>

            <div class="row g-4">
                @forelse ($productos as $p)
                    <div class="col-sm-6 col-lg-4">
                        @include('catalogo._tarjeta', ['p' => $p, 'nuevo' => $loop->first])
                    </div>
                @empty
                    <div class="col-12 text-center text-white-50 py-5">Pronto tendremos novedades para ti.</div>
                @endforelse
            </div>
        </div>
    </section>

    {{-- =====================================================================
         SERVICIOS
    ====================================================================== --}}
    <section id="servicios" class="bg-primary-subtle bg-gradient border-top border-bottom border-primary border-opacity-25 py-5">
        <div class="container py-lg-3">
            <div class="text-center mb-5">
                <span class="text-warning fw-bold small text-uppercase"><i class="fas fa-star me-1"></i>Lo que hacemos</span>
                <h2 class="fw-bold mt-2">Una librería pensada para ti</h2>
                <p class="text-white-50 col-lg-7 mx-auto">Lectores, estudiantes, docentes y colegios encuentran aquí todo lo que necesitan.</p>
            </div>

            <div class="row g-4 mb-5">
                @php
                    $servicios = [
                        ['fa-book-open-reader', 'info',    'Venta de libros',       'Novelas, poesía, cuentos y literatura peruana para todas las edades.', 'Ver libros',            route('catalogo', ['tipo' => 'libros'])],
                        ['fa-graduation-cap',   'primary', 'Textos académicos',     'Informática, matemáticas, historia y más para tu carrera.',           'Ver textos',            route('catalogo', ['q' => 'Informática'])],
                        ['fa-pencil-ruler',     'success', 'Útiles escolares',      'Cuadernos, folders, papelería y todo para el aula.',                  'Ver útiles',            route('catalogo', ['tipo' => 'utiles'])],
                        ['fa-school',           'warning', 'Pedidos para colegios', 'Compras por volumen con factura y precios especiales.',               'Solicitar cotización',  $wa('Hola, somos un colegio y queremos cotizar una compra por volumen con factura.')],
                    ];
                @endphp
                @foreach ($servicios as $s)
                    @php [$icono, $color, $titulo, $texto, $boton, $enlace] = $s; @endphp
                    <div class="col-md-6 col-lg-3">
                        <div class="h-100 d-flex flex-column bg-black bg-opacity-50 border border-{{ $color }} border-opacity-25 rounded-4 p-4">
                            <span class="d-inline-block align-self-start bg-{{ $color }} bg-opacity-25 border border-{{ $color }} border-opacity-50 text-{{ $color }}-emphasis rounded-3 p-3 lh-1 mb-3">
                                <i class="fas {{ $icono }} fa-xl fa-fw"></i>
                            </span>
                            <h5 class="fw-bold">{{ $titulo }}</h5>
                            <p class="text-white-50 small">{{ $texto }}</p>
                            <a href="{{ $enlace }}" class="btn btn-outline-{{ $color }} btn-sm rounded-pill mt-auto align-self-start px-3" data-animar="fa-beat"
                               @if (str_starts_with($enlace, 'https://wa.me')) target="_blank" rel="noopener" @endif>
                                {{ $boton }} <i class="fas fa-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

        </div>
    </section>

    {{-- =====================================================================
         MÉTODOS DE PAGO
    ====================================================================== --}}
    <section id="pagos" class="bg-black py-5">
        <div class="container">
            <div class="text-center mb-5">
                <span class="text-warning fw-bold small text-uppercase"><i class="fas fa-shield-halved me-1"></i>Pagos seguros</span>
                <h2 class="fw-bold mt-2">Paga como prefieras</h2>
                <p class="text-white-50">Aceptamos billeteras digitales, tarjetas, efectivo y transferencias. Emitimos boleta o factura.</p>
            </div>

            @php
                // [ícono, clase FontAwesome, color, título, detalle, dato a copiar, texto del dato, imagen QR]
                $pagos = [
                    ['fa-mobile-screen',     'fas', 'info',    'Yape',          'A nombre de ' . $datos['titular'],                '',               '',                          $datos['yape_qr']],
                    ['fa-mobile-screen-button','fas','primary', 'Plin',          'A nombre de ' . $datos['titular'],                '',               '',                          $datos['plin_qr']],
                    ['fa-credit-card',       'fas', 'warning', 'Tarjeta',       'Débito o crédito: Visa y Mastercard en tienda.',  '',               '',                          null],
                    ['fa-money-bill-wave',   'fas', 'success', 'Efectivo',      'Paga en tienda al recoger tu pedido.',            '',               '',                          null],
                    ['fa-building-columns',  'fas', 'light',   'Transferencia', $datos['banco'] . ' · CCI: ' . $datos['cci'],      $datos['cuenta'], 'Cuenta ' . $datos['banco'], null],
                ];
            @endphp

            <div class="row g-4 justify-content-center">
                @foreach ($pagos as $pg)
                    @php [$icono, $tipoIcono, $color, $titulo, $detalle, $dato, $etiqueta, $qr] = $pg; @endphp
                    <div class="col-sm-6 col-lg-4 col-xl">
                        <div class="h-100 text-center bg-primary-subtle border border-{{ $color }} border-opacity-50 rounded-4 shadow p-4 d-flex flex-column">
                            <span class="d-inline-block align-self-center bg-{{ $color }} bg-gradient {{ in_array($color, ['warning', 'info', 'light']) ? 'text-dark' : 'text-white' }} rounded-circle shadow p-3 lh-1 mb-3">
                                <i class="{{ $tipoIcono }} {{ $icono }} fa-2x fa-fw"></i>
                            </span>
                            <h5 class="fw-bold mb-1">{{ $titulo }}</h5>
                            <p class="text-white-50 small mb-3">{{ $detalle }}</p>
                            @if ($qr)
                                <div class="mt-auto">
                                    @include('catalogo._qr', ['archivo' => $qr, 'nombre' => $titulo, 'color' => $color])
                                </div>
                            @elseif ($dato)
                                <div class="mt-auto">
                                    <div class="small text-white-50">{{ $etiqueta }}</div>
                                    <div class="fs-5 fw-bold font-monospace text-{{ $color }}-emphasis mb-2">{{ $dato }}</div>
                                    <button type="button" class="btn btn-outline-{{ $color }} btn-sm rounded-pill px-3" data-copiar="{{ str_replace(' ', '', $dato) }}"
                                            data-bs-toggle="tooltip" data-bs-title="Copiar {{ strtolower($etiqueta) }}">
                                        <i class="fas fa-copy me-1"></i>Copiar
                                    </button>
                                </div>
                            @else
                                <div class="mt-auto d-flex justify-content-center gap-2 fs-2">
                                    @if ($titulo === 'Tarjeta')
                                        <i class="fab fa-cc-visa text-light"></i>
                                        <i class="fab fa-cc-mastercard text-warning"></i>
                                    @else
                                        <i class="fas fa-store text-success"></i>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="text-center mt-4">
                <a href="{{ $wa('Hola, ya realicé mi pago. Les envío la captura de la operación.') }}" target="_blank" rel="noopener"
                   class="btn btn-success rounded-pill px-4 fw-semibold shadow" data-animar="fa-beat">
                    <i class="fas fa-comment-dots me-2"></i>Enviar comprobante de pago por WhatsApp
                </a>
            </div>
        </div>
    </section>

    {{-- =====================================================================
         RESEÑAS DE CLIENTES (se guardan en la tabla "resenas")
    ====================================================================== --}}
    <section id="resenas" class="bg-primary-subtle bg-gradient border-top border-bottom border-primary border-opacity-25 py-5">
        <div class="container py-lg-3">
            <div class="d-flex flex-wrap justify-content-between align-items-end gap-3 mb-5">
                <div>
                    <span class="text-warning fw-bold small text-uppercase"><i class="fas fa-comments me-1"></i>Reseñas</span>
                    <h2 class="fw-bold mt-2 mb-0">Lo que opinan nuestros clientes</h2>
                </div>
                @if ($totalResenas > 0)
                    <div class="d-flex align-items-center gap-3 bg-black bg-opacity-50 border border-warning border-opacity-50 rounded-4 px-4 py-3">
                        <span class="display-6 fw-bold text-warning">{{ number_format($promedio, 1) }}</span>
                        <div class="lh-sm">
                            <div class="text-warning">
                                @for ($e = 1; $e <= 5; $e++)
                                    <i class="{{ $e <= round($promedio) ? 'fas' : 'far' }} fa-star"></i>
                                @endfor
                            </div>
                            <small class="text-white-50">{{ $totalResenas }} reseña(s)</small>
                        </div>
                    </div>
                @endif
            </div>

            <div class="row g-4">
                {{-- Reseñas publicadas --}}
                <div class="col-lg-7">
                    @if ($resenas->isEmpty())
                        <div class="h-100 text-center bg-black bg-opacity-50 border border-warning border-opacity-25 rounded-4 p-5 d-flex flex-column justify-content-center">
                            <i class="far fa-star fa-3x text-warning mb-3 fa-beat-fade"></i>
                            <h5 class="fw-bold">Sé la primera persona en opinar</h5>
                            <p class="text-white-50 mb-0">Cuéntanos cómo fue tu experiencia en nuestra librería usando el formulario.</p>
                        </div>
                    @else
                        <div class="row g-3">
                            @foreach ($resenas as $r)
                                <div class="col-md-6">
                                    <div class="h-100 bg-black bg-opacity-50 border border-primary border-opacity-25 rounded-4 p-4 d-flex flex-column">
                                        <div class="text-warning mb-2">
                                            @for ($e = 1; $e <= 5; $e++)
                                                <i class="{{ $e <= $r->calificacion ? 'fas' : 'far' }} fa-star"></i>
                                            @endfor
                                        </div>
                                        <p class="text-white-50 fst-italic">"{{ $r->comentario }}"</p>
                                        <div class="d-flex align-items-center gap-2 mt-auto">
                                            <span class="bg-warning text-dark fw-bold rounded-circle px-2 py-1 lh-sm">{{ mb_strtoupper(mb_substr($r->nombre, 0, 1)) }}</span>
                                            <div class="lh-sm">
                                                <div class="fw-semibold small">{{ $r->nombre }}</div>
                                                <small class="text-white-50">{{ $r->created_at?->diffForHumans() }}</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- Formulario para dejar una reseña --}}
                <div class="col-lg-5">
                    <div class="bg-black bg-opacity-50 border border-warning border-opacity-50 rounded-4 shadow p-4">
                        <h5 class="fw-bold mb-1"><i class="fas fa-pen-to-square text-warning me-2"></i>Deja tu reseña</h5>
                        <p class="text-white-50 small mb-4">Tu opinión ayuda a otros lectores.</p>

                        @if (session('resena_ok'))
                            <div class="alert alert-success d-flex align-items-center gap-2 py-2">
                                <i class="fas fa-circle-check"></i><span>{{ session('resena_ok') }}</span>
                            </div>
                        @endif

                        <form action="{{ route('resenas.store') }}" method="POST" novalidate>
                            @csrf

                            <div class="mb-3">
                                <label for="nombre" class="form-label fw-bold">Tu nombre</label>
                                <input type="text" name="nombre" id="nombre" maxlength="80" value="{{ old('nombre') }}"
                                       class="form-control bg-black border-warning border-opacity-50 @error('nombre') is-invalid @enderror"
                                       placeholder="Ej: Ana Flores">
                                @error('nombre')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold d-block">Calificación</label>
                                <div class="btn-group" role="group" aria-label="Calificación">
                                    @for ($e = 1; $e <= 5; $e++)
                                        <input type="radio" class="btn-check" name="calificacion" id="estrella{{ $e }}" value="{{ $e }}"
                                               autocomplete="off" {{ old('calificacion', 5) == $e ? 'checked' : '' }}>
                                        <label class="btn btn-outline-warning estrella" for="estrella{{ $e }}"
                                               data-bs-toggle="tooltip" data-bs-title="{{ $e }} de 5">
                                            <i class="fas fa-star"></i>
                                        </label>
                                    @endfor
                                </div>
                                @error('calificacion')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>

                            <div class="mb-3">
                                <label for="comentario" class="form-label fw-bold">Tu comentario</label>
                                <textarea name="comentario" id="comentario" rows="4" maxlength="500"
                                          class="form-control bg-black border-warning border-opacity-50 @error('comentario') is-invalid @enderror"
                                          placeholder="¿Qué te pareció la atención, los productos, los precios?">{{ old('comentario') }}</textarea>
                                @error('comentario')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                <div class="text-end small text-white-50 mt-1"><span id="contador">0</span>/500</div>
                            </div>

                            <button type="submit" class="btn btn-warning w-100 rounded-pill fw-bold shadow">
                                <i class="fas fa-paper-plane me-2"></i>Publicar reseña
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- =====================================================================
         LLAMADO A LA ACCIÓN
    ====================================================================== --}}
    <section class="bg-black py-5">
        <div class="container">
            <div class="bg-warning bg-gradient text-dark rounded-4 shadow-lg p-4 p-lg-5 position-relative overflow-hidden">
                <i class="fas fa-book-open-reader fa-10x opacity-25 position-absolute top-50 end-0 translate-middle-y me-5 d-none d-lg-block"></i>
                <div class="row align-items-center position-relative">
                    <div class="col-lg-8">
                        <h2 class="fw-bold mb-2">Las mejores historias comienzan con un libro.</h2>
                        <p class="mb-lg-0">¿Eres parte del equipo? Ingresa al sistema para gestionar ventas, inventario y clientes.</p>
                    </div>
                    <div class="col-lg-4 text-lg-end">
                        <a href="{{ $miZona }}" class="btn btn-dark btn-lg rounded-pill px-4 fw-semibold shadow">
                            <i class="fas fa-right-to-bracket me-2"></i>Acceso al sistema
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection

@push('scripts')
<script>
        document.addEventListener('DOMContentLoaded', function () {
            // 4) Estrellas de la reseña: al elegir 3, se pintan las 3 primeras
            const estrellas = document.querySelectorAll('input[name="calificacion"]');
            function pintarEstrellas() {
                const elegida = document.querySelector('input[name="calificacion"]:checked');
                const valor = elegida ? parseInt(elegida.value) : 0;
                document.querySelectorAll('label.estrella').forEach(function (label, i) {
                    label.classList.toggle('btn-warning', i < valor);
                    label.classList.toggle('btn-outline-warning', i >= valor);
                });
            }
            estrellas.forEach(function (e) { e.addEventListener('change', pintarEstrellas); });
            pintarEstrellas();

            // 5) Contador de caracteres del comentario
            const comentario = document.getElementById('comentario');
            if (comentario) {
                const contar = function () { document.getElementById('contador').textContent = comentario.value.length; };
                comentario.addEventListener('input', contar);
                contar();
            }
        });
</script>
@endpush
