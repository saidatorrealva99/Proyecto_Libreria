{{-- resources/views/pago/form.blade.php  (reemplaza TODO el archivo) --}}
{{-- Solo clases de Bootstrap 5 + iconos FontAwesome (cargados en layouts/app.blade.php) --}}

<div class="card border-0 shadow rounded-4 overflow-hidden">

    {{-- ========== ENCABEZADO ========== --}}
    <div class="card-header bg-primary bg-gradient text-white border-0 p-4 position-relative overflow-hidden">
        <div class="d-flex align-items-center gap-3 position-relative">
            <div class="bg-white bg-opacity-25 rounded-3 shadow-sm p-3 lh-1">
                <i class="fas fa-credit-card fa-2x fa-fw"></i>
            </div>
            <div class="vr opacity-50"></div>
            <div>
                <h3 class="fw-bold mb-1">{{ $pago->exists ? 'Editar' : 'Crear' }} Pago</h3>
                <p class="mb-0 opacity-75">{{ $pago->exists ? 'Actualiza los datos del pago' : 'Registra el cobro de una venta' }}</p>
            </div>
        </div>
        <i class="fas fa-credit-card fa-6x opacity-25 position-absolute top-50 end-0 translate-middle-y me-5 d-none d-md-block"></i>
    </div>

    {{-- ========== CAMPOS ========== --}}
    <div class="card-body p-4">
        <div class="alert alert-primary border-0 rounded-3 d-flex align-items-center gap-2 py-2 mb-4">
            <i class="fas fa-info-circle fs-5"></i>
            <span>Los campos con <span class="text-danger fw-bold">*</span> son obligatorios.</span>
        </div>

        <div class="row g-4">
            <div class="col-md-6">
                <label for="venta_id" class="form-label fw-bold text-dark"><i class="fas fa-file-invoice fa-fw text-primary me-1"></i>Venta <span class="text-danger">*</span></label>
                <select name="venta_id" id="venta_id" class="form-select form-select-lg @error('venta_id') is-invalid @enderror">
                    <option value="">Seleccione la venta</option>
                    @foreach($ventas as $id => $nombre)
                        <option value="{{ $id }}" {{ old('venta_id', $pago?->venta_id) == $id ? 'selected' : '' }}>{{ $nombre }}</option>
                    @endforeach
                </select>
                @error('venta_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label for="metodo_pago" class="form-label fw-bold text-dark"><i class="fas fa-wallet fa-fw text-primary me-1"></i>Método de pago <span class="text-danger">*</span></label>
                <select name="metodo_pago" id="metodo_pago" class="form-select form-select-lg @error('metodo_pago') is-invalid @enderror">
                    <option value="">Seleccione un método</option>
                    <option value="efectivo" {{ (string) old('metodo_pago', $pago?->metodo_pago) === 'efectivo' ? 'selected' : '' }}>Efectivo</option>
                    <option value="tarjeta" {{ (string) old('metodo_pago', $pago?->metodo_pago) === 'tarjeta' ? 'selected' : '' }}>Tarjeta</option>
                    <option value="yape" {{ (string) old('metodo_pago', $pago?->metodo_pago) === 'yape' ? 'selected' : '' }}>Yape</option>
                    <option value="plin" {{ (string) old('metodo_pago', $pago?->metodo_pago) === 'plin' ? 'selected' : '' }}>Plin</option>
                    <option value="transferencia" {{ (string) old('metodo_pago', $pago?->metodo_pago) === 'transferencia' ? 'selected' : '' }}>Transferencia</option>
                </select>
                @error('metodo_pago')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label for="monto" class="form-label fw-bold text-dark"><i class="fas fa-money-bill-wave fa-fw text-primary me-1"></i>Monto (S/) <span class="text-danger">*</span></label>
                <input type="number" name="monto" id="monto" class="form-control form-control-lg @error('monto') is-invalid @enderror"
                       value="{{ old('monto', $pago?->monto) }}" step="0.01" min="0.01" placeholder="0.00">
                @error('monto')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label for="numero_operacion" class="form-label fw-bold text-dark"><i class="fas fa-receipt fa-fw text-primary me-1"></i>N° de operación</label>
                <input type="text" name="numero_operacion" id="numero_operacion" class="form-control form-control-lg @error('numero_operacion') is-invalid @enderror"
                       value="{{ old('numero_operacion', $pago?->numero_operacion) }}" maxlength="30" placeholder="Código Yape, voucher...">
                @error('numero_operacion')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label for="fecha_pago" class="form-label fw-bold text-dark"><i class="fas fa-calendar-alt fa-fw text-primary me-1"></i>Fecha y hora <span class="text-danger">*</span></label>
                <input type="datetime-local" name="fecha_pago" id="fecha_pago" class="form-control form-control-lg @error('fecha_pago') is-invalid @enderror"
                       value="{{ old('fecha_pago', \Illuminate\Support\Carbon::parse($pago?->fecha_pago ?? now())->format('Y-m-d\TH:i')) }}">
                @error('fecha_pago')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>
    </div>

    {{-- ========== BOTONES ========== --}}
    <div class="card-footer bg-light border-0 p-4 d-flex justify-content-end gap-2">
        <a href="{{ route('pagos.index') }}" class="btn btn-outline-secondary btn-lg rounded-pill px-4">
            <i class="fas fa-arrow-left me-2"></i>Cancelar
        </a>
        <button type="submit" class="btn btn-primary btn-lg rounded-pill px-4 shadow">
            <i class="fas fa-save me-2"></i>Guardar Pago
        </button>
    </div>
</div>

<script>
    (function () {
        // El N° de operación solo aplica si NO es efectivo
        const metodo = document.getElementById('metodo_pago');
        const oper   = document.getElementById('numero_operacion');
        function aplicar() {
            const efectivo = metodo.value === 'efectivo';
            oper.disabled = efectivo;
            if (efectivo) oper.value = '';
        }
        metodo.addEventListener('change', aplicar);
        aplicar();
    })();
</script>
