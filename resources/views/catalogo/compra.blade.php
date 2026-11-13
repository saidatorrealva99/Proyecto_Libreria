
@extends('layouts.tienda')

@section('titulo', 'Finalizar compra')

@section('content')
@php
    $entrega     = old('entrega', 'recojo');
    $comprobante = old('comprobante', $anterior?->tipo_documento === 'RUC' ? 'factura' : 'boleta');
    $metodo      = old('metodo_pago', 'yape');
    $pagos = [
        // [nombre, ícono, color, texto debajo]
        'yape'          => ['Yape',          'fa-mobile-screen',        'info',    'Escanea el QR'],
        'plin'          => ['Plin',          'fa-mobile-screen-button', 'primary', 'Escanea el QR'],
        'transferencia' => ['Transferencia', 'fa-building-columns',     'light',   $datos['banco']],
    ];
@endphp

    {{-- ========== ENCABEZADO ========== --}}
    <header class="bg-primary-subtle bg-gradient border-bottom border-primary border-opacity-25">
        <div class="container py-4">
            <nav aria-label="Ruta">
                <ol class="breadcrumb small mb-2">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}" class="link-warning text-decoration-none"><i class="fas fa-house me-1"></i>Inicio</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('carrito') }}" class="link-warning text-decoration-none">Mi carrito</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Finalizar compra</li>
                </ol>
            </nav>
            <div class="d-flex flex-wrap justify-content-between align-items-end gap-3">
                <h1 class="fw-bold mb-0"><i class="fas fa-lock text-warning me-2"></i>Finalizar compra</h1>
                <div class="d-flex align-items-center gap-2 small">
                    <a href="{{ route('carrito') }}" class="badge rounded-pill bg-success text-decoration-none px-3 py-2"><i class="fas fa-check me-1"></i>1. Carrito</a>
                    <i class="fas fa-chevron-right text-white-50"></i>
                    <span class="badge rounded-pill bg-warning text-dark px-3 py-2"><i class="fas fa-truck me-1"></i>2. Entrega y datos</span>
                    <i class="fas fa-chevron-right text-white-50"></i>
                    <span class="badge rounded-pill bg-warning text-dark px-3 py-2"><i class="fas fa-mobile-screen me-1"></i>3. Pago</span>
                </div>
            </div>
        </div>
    </header>

    <section class="bg-black py-5">
        <div class="container">

            {{-- Errores --}}
            @if (session('compra_error'))
                <div class="alert alert-danger d-flex align-items-center gap-2 rounded-4">
                    <i class="fas fa-triangle-exclamation fa-lg"></i><span>{{ session('compra_error') }}</span>
                    <a href="{{ route('carrito') }}" class="btn btn-sm btn-outline-danger rounded-pill ms-auto">Revisar carrito</a>
                </div>
            @endif
            @if ($errors->any())
                <div class="alert alert-warning rounded-4">
                    <div class="fw-bold mb-1"><i class="fas fa-circle-exclamation me-2"></i>Revisa estos datos:</div>
                    <ul class="mb-0 small">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('compra.store') }}" method="POST" id="formCompra" novalidate
                  data-recojo-envio="{{ $montos['recojo']['envio'] }}"  data-recojo-total="{{ $montos['recojo']['total'] }}"  data-recojo-pagar="{{ $montos['recojo']['pagar'] }}"
                  data-delivery-envio="{{ $montos['delivery']['envio'] }}" data-delivery-total="{{ $montos['delivery']['total'] }}" data-delivery-pagar="{{ $montos['delivery']['pagar'] }}">
                @csrf

                <div class="row g-4">
                    <div class="col-lg-8">

                        {{-- =========================================================
                             1. ENTREGA
                        ========================================================== --}}
                        <div class="bg-primary-subtle border border-primary border-opacity-25 rounded-4 p-4 mb-4">
                            <h5 class="fw-bold mb-3"><span class="badge rounded-circle bg-warning text-dark me-2">1</span>¿Cómo quieres recibir tu pedido?</h5>

                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <input type="radio" class="btn-check" name="entrega" id="entregaRecojo" value="recojo" autocomplete="off" {{ $entrega === 'recojo' ? 'checked' : '' }}>
                                    <label class="btn btn-outline-success w-100 h-100 text-start rounded-4 p-3" for="entregaRecojo">
                                        <i class="fas fa-store fa-xl mb-2 d-block"></i>
                                        <span class="fw-bold d-block">Recojo en tienda</span>
                                        <small>Pagas el {{ $porcAdelanto * 100 }} % ahora y el resto al recoger. Sin costo de envío.</small>
                                    </label>
                                </div>
                                <div class="col-md-6">
                                    <input type="radio" class="btn-check" name="entrega" id="entregaDelivery" value="delivery" autocomplete="off" {{ $entrega === 'delivery' ? 'checked' : '' }}>
                                    <label class="btn btn-outline-info w-100 h-100 text-start rounded-4 p-3" for="entregaDelivery">
                                        <i class="fas fa-truck-fast fa-xl mb-2 d-block"></i>
                                        <span class="fw-bold d-block">Envío a domicilio</span>
                                        <small>
                                            {{ $montos['delivery']['envio'] > 0 ? 'S/ ' . number_format($montos['delivery']['envio'], 2) : '¡Gratis!' }}
                                            · Pagas el total ahora.
                                        </small>
                                    </label>
                                </div>
                            </div>

                            {{-- Dirección: solo para envío --}}
                            <div id="bloqueDireccion" class="row g-3 mb-3 {{ $entrega === 'delivery' ? '' : 'd-none' }}">
                                <div class="col-md-7">
                                    <label for="direccion" class="form-label fw-semibold small"><i class="fas fa-location-dot text-info me-1"></i>Dirección de entrega</label>
                                    <input type="text" name="direccion" id="direccion" maxlength="200" value="{{ old('direccion', $anterior?->direccion) }}"
                                           class="form-control bg-black border-info border-opacity-50 @error('direccion') is-invalid @enderror" placeholder="Av. / Jr. / Calle, número, distrito">
                                    @error('direccion')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-5">
                                    <label for="referencia" class="form-label fw-semibold small"><i class="fas fa-signs-post text-info me-1"></i>Referencia (opcional)</label>
                                    <input type="text" name="referencia" id="referencia" maxlength="200" value="{{ old('referencia') }}"
                                           class="form-control bg-black border-info border-opacity-50" placeholder="Frente al parque, 2do piso…">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label for="telefono" class="form-label fw-semibold small"><i class="fas fa-mobile-screen text-warning me-1"></i>Celular de contacto</label>
                                <input type="tel" name="telefono" id="telefono" maxlength="9" inputmode="numeric" value="{{ old('telefono', $anterior?->telefono) }}"
                                       class="form-control bg-black border-warning border-opacity-50 @error('telefono') is-invalid @enderror" placeholder="9XXXXXXXX">
                                @error('telefono')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        {{-- =========================================================
                             2. COMPROBANTE Y DATOS
                        ========================================================== --}}
                        <div class="bg-primary-subtle border border-primary border-opacity-25 rounded-4 p-4 mb-4">
                            <h5 class="fw-bold mb-3"><span class="badge rounded-circle bg-warning text-dark me-2">2</span>Comprobante</h5>

                            <div class="btn-group mb-3" role="group" aria-label="Tipo de comprobante">
                                <input type="radio" class="btn-check" name="comprobante" id="compBoleta" value="boleta" autocomplete="off" {{ $comprobante === 'boleta' ? 'checked' : '' }}>
                                <label class="btn btn-outline-warning px-4" for="compBoleta"><i class="fas fa-receipt me-2"></i>Boleta</label>
                                <input type="radio" class="btn-check" name="comprobante" id="compFactura" value="factura" autocomplete="off" {{ $comprobante === 'factura' ? 'checked' : '' }}>
                                <label class="btn btn-outline-warning px-4" for="compFactura"><i class="fas fa-file-invoice me-2"></i>Factura</label>
                            </div>

                            <div class="row g-3">
                                <div class="col-md-4" id="bloqueTipoDoc">
                                    <label for="tipo_documento" class="form-label fw-semibold small">Documento</label>
                                    <select name="tipo_documento" id="tipo_documento" class="form-select bg-black border-warning border-opacity-50">
                                        <option value="DNI" {{ old('tipo_documento', $anterior?->tipo_documento) === 'DNI' ? 'selected' : '' }}>DNI</option>
                                        <option value="CE"  {{ old('tipo_documento', $anterior?->tipo_documento) === 'CE'  ? 'selected' : '' }}>Carné de extranjería</option>
                                    </select>
                                </div>
                                <div class="col-md-8" id="bloqueNumDoc">
                                    <label for="numero_documento" class="form-label fw-semibold small" id="etiquetaDoc">Número de documento</label>
                                    <input type="text" name="numero_documento" id="numero_documento" maxlength="12" inputmode="numeric"
                                           value="{{ old('numero_documento', $anterior?->numero_documento) }}"
                                           class="form-control bg-black border-warning border-opacity-50 @error('numero_documento') is-invalid @enderror">
                                    @error('numero_documento')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6" id="bloqueNombres">
                                    <label for="nombres" class="form-label fw-semibold small" id="etiquetaNombres">Nombres</label>
                                    <input type="text" name="nombres" id="nombres" maxlength="100" value="{{ old('nombres', $anterior?->nombres ?? Auth::user()->name) }}"
                                           class="form-control bg-black border-warning border-opacity-50 @error('nombres') is-invalid @enderror">
                                    @error('nombres')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6" id="bloqueApellidos">
                                    <label for="apellidos" class="form-label fw-semibold small">Apellidos</label>
                                    <input type="text" name="apellidos" id="apellidos" maxlength="100" value="{{ old('apellidos', $anterior?->apellidos) }}"
                                           class="form-control bg-black border-warning border-opacity-50 @error('apellidos') is-invalid @enderror">
                                    @error('apellidos')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                        </div>

                        {{-- =========================================================
                             3. PAGO
                        ========================================================== --}}
                        <div class="bg-primary-subtle border border-warning border-opacity-50 rounded-4 p-4">
                            <h5 class="fw-bold mb-1"><span class="badge rounded-circle bg-warning text-dark me-2">3</span>Pago</h5>
                            <p class="text-white-50 small mb-3">
                                Paga <strong class="text-warning fs-5" data-mostrar="pagar">S/ 0.00</strong>
                                <span data-mostrar="textoPago"></span>
                            </p>

                            <div class="row g-3 mb-3">
                                @foreach ($pagos as $clave => [$nombre, $icono, $color, $textoPago])
                                    <div class="col-md-4">
                                        <input type="radio" class="btn-check" name="metodo_pago" id="pago{{ $clave }}" value="{{ $clave }}" autocomplete="off" {{ $metodo === $clave ? 'checked' : '' }}>
                                        <label class="btn btn-outline-{{ $color }} w-100 h-100 rounded-4 p-3" for="pago{{ $clave }}">
                                            <i class="fas {{ $icono }} fa-xl d-block mb-2"></i>
                                            <span class="fw-bold d-block">{{ $nombre }}</span>
                                            <small>{{ $textoPago }}</small>
                                        </label>
                                    </div>
                                @endforeach
                            </div>

                            {{-- Datos del método elegido: QR de Yape / QR de Plin / cuenta para transferir --}}
                            <div class="bg-black bg-opacity-50 rounded-4 p-3 mb-3 text-center">
                                <div data-datos-pago="yape">
                                    @include('catalogo._qr', ['archivo' => $datos['yape_qr'], 'nombre' => 'Yape', 'color' => 'info'])
                                </div>
                                <div data-datos-pago="plin" class="d-none">
                                    @include('catalogo._qr', ['archivo' => $datos['plin_qr'], 'nombre' => 'Plin', 'color' => 'primary'])
                                </div>
                                <div data-datos-pago="transferencia" class="d-none small text-start">
                                    <div class="fw-bold mb-1"><i class="fas fa-building-columns text-light me-1"></i>{{ $datos['banco'] }}</div>
                                    <div>Cuenta: <span class="font-monospace">{{ $datos['cuenta'] }}</span>
                                        <button type="button" class="btn btn-sm btn-outline-light rounded-pill ms-1 py-0" data-copiar="{{ $datos['cuenta'] }}"><i class="fas fa-copy"></i></button></div>
                                    <div>CCI: <span class="font-monospace">{{ $datos['cci'] }}</span>
                                        <button type="button" class="btn btn-sm btn-outline-light rounded-pill ms-1 py-0" data-copiar="{{ $datos['cci'] }}"><i class="fas fa-copy"></i></button></div>
                                </div>
                                <div class="small text-white-50 mt-2">A nombre de <strong class="text-white">{{ $datos['titular'] }}</strong></div>
                            </div>

                            <label for="numero_operacion" class="form-label fw-semibold small"><i class="fas fa-receipt text-warning me-1"></i>Número de operación</label>
                            <input type="text" name="numero_operacion" id="numero_operacion" maxlength="30" value="{{ old('numero_operacion') }}"
                                   class="form-control form-control-lg bg-black border-warning @error('numero_operacion') is-invalid @enderror"
                                   placeholder="Lo encuentras en el comprobante de Yape, Plin o tu banco">
                            @error('numero_operacion')<div class="invalid-feedback">{{ $message }}</div>@enderror

                            <div class="form-check mt-4">
                                <input class="form-check-input @error('acepto') is-invalid @enderror" type="checkbox" name="acepto" value="1" id="acepto" {{ old('acepto') ? 'checked' : '' }}>
                                <label class="form-check-label small" for="acepto">
                                    Entiendo que la librería verificará mi pago antes de confirmar el pedido y que,
                                    si recojo en tienda, pagaré el saldo al recoger.
                                </label>
                            </div>
                        </div>
                    </div>

                    {{-- =========================================================
                         RESUMEN
                    ========================================================== --}}
                    <div class="col-lg-4">
                        <div class="bg-primary-subtle border border-warning border-opacity-50 rounded-4 shadow-lg p-4 sticky-top">
                            <h5 class="fw-bold mb-3"><i class="fas fa-receipt text-warning me-2"></i>Tu pedido</h5>

                            <ul class="list-unstyled small mb-3">
                                @foreach ($items as $item)
                                    <li class="d-flex justify-content-between gap-2 mb-2">
                                        <span><span class="text-warning fw-bold">{{ $item->cantidad }} ×</span> {{ $item->producto->nombre }}</span>
                                        <span class="text-nowrap">S/ {{ number_format($item->subtotal, 2) }}</span>
                                    </li>
                                @endforeach
                            </ul>

                            <div class="border-top border-warning border-opacity-25 pt-3 small">
                                <div class="d-flex justify-content-between mb-1"><span class="text-white-50">Subtotal</span><span>S/ {{ number_format($subtotal, 2) }}</span></div>
                                <div class="d-flex justify-content-between mb-1"><span class="text-white-50">Envío</span><span data-mostrar="envio">S/ 0.00</span></div>
                                <div class="d-flex justify-content-between align-items-center mt-2">
                                    <span class="fw-bold">Total</span><span class="fs-4 fw-bold" data-mostrar="total">S/ 0.00</span>
                                </div>
                            </div>

                            <div class="bg-black bg-opacity-50 rounded-3 p-3 my-3">
                                <div class="d-flex justify-content-between"><span>Pagas ahora</span><strong class="text-warning" data-mostrar="pagar">S/ 0.00</strong></div>
                                <div class="d-flex justify-content-between small text-white-50"><span>Pagas al recoger</span><span data-mostrar="saldo">S/ 0.00</span></div>
                            </div>

                            <button type="submit" class="btn btn-warning btn-lg w-100 rounded-pill fw-bold shadow" data-animar="fa-beat">
                                <i class="fas fa-check-double me-2"></i>Confirmar compra
                            </button>
                            <a href="{{ route('carrito') }}" class="btn btn-link link-light w-100 small mt-2">
                                <i class="fas fa-arrow-left me-1"></i>Volver al carrito
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('formCompra');
        const soles = function (n) { return 'S/ ' + Number(n).toFixed(2); };

        // ----- Montos según la forma de entrega -----
        function actualizarEntrega() {
            const tipo  = form.querySelector('input[name="entrega"]:checked').value;
            const envio = parseFloat(form.dataset[tipo + 'Envio']);
            const total = parseFloat(form.dataset[tipo + 'Total']);
            const pagar = parseFloat(form.dataset[tipo + 'Pagar']);

            form.querySelectorAll('[data-mostrar="envio"]').forEach(function (e) { e.textContent = envio > 0 ? soles(envio) : 'Gratis'; });
            form.querySelectorAll('[data-mostrar="total"]').forEach(function (e) { e.textContent = soles(total); });
            form.querySelectorAll('[data-mostrar="pagar"]').forEach(function (e) { e.textContent = soles(pagar); });
            form.querySelectorAll('[data-mostrar="saldo"]').forEach(function (e) { e.textContent = soles(total - pagar); });
            form.querySelectorAll('[data-mostrar="textoPago"]').forEach(function (e) {
                e.textContent = tipo === 'recojo' ? 'ahora como adelanto (el resto lo pagas al recoger).' : 'ahora (el total con envío).';
            });
            document.getElementById('bloqueDireccion').classList.toggle('d-none', tipo !== 'delivery');
        }

        // ----- Boleta o factura -----
        function actualizarComprobante() {
            const factura = form.querySelector('input[name="comprobante"]:checked').value === 'factura';
            document.getElementById('bloqueTipoDoc').classList.toggle('d-none', factura);
            document.getElementById('bloqueApellidos').classList.toggle('d-none', factura);
            document.getElementById('etiquetaDoc').textContent = factura ? 'RUC (11 dígitos)' : 'Número de documento';
            document.getElementById('etiquetaNombres').textContent = factura ? 'Razón social' : 'Nombres';
            document.getElementById('numero_documento').maxLength = factura ? 11 : 12;
        }

        // ----- Muestra el QR (o la cuenta) del método de pago elegido -----
        function actualizarMetodo() {
            const metodo = form.querySelector('input[name="metodo_pago"]:checked').value;
            form.querySelectorAll('[data-datos-pago]').forEach(function (bloque) {
                bloque.classList.toggle('d-none', bloque.dataset.datosPago !== metodo);
            });
        }

        form.querySelectorAll('input[name="metodo_pago"]').forEach(function (r) { r.addEventListener('change', actualizarMetodo); });
        form.querySelectorAll('input[name="entrega"]').forEach(function (r) { r.addEventListener('change', actualizarEntrega); });
        form.querySelectorAll('input[name="comprobante"]').forEach(function (r) { r.addEventListener('change', actualizarComprobante); });
        actualizarEntrega();
        actualizarComprobante();
        actualizarMetodo();
    });
</script>
@endpush
