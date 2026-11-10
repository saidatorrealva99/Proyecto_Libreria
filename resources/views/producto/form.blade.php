{{-- resources/views/producto/form.blade.php  (reemplaza TODO el archivo) --}}
{{-- Solo clases de Bootstrap 5 + iconos FontAwesome (cargados en layouts/app.blade.php) --}}

<div class="card border-0 shadow rounded-4 overflow-hidden">

    {{-- ========== ENCABEZADO ========== --}}
    <div class="card-header bg-primary bg-gradient text-white border-0 p-4 position-relative overflow-hidden">
        <div class="d-flex align-items-center gap-3 position-relative">
            <div class="bg-white bg-opacity-25 rounded-3 shadow-sm p-3 lh-1">
                <i class="fas fa-book fa-2x fa-fw"></i>
            </div>
            <div class="vr opacity-50"></div>
            <div>
                <h3 class="fw-bold mb-1">{{ $producto->exists ? 'Editar' : 'Crear' }} Producto</h3>
                <p class="mb-0 opacity-75">{{ $producto->exists ? 'Actualiza los datos del producto' : 'Registra un libro, útil escolar o artículo de papelería' }}</p>
            </div>
        </div>
        <i class="fas fa-book fa-6x opacity-25 position-absolute top-50 end-0 translate-middle-y me-5 d-none d-md-block"></i>
    </div>

    {{-- ========== CAMPOS ========== --}}
    <div class="card-body p-4">
        <div class="alert alert-primary border-0 rounded-3 d-flex align-items-center gap-2 py-2 mb-4">
            <i class="fas fa-info-circle fs-5"></i>
            <span>Los campos con <span class="text-danger fw-bold">*</span> son obligatorios.</span>
        </div>

        <div class="row g-4">
            <div class="col-12">
                <h6 class="text-primary fw-bold text-uppercase small border-bottom border-primary-subtle pb-2 mb-0"><i class="fas fa-book me-2"></i>Datos del producto</h6>
            </div>
            <div class="col-md-4">
                <label for="codigo" class="form-label fw-bold text-dark"><i class="fas fa-barcode fa-fw text-primary me-1"></i>Código <span class="text-danger">*</span></label>
                <input type="text" name="codigo" id="codigo" class="form-control form-control-lg @error('codigo') is-invalid @enderror"
                       value="{{ old('codigo', $producto?->codigo) }}" maxlength="20" placeholder="LIB-0001" oninput="this.value = this.value.toUpperCase()">
                @error('codigo')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-8">
                <label for="nombre" class="form-label fw-bold text-dark"><i class="fas fa-book fa-fw text-primary me-1"></i>Nombre <span class="text-danger">*</span></label>
                <input type="text" name="nombre" id="nombre" class="form-control form-control-lg @error('nombre') is-invalid @enderror"
                       value="{{ old('nombre', $producto?->nombre) }}" maxlength="150" placeholder="Nombre del libro o útil">
                @error('nombre')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-12">
                <label for="descripcion" class="form-label fw-bold text-dark"><i class="fas fa-align-left fa-fw text-primary me-1"></i>Descripción</label>
                <input type="text" name="descripcion" id="descripcion" class="form-control form-control-lg @error('descripcion') is-invalid @enderror"
                       value="{{ old('descripcion', $producto?->descripcion) }}" maxlength="255" placeholder="Opcional">
                @error('descripcion')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label for="categoria_id" class="form-label fw-bold text-dark"><i class="fas fa-tags fa-fw text-primary me-1"></i>Categoría <span class="text-danger">*</span></label>
                <select name="categoria_id" id="categoria_id" class="form-select form-select-lg @error('categoria_id') is-invalid @enderror">
                    <option value="">Seleccione una categoría</option>
                    @foreach($categorias as $id => $nombre)
                        <option value="{{ $id }}" {{ old('categoria_id', $producto?->categoria_id) == $id ? 'selected' : '' }}>{{ $nombre }}</option>
                    @endforeach
                </select>
                @error('categoria_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label for="autor_id" class="form-label fw-bold text-dark"><i class="fas fa-user-edit fa-fw text-primary me-1"></i>Autor</label>
                <select name="autor_id" id="autor_id" class="form-select form-select-lg @error('autor_id') is-invalid @enderror">
                    <option value="">— Sin autor (útiles / papelería) —</option>
                    @foreach($autores as $id => $nombre)
                        <option value="{{ $id }}" {{ old('autor_id', $producto?->autor_id) == $id ? 'selected' : '' }}>{{ $nombre }}</option>
                    @endforeach
                </select>
                @error('autor_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label for="isbn" class="form-label fw-bold text-dark"><i class="fas fa-hashtag fa-fw text-primary me-1"></i>ISBN</label>
                <input type="text" name="isbn" id="isbn" class="form-control form-control-lg @error('isbn') is-invalid @enderror"
                       value="{{ old('isbn', $producto?->isbn) }}" maxlength="17" placeholder="978-6124215037 (solo libros)">
                @error('isbn')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label for="marca_editorial" class="form-label fw-bold text-dark"><i class="fas fa-building fa-fw text-primary me-1"></i>Editorial / Marca</label>
                <input type="text" name="marca_editorial" id="marca_editorial" class="form-control form-control-lg @error('marca_editorial') is-invalid @enderror"
                       value="{{ old('marca_editorial', $producto?->marca_editorial) }}" maxlength="100" placeholder="Alfaguara, Standford...">
                @error('marca_editorial')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-12">
                <h6 class="text-primary fw-bold text-uppercase small border-bottom border-primary-subtle pb-2 mb-0"><i class="fas fa-boxes me-2"></i>Precio e inventario</h6>
            </div>
            <div class="col-md-3">
                <label for="precio_venta" class="form-label fw-bold text-dark"><i class="fas fa-money-bill-wave fa-fw text-primary me-1"></i>Precio de venta (S/) <span class="text-danger">*</span></label>
                <input type="number" name="precio_venta" id="precio_venta" class="form-control form-control-lg @error('precio_venta') is-invalid @enderror"
                       value="{{ old('precio_venta', $producto?->precio_venta) }}" step="0.01" min="0.01" placeholder="0.00">
                @error('precio_venta')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-3">
                <label for="stock" class="form-label fw-bold text-dark"><i class="fas fa-boxes fa-fw text-primary me-1"></i>Stock <span class="text-danger">*</span></label>
                <input type="number" name="stock" id="stock" class="form-control form-control-lg @error('stock') is-invalid @enderror"
                       value="{{ old('stock', $producto?->stock ?? 0) }}" step="1" min="0">
                @error('stock')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-3">
                <label for="stock_minimo" class="form-label fw-bold text-dark"><i class="fas fa-exclamation-triangle fa-fw text-primary me-1"></i>Stock mínimo <span class="text-danger">*</span></label>
                <input type="number" name="stock_minimo" id="stock_minimo" class="form-control form-control-lg @error('stock_minimo') is-invalid @enderror"
                       value="{{ old('stock_minimo', $producto?->stock_minimo ?? 5) }}" step="1" min="0">
                @error('stock_minimo')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-3">
                <label for="estado" class="form-label fw-bold text-dark"><i class="fas fa-toggle-on fa-fw text-primary me-1"></i>Estado <span class="text-danger">*</span></label>
                <div class="input-group input-group-lg has-validation">
                    <span class="input-group-text bg-white"><i class="fas fa-circle small" id="estado-punto"></i></span>
                    <select name="estado" id="estado" class="form-select @error('estado') is-invalid @enderror">
                        <option value="1" {{ (string) old('estado', $producto?->estado ?? '1') === '1' ? 'selected' : '' }}>Disponible para la venta</option>
                        <option value="0" {{ (string) old('estado', $producto?->estado ?? '1') === '0' ? 'selected' : '' }}>Descontinuado</option>
                    </select>
                    @error('estado')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>
    </div>

    {{-- ========== BOTONES ========== --}}
    <div class="card-footer bg-light border-0 p-4 d-flex justify-content-end gap-2">
        <a href="{{ route('productos.index') }}" class="btn btn-outline-secondary btn-lg rounded-pill px-4">
            <i class="fas fa-arrow-left me-2"></i>Cancelar
        </a>
        <button type="submit" class="btn btn-primary btn-lg rounded-pill px-4 shadow">
            <i class="fas fa-save me-2"></i>Guardar Producto
        </button>
    </div>
</div>

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
