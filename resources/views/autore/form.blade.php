{{-- resources/views/autore/form.blade.php  (reemplaza TODO el archivo) --}}
{{-- Solo clases de Bootstrap 5 + iconos FontAwesome (cargados en layouts/app.blade.php) --}}

<div class="card border-0 shadow rounded-4 overflow-hidden">

    {{-- ========== ENCABEZADO ========== --}}
    <div class="card-header bg-primary bg-gradient text-white border-0 p-4 position-relative overflow-hidden">
        <div class="d-flex align-items-center gap-3 position-relative">
            <div class="bg-white bg-opacity-25 rounded-3 shadow-sm p-3 lh-1">
                <i class="fas fa-feather-alt fa-2x fa-fw"></i>
            </div>
            <div class="vr opacity-50"></div>
            <div>
                <h3 class="fw-bold mb-1">{{ $autore->exists ? 'Editar' : 'Crear' }} Autor</h3>
                <p class="mb-0 opacity-75">{{ $autore->exists ? 'Actualiza los datos del autor' : 'Registra un nuevo autor de libros' }}</p>
            </div>
        </div>
        <i class="fas fa-feather-alt fa-6x opacity-25 position-absolute top-50 end-0 translate-middle-y me-5 d-none d-md-block"></i>
    </div>

    {{-- ========== CAMPOS ========== --}}
    <div class="card-body p-4">
        <div class="alert alert-primary border-0 rounded-3 d-flex align-items-center gap-2 py-2 mb-4">
            <i class="fas fa-info-circle fs-5"></i>
            <span>Los campos con <span class="text-danger fw-bold">*</span> son obligatorios.</span>
        </div>

        <div class="row g-4">
            <div class="col-md-6">
                <label for="nombres" class="form-label fw-bold text-dark"><i class="fas fa-user fa-fw text-primary me-1"></i>Nombres <span class="text-danger">*</span></label>
                <input type="text" name="nombres" id="nombres" class="form-control form-control-lg @error('nombres') is-invalid @enderror"
                       value="{{ old('nombres', $autore?->nombres) }}" maxlength="100" placeholder="Ej: Mario">
                @error('nombres')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label for="apellidos" class="form-label fw-bold text-dark"><i class="fas fa-user-tag fa-fw text-primary me-1"></i>Apellidos <span class="text-danger">*</span></label>
                <input type="text" name="apellidos" id="apellidos" class="form-control form-control-lg @error('apellidos') is-invalid @enderror"
                       value="{{ old('apellidos', $autore?->apellidos) }}" maxlength="100" placeholder="Ej: Vargas Llosa">
                @error('apellidos')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label for="nacionalidad" class="form-label fw-bold text-dark"><i class="fas fa-flag fa-fw text-primary me-1"></i>Nacionalidad</label>
                <input type="text" name="nacionalidad" id="nacionalidad" class="form-control form-control-lg @error('nacionalidad') is-invalid @enderror"
                       value="{{ old('nacionalidad', $autore?->nacionalidad) }}" maxlength="60" list="lista-nacionalidades" placeholder="Ej: Peruana">
                @error('nacionalidad')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label for="fecha_nacimiento" class="form-label fw-bold text-dark"><i class="fas fa-birthday-cake fa-fw text-primary me-1"></i>Fecha de nacimiento</label>
                <input type="date" name="fecha_nacimiento" id="fecha_nacimiento" class="form-control form-control-lg @error('fecha_nacimiento') is-invalid @enderror"
                       value="{{ old('fecha_nacimiento', $autore?->fecha_nacimiento) }}" max="{{ now()->subDay()->format('Y-m-d') }}">
                @error('fecha_nacimiento')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>
    </div>

    {{-- ========== BOTONES ========== --}}
    <div class="card-footer bg-light border-0 p-4 d-flex justify-content-end gap-2">
        <a href="{{ route('autores.index') }}" class="btn btn-outline-secondary btn-lg rounded-pill px-4">
            <i class="fas fa-arrow-left me-2"></i>Cancelar
        </a>
        <button type="submit" class="btn btn-primary btn-lg rounded-pill px-4 shadow">
            <i class="fas fa-save me-2"></i>Guardar Autor
        </button>
    </div>
</div>

<datalist id="lista-nacionalidades">
    <option value="Peruana"><option value="Colombiana"><option value="Chilena">
    <option value="Argentina"><option value="Mexicana"><option value="Española">
    <option value="Estadounidense">
</datalist>
