{{-- resources/views/detalle-venta/form.blade.php  (reemplaza TODO el archivo) --}}
{{-- Solo clases de Bootstrap 5 + iconos FontAwesome (cargados en layouts/app.blade.php) --}}

<div class="card border-0 shadow rounded-4 overflow-hidden">

    {{-- ========== ENCABEZADO ========== --}}
    <div class="card-header bg-primary bg-gradient text-white border-0 p-4 position-relative overflow-hidden">
        <div class="d-flex align-items-center gap-3 position-relative">
            <div class="bg-white bg-opacity-25 rounded-3 shadow-sm p-3 lh-1">
                <i class="fas fa-list-ul fa-2x fa-fw"></i>
            </div>
            <div class="vr opacity-50"></div>
            <div>
                <h3 class="fw-bold mb-1">{{ $detalleVenta->exists ? 'Editar' : 'Crear' }} Detalle de venta</h3>
                <p class="mb-0 opacity-75">{{ $detalleVenta->exists ? 'Actualiza el producto de la venta' : 'Agrega un producto a una venta' }}</p>
            </div>
        </div>
        <i class="fas fa-list-ul fa-6x opacity-25 position-absolute top-50 end-0 translate-middle-y me-5 d-none d-md-block"></i>
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
                        <option value="{{ $id }}" {{ old('venta_id', $detalleVenta?->venta_id) == $id ? 'selected' : '' }}>{{ $nombre }}</option>
                    @endforeach
                </select>
                @error('venta_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label for="producto_id" class="form-label fw-bold text-dark"><i class="fas fa-book fa-fw text-primary me-1"></i>Producto <span class="text-danger">*</span></label>
                <select name="producto_id" id="producto_id" class="form-select form-select-lg @error('producto_id') is-invalid @enderror">
                    <option value="" data-precio="">Seleccione un producto</option>
                    @foreach($productos as $p)
                        <option value="{{ $p->id }}" data-precio="{{ $p->precio_venta }}"
                            {{ old('producto_id', $detalleVenta?->producto_id) == $p->id ? 'selected' : '' }}>
                            {{ $p->codigo }} - {{ $p->nombre }} (stock: {{ $p->stock }})
                        </option>
                    @endforeach
                </select>
                @error('producto_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label for="cantidad" class="form-label fw-bold text-dark"><i class="fas fa-sort-numeric-up fa-fw text-primary me-1"></i>Cantidad <span class="text-danger">*</span></label>
                <input type="number" name="cantidad" id="cantidad" class="form-control form-control-lg @error('cantidad') is-invalid @enderror"
                       value="{{ old('cantidad', $detalleVenta?->cantidad ?? 1) }}" step="1" min="1">
                @error('cantidad')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label for="precio_unitario" class="form-label fw-bold text-dark"><i class="fas fa-tag fa-fw text-primary me-1"></i>Precio unitario (S/) <span class="text-danger">*</span></label>
                <input type="number" name="precio_unitario" id="precio_unitario" class="form-control form-control-lg @error('precio_unitario') is-invalid @enderror"
                       value="{{ old('precio_unitario', $detalleVenta?->precio_unitario) }}" step="0.01" min="0.01" placeholder="0.00">
                @error('precio_unitario')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label for="subtotal" class="form-label fw-bold text-dark"><i class="fas fa-money-check-alt fa-fw text-primary me-1"></i>Subtotal (S/) <span class="text-danger">*</span></label>
                <input type="number" name="subtotal" id="subtotal" class="form-control form-control-lg bg-primary-subtle text-primary fw-bold @error('subtotal') is-invalid @enderror"
                       value="{{ old('subtotal', $detalleVenta?->subtotal) }}" step="0.01" min="0" readonly tabindex="-1">
                @error('subtotal')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>
    </div>

    {{-- ========== BOTONES ========== --}}
    <div class="card-footer bg-light border-0 p-4 d-flex justify-content-end gap-2">
        <a href="{{ route('detalle-ventas.index') }}" class="btn btn-outline-secondary btn-lg rounded-pill px-4">
            <i class="fas fa-arrow-left me-2"></i>Cancelar
        </a>
        <button type="submit" class="btn btn-primary btn-lg rounded-pill px-4 shadow">
            <i class="fas fa-save me-2"></i>Guardar Detalle
        </button>
    </div>
</div>

<script>
    (function () {
        const prod   = document.getElementById('producto_id');
        const cant   = document.getElementById('cantidad');
        const precio = document.getElementById('precio_unitario');
        const sub    = document.getElementById('subtotal');

        function calcular() {
            sub.value = ((parseInt(cant.value) || 0) * (parseFloat(precio.value) || 0)).toFixed(2);
        }
        // Al elegir un producto se copia su precio de venta
        prod.addEventListener('change', function () {
            const p = this.options[this.selectedIndex].dataset.precio;
            if (p) precio.value = parseFloat(p).toFixed(2);
            calcular();
        });
        cant.addEventListener('input', calcular);
        precio.addEventListener('input', calcular);
        calcular();
    })();
</script>
