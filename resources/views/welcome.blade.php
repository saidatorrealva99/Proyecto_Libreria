{{-- resources/views/welcome.blade.php  (reemplaza TODO el archivo) --}}
{{-- Portada pública de la librería. Solo Bootstrap 5 + FontAwesome. --}}
<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Librería') }} | Libros, útiles y más</title>

    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito:400,600,700,800" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.8/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="bg-white">

    {{-- =====================================================================
         MENÚ
    ====================================================================== --}}
    <nav class="navbar navbar-expand-lg bg-white border-bottom shadow-sm sticky-top py-3">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2" href="#inicio">
                <span class="bg-primary bg-gradient text-white rounded-3 shadow-sm p-2 lh-1">
                    <i class="fas fa-book-open fa-lg fa-fw"></i>
                </span>
                <span class="lh-1">
                    <span class="fw-bold text-dark d-block">{{ config('app.name', 'Librería') }}</span>
                    <small class="text-muted">Libros, útiles y mucho más</small>
                </span>
            </a>

            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#menuPublico" aria-controls="menuPublico" aria-expanded="false" aria-label="Menú">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="menuPublico">
                <ul class="navbar-nav mx-auto fw-semibold">
                    <li class="nav-item"><a class="nav-link text-primary" href="#inicio">Inicio</a></li>
                    <li class="nav-item"><a class="nav-link" href="#nosotros">Nosotros</a></li>
                    <li class="nav-item"><a class="nav-link" href="#catalogo">Catálogo</a></li>
                    <li class="nav-item"><a class="nav-link" href="#servicios">Servicios</a></li>
                    <li class="nav-item"><a class="nav-link" href="#contacto">Contacto</a></li>
                </ul>
                <div class="d-flex gap-2 mt-3 mt-lg-0">
                    @auth
                        <a href="{{ route('home') }}" class="btn btn-primary rounded-pill px-4 fw-semibold shadow-sm">
                            <i class="fas fa-tachometer-alt me-2"></i>Ir al panel
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-outline-secondary rounded-pill px-4 fw-semibold">
                            <i class="far fa-user me-2"></i>Iniciar sesión
                        </a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="btn btn-primary rounded-pill px-4 fw-semibold shadow-sm">
                                Crear cuenta <i class="fas fa-arrow-right ms-2"></i>
                            </a>
                        @endif
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    {{-- =====================================================================
         PORTADA (HERO)
    ====================================================================== --}}
    <section id="inicio" class="bg-primary-subtle bg-gradient py-5">
        <div class="container py-lg-4">
            <div class="row align-items-center g-5">
                <div class="col-lg-6">
                    <span class="badge rounded-pill bg-white text-primary border border-primary-subtle shadow-sm px-3 py-2 mb-3">
                        <span class="spinner-grow spinner-grow-sm text-success me-1" role="status"></span>
                        TU ALIADO EN LA LECTURA Y EL ESTUDIO
                    </span>
                    <h1 class="display-4 fw-bold text-dark lh-sm mb-3">
                        Conectamos a los lectores con los libros que
                        <span class="text-primary">transforman vidas.</span>
                    </h1>
                    <p class="lead text-secondary mb-4">
                        Novelas, literatura peruana, textos universitarios, útiles escolares y papelería.
                        Todo lo que necesitas para leer, estudiar y crear, en un solo lugar.
                    </p>
                    <div class="d-flex flex-wrap gap-3">
                        <a href="#catalogo" class="btn btn-primary btn-lg rounded-pill px-4 fw-semibold shadow">
                            Ver catálogo <i class="fas fa-arrow-right ms-2"></i>
                        </a>
                        <a href="#servicios" class="btn btn-light btn-lg rounded-pill px-4 fw-semibold border shadow-sm">
                            <i class="fas fa-school me-2 text-primary"></i>Pedidos para colegios
                        </a>
                    </div>
                </div>

                {{-- Carrusel de la portada --}}
                <div class="col-lg-6">
                    <div id="carruselPortada" class="carousel slide carousel-fade shadow-lg rounded-4 overflow-hidden" data-bs-ride="carousel" data-bs-interval="4000">
                        <div class="carousel-indicators mb-2">
                            <button type="button" data-bs-target="#carruselPortada" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Literatura"></button>
                            <button type="button" data-bs-target="#carruselPortada" data-bs-slide-to="1" aria-label="Universitarios"></button>
                            <button type="button" data-bs-target="#carruselPortada" data-bs-slide-to="2" aria-label="Útiles"></button>
                        </div>
                        <div class="carousel-inner">
                            @php
                                $slides = [
                                    ['bg-dark',    'fa-feather-alt',  'text-warning', 'Literatura peruana y latinoamericana', 'Vallejo, Arguedas, Vargas Llosa, García Márquez y muchos más.'],
                                    ['bg-primary', 'fa-laptop-code',  'text-info',    'Textos universitarios',                'Informática, matemáticas, historia y ciencias para tu carrera.'],
                                    ['bg-success', 'fa-pencil-ruler', 'text-white',   'Útiles escolares y papelería',         'Cuadernos, folders y todo para el inicio de clases.'],
                                ];
                            @endphp
                            @foreach ($slides as $s)
                                @php [$fondo, $icono, $colorIcono, $titulo, $texto] = $s; @endphp
                                <div class="carousel-item {{ $loop->first ? 'active' : '' }} {{ $fondo }} bg-gradient text-white">
                                    <div class="text-center px-4 px-md-5 pt-5 pb-5">
                                        <div class="bg-white bg-opacity-10 rounded-circle d-inline-block p-4 mb-4 lh-1 shadow">
                                            <i class="fas {{ $icono }} fa-4x fa-fw {{ $colorIcono }}"></i>
                                        </div>
                                        <h3 class="fw-bold mb-2">{{ $titulo }}</h3>
                                        <p class="opacity-75 mb-4">{{ $texto }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- =====================================================================
         CIFRAS (datos reales de la base de datos)
    ====================================================================== --}}
    <section class="bg-white border-bottom">
        <div class="container py-4">
            <div class="row g-3 text-center">
                @php
                    $cifras = [
                        [$totalProductos,  'Productos en catálogo', 'fa-book',         'primary'],
                        [$totalAutores,    'Autores',               'fa-feather-alt',  'success'],
                        [$totalCategorias, 'Categorías',            'fa-tags',         'warning'],
                        [$totalClientes,   'Clientes atendidos',    'fa-users',        'info'],
                    ];
                @endphp
                @foreach ($cifras as $c)
                    @php [$numero, $texto, $icono, $color] = $c; @endphp
                    <div class="col-6 col-md-3">
                        <div class="d-flex align-items-center justify-content-center gap-3">
                            <span class="bg-{{ $color }} bg-opacity-10 text-{{ $color }} rounded-circle p-3 lh-1">
                                <i class="fas {{ $icono }} fa-lg fa-fw"></i>
                            </span>
                            <div class="text-start">
                                <div class="fs-3 fw-bold text-dark lh-1">{{ $numero }}+</div>
                                <small class="text-muted">{{ $texto }}</small>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- =====================================================================
         NOSOTROS
    ====================================================================== --}}
    <section id="nosotros" class="py-5">
        <div class="container py-lg-4">
            <div class="text-center mb-5">
                <span class="text-primary fw-bold small text-uppercase">Quiénes somos</span>
                <h2 class="fw-bold text-dark mt-2">Un buen libro puede cambiarlo todo</h2>
                <p class="text-secondary col-lg-7 mx-auto">
                    Somos una librería que acerca la lectura y el estudio a estudiantes, docentes, familias y colegios,
                    con atención personalizada y precios justos.
                </p>
            </div>
            <div class="row g-4">
                @php
                    $pilares = [
                        ['fa-book-reader',   'Venta de libros',       'Novelas, poesía, cuentos y literatura peruana para todas las edades.'],
                        ['fa-graduation-cap','Textos académicos',     'Libros universitarios de informática, matemáticas, historia y más.'],
                        ['fa-pencil-alt',    'Útiles escolares',      'Cuadernos, folders, papelería y todo para el aula.'],
                        ['fa-school',        'Pedidos para colegios', 'Atendemos compras por volumen con factura para instituciones.'],
                    ];
                @endphp
                @foreach ($pilares as $p)
                    @php [$icono, $titulo, $texto] = $p; @endphp
                    <div class="col-md-6 col-lg-3">
                        <div class="card h-100 border border-light-subtle shadow-sm rounded-4 p-2">
                            <div class="card-body">
                                <div class="bg-primary bg-opacity-10 text-primary rounded-3 d-inline-block p-3 mb-3 lh-1">
                                    <i class="fas {{ $icono }} fa-lg fa-fw"></i>
                                </div>
                                <h5 class="fw-bold text-dark">{{ $titulo }}</h5>
                                <p class="text-secondary small mb-0">{{ $texto }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- =====================================================================
         CATÁLOGO (últimos productos de la base de datos)
    ====================================================================== --}}
    <section id="catalogo" class="bg-light py-5">
        <div class="container py-lg-4">
            <div class="d-flex flex-wrap justify-content-between align-items-end gap-3 mb-4">
                <div>
                    <span class="text-primary fw-bold small text-uppercase"><i class="fas fa-bolt text-warning me-1"></i>Novedades</span>
                    <h2 class="fw-bold text-dark mt-2 mb-0">Lo último en nuestra librería</h2>
                </div>
                <div class="d-flex flex-wrap gap-2">
                    @foreach ($categorias as $cat)
                        <span class="badge rounded-pill bg-white text-dark border px-3 py-2 fw-semibold">
                            {{ $cat->nombre }} <span class="text-primary">({{ $cat->productos_count }})</span>
                        </span>
                    @endforeach
                </div>
            </div>

            <div class="row g-4">
                @forelse ($novedades as $p)
                    @php $esLibro = ! is_null($p->autor_id); @endphp
                    <div class="col-sm-6 col-lg-4">
                        <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden">
                            <div class="{{ $esLibro ? 'bg-primary' : 'bg-success' }} bg-gradient text-white text-center py-4 position-relative">
                                <span class="badge bg-white {{ $esLibro ? 'text-primary' : 'text-success' }} position-absolute top-0 start-0 m-3">
                                    {{ $p->categoria?->nombre }}
                                </span>
                                @if ($p->stock > 0)
                                    <span class="badge bg-warning text-dark position-absolute top-0 end-0 m-3">Disponible</span>
                                @else
                                    <span class="badge bg-danger position-absolute top-0 end-0 m-3">Agotado</span>
                                @endif
                                <i class="fas {{ $esLibro ? 'fa-book-open' : 'fa-pencil-ruler' }} fa-4x mt-2"></i>
                            </div>
                            <div class="card-body p-4">
                                <h5 class="fw-bold text-dark mb-1">{{ $p->nombre }}</h5>
                                <p class="text-muted small mb-3">
                                    @if ($esLibro)
                                        <i class="fas fa-feather-alt me-1"></i>{{ $p->autor?->nombres }} {{ $p->autor?->apellidos }}
                                        · {{ $p->marca_editorial }}
                                    @else
                                        <i class="fas fa-tag me-1"></i>{{ $p->marca_editorial }}
                                    @endif
                                </p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="fs-4 fw-bold text-primary">S/ {{ number_format($p->precio_venta, 2) }}</span>
                                    <small class="text-muted">{{ $p->descripcion }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center text-muted py-5">Pronto tendremos novedades para ti.</div>
                @endforelse
            </div>
        </div>
    </section>

    {{-- =====================================================================
         SERVICIOS: DOS CAMINOS
    ====================================================================== --}}
    <section id="servicios" class="py-5">
        <div class="container py-lg-4">
            <div class="text-center mb-5">
                <span class="text-primary fw-bold small text-uppercase">Una librería, dos caminos</span>
                <h2 class="fw-bold text-dark mt-2">Creemos en el poder de la lectura</h2>
                <p class="text-secondary">Ya sea que compres para ti o para toda una institución, te atendemos.</p>
            </div>
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="card h-100 border border-light-subtle shadow-sm rounded-4 p-3">
                        <div class="card-body">
                            <div class="bg-primary bg-opacity-10 text-primary rounded-3 d-inline-block p-3 mb-3 lh-1">
                                <i class="fas fa-user-graduate fa-lg fa-fw"></i>
                            </div>
                            <h4 class="fw-bold text-dark">Para lectores y estudiantes</h4>
                            <p class="text-secondary">Encuentra tu próxima lectura o el texto que pide tu curso. Te emitimos boleta al instante.</p>
                            <ul class="list-unstyled text-secondary small mb-4">
                                <li class="mb-1"><i class="fas fa-check-circle text-success me-2"></i>Novelas, poesía y cuentos</li>
                                <li class="mb-1"><i class="fas fa-check-circle text-success me-2"></i>Textos universitarios</li>
                                <li><i class="fas fa-check-circle text-success me-2"></i>Útiles para el día a día</li>
                            </ul>
                            <a href="#catalogo" class="btn btn-primary rounded-pill px-4 fw-semibold">Ver catálogo <i class="fas fa-arrow-right ms-2"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card h-100 border border-light-subtle shadow-sm rounded-4 p-3">
                        <div class="card-body">
                            <div class="bg-success bg-opacity-10 text-success rounded-3 d-inline-block p-3 mb-3 lh-1">
                                <i class="fas fa-school fa-lg fa-fw"></i>
                            </div>
                            <h4 class="fw-bold text-dark">Para colegios e instituciones</h4>
                            <p class="text-secondary">Compras por volumen para el inicio de clases, con factura electrónica y precios especiales.</p>
                            <ul class="list-unstyled text-secondary small mb-4">
                                <li class="mb-1"><i class="fas fa-check-circle text-success me-2"></i>Atención con RUC y factura</li>
                                <li class="mb-1"><i class="fas fa-check-circle text-success me-2"></i>Descuentos por cantidad</li>
                                <li><i class="fas fa-check-circle text-success me-2"></i>Pago por transferencia</li>
                            </ul>
                            <a href="#contacto" class="btn btn-outline-success rounded-pill px-4 fw-semibold">Solicitar cotización <i class="fas fa-arrow-right ms-2"></i></a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Métodos de pago --}}
            <div class="bg-light rounded-4 p-4 mt-5 d-flex flex-wrap align-items-center justify-content-center gap-4">
                <span class="fw-bold text-dark"><i class="fas fa-shield-alt text-primary me-2"></i>Paga como prefieras:</span>
                <span class="badge rounded-pill bg-success px-3 py-2"><i class="fas fa-money-bill-wave me-1"></i>Efectivo</span>
                <span class="badge rounded-pill bg-primary px-3 py-2"><i class="fas fa-credit-card me-1"></i>Tarjeta</span>
                <span class="badge rounded-pill bg-info text-dark px-3 py-2"><i class="fas fa-mobile-alt me-1"></i>Yape</span>
                <span class="badge rounded-pill bg-info text-dark px-3 py-2"><i class="fas fa-mobile-alt me-1"></i>Plin</span>
                <span class="badge rounded-pill bg-dark px-3 py-2"><i class="fas fa-university me-1"></i>Transferencia</span>
            </div>
        </div>
    </section>

    {{-- =====================================================================
         LLAMADO A LA ACCIÓN
    ====================================================================== --}}
    <section class="pb-5">
        <div class="container">
            <div class="bg-primary bg-gradient text-white rounded-4 shadow-lg p-4 p-lg-5 position-relative overflow-hidden">
                <i class="fas fa-book-open fa-10x opacity-25 position-absolute top-50 end-0 translate-middle-y me-5 d-none d-lg-block"></i>
                <div class="row align-items-center position-relative">
                    <div class="col-lg-8">
                        <h2 class="fw-bold mb-2">Las mejores historias comienzan con un libro.</h2>
                        <p class="opacity-75 mb-lg-0">¿Eres parte del equipo? Ingresa al sistema para gestionar ventas, inventario y clientes.</p>
                    </div>
                    <div class="col-lg-4 text-lg-end">
                        <a href="{{ Auth::check() ? route('home') : route('login') }}" class="btn btn-light btn-lg rounded-pill px-4 fw-semibold text-primary shadow">
                            <i class="fas fa-sign-in-alt me-2"></i>Acceso al sistema
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- =====================================================================
         PIE DE PÁGINA / CONTACTO
    ====================================================================== --}}
    <footer id="contacto" class="bg-dark text-white-50 pt-5 pb-4">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <span class="bg-primary text-white rounded-3 p-2 lh-1"><i class="fas fa-book-open fa-lg fa-fw"></i></span>
                        <span class="fw-bold text-white fs-5">{{ config('app.name', 'Librería') }}</span>
                    </div>
                    <p class="small">Una librería pensada para lectores, estudiantes y colegios. Libros, textos académicos, útiles y papelería.</p>
                </div>
                <div class="col-6 col-lg-2">
                    <h6 class="text-white fw-bold mb-3">Enlaces rápidos</h6>
                    <ul class="list-unstyled small">
                        <li class="mb-2"><a href="#inicio" class="link-light link-opacity-50 link-opacity-100-hover text-decoration-none">Inicio</a></li>
                        <li class="mb-2"><a href="#nosotros" class="link-light link-opacity-50 link-opacity-100-hover text-decoration-none">Nosotros</a></li>
                        <li class="mb-2"><a href="#catalogo" class="link-light link-opacity-50 link-opacity-100-hover text-decoration-none">Catálogo</a></li>
                        <li><a href="#servicios" class="link-light link-opacity-50 link-opacity-100-hover text-decoration-none">Servicios</a></li>
                    </ul>
                </div>
                <div class="col-6 col-lg-3">
                    <h6 class="text-white fw-bold mb-3">Acceso</h6>
                    <ul class="list-unstyled small">
                        <li class="mb-2"><a href="{{ route('login') }}" class="link-light link-opacity-50 link-opacity-100-hover text-decoration-none"><i class="fas fa-sign-in-alt me-2"></i>Iniciar sesión</a></li>
                        @if (Route::has('register'))
                            <li><a href="{{ route('register') }}" class="link-light link-opacity-50 link-opacity-100-hover text-decoration-none"><i class="fas fa-user-plus me-2"></i>Crear cuenta</a></li>
                        @endif
                    </ul>
                </div>
                <div class="col-lg-3">
                    <h6 class="text-white fw-bold mb-3">Contacto</h6>
                    {{-- Cambia estos datos por los de tu librería --}}
                    <ul class="list-unstyled small">
                        <li class="mb-2"><i class="fas fa-map-marker-alt text-primary me-2"></i>Av. Principal 123, Lima</li>
                        <li class="mb-2"><i class="fas fa-phone-alt text-primary me-2"></i>(01) 000-0000</li>
                        <li class="mb-2"><i class="fas fa-envelope text-primary me-2"></i>contacto@libreria.pe</li>
                        <li><i class="far fa-clock text-primary me-2"></i>Lun - Sáb: 9:00 a.m. - 8:00 p.m.</li>
                    </ul>
                </div>
            </div>
            <hr class="border-secondary my-4">
            <div class="d-flex flex-wrap justify-content-between gap-2 small">
                <span>&copy; {{ date('Y') }} {{ config('app.name', 'Librería') }}. Todos los derechos reservados.</span>
                <a href="#inicio" class="link-light link-opacity-75 text-decoration-none">Volver al inicio <i class="fas fa-arrow-up ms-1"></i></a>
            </div>
        </div>
    </footer>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.8/js/bootstrap.bundle.min.js"></script>
</body>

</html>
