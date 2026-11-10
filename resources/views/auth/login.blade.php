{{-- resources/views/auth/login.blade.php  (reemplaza TODO el archivo) --}}
@extends('layouts.app')

@section('content')
<div class="container py-3">
    <div class="row justify-content-center">
        <div class="col-lg-10 col-xl-9">
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="row g-0">

                    {{-- ========== PANEL DE MARCA ========== --}}
                    <div class="col-md-5 bg-primary bg-gradient text-white p-4 p-lg-5 d-flex flex-column justify-content-between position-relative overflow-hidden">
                        <i class="fas fa-book-open fa-10x opacity-25 position-absolute bottom-0 end-0 mb-3 me-3"></i>
                        <div class="position-relative">
                            <a href="{{ url('/') }}" class="d-inline-flex align-items-center gap-2 text-white text-decoration-none mb-5">
                                <span class="bg-white bg-opacity-25 rounded-3 p-2 lh-1"><i class="fas fa-book-open fa-lg fa-fw"></i></span>
                                <span class="fw-bold fs-5">{{ config('app.name', 'Librería') }}</span>
                            </a>
                            <h2 class="fw-bold mb-3">¡Bienvenido de nuevo!</h2>
                            <p class="opacity-75 mb-4">Ingresa al sistema para gestionar ventas, inventario, clientes y mucho más.</p>
                            <ul class="list-unstyled mb-0">
                                <li class="d-flex align-items-center gap-3 mb-3">
                                    <span class="bg-white bg-opacity-25 rounded-circle p-2 lh-1"><i class="fas fa-cash-register fa-fw"></i></span>
                                    Registra boletas y facturas
                                </li>
                                <li class="d-flex align-items-center gap-3 mb-3">
                                    <span class="bg-white bg-opacity-25 rounded-circle p-2 lh-1"><i class="fas fa-boxes fa-fw"></i></span>
                                    Controla el stock en tiempo real
                                </li>
                                <li class="d-flex align-items-center gap-3">
                                    <span class="bg-white bg-opacity-25 rounded-circle p-2 lh-1"><i class="fas fa-chart-line fa-fw"></i></span>
                                    Revisa tus indicadores en el panel
                                </li>
                            </ul>
                        </div>
                        <small class="opacity-75 position-relative mt-5"><i class="fas fa-shield-alt me-1"></i>Acceso exclusivo para el personal.</small>
                    </div>

                    {{-- ========== FORMULARIO ========== --}}
                    <div class="col-md-7 bg-white p-4 p-lg-5">
                        <div class="mb-4">
                            <span class="badge rounded-pill bg-primary-subtle text-primary-emphasis px-3 py-2 mb-3"><i class="fas fa-lock me-1"></i>Acceso seguro</span>
                            <h3 class="fw-bold text-dark mb-1">Iniciar sesión</h3>
                            <p class="text-muted mb-0">Escribe tu correo y contraseña para continuar.</p>
                        </div>

                        <form method="POST" action="{{ route('login') }}">
                            @csrf

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

                            <button type="submit" class="btn btn-primary btn-lg w-100 rounded-pill fw-semibold shadow">
                                <i class="fas fa-sign-in-alt me-2"></i>{{ __('Ingresar') }}
                            </button>
                        </form>

                        @if (Route::has('register'))
                            <div class="d-flex align-items-center gap-3 my-4">
                                <hr class="flex-grow-1 m-0"><small class="text-muted">o</small><hr class="flex-grow-1 m-0">
                            </div>
                            <p class="text-center text-muted mb-0">
                                ¿No tienes una cuenta?
                                <a href="{{ route('register') }}" class="fw-bold text-decoration-none">Regístrate aquí</a>
                            </p>
                        @endif
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
</script>
@endsection
