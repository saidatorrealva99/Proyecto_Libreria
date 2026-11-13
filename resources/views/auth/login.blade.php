{{-- resources/views/auth/login.blade.php  (reemplaza TODO el archivo)
     Login con dos opciones:  "Soy cliente"  |  "Soy personal" --}}
@extends('layouts.app')

@section('content')
@php
    // Opción marcada al abrir: si venía del sistema interno -> personal; si venía de la tienda -> cliente
    $pendiente     = (string) session('url.intended', '');
    $rutaPendiente = parse_url($pendiente, PHP_URL_PATH) ?? '';
    $vieneDeSistema = \Illuminate\Support\Str::startsWith($rutaPendiente, [
        '/home', '/ventas', '/pedidos-web', '/productos', '/categorias', '/autores',
        '/clientes', '/empleados', '/detalle-ventas', '/pagos',
    ]);
    $acceso = old('acceso', request('como', $vieneDeSistema ? 'personal' : 'cliente'));
    $acceso = in_array($acceso, ['cliente', 'personal'], true) ? $acceso : 'cliente';
    $tienda = config('libreria.nombre', 'Librería');

    // Contenido del panel de la izquierda para cada opción
    $paneles = [
        'cliente' => [
            'color'   => 'bg-warning text-dark',
            'titulo'  => '¡Hola, lector!',
            'texto'   => 'Entra a tu cuenta para terminar tu compra y seguir tus pedidos.',
            'puntos'  => [['fa-cart-shopping', 'Compra libros y útiles en línea'], ['fa-qrcode', 'Paga con Yape, Plin o transferencia'], ['fa-file-invoice', 'Descarga tu boleta o factura']],
            'pie'     => 'Tus datos están protegidos.',
            'icono'   => 'fa-bag-shopping',
        ],
        'personal' => [
            'color'   => 'bg-primary text-white',
            'titulo'  => '¡Bienvenido de nuevo!',
            'texto'   => 'Ingresa al sistema para gestionar ventas, pedidos web, inventario y clientes.',
            'puntos'  => [['fa-cash-register', 'Registra boletas y facturas'], ['fa-globe', 'Atiende los pedidos de la tienda web'], ['fa-chart-line', 'Revisa tus indicadores en el panel']],
            'pie'     => 'Acceso exclusivo para el personal.',
            'icono'   => 'fa-book-open',
        ],
    ];
@endphp
<div class="container py-3">
    <div class="row justify-content-center">
        <div class="col-lg-10 col-xl-9">
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="row g-0">

                    {{-- ========== PANEL DE LA IZQUIERDA (cambia según la opción) ========== --}}
                    @foreach ($paneles as $clave => $panel)
                        <div data-panel="{{ $clave }}"
                             class="col-md-5 {{ $panel['color'] }} bg-gradient p-4 p-lg-5 flex-column justify-content-between position-relative overflow-hidden {{ $acceso === $clave ? 'd-flex' : 'd-none' }}">
                            <i class="fas {{ $panel['icono'] }} fa-10x opacity-25 position-absolute bottom-0 end-0 mb-3 me-3"></i>
                            <div class="position-relative">
                                <a href="{{ url('/') }}" class="d-inline-flex align-items-center gap-2 text-reset text-decoration-none mb-5">
                                    <span class="bg-white bg-opacity-25 rounded-3 p-2 lh-1"><i class="fas fa-book-open fa-lg fa-fw"></i></span>
                                    <span class="fw-bold fs-5">{{ $tienda }}</span>
                                </a>
                                <h2 class="fw-bold mb-3">{{ $panel['titulo'] }}</h2>
                                <p class="opacity-75 mb-4">{{ $panel['texto'] }}</p>
                                <ul class="list-unstyled mb-0">
                                    @foreach ($panel['puntos'] as [$icono, $texto])
                                        <li class="d-flex align-items-center gap-3 {{ $loop->last ? '' : 'mb-3' }}">
                                            <span class="bg-white bg-opacity-25 rounded-circle p-2 lh-1"><i class="fas {{ $icono }} fa-fw"></i></span>
                                            {{ $texto }}
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                            <small class="opacity-75 position-relative mt-5"><i class="fas fa-shield-alt me-1"></i>{{ $panel['pie'] }}</small>
                        </div>
                    @endforeach

                    {{-- ========== FORMULARIO ========== --}}
                    <div class="col-md-7 bg-white p-4 p-lg-5">
                        <div class="mb-4">
                            <span class="badge rounded-pill bg-primary-subtle text-primary-emphasis px-3 py-2 mb-3"><i class="fas fa-lock me-1"></i>Acceso seguro</span>
                            <h3 class="fw-bold text-dark mb-1">Iniciar sesión</h3>
                            <p class="text-muted mb-0">Elige cómo quieres entrar y escribe tu correo y contraseña.</p>
                        </div>

                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            {{-- ¿Cómo entras? --}}
                            <div class="row g-2 mb-4">
                                <div class="col-6">
                                    <input type="radio" class="btn-check" name="acceso" id="acceso-cliente" value="cliente" @checked($acceso === 'cliente')>
                                    <label class="btn btn-outline-warning w-100 py-3 rounded-4 text-start d-flex align-items-center gap-3" for="acceso-cliente">
                                        <i class="fas fa-bag-shopping fa-2x fa-fw"></i>
                                        <span>
                                            <span class="d-block fw-bold">Soy cliente</span>
                                            <small class="opacity-75">Comprar en la tienda</small>
                                        </span>
                                    </label>
                                </div>
                                <div class="col-6">
                                    <input type="radio" class="btn-check" name="acceso" id="acceso-personal" value="personal" @checked($acceso === 'personal')>
                                    <label class="btn btn-outline-primary w-100 py-3 rounded-4 text-start d-flex align-items-center gap-3" for="acceso-personal">
                                        <i class="fas fa-id-badge fa-2x fa-fw"></i>
                                        <span>
                                            <span class="d-block fw-bold">Soy personal</span>
                                            <small class="opacity-75">Administrar la librería</small>
                                        </span>
                                    </label>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label fw-bold text-dark">{{ __('Correo electrónico') }}</label>
                                <div class="input-group input-group-lg has-validation">
                                    <span class="input-group-text bg-white text-primary"><i class="fas fa-envelope fa-fw"></i></span>
                                    <input id="email" type="email" name="email" value="{{ old('email') }}"
                                           class="form-control @error('email') is-invalid @enderror"
                                           placeholder="tucorreo@ejemplo.com" required autocomplete="email" autofocus>
                                    @error('email')
                                        <div class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label fw-bold text-dark">{{ __('Contraseña') }}</label>
                                <div class="input-group input-group-lg has-validation">
                                    <span class="input-group-text bg-white text-primary"><i class="fas fa-key fa-fw"></i></span>
                                    <input id="password" type="password" name="password"
                                           class="form-control @error('password') is-invalid @enderror"
                                           placeholder="••••••••" required autocomplete="current-password">
                                    <button class="btn btn-outline-secondary" type="button" data-ver="password" title="Mostrar / ocultar">
                                        <i class="fas fa-eye fa-fw"></i>
                                    </button>
                                    @error('password')
                                        <div class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></div>
                                    @enderror
                                </div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="remember">{{ __('Recordarme') }}</label>
                                </div>
                                @if (Route::has('password.request'))
                                    <a class="small fw-semibold text-decoration-none" href="{{ route('password.request') }}">
                                        {{ __('¿Olvidaste tu contraseña?') }}
                                    </a>
                                @endif
                            </div>

                            <button type="submit" id="btn-ingresar"
                                    class="btn {{ $acceso === 'personal' ? 'btn-primary' : 'btn-warning' }} btn-lg w-100 rounded-pill fw-semibold shadow">
                                <i class="fas fa-sign-in-alt me-2"></i><span>{{ $acceso === 'personal' ? 'Ingresar al sistema' : 'Ingresar a la tienda' }}</span>
                            </button>
                        </form>

                        @if (Route::has('register'))
                            <div data-solo="cliente" class="{{ $acceso === 'cliente' ? '' : 'd-none' }}">
                                <div class="d-flex align-items-center gap-3 my-4">
                                    <hr class="flex-grow-1 m-0"><small class="text-muted">o</small><hr class="flex-grow-1 m-0">
                                </div>
                                <p class="text-center text-muted mb-0">
                                    ¿No tienes una cuenta?
                                    <a href="{{ route('register') }}" class="fw-bold text-decoration-none">Regístrate aquí</a>
                                </p>
                            </div>
                        @endif
                        <p data-solo="personal" class="text-center text-muted small mt-4 mb-0 {{ $acceso === 'personal' ? '' : 'd-none' }}">
                            <i class="fas fa-circle-info me-1"></i>Las cuentas del personal las crea el administrador.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Botón del ojo: mostrar / ocultar contraseña
    document.querySelectorAll('[data-ver]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const input = document.getElementById(this.dataset.ver);
            const icono = this.querySelector('i');
            const oculto = input.type === 'password';
            input.type = oculto ? 'text' : 'password';
            icono.className = 'fas fa-fw ' + (oculto ? 'fa-eye-slash' : 'fa-eye');
        });
    });

    // Cambiar entre "Soy cliente" y "Soy personal"
    document.querySelectorAll('input[name="acceso"]').forEach(function (radio) {
        radio.addEventListener('change', function () {
            const opcion = this.value;

            document.querySelectorAll('[data-panel]').forEach(function (panel) {
                const visible = panel.dataset.panel === opcion;
                panel.classList.toggle('d-flex', visible);
                panel.classList.toggle('d-none', !visible);
            });
            document.querySelectorAll('[data-solo]').forEach(function (bloque) {
                bloque.classList.toggle('d-none', bloque.dataset.solo !== opcion);
            });

            const boton = document.getElementById('btn-ingresar');
            boton.classList.toggle('btn-primary', opcion === 'personal');
            boton.classList.toggle('btn-warning', opcion === 'cliente');
            boton.querySelector('span').textContent = opcion === 'personal' ? 'Ingresar al sistema' : 'Ingresar a la tienda';
        });
    });
</script>
@endsection