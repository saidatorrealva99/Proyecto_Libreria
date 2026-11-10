{{-- resources/views/venta/form.blade.php  (reemplaza TODO el archivo) --}}
{{-- Solo clases de Bootstrap 5 + iconos FontAwesome (cargados en layouts/app.blade.php) --}}

<div class="card border-0 shadow rounded-4 overflow-hidden">

    {{-- ========== ENCABEZADO ========== --}}
    <div class="card-header bg-primary bg-gradient text-white border-0 p-4 position-relative overflow-hidden">
        <div class="d-flex align-items-center gap-3 position-relative">
            <div class="bg-white bg-opacity-25 rounded-3 shadow-sm p-3 lh-1">
                <i class="fas fa-cash-register fa-2x fa-fw"></i>
            </div>
            <div class="vr opacity-50"></div>
            <div>
                <h3 class="fw-bold mb-1">{{ $venta->exists ? 'Editar' : 'Crear' }} Venta</h3>
                <p class="mb-0 opacity-75">{{ $venta->exists ? 'Actualiza los datos de la venta' : 'Registra la cabecera de un comprobante de venta' }}</p>
            </div>
        </div>
        <i class="fas fa-cash-register fa-6x opacity-25 position-absolute top-50 end-0 translate-middle-y me-5 d-none d-md-block"></i>
    </div>

    {{-- ========== CAMPOS ========== --}}
    <div class="card-body p-4">
        <div class="alert alert-primary border-0 rounded-3 d-flex align-items-center gap-2 py-2 mb-4">
            <i class="fas fa-info-circle fs-5"></i>
            <span>Los campos con <span class="text-danger fw-bold">*</span> son obligatorios.</span>
        </div>

        <div class="row g-4">
            <div class="col-12">
                <h6 class="text-primary fw-bold text-uppercase small border-bottom border-primary-subtle pb-2 mb-0"><i class="fas fa-file-invoice me-2"></i>Comprobante</h6>
            </div>
            <div class="col-md-4">
                <label for="tipo_comprobante" class="form-label fw-bold text-dark"><i class="fas fa-file-invoice fa-fw text-primary me-1"></i>Tipo de comprobante <span class="text-danger">*</span></label>
                <select name="tipo_comprobante" id="tipo_comprobante" class="form-select form-select-lg @error('tipo_comprobante') is-invalid @enderror">
                    <option value="boleta" {{ (string) old('tipo_comprobante', $venta?->tipo_comprobante ?? 'boleta') === 'boleta' ? 'selected' : '' }}>Boleta</option>
                    <option value="factura" {{ (string) old('tipo_comprobante', $venta?->tipo_comprobante ?? 'boleta') === 'factura' ? 'selected' : '' }}>Factura</option>
                </select>
                @error('tipo_comprobante')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label for="numero_comprobante" class="form-label fw-bold text-dark"><i class="fas fa-hashtag fa-fw text-primary me-1"></i>N° de comprobante <span class="text-danger">*</span></label>
                <input type="text" name="numero_comprobante" id="numero_comprobante" class="form-control form-control-lg @error('numero_comprobante') is-invalid @enderror"
                       value="{{ old('numero_comprobante', $venta?->numero_comprobante) }}" maxlength="15" placeholder="B001-00000001" oninput="this.value = this.value.toUpperCase()">
                @error('numero_comprobante')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label for="fecha_venta" class="form-label fw-bold text-dark"><i class="fas fa-calendar-alt fa-fw text-primary me-1"></i>Fecha y hora <span class="text-danger">*</span></label>
                <input type="datetime-local" name="fecha_venta" id="fecha_venta" class="form-control form-control-lg @error('fecha_venta') is-invalid @enderror"
                       value="{{ old('fecha_venta', \Illuminate\Support\Carbon::parse($venta?->fecha_venta ?? now())->format('Y-m-d\TH:i')) }}">
                @error('fecha_venta')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label for="cliente_id" class="form-label fw-bold text-dark"><i class="fas fa-user fa-fw text-primary me-1"></i>Cliente <span class="text-danger">*</span></label>
                <select name="cliente_id" id="cliente_id" class="form-select form-select-lg @error('cliente_id') is-invalid @enderror">
                    <option value="">Seleccione un cliente</option>
                    @foreach($clientes as $id => $nombre)
                        <option value="{{ $id }}" {{ old('cliente_id', $venta?->cliente_id) == $id ? 'selected' : '' }}>{{ $nombre }}</option>
                    @endforeach
                </select>
                @error('cliente_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label for="empleado_id" class="form-label fw-bold text-dark"><i class="fas fa-id-badge fa-fw text-primary me-1"></i>Vendedor <span class="text-danger">*</span></label>
                <select name="empleado_id" id="empleado_id" class="form-select form-select-lg @error('empleado_id') is-invalid @enderror">
                    <option value="">Seleccione un vendedor</option>
                    @foreach($empleados as $id => $nombre)
                        <option value="{{ $id }}" {{ old('empleado_id', $venta?->empleado_id) == $id ? 'selected' : '' }}>{{ $nombre }}</option>
                    @endforeach
                </select>
                @error('empleado_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-12">
                <h6 class="text-primary fw-bold text-uppercase small border-bottom border-primary-subtle pb-2 mb-0"><i class="fas fa-calculator me-2"></i>Montos</h6>
            </div>
            <div class="col-md-3">
                <label for="subtotal" class="form-label fw-bold text-dark"><i class="fas fa-coins fa-fw text-primary me-1"></i>Subtotal (S/) <span class="text-danger">*</span></label>
                <input type="number" name="subtotal" id="subtotal" class="form-control form-control-lg @error('subtotal') is-invalid @enderror"
                       value="{{ old('subtotal', $venta?->subtotal ?? '0.00') }}" step="0.01" min="0">
                @error('subtotal')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-3">
                <label for="descuento" class="form-label fw-bold text-dark"><i class="fas fa-percent fa-fw text-primary me-1"></i>Descuento (S/) <span class="text-danger">*</span></label>
                <input type="number" name="descuento" id="descuento" class="form-control form-control-lg @error('descuento') is-invalid @enderror"
                       value="{{ old('descuento', $venta?->descuento ?? '0.00') }}" step="0.01" min="0">
                @error('descuento')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-3">
                <label for="total" class="form-label fw-bold text-dark"><i class="fas fa-money-check-alt fa-fw text-primary me-1"></i>Total (S/) <span class="text-danger">*</span></label>
                <input type="number" name="total" id="total" class="form-control form-control-lg bg-primary-subtle text-primary fw-bold @error('total') is-invalid @enderror"
                       value="{{ old('total', $venta?->total ?? '0.00') }}" step="0.01" min="0" readonly tabindex="-1">
                @error('total')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-3">
                <label for="estado" class="form-label fw-bold text-dark"><i class="fas fa-toggle-on fa-fw text-primary me-1"></i>Estado <span class="text-danger">*</span></label>
                <div class="input-group input-group-lg has-validation">
                    <span class="input-group-text bg-white"><i class="fas fa-circle small" id="estado-punto"></i></span>
                    <select name="estado" id="estado" class="form-select @error('estado') is-invalid @enderror">
                        <option value="pendiente" {{ (string) old('estado', $venta?->estado ?? 'pendiente') === 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                        <option value="pagada" {{ (string) old('estado', $venta?->estado ?? 'pendiente') === 'pagada' ? 'selected' : '' }}>Pagada</option>
                        <option value="anulada" {{ (string) old('estado', $venta?->estado ?? 'pendiente') === 'anulada' ? 'selected' : '' }}>Anulada</option>
                    </select>
                    @error('estado')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>
    </div>

    {{-- ========== BOTONES ========== --}}
    <div class="card-footer bg-light border-0 p-4 d-flex justify-content-end gap-2">
        <a href="{{ route('ventas.index') }}" class="btn btn-outline-secondary btn-lg rounded-pill px-4">
            <i class="fas fa-arrow-left me-2"></i>Cancelar
        </a>
        <button type="submit" class="btn btn-primary btn-lg rounded-pill px-4 shadow">
            <i class="fas fa-save me-2"></i>Guardar Venta
        </button>
    </div>
</div>

<script>
    (function () {
        // Total = Subtotal - Descuento (se calcula solo)
        const sub = document.getElementById('subtotal');
        const des = document.getElementById('descuento');
        const tot = document.getElementById('total');
        function calcular() {
            const t = (parseFloat(sub.value) || 0) - (parseFloat(des.value) || 0);
            tot.value = t.toFixed(2);
        }
        sub.addEventListener('input', calcular);
        des.addEventListener('input', calcular);
        calcular();

        // Sugiere el prefijo B / F según el tipo de comprobante
        const tipo = document.getElementById('tipo_comprobante');
        const num  = document.getElementById('numero_comprobante');
        tipo.addEventListener('change', function () {
            num.placeholder = this.value === 'factura' ? 'F001-00000001' : 'B001-00000001';
            if (num.value.length > 0) num.value = (this.value === 'factura' ? 'F' : 'B') + num.value.substring(1);
        });
    })();
</script>

<script>
    // Punto de color según el estado seleccionado
    (function () {
        const sel   = document.getElementById('estado');
        const punto = document.getElementById('estado-punto');
        if (!sel || !punto) return;
        const colores = { '1': 'text-success', '0': 'text-secondary',
                          'pendiente': 'text-warning', 'pagada': 'text-success', 'anulada': 'text-danger' };
        function pintar() {
            punto.className = 'fas fa-circle small ' + (colores[sel.value] || 'text-secondary');
        }
        sel.addEventListener('change', pintar);
        pintar();
    })();
</script>
