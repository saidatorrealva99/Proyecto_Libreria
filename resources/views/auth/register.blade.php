{{-- resources/views/auth/register.blade.php  (reemplaza TODO el archivo)
     Registro SOLO para clientes (las cuentas del personal las crea el administrador) --}}
@extends('layouts.app')

@section('content')
<div class="container py-3">
    <div class="row justify-content-center">
        <div class="col-lg-10 col-xl-9">
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="row g-0">

                    {{-- ========== PANEL DE MARCA ========== --}}
                    <div class="col-md-5 bg-warning bg-gradient text-dark p-4 p-lg-5 d-flex flex-column justify-content-between position-relative overflow-hidden">
                        <i class="fas fa-bag-shopping fa-10x opacity-25 position-absolute bottom-0 end-0 mb-3 me-3"></i>
                        <div class="position-relative">
                            <a href="{{ url('/') }}" class="d-inline-flex align-items-center gap-2 text-dark text-decoration-none mb-5">
                                <span class="bg-white bg-opacity-50 rounded-3 p-2 lh-1"><i class="fas fa-book-open fa-lg fa-fw"></i></span>
                                <span class="fw-bold fs-5">{{ config('libreria.nombre', 'Librería') }}</span>
                            </a>
                            <h2 class="fw-bold mb-3">Crea tu cuenta</h2>
                            <p class="opacity-75 mb-4">Regístrate gratis para comprar libros y útiles en línea y seguir tus pedidos.</p>
                            <div class="d-flex flex-column gap-3">
                                <div class="bg-white bg-opacity-50 rounded-3 p-3 d-flex align-items-center gap-3">
                                    <i class="fas fa-bolt fa-lg fa-fw text-danger"></i>
                                    <span class="small">Registro rápido: solo nombre, correo y contraseña.</span>
                                </div>
                                <div class="bg-white bg-opacity-50 rounded-3 p-3 d-flex align-items-center gap-3">
                                    <i class="fas fa-qrcode fa-lg fa-fw text-primary"></i>
                                    <span class="small">Paga con Yape, Plin o transferencia.</span>
                                </div>
                                <div class="bg-white bg-opacity-50 rounded-3 p-3 d-flex align-items-center gap-3">
                                    <i class="fas fa-file-invoice fa-lg fa-fw text-success"></i>
                                    <span class="small">Sigue tus pedidos y descarga tu boleta o factura.</span>
                                </div>
                            </div>
                        </div>
                        <small class="opacity-75 position-relative mt-5">¿Ya tienes cuenta?
                            <a href="{{ route('login') }}" class="link-dark fw-bold">Inicia sesión</a>
                        </small>
                    </div>

                    {{-- ========== FORMULARIO ========== --}}
                    <div class="col-md-7 bg-white p-4 p-lg-5">
                        <div class="mb-4">
                            <span class="badge rounded-pill bg-warning-subtle text-warning-emphasis px-3 py-2 mb-3"><i class="fas fa-bag-shopping me-1"></i>Cuenta de cliente</span>
                            <h3 class="fw-bold text-dark mb-1">Crea tu cuenta</h3>
                            <p class="text-muted mb-0">Completa tus datos. Todos los campos son obligatorios.</p>
                        </div>

                        <form method="POST" action="{{ route('register') }}">
                            @csrf

                            <div class="mb-3">
                                <label for="name" class="form-label fw-bold text-dark">{{ __('Nombre completo') }}</label>
                                <div class="input-group input-group-lg has-validation">
                                    <span class="input-group-text bg-white text-primary"><i class="fas fa-user fa-fw"></i></span>
                                    <input id="name" type="text" name="name" value="{{ old('name') }}"
                                           class="form-control @error('name') is-invalid @enderror"
                                           placeholder="Ej: Ana Flores" required autocomplete="name" autofocus>
                                    @error('name')
                                        <div class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label fw-bold text-dark">{{ __('Correo electrónico') }}</label>
                                <div class="input-group input-group-lg has-validation">
                                    <span class="input-group-text bg-white text-primary"><i class="fas fa-envelope fa-fw"></i></span>
                                    <input id="email" type="email" name="email" value="{{ old('email') }}"
                                           class="form-control @error('email') is-invalid @enderror"
                                           placeholder="tucorreo@ejemplo.com" required autocomplete="email">
                                    @error('email')
                                        <div class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row g-3 mb-4">
                                <div class="col-sm-6">
                                    <label for="password" class="form-label fw-bold text-dark">{{ __('Contraseña') }}</label>
                                    <div class="input-group input-group-lg has-validation">
                                        <span class="input-group-text bg-white text-primary"><i class="fas fa-key fa-fw"></i></span>
                                        <input id="password" type="password" name="password"
                                               class="form-control @error('password') is-invalid @enderror"
                                               placeholder="Mín. 8 caracteres" required autocomplete="new-password">
                                        <button class="btn btn-outline-secondary" type="button" data-ver="password" title="Mostrar / ocultar">
                                            <i class="fas fa-eye fa-fw"></i>
                                        </button>
                                        @error('password')
                                            <div class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <label for="password-confirm" class="form-label fw-bold text-dark">{{ __('Confirmar contraseña') }}</label>
                                    <div class="input-group input-group-lg">
                                        <span class="input-group-text bg-white text-primary"><i class="fas fa-check-double fa-fw"></i></span>
                                        <input id="password-confirm" type="password" name="password_confirmation"
                                               class="form-control" placeholder="Repítela" required autocomplete="new-password">
                                        <button class="btn btn-outline-secondary" type="button" data-ver="password-confirm" title="Mostrar / ocultar">
                                            <i class="fas fa-eye fa-fw"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <small id="aviso-clave" class="text-muted"><i class="fas fa-info-circle me-1"></i>La contraseña debe tener al menos 8 caracteres.</small>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-warning btn-lg w-100 rounded-pill fw-semibold shadow">
                                <i class="fas fa-user-check me-2"></i>{{ __('Crear cuenta') }}
                            </button>
                        </form>

                        <p class="text-center text-muted mt-4 mb-1">
                            ¿Ya tienes una cuenta?
                            <a href="{{ route('login', ['como' => 'cliente']) }}" class="fw-bold text-decoration-none">Inicia sesión</a>
                        </p>
                        <p class="text-center text-muted small mb-0">
                            <i class="fas fa-circle-info me-1"></i>¿Trabajas en la librería? Tu cuenta la crea el administrador.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Botones del ojo: mostrar / ocultar contraseña
    document.querySelectorAll('[data-ver]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const input = document.getElementById(this.dataset.ver);
            const icono = this.querySelector('i');
            const oculto = input.type === 'password';
            input.type = oculto ? 'text' : 'password';
            icono.className = 'fas fa-fw ' + (oculto ? 'fa-eye-slash' : 'fa-eye');
        });
    });

    // Aviso en vivo: largo mínimo y que ambas contraseñas coincidan
    (function () {
        const clave = document.getElementById('password');
        const conf  = document.getElementById('password-confirm');
        const aviso = document.getElementById('aviso-clave');
        function revisar() {
            if (clave.value.length > 0 && clave.value.length < 8) {
                aviso.className = 'text-danger';
                aviso.innerHTML = '<i class="fas fa-times-circle me-1"></i>Faltan ' + (8 - clave.value.length) + ' caracteres.';
            } else if (conf.value.length > 0 && conf.value !== clave.value) {
                aviso.className = 'text-danger';
                aviso.innerHTML = '<i class="fas fa-times-circle me-1"></i>Las contraseñas no coinciden.';
            } else if (clave.value.length >= 8 && conf.value === clave.value) {
                aviso.className = 'text-success';
                aviso.innerHTML = '<i class="fas fa-check-circle me-1"></i>¡Todo listo!';
            } else {
                aviso.className = 'text-muted';
                aviso.innerHTML = '<i class="fas fa-info-circle me-1"></i>La contraseña debe tener al menos 8 caracteres.';
            }
        }
        clave.addEventListener('input', revisar);
        conf.addEventListener('input', revisar);
    })();
</script>
@endsection
