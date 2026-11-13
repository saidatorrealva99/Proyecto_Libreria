{{-- resources/views/layouts/tienda.blade.php  (archivo NUEVO)
     Plantilla de la TIENDA pública: menú, pie de página y scripts.
     La usan: welcome, catalogo/index, catalogo/show (y luego el carrito).
     Los datos ($datos, $wa, $barraSuperior...) llegan desde app/View/Composers/TiendaComposer.php --}}
<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="dark">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('titulo', $datos['eslogan']) | {{ $datos['nombre'] }}</title>

    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito:400,600,700,800" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.8/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="bg-black text-white">

    {{-- =====================================================================
         MENÚ
    ====================================================================== --}}
    <nav class="navbar navbar-expand-lg bg-black border-bottom border-primary border-opacity-25 sticky-top py-2">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2" href="{{ url('/') }}">
                <span class="bg-warning bg-gradient text-dark rounded-3 shadow p-2 lh-1">
                    <i class="fas fa-book-open-reader fa-lg fa-fw"></i>
                </span>
                <span class="lh-1">
                    <span class="fw-bold d-block">{{ $datos['nombre'] }}</span>
                    <small class="text-white-50">{{ $datos['eslogan'] }}</small>
                </span>
            </a>

            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#menuPublico" aria-controls="menuPublico" aria-expanded="false" aria-label="Menú">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="menuPublico">
                <ul class="navbar-nav mx-auto gap-lg-2 fw-semibold">
                    @php
                        $menuTienda = [
                            ['Inicio',    url('/'),                 request()->routeIs('inicio')],
                            ['Catálogo',  route('catalogo'),        request()->routeIs('catalogo', 'producto.ver')],
                            ['Servicios', url('/') . '#servicios',  false],
                            ['Reseñas',   url('/') . '#resenas',    false],
                            ['Contacto',  '#contacto',              false],
                        ];
                    @endphp
                    @foreach ($menuTienda as [$texto, $enlace, $activo])
                        <li class="nav-item">
                            <a class="nav-link {{ $activo ? 'text-warning border-bottom border-2 border-warning' : '' }}" href="{{ $enlace }}">{{ $texto }}</a>
                        </li>
                    @endforeach
                </ul>
                <div class="d-flex align-items-center gap-2 mt-3 mt-lg-0">
                    {{-- Carrito con contador --}}
                    <a href="{{ route('carrito') }}" class="btn btn-outline-warning rounded-pill position-relative px-3 me-1" data-animar="fa-bounce"
                       data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Ver mi carrito">
                        <i class="fas fa-cart-shopping"></i>
                        <span class="d-lg-none ms-1">Carrito</span>
                        @if ($carritoCantidad > 0)
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger border border-dark">
                                {{ $carritoCantidad }}<span class="visually-hidden"> productos en el carrito</span>
                            </span>
                        @endif
                    </a>
                    @auth
                        <a href="{{ $miZona }}" class="btn btn-warning rounded-pill px-4 fw-semibold shadow">
                            <i class="fas {{ Auth::user()->esPersonal() ? 'fa-gauge-high' : 'fa-user' }} me-2"></i>{{ $miZonaTexto }}
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-outline-light rounded-pill px-4">
                            <i class="fas fa-right-to-bracket me-2"></i>Ingresar
                        </a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="btn btn-warning rounded-pill px-4 fw-semibold shadow">Crear cuenta</a>
                        @endif
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    {{-- Contenido de cada página --}}
    @yield('content')

    {{-- =====================================================================
         PIE DE PÁGINA / CONTACTO
    ====================================================================== --}}
    <footer id="contacto" class="bg-black border-top border-primary border-opacity-25 text-white-50 pt-5 pb-4">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <span class="bg-warning text-dark rounded-3 p-2 lh-1"><i class="fas fa-book-open-reader fa-lg fa-fw"></i></span>
                        <span class="fw-bold text-white fs-5">{{ $datos['nombre'] }}</span>
                    </div>
                    <p class="small">Libros, textos académicos, útiles y papelería para lectores, estudiantes y colegios.</p>
                    <ul class="list-unstyled small mb-0">
                        <li class="mb-1"><i class="fas fa-user-tie text-warning me-2"></i>Propietaria: <span class="text-white fw-semibold">{{ $datos['duena'] }}</span></li>
                        <li class="mb-1"><i class="fas fa-id-card text-warning me-2"></i>RUC: {{ $datos['ruc'] }}</li>
                        <li><i class="fas fa-calendar-check text-warning me-2"></i>Desde {{ $datos['fundacion'] }}</li>
                    </ul>
                </div>
                <div class="col-6 col-lg-2">
                    <h6 class="text-white fw-bold mb-3">Enlaces</h6>
                    <ul class="list-unstyled small">
                        <li class="mb-2"><a href="{{ url('/') }}" class="link-warning link-opacity-75 link-opacity-100-hover text-decoration-none">Inicio</a></li>
                        <li class="mb-2"><a href="{{ route('catalogo') }}" class="link-warning link-opacity-75 link-opacity-100-hover text-decoration-none">Catálogo</a></li>
                        <li><a href="{{ url('/') }}#servicios" class="link-warning link-opacity-75 link-opacity-100-hover text-decoration-none">Servicios</a></li>
                    </ul>
                </div>
                <div class="col-6 col-lg-3">
                    <h6 class="text-white fw-bold mb-3">Acceso</h6>
                    <ul class="list-unstyled small">
                        <li class="mb-2"><a href="{{ route('login') }}" class="link-warning link-opacity-75 link-opacity-100-hover text-decoration-none"><i class="fas fa-right-to-bracket me-2"></i>Iniciar sesión</a></li>
                        @if (Route::has('register'))
                            <li><a href="{{ route('register') }}" class="link-warning link-opacity-75 link-opacity-100-hover text-decoration-none"><i class="fas fa-user-plus me-2"></i>Crear cuenta</a></li>
                        @endif
                    </ul>
                </div>
                <div class="col-lg-3">
                    <h6 class="text-white fw-bold mb-3">Contacto</h6>
                    {{-- Los datos se cambian arriba, en el arreglo $datos --}}
                    <ul class="list-unstyled small">
                        <li class="mb-2"><i class="fas fa-location-dot text-warning me-2"></i>{{ $datos['direccion'] }}</li>
                        <li class="mb-2"><i class="fas fa-phone text-warning me-2"></i>{{ $datos['telefono'] }}</li>
                        <li class="mb-2"><a href="mailto:{{ $datos['correo'] }}" class="link-light link-opacity-75 link-opacity-100-hover text-decoration-none"><i class="fas fa-envelope text-warning me-2"></i>{{ $datos['correo'] }}</a></li>
                        <li class="mb-3"><i class="far fa-clock text-warning me-2"></i>{{ $datos['horario'] }}</li>
                    </ul>
                    <div class="d-flex flex-wrap gap-2">
                        <a href="{{ $wa('Hola, quisiera información sobre sus productos.') }}" target="_blank" rel="noopener" class="btn btn-success btn-sm rounded-pill px-3" data-animar="fa-beat">
                            <i class="fas fa-comment-dots me-1"></i>WhatsApp
                        </a>
                        <a href="{{ $mapa }}" target="_blank" rel="noopener" class="btn btn-outline-warning btn-sm rounded-pill px-3" data-animar="fa-bounce">
                            <i class="fas fa-map-location-dot me-1"></i>Cómo llegar
                        </a>
                    </div>
                </div>
            </div>

            {{-- Mini barra de útiles --}}
            <div class="d-flex flex-wrap justify-content-center gap-4 py-4 opacity-50">
                @foreach (array_merge($barraSuperior, $barraInferior) as $util)
                    <i class="fas {{ $util[0] }} text-{{ $util[1] }}"></i>
                @endforeach
            </div>

            <div class="border-top border-secondary border-opacity-25 pt-4 d-flex flex-wrap justify-content-between gap-2 small">
                <span>&copy; {{ date('Y') }} {{ $datos['nombre'] }} · {{ $datos['duena'] }}. Todos los derechos reservados.</span>
                <span><i class="fas fa-code text-warning me-1"></i>Desarrollado por {{ $datos['duena'] }} · Laravel y MySQL · SENATI</span>
                <a href="#" class="link-warning text-decoration-none">Volver arriba <i class="fas fa-arrow-up ms-1"></i></a>
            </div>
        </div>
    </footer>

    {{-- ===================== NOTIFICACIONES DEL CARRITO ===================== --}}
    <div class="toast-container position-fixed bottom-0 end-0 p-3">
        @foreach (['carrito_ok' => ['success', 'fa-circle-check'], 'carrito_info' => ['info', 'fa-circle-info'], 'carrito_error' => ['warning', 'fa-triangle-exclamation']] as $clave => [$colorAviso, $iconoAviso])
            @if (session($clave))
                <div class="toast align-items-center bg-{{ $colorAviso }}-subtle border border-{{ $colorAviso }} rounded-4 shadow-lg" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="5000">
                    <div class="toast-body d-flex align-items-center gap-3 p-3">
                        <i class="fas {{ $iconoAviso }} fa-xl text-{{ $colorAviso }}"></i>
                        <div class="flex-grow-1">
                            <div class="text-{{ $colorAviso }}-emphasis">{{ session($clave) }}</div>
                            @if ($clave === 'carrito_ok')
                                <a href="{{ route('carrito') }}" class="btn btn-warning btn-sm rounded-pill fw-semibold mt-2 px-3">
                                    <i class="fas fa-cart-shopping me-1"></i>Ver carrito ({{ $carritoCantidad }})
                                </a>
                            @endif
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Cerrar"></button>
                    </div>
                </div>
            @endif
        @endforeach
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.8/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            // 1) Textos de ayuda (tooltips): muestran el destino al pasar el mouse
            document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(function (el) {
                new bootstrap.Tooltip(el);
            });

            // 2) Animación al pasar el mouse (clase de FontAwesome indicada en data-animar)
            document.querySelectorAll('[data-animar]').forEach(function (el) {
                const iconos = el.querySelectorAll('i.fas, i.far, i.fab');
                el.addEventListener('mouseenter', function () {
                    iconos.forEach(function (i) { i.classList.add(el.dataset.animar); });
                });
                el.addEventListener('mouseleave', function () {
                    iconos.forEach(function (i) { i.classList.remove(el.dataset.animar); });
                });
            });

            // Mostrar las notificaciones del carrito
            document.querySelectorAll('.toast').forEach(function (t) {
                new bootstrap.Toast(t).show();
            });

            // Botones − / + de cantidad (página de producto y carrito)
            document.querySelectorAll('[data-cantidad]').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    const input = document.getElementById(btn.dataset.objetivo);
                    const nuevo = Math.min(parseInt(input.max || 99), Math.max(parseInt(input.min || 1), parseInt(input.value || 1) + parseInt(btn.dataset.cantidad)));
                    input.value = nuevo;
                    if (input.dataset.autoenviar !== undefined) {
                        input.form.submit();
                    }
                });
            });

            // 3) Botones "Copiar" (números de Yape, Plin, cuenta, enlace del producto)
            document.querySelectorAll('[data-copiar]').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    navigator.clipboard.writeText(btn.dataset.copiar).then(function () {
                        const original = btn.innerHTML;
                        btn.innerHTML = '<i class="fas fa-check me-1"></i>¡Copiado!';
                        setTimeout(function () { btn.innerHTML = original; }, 1800);
                    });
                });
            });
        });
    </script>

    {{-- Scripts propios de cada página --}}
    @stack('scripts')
</body>

</html>
