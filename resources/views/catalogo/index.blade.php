{{-- resources/views/catalogo/index.blade.php  (archivo NUEVO)
     Catálogo completo de la tienda: filtros a la izquierda, productos a la derecha. --}}
@extends('layouts.tienda')

@section('titulo', 'Catálogo')

@section('content')

    {{-- ========== ENCABEZADO ========== --}}
    <header class="bg-primary-subtle bg-gradient border-bottom border-primary border-opacity-25">
        @include('catalogo._barra-utiles')

        <div class="container py-4">
            <nav aria-label="Ruta">
                <ol class="breadcrumb small mb-2">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}" class="link-warning text-decoration-none"><i class="fas fa-house me-1"></i>Inicio</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Catálogo</li>
                </ol>
            </nav>
            <div class="d-flex flex-wrap justify-content-between align-items-end gap-3">
                <div>
                    <h1 class="fw-bold mb-1"><i class="fas fa-book-open-reader text-warning me-2"></i>Catálogo</h1>
                    <p class="text-white-50 mb-0">Libros, textos universitarios, útiles y papelería.</p>
                </div>
                <span class="badge rounded-pill bg-black bg-opacity-50 border border-warning text-warning fs-6 px-3 py-2">
                    {{ $productos->total() }} producto(s)
                </span>
            </div>
        </div>
    </header>

    <section class="bg-black py-5">
        <div class="container">
            <div class="row g-4">

                {{-- =============================================================
                     FILTROS
                ============================================================== --}}
                <aside class="col-lg-3">
                    {{-- En celular los filtros se abren con este botón --}}
                    <button class="btn btn-outline-warning rounded-pill w-100 mb-3 d-lg-none" type="button"
                            data-bs-toggle="collapse" data-bs-target="#panelFiltros" aria-expanded="false" aria-controls="panelFiltros">
                        <i class="fas fa-sliders me-2"></i>Filtros
                    </button>

                    <div class="collapse d-lg-block" id="panelFiltros">
                        <form id="filtros" action="{{ route('catalogo') }}" method="GET"
                              class="bg-primary-subtle border border-primary border-opacity-25 rounded-4 p-4">

                            {{-- Buscar --}}
                            <label for="q" class="form-label fw-bold small text-uppercase text-warning"><i class="fas fa-magnifying-glass me-1"></i>Buscar</label>
                            <input type="text" name="q" id="q" value="{{ $filtros['q'] }}" class="form-control bg-black border-warning border-opacity-50 mb-4"
                                   placeholder="Título, autor, editorial…">

                            {{-- Tipo --}}
                            <span class="form-label d-block fw-bold small text-uppercase text-warning"><i class="fas fa-layer-group me-1"></i>Tipo</span>
                            <div class="btn-group w-100 mb-4" role="group" aria-label="Tipo de producto">
                                @foreach (['' => 'Todo', 'libros' => 'Libros', 'utiles' => 'Útiles'] as $valor => $texto)
                                    <input type="radio" class="btn-check" name="tipo" id="tipo{{ $valor ?: 'todo' }}" value="{{ $valor }}" autocomplete="off"
                                           {{ $filtros['tipo'] === $valor ? 'checked' : '' }}>
                                    <label class="btn btn-sm btn-outline-warning" for="tipo{{ $valor ?: 'todo' }}">{{ $texto }}</label>
                                @endforeach
                            </div>

                            {{-- Categorías --}}
                            <span class="form-label d-block fw-bold small text-uppercase text-warning"><i class="fas fa-tags me-1"></i>Categoría</span>
                            <div class="list-group list-group-flush rounded-3 border border-primary border-opacity-25 mb-4">
                                <label class="list-group-item list-group-item-action bg-black bg-opacity-50 d-flex justify-content-between align-items-center small">
                                    <span><input class="form-check-input me-2" type="radio" name="categoria" value="0" {{ ! $filtros['categoria'] ? 'checked' : '' }}>Todas</span>
                                </label>
                                @foreach ($categorias as $cat)
                                    @php [$colorCat, $iconoCat] = $estilo($cat->nombre); @endphp
                                    <label class="list-group-item list-group-item-action bg-black bg-opacity-50 d-flex justify-content-between align-items-center small {{ $cat->productos_count ? '' : 'opacity-50' }}">
                                        <span>
                                            <input class="form-check-input me-2" type="radio" name="categoria" value="{{ $cat->id }}"
                                                   {{ $filtros['categoria'] === $cat->id ? 'checked' : '' }} {{ $cat->productos_count ? '' : 'disabled' }}>
                                            <i class="fas {{ $iconoCat }} fa-fw text-{{ $colorCat }} me-1"></i>{{ $cat->nombre }}
                                        </span>
                                        <span class="badge rounded-pill bg-{{ $colorCat }}-subtle text-{{ $colorCat }}-emphasis">{{ $cat->productos_count }}</span>
                                    </label>
                                @endforeach
                            </div>

                            {{-- Autor --}}
                            <label for="autor" class="form-label fw-bold small text-uppercase text-warning"><i class="fas fa-feather-pointed me-1"></i>Autor</label>
                            <select name="autor" id="autor" class="form-select bg-black border-warning border-opacity-50 mb-4">
                                <option value="0">Todos los autores</option>
                                @foreach ($autores as $a)
                                    <option value="{{ $a->id }}" {{ $filtros['autor'] === $a->id ? 'selected' : '' }}>{{ $a->nombres }} {{ $a->apellidos }}</option>
                                @endforeach
                            </select>

                            {{-- Precio --}}
                            <span class="form-label d-block fw-bold small text-uppercase text-warning"><i class="fas fa-coins me-1"></i>Precio (S/)</span>
                            <div class="input-group input-group-sm mb-4">
                                <input type="number" name="min" min="0" step="0.5" value="{{ $filtros['min'] }}" class="form-control bg-black border-warning border-opacity-50" placeholder="Mín. 0" aria-label="Precio mínimo">
                                <span class="input-group-text bg-black border-warning border-opacity-50">a</span>
                                <input type="number" name="max" min="0" step="0.5" value="{{ $filtros['max'] }}" class="form-control bg-black border-warning border-opacity-50" placeholder="Máx. {{ ceil($precioMax) }}" aria-label="Precio máximo">
                            </div>

                            {{-- Solo con stock --}}
                            <div class="form-check form-switch mb-4">
                                <input class="form-check-input" type="checkbox" role="switch" name="stock" value="1" id="stock" {{ $filtros['stock'] ? 'checked' : '' }}>
                                <label class="form-check-label small" for="stock">Solo productos disponibles</label>
                            </div>

                            <input type="hidden" name="orden" value="{{ $filtros['orden'] }}">

                            <button type="submit" class="btn btn-warning w-100 rounded-pill fw-bold mb-2">
                                <i class="fas fa-filter me-2"></i>Aplicar filtros
                            </button>
                            @if ($hayFiltros)
                                <a href="{{ route('catalogo') }}" class="btn btn-outline-light btn-sm w-100 rounded-pill">
                                    <i class="fas fa-xmark me-1"></i>Limpiar filtros
                                </a>
                            @endif
                        </form>
                    </div>
                </aside>

                {{-- =============================================================
                     RESULTADOS
                ============================================================== --}}
                <div class="col-lg-9">

                    {{-- Barra: cantidad + orden --}}
                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3">
                        <span class="text-white-50 small">
                            @if ($productos->total())
                                Mostrando <strong class="text-white">{{ $productos->firstItem() }}–{{ $productos->lastItem() }}</strong>
                                de <strong class="text-white">{{ $productos->total() }}</strong> producto(s)
                            @endif
                        </span>
                        <div class="d-flex align-items-center gap-2">
                            <label for="orden" class="small text-white-50 text-nowrap"><i class="fas fa-arrow-down-wide-short me-1"></i>Ordenar:</label>
                            <select id="orden" class="form-select form-select-sm bg-black border-warning border-opacity-50 w-auto">
                                @foreach (['recientes' => 'Más recientes', 'precio_asc' => 'Precio: menor a mayor', 'precio_desc' => 'Precio: mayor a menor', 'nombre' => 'Nombre (A-Z)'] as $valor => $texto)
                                    <option value="{{ $valor }}" {{ $filtros['orden'] === $valor ? 'selected' : '' }}>{{ $texto }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Filtros activos (se quitan con la X) --}}
                    @if ($hayFiltros)
                        @php
                            $activos = [];
                            if ($filtros['q'] !== '')    $activos[] = ['«' . $filtros['q'] . '»', 'q'];
                            if ($filtros['tipo'])        $activos[] = [$filtros['tipo'] === 'libros' ? 'Libros' : 'Útiles', 'tipo'];
                            if ($filtros['categoria'])   $activos[] = [$categorias->firstWhere('id', $filtros['categoria'])?->nombre ?? 'Categoría', 'categoria'];
                            if ($filtros['autor'])       { $au = $autores->firstWhere('id', $filtros['autor']); $activos[] = [$au ? $au->nombres . ' ' . $au->apellidos : 'Autor', 'autor']; }
                            if ($filtros['min'] !== null) $activos[] = ['Desde S/ ' . number_format($filtros['min'], 2), 'min'];
                            if ($filtros['max'] !== null) $activos[] = ['Hasta S/ ' . number_format($filtros['max'], 2), 'max'];
                            if ($filtros['stock'])       $activos[] = ['Solo disponibles', 'stock'];
                        @endphp
                        <div class="d-flex flex-wrap gap-2 mb-4">
                            @foreach ($activos as [$texto, $clave])
                                <a href="{{ request()->fullUrlWithoutQuery([$clave, 'page']) }}"
                                   class="badge rounded-pill bg-warning-subtle text-warning-emphasis border border-warning-subtle text-decoration-none px-3 py-2"
                                   data-bs-toggle="tooltip" data-bs-title="Quitar este filtro">
                                    {{ $texto }} <i class="fas fa-xmark ms-1"></i>
                                </a>
                            @endforeach
                        </div>
                    @endif

                    {{-- Productos --}}
                    <div class="row g-4">
                        @forelse ($productos as $p)
                            <div class="col-sm-6 col-xl-4">
                                @include('catalogo._tarjeta', ['p' => $p])
                            </div>
                        @empty
                            <div class="col-12">
                                <div class="text-center bg-primary-subtle border border-primary border-opacity-25 rounded-4 py-5 px-3">
                                    <i class="fas fa-book-open fa-3x text-warning mb-3"></i>
                                    <h5 class="fw-bold">No encontramos productos con esos filtros</h5>
                                    <p class="text-white-50 mb-3">Prueba quitando algún filtro o buscando otra palabra.</p>
                                    <a href="{{ route('catalogo') }}" class="btn btn-warning rounded-pill px-4">Ver todo el catálogo</a>
                                </div>
                            </div>
                        @endforelse
                    </div>

                    {{-- Paginación --}}
                    @if ($productos->hasPages())
                        <div class="d-flex justify-content-center mt-5">
                            {{ $productos->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form   = document.getElementById('filtros');
        const buscar = document.getElementById('q');
        const orden  = document.getElementById('orden');

        // 1) Ordenar: se envía con el nuevo orden
        orden.addEventListener('change', function () {
            form.querySelector('input[name="orden"]').value = this.value;
            form.submit();
        });

        // 2) Buscar mientras escribes: espera medio segundo sin teclear y busca
        let espera;
        buscar.addEventListener('input', function () {
            clearTimeout(espera);
            const texto = this.value.trim();
            if (texto.length === 0 || texto.length >= 2) {
                espera = setTimeout(function () { form.submit(); }, 600);
            }
        });

        // 3) Tipo, categoría y "solo disponibles": filtran al hacer clic
        form.querySelectorAll('input[type="radio"], input[type="checkbox"], select').forEach(function (campo) {
            campo.addEventListener('change', function () { form.submit(); });
        });

        // 4) Después de buscar, el cursor vuelve al final del texto
        if (buscar.value) {
            buscar.focus();
            buscar.setSelectionRange(buscar.value.length, buscar.value.length);
        }
    });
</script>

@endpush
