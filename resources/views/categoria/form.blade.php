{{-- resources/views/categoria/form.blade.php  (reemplaza TODO el archivo) --}}
{{-- Solo clases de Bootstrap 5 + iconos FontAwesome (cargados en layouts/app.blade.php) --}}

<div class="card border-0 shadow rounded-4 overflow-hidden">

    {{-- ========== ENCABEZADO ========== --}}
    <div class="card-header bg-primary bg-gradient text-white border-0 p-4 position-relative overflow-hidden">
        <div class="d-flex align-items-center gap-3 position-relative">
            <div class="bg-white bg-opacity-25 rounded-3 shadow-sm p-3 lh-1">
                <i class="fas fa-tag fa-2x fa-fw"></i>
            </div>
            <div class="vr opacity-50"></div>
            <div>
                <h3 class="fw-bold mb-1">{{ $categoria->exists ? 'Editar' : 'Crear' }} Categoría</h3>
                <p class="mb-0 opacity-75">{{ $categoria->exists ? 'Actualiza los datos de la categoría' : 'Registra una nueva categoría para organizar tus productos' }}</p>
            </div>
        </div>
        <i class="fas fa-tag fa-6x opacity-25 position-absolute top-50 end-0 translate-middle-y me-5 d-none d-md-block"></i>
    </div>

    {{-- ========== CAMPOS ========== --}}
    <div class="card-body p-4">
        <div class="alert alert-primary border-0 rounded-3 d-flex align-items-center gap-2 py-2 mb-4">
            <i class="fas fa-info-circle fs-5"></i>
            <span>Los campos con <span class="text-danger fw-bold">*</span> son obligatorios.</span>
        </div>

        <div class="row g-4">
            <div class="col-md-8">
                <label for="nombre" class="form-label fw-bold text-dark"><i class="fas fa-tag fa-fw text-primary me-1"></i>Nombre <span class="text-danger">*</span></label>
                <input type="text" name="nombre" id="nombre" class="form-control form-control-lg @error('nombre') is-invalid @enderror"
                       value="{{ old('nombre', $categoria?->nombre) }}" maxlength="80" placeholder="Ej: Novela">
                @error('nombre')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label for="estado" class="form-label fw-bold text-dark"><i class="fas fa-toggle-on fa-fw text-primary me-1"></i>Estado <span class="text-danger">*</span></label>
                <div class="input-group input-group-lg has-validation">
                    <span class="input-group-text bg-white"><i class="fas fa-circle small" id="estado-punto"></i></span>
                    <select name="estado" id="estado" class="form-select @error('estado') is-invalid @enderror">
                        <option value="1" {{ (string) old('estado', $categoria?->estado ?? '1') === '1' ? 'selected' : '' }}>Activa</option>
                        <option value="0" {{ (string) old('estado', $categoria?->estado ?? '1') === '0' ? 'selected' : '' }}>Inactiva</option>
                    </select>
                    @error('estado')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="col-12">
                <label for="descripcion" class="form-label fw-bold text-dark"><i class="fas fa-align-left fa-fw text-primary me-1"></i>Descripción</label>
                <textarea name="descripcion" id="descripcion" rows="3" class="form-control form-control-lg @error('descripcion') is-invalid @enderror" maxlength="255" placeholder="Breve descripción de la categoría">{{ old('descripcion', $categoria?->descripcion) }}</textarea>
                @error('descripcion')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>
    </div>

    {{-- ========== BOTONES ========== --}}
    <div class="card-footer bg-light border-0 p-4 d-flex justify-content-end gap-2">
        <a href="{{ route('categorias.index') }}" class="btn btn-outline-secondary btn-lg rounded-pill px-4">
            <i class="fas fa-arrow-left me-2"></i>Cancelar
        </a>
        <button type="submit" class="btn btn-primary btn-lg rounded-pill px-4 shadow">
            <i class="fas fa-save me-2"></i>Guardar Categoría
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
