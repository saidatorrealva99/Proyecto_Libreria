{{-- resources/views/reclamacion/create.blade.php  (archivo NUEVO)
     Libro de Reclamaciones virtual - formulario público:  /libro-de-reclamaciones --}}
@extends('layouts.tienda')

@section('titulo', 'Libro de Reclamaciones')

@section('content')
@php
    $tienda = config('libreria');
    $campo  = 'form-control bg-black border-secondary text-white';
@endphp
<section class="bg-black py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10 col-xl-9">

                {{-- ========== ENCABEZADO ========== --}}
                <div class="text-center mb-4">
                    <span class="bg-warning text-dark rounded-circle d-inline-flex p-3 mb-3"><i class="fas fa-book fa-2x fa-fw"></i></span>
                    <h1 class="fw-bold">Libro de Reclamaciones</h1>
                    <p class="text-white-50 mb-0">Conforme a lo establecido en el Código de Protección y Defensa del Consumidor (Ley N.° 29571).</p>
                </div>

                <form method="POST" action="{{ route('reclamaciones.store') }}" class="bg-primary-subtle border border-warning border-opacity-50 rounded-4 shadow-lg p-4 p-lg-5" novalidate>
                    @csrf

                    @if ($errors->any())
                        <div class="alert alert-danger d-flex align-items-center gap-2">
                            <i class="fas fa-triangle-exclamation"></i><span>Revisa los campos marcados en rojo.</span>
                        </div>
                    @endif

                    {{-- Datos del proveedor --}}
                    <div class="row g-3 small bg-black bg-opacity-50 rounded-3 p-3 mb-4">
                        <div class="col-md-6"><span class="text-white-50">Proveedor:</span> <strong>{{ $tienda['nombre'] }}</strong> ({{ $tienda['titular'] }})</div>
                        <div class="col-md-6"><span class="text-white-50">RUC:</span> <strong>{{ $tienda['ruc'] }}</strong></div>
                        <div class="col-md-6"><span class="text-white-50">Dirección:</span> {{ $tienda['direccion'] }}</div>
                        <div class="col-md-6"><span class="text-white-50">Fecha:</span> {{ now()->format('d/m/Y') }} · <span class="text-white-50">Hoja N.°:</span> se asigna al enviar</div>
                    </div>

                    {{-- ========== 1. CONSUMIDOR ========== --}}
                    <h5 class="fw-bold text-warning mb-3"><span class="badge bg-warning text-dark me-2">1</span>Identificación del consumidor reclamante</h5>
                    <div class="row g-3 mb-4">
                        <div class="col-md-12">
                            <label for="nombre" class="form-label small fw-semibold">Nombre completo</label>
                            <input type="text" id="nombre" name="nombre" maxlength="150" class="{{ $campo }} @error('nombre') is-invalid @enderror"
                                   value="{{ old('nombre', trim(($ficha?->nombres ?? '') . ' ' . ($ficha?->apellidos ?? '')) ?: $usuario?->name) }}">
                            @error('nombre')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label for="tipo_documento" class="form-label small fw-semibold">Tipo de documento</label>
                            <select id="tipo_documento" name="tipo_documento" class="form-select bg-black border-secondary text-white @error('tipo_documento') is-invalid @enderror">
                                @foreach (['DNI' => 'DNI', 'CE' => 'Carné de extranjería', 'Pasaporte' => 'Pasaporte', 'RUC' => 'RUC'] as $valor => $texto)
                                    <option value="{{ $valor }}" @selected(old('tipo_documento', $ficha?->tipo_documento ?? 'DNI') === $valor)>{{ $texto }}</option>
                                @endforeach
                            </select>
                            @error('tipo_documento')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-8">
                            <label for="numero_documento" class="form-label small fw-semibold">Número de documento</label>
                            <input type="text" id="numero_documento" name="numero_documento" maxlength="12" class="{{ $campo }} @error('numero_documento') is-invalid @enderror"
                                   value="{{ old('numero_documento', $ficha?->numero_documento) }}">
                            @error('numero_documento')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-12">
                            <label for="domicilio" class="form-label small fw-semibold">Domicilio</label>
                            <input type="text" id="domicilio" name="domicilio" maxlength="200" class="{{ $campo }} @error('domicilio') is-invalid @enderror"
                                   value="{{ old('domicilio', $ficha?->direccion) }}" placeholder="Av. / Calle, número, distrito">
                            @error('domicilio')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="telefono" class="form-label small fw-semibold">Celular</label>
                            <input type="tel" id="telefono" name="telefono" maxlength="9" class="{{ $campo }} @error('telefono') is-invalid @enderror"
                                   value="{{ old('telefono', $ficha?->telefono) }}" placeholder="9XXXXXXXX">
                            @error('telefono')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="email" class="form-label small fw-semibold">Correo electrónico (aquí te enviaremos la respuesta)</label>
                            <input type="email" id="email" name="email" maxlength="120" class="{{ $campo }} @error('email') is-invalid @enderror"
                                   value="{{ old('email', $usuario?->email) }}">
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-12">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" id="menor_edad" name="menor_edad" value="1" @checked(old('menor_edad'))>
                                <label class="form-check-label small" for="menor_edad">Soy menor de edad</label>
                            </div>
                        </div>
                        <div class="col-md-12 {{ old('menor_edad') ? '' : 'd-none' }}" id="campo-apoderado">
                            <label for="apoderado" class="form-label small fw-semibold">Nombre del padre, madre o tutor</label>
                            <input type="text" id="apoderado" name="apoderado" maxlength="150" class="{{ $campo }} @error('apoderado') is-invalid @enderror" value="{{ old('apoderado') }}">
                            @error('apoderado')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    {{-- ========== 2. BIEN CONTRATADO ========== --}}
                    <h5 class="fw-bold text-warning mb-3"><span class="badge bg-warning text-dark me-2">2</span>Identificación del bien contratado</h5>
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <span class="form-label small fw-semibold d-block mb-2">Tipo</span>
                            <div class="btn-group w-100" role="group">
                                <input type="radio" class="btn-check" name="tipo_bien" id="bien-producto" value="producto" @checked(old('tipo_bien', 'producto') === 'producto')>
                                <label class="btn btn-outline-warning" for="bien-producto"><i class="fas fa-box me-1"></i>Producto</label>
                                <input type="radio" class="btn-check" name="tipo_bien" id="bien-servicio" value="servicio" @checked(old('tipo_bien') === 'servicio')>
                                <label class="btn btn-outline-warning" for="bien-servicio"><i class="fas fa-handshake me-1"></i>Servicio</label>
                            </div>
                            @error('tipo_bien')<div class="text-danger small">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label for="numero_pedido" class="form-label small fw-semibold">N.° de pedido o comprobante (opcional)</label>
                            <input type="text" id="numero_pedido" name="numero_pedido" maxlength="13" class="{{ $campo }} font-monospace @error('numero_pedido') is-invalid @enderror"
                                   value="{{ old('numero_pedido', request('pedido')) }}" placeholder="B001-00000010">
                            @error('numero_pedido')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label for="monto" class="form-label small fw-semibold">Monto reclamado S/ (opcional)</label>
                            <input type="number" id="monto" name="monto" step="0.01" min="0" class="{{ $campo }} @error('monto') is-invalid @enderror" value="{{ old('monto') }}">
                            @error('monto')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-12">
                            <label for="descripcion_bien" class="form-label small fw-semibold">Descripción del producto o servicio</label>
                            <input type="text" id="descripcion_bien" name="descripcion_bien" maxlength="255" class="{{ $campo }} @error('descripcion_bien') is-invalid @enderror"
                                   value="{{ old('descripcion_bien') }}" placeholder="Ej.: Libro 'Cien años de soledad', cuaderno A4...">
                            @error('descripcion_bien')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    {{-- ========== 3. DETALLE ========== --}}
                    <h5 class="fw-bold text-warning mb-3"><span class="badge bg-warning text-dark me-2">3</span>Detalle de la reclamación y pedido del consumidor</h5>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <input type="radio" class="btn-check" name="tipo" id="tipo-reclamo" value="reclamo" @checked(old('tipo', 'reclamo') === 'reclamo')>
                            <label class="btn btn-outline-danger w-100 h-100 text-start p-3 rounded-4" for="tipo-reclamo">
                                <strong class="d-block"><i class="fas fa-circle-exclamation me-1"></i>Reclamo</strong>
                                <small>Disconformidad relacionada con los productos o servicios.</small>
                            </label>
                        </div>
                        <div class="col-md-6">
                            <input type="radio" class="btn-check" name="tipo" id="tipo-queja" value="queja" @checked(old('tipo') === 'queja')>
                            <label class="btn btn-outline-info w-100 h-100 text-start p-3 rounded-4" for="tipo-queja">
                                <strong class="d-block"><i class="fas fa-comment-slash me-1"></i>Queja</strong>
                                <small>Disconformidad no relacionada con los productos o servicios, o malestar por la atención al público.</small>
                            </label>
                        </div>
                        @error('tipo')<div class="col-12 text-danger small">{{ $message }}</div>@enderror

                        <div class="col-md-12">
                            <label for="detalle" class="form-label small fw-semibold">Detalle</label>
                            <textarea id="detalle" name="detalle" rows="5" maxlength="2000" data-contador="contador-detalle" class="{{ $campo }} @error('detalle') is-invalid @enderror"
                                      placeholder="Cuéntanos qué pasó, cuándo y dónde.">{{ old('detalle') }}</textarea>
                            <div class="d-flex justify-content-between">
                                @error('detalle')<div class="text-danger small">{{ $message }}</div>@else<span></span>@enderror
                                <small class="text-white-50" id="contador-detalle">0 / 2000</small>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <label for="pedido_consumidor" class="form-label small fw-semibold">Pedido (¿qué solución esperas?)</label>
                            <textarea id="pedido_consumidor" name="pedido_consumidor" rows="3" maxlength="1000" class="{{ $campo }} @error('pedido_consumidor') is-invalid @enderror"
                                      placeholder="Ej.: Cambio del producto, devolución del dinero...">{{ old('pedido_consumidor') }}</textarea>
                            @error('pedido_consumidor')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    {{-- Avisos legales --}}
                    <div class="small text-white-50 bg-black bg-opacity-50 rounded-3 p-3 mb-4">
                        <p class="mb-2"><i class="fas fa-circle-info text-warning me-1"></i>La formulación del reclamo no impide acudir a otras vías de solución de controversias ni es requisito previo para interponer una denuncia ante el INDECOPI.</p>
                        <p class="mb-0"><i class="fas fa-clock text-warning me-1"></i>El proveedor deberá dar respuesta al reclamo en un plazo no mayor a quince (15) días hábiles improrrogables.</p>
                    </div>

                    <button type="submit" class="btn btn-warning btn-lg w-100 rounded-pill fw-bold shadow">
                        <i class="fas fa-paper-plane me-2"></i>Enviar hoja de reclamación
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    // Mostrar el campo del apoderado solo si es menor de edad
    document.getElementById('menor_edad').addEventListener('change', function () {
        document.getElementById('campo-apoderado').classList.toggle('d-none', !this.checked);
    });

    // Contador de caracteres del detalle
    document.querySelectorAll('[data-contador]').forEach(function (campo) {
        const contador = document.getElementById(campo.dataset.contador);
        const actualizar = () => contador.textContent = campo.value.length + ' / ' + campo.maxLength;
        campo.addEventListener('input', actualizar);
        actualizar();
    });
</script>
@endpush
