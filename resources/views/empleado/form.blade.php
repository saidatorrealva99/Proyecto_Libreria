{{-- resources/views/empleado/form.blade.php  (reemplaza TODO el archivo) --}}
{{-- Solo clases de Bootstrap 5 + iconos FontAwesome (cargados en layouts/app.blade.php) --}}

<div class="card border-0 shadow rounded-4 overflow-hidden">

    {{-- ========== ENCABEZADO ========== --}}
    <div class="card-header bg-primary bg-gradient text-white border-0 p-4 position-relative overflow-hidden">
        <div class="d-flex align-items-center gap-3 position-relative">
            <div class="bg-white bg-opacity-25 rounded-3 shadow-sm p-3 lh-1">
                <i class="fas fa-id-badge fa-2x fa-fw"></i>
            </div>
            <div class="vr opacity-50"></div>
            <div>
                <h3 class="fw-bold mb-1">{{ $empleado->exists ? 'Editar' : 'Crear' }} Empleado</h3>
                <p class="mb-0 opacity-75">{{ $empleado->exists ? 'Actualiza los datos del empleado' : 'Registra un nuevo trabajador de la librería' }}</p>
            </div>
        </div>
        <i class="fas fa-id-badge fa-6x opacity-25 position-absolute top-50 end-0 translate-middle-y me-5 d-none d-md-block"></i>
    </div>

    {{-- ========== CAMPOS ========== --}}
    <div class="card-body p-4">
        <div class="alert alert-primary border-0 rounded-3 d-flex align-items-center gap-2 py-2 mb-4">
            <i class="fas fa-info-circle fs-5"></i>
            <span>Los campos con <span class="text-danger fw-bold">*</span> son obligatorios.</span>
        </div>

        <div class="row g-4">
            <div class="col-12">
                <h6 class="text-primary fw-bold text-uppercase small border-bottom border-primary-subtle pb-2 mb-0"><i class="fas fa-user me-2"></i>Datos personales</h6>
            </div>
            <div class="col-md-4">
                <label for="dni" class="form-label fw-bold text-dark"><i class="fas fa-id-card fa-fw text-primary me-1"></i>DNI <span class="text-danger">*</span></label>
                <input type="text" name="dni" id="dni" class="form-control form-control-lg @error('dni') is-invalid @enderror"
                       value="{{ old('dni', $empleado?->dni) }}" maxlength="8" inputmode="numeric" placeholder="8 dígitos">
                @error('dni')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label for="nombres" class="form-label fw-bold text-dark"><i class="fas fa-user fa-fw text-primary me-1"></i>Nombres <span class="text-danger">*</span></label>
                <input type="text" name="nombres" id="nombres" class="form-control form-control-lg @error('nombres') is-invalid @enderror"
                       value="{{ old('nombres', $empleado?->nombres) }}" maxlength="100">
                @error('nombres')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label for="apellidos" class="form-label fw-bold text-dark"><i class="fas fa-user-tag fa-fw text-primary me-1"></i>Apellidos <span class="text-danger">*</span></label>
                <input type="text" name="apellidos" id="apellidos" class="form-control form-control-lg @error('apellidos') is-invalid @enderror"
                       value="{{ old('apellidos', $empleado?->apellidos) }}" maxlength="100">
                @error('apellidos')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label for="email" class="form-label fw-bold text-dark"><i class="fas fa-envelope fa-fw text-primary me-1"></i>Correo electrónico <span class="text-danger">*</span></label>
                <input type="email" name="email" id="email" class="form-control form-control-lg @error('email') is-invalid @enderror"
                       value="{{ old('email', $empleado?->email) }}" maxlength="120" placeholder="nombre@libreria.pe">
                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label for="telefono" class="form-label fw-bold text-dark"><i class="fas fa-mobile-alt fa-fw text-primary me-1"></i>Celular</label>
                <input type="tel" name="telefono" id="telefono" class="form-control form-control-lg @error('telefono') is-invalid @enderror"
                       value="{{ old('telefono', $empleado?->telefono) }}" maxlength="9" inputmode="numeric" placeholder="9XXXXXXXX">
                @error('telefono')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-12">
                <h6 class="text-primary fw-bold text-uppercase small border-bottom border-primary-subtle pb-2 mb-0"><i class="fas fa-briefcase me-2"></i>Datos laborales</h6>
            </div>
            <div class="col-md-3">
                <label for="cargo" class="form-label fw-bold text-dark"><i class="fas fa-briefcase fa-fw text-primary me-1"></i>Cargo <span class="text-danger">*</span></label>
                <select name="cargo" id="cargo" class="form-select form-select-lg @error('cargo') is-invalid @enderror">
                    <option value="administrador" {{ (string) old('cargo', $empleado?->cargo ?? 'vendedor') === 'administrador' ? 'selected' : '' }}>Administrador</option>
                    <option value="vendedor" {{ (string) old('cargo', $empleado?->cargo ?? 'vendedor') === 'vendedor' ? 'selected' : '' }}>Vendedor</option>
                    <option value="cajero" {{ (string) old('cargo', $empleado?->cargo ?? 'vendedor') === 'cajero' ? 'selected' : '' }}>Cajero</option>
                    <option value="almacenero" {{ (string) old('cargo', $empleado?->cargo ?? 'vendedor') === 'almacenero' ? 'selected' : '' }}>Almacenero</option>
                </select>
                @error('cargo')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-3">
                <label for="sueldo" class="form-label fw-bold text-dark"><i class="fas fa-money-bill-wave fa-fw text-primary me-1"></i>Sueldo (S/) <span class="text-danger">*</span></label>
                <input type="number" name="sueldo" id="sueldo" class="form-control form-control-lg @error('sueldo') is-invalid @enderror"
                       value="{{ old('sueldo', $empleado?->sueldo) }}" step="0.01" min="0.01" placeholder="0.00">
                @error('sueldo')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-3">
                <label for="fecha_contratacion" class="form-label fw-bold text-dark"><i class="fas fa-calendar-check fa-fw text-primary me-1"></i>Fecha de contratación <span class="text-danger">*</span></label>
                <input type="date" name="fecha_contratacion" id="fecha_contratacion" class="form-control form-control-lg @error('fecha_contratacion') is-invalid @enderror"
                       value="{{ old('fecha_contratacion', $empleado?->fecha_contratacion) }}" max="{{ now()->format('Y-m-d') }}">
                @error('fecha_contratacion')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-3">
                <label for="estado" class="form-label fw-bold text-dark"><i class="fas fa-toggle-on fa-fw text-primary me-1"></i>Estado <span class="text-danger">*</span></label>
                <div class="input-group input-group-lg has-validation">
                    <span class="input-group-text bg-white"><i class="fas fa-circle small" id="estado-punto"></i></span>
                    <select name="estado" id="estado" class="form-select @error('estado') is-invalid @enderror">
                        <option value="1" {{ (string) old('estado', $empleado?->estado ?? '1') === '1' ? 'selected' : '' }}>Activo</option>
                        <option value="0" {{ (string) old('estado', $empleado?->estado ?? '1') === '0' ? 'selected' : '' }}>Cesado</option>
                    </select>
                    @error('estado')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>
    </div>

    {{-- ========== BOTONES ========== --}}
    <div class="card-footer bg-light border-0 p-4 d-flex justify-content-end gap-2">
        <a href="{{ route('empleados.index') }}" class="btn btn-outline-secondary btn-lg rounded-pill px-4">
            <i class="fas fa-arrow-left me-2"></i>Cancelar
        </a>
        <button type="submit" class="btn btn-primary btn-lg rounded-pill px-4 shadow">
            <i class="fas fa-save me-2"></i>Guardar Empleado
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
