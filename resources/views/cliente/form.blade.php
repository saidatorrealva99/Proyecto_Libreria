{{-- resources/views/cliente/form.blade.php  (reemplaza TODO el archivo) --}}
{{-- Solo clases de Bootstrap 5 + iconos FontAwesome (cargados en layouts/app.blade.php) --}}

<div class="card border-0 shadow rounded-4 overflow-hidden">

    {{-- ========== ENCABEZADO ========== --}}
    <div class="card-header bg-primary bg-gradient text-white border-0 p-4 position-relative overflow-hidden">
        <div class="d-flex align-items-center gap-3 position-relative">
            <div class="bg-white bg-opacity-25 rounded-3 shadow-sm p-3 lh-1">
                <i class="fas fa-user-plus fa-2x fa-fw"></i>
            </div>
            <div class="vr opacity-50"></div>
            <div>
                <h3 class="fw-bold mb-1">{{ $cliente->exists ? 'Editar' : 'Crear' }} Cliente</h3>
                <p class="mb-0 opacity-75">{{ $cliente->exists ? 'Actualiza los datos del cliente' : 'Registra un nuevo comprador (persona o empresa)' }}</p>
            </div>
        </div>
        <i class="fas fa-user-plus fa-6x opacity-25 position-absolute top-50 end-0 translate-middle-y me-5 d-none d-md-block"></i>
    </div>

    {{-- ========== CAMPOS ========== --}}
    <div class="card-body p-4">
        <div class="alert alert-primary border-0 rounded-3 d-flex align-items-center gap-2 py-2 mb-4">
            <i class="fas fa-info-circle fs-5"></i>
            <span>Los campos con <span class="text-danger fw-bold">*</span> son obligatorios.</span>
        </div>

        <div class="row g-4">
            <div class="col-12">
                <h6 class="text-primary fw-bold text-uppercase small border-bottom border-primary-subtle pb-2 mb-0"><i class="fas fa-id-card me-2"></i>Documento de identidad</h6>
            </div>
            <div class="col-md-4">
                <label for="tipo_documento" class="form-label fw-bold text-dark"><i class="fas fa-id-card fa-fw text-primary me-1"></i>Tipo de documento <span class="text-danger">*</span></label>
                <select name="tipo_documento" id="tipo_documento" class="form-select form-select-lg @error('tipo_documento') is-invalid @enderror">
                    <option value="DNI" {{ (string) old('tipo_documento', $cliente?->tipo_documento ?? 'DNI') === 'DNI' ? 'selected' : '' }}>DNI</option>
                    <option value="RUC" {{ (string) old('tipo_documento', $cliente?->tipo_documento ?? 'DNI') === 'RUC' ? 'selected' : '' }}>RUC</option>
                    <option value="CE" {{ (string) old('tipo_documento', $cliente?->tipo_documento ?? 'DNI') === 'CE' ? 'selected' : '' }}>Carné de extranjería</option>
                </select>
                @error('tipo_documento')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-8">
                <label for="numero_documento" class="form-label fw-bold text-dark"><i class="fas fa-hashtag fa-fw text-primary me-1"></i>Número de documento <span class="text-danger">*</span></label>
                <input type="text" name="numero_documento" id="numero_documento" class="form-control form-control-lg @error('numero_documento') is-invalid @enderror"
                       value="{{ old('numero_documento', $cliente?->numero_documento) }}" maxlength="12" inputmode="numeric" placeholder="8 dígitos (DNI)">
                @error('numero_documento')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-12">
                <h6 class="text-primary fw-bold text-uppercase small border-bottom border-primary-subtle pb-2 mb-0"><i class="fas fa-user me-2"></i>Datos personales / Razón social</h6>
            </div>
            <div class="col-md-6">
                <label for="nombres" class="form-label fw-bold text-dark"><i class="fas fa-user fa-fw text-primary me-1"></i>Nombres o razón social <span class="text-danger">*</span></label>
                <input type="text" name="nombres" id="nombres" class="form-control form-control-lg @error('nombres') is-invalid @enderror"
                       value="{{ old('nombres', $cliente?->nombres) }}" maxlength="100">
                @error('nombres')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label for="apellidos" class="form-label fw-bold text-dark"><i class="fas fa-user-tag fa-fw text-primary me-1"></i>Apellidos</label>
                <input type="text" name="apellidos" id="apellidos" class="form-control form-control-lg @error('apellidos') is-invalid @enderror"
                       value="{{ old('apellidos', $cliente?->apellidos) }}" maxlength="100" placeholder="No aplica para RUC">
                @error('apellidos')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-12">
                <h6 class="text-primary fw-bold text-uppercase small border-bottom border-primary-subtle pb-2 mb-0"><i class="fas fa-address-book me-2"></i>Contacto</h6>
            </div>
            <div class="col-md-6">
                <label for="email" class="form-label fw-bold text-dark"><i class="fas fa-envelope fa-fw text-primary me-1"></i>Correo electrónico</label>
                <input type="email" name="email" id="email" class="form-control form-control-lg @error('email') is-invalid @enderror"
                       value="{{ old('email', $cliente?->email) }}" maxlength="120" placeholder="correo@ejemplo.com">
                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label for="telefono" class="form-label fw-bold text-dark"><i class="fas fa-phone fa-fw text-primary me-1"></i>Teléfono</label>
                <input type="tel" name="telefono" id="telefono" class="form-control form-control-lg @error('telefono') is-invalid @enderror"
                       value="{{ old('telefono', $cliente?->telefono) }}" maxlength="9" inputmode="numeric" placeholder="987654321">
                @error('telefono')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-12">
                <label for="direccion" class="form-label fw-bold text-dark"><i class="fas fa-map-marker-alt fa-fw text-primary me-1"></i>Dirección</label>
                <input type="text" name="direccion" id="direccion" class="form-control form-control-lg @error('direccion') is-invalid @enderror"
                       value="{{ old('direccion', $cliente?->direccion) }}" maxlength="200" placeholder="Av. / Jr. / Calle, distrito">
                @error('direccion')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>
    </div>

    {{-- ========== BOTONES ========== --}}
    <div class="card-footer bg-light border-0 p-4 d-flex justify-content-end gap-2">
        <a href="{{ route('clientes.index') }}" class="btn btn-outline-secondary btn-lg rounded-pill px-4">
            <i class="fas fa-arrow-left me-2"></i>Cancelar
        </a>
        <button type="submit" class="btn btn-primary btn-lg rounded-pill px-4 shadow">
            <i class="fas fa-save me-2"></i>Guardar Cliente
        </button>
    </div>
</div>

<script>
    // Ajusta el campo número según el tipo de documento
    (function () {
        const tipo = document.getElementById('tipo_documento');
        const num  = document.getElementById('numero_documento');
        const ape  = document.getElementById('apellidos');
        const reglas = {
            DNI: { max: 8,  ph: '8 dígitos (DNI)' },
            RUC: { max: 11, ph: '11 dígitos (RUC)' },
            CE:  { max: 12, ph: '9 a 12 dígitos (CE)' },
        };
        function aplicar() {
            const r = reglas[tipo.value] || reglas.DNI;
            num.maxLength = r.max;
            num.placeholder = r.ph;
            ape.disabled = (tipo.value === 'RUC');
            if (ape.disabled) ape.value = '';
        }
        tipo.addEventListener('change', aplicar);
        aplicar();
    })();
</script>
