{{-- resources/views/catalogo/_tarjeta.blade.php  (archivo NUEVO)
     Tarjeta de un producto. Se usa en la portada y en el catálogo:
         @include('catalogo._tarjeta', ['p' => $producto])
     Opcional: 'nuevo' => true  muestra la etiqueta NUEVO --}}
@php
    $esLibro = ! is_null($p->autor_id);
    [$color, $icono] = $estilo($p->categoria?->nombre);
    $enlace = route('producto.ver', $p->codigo);
@endphp

<div class="card h-100 bg-primary-subtle border border-{{ $color }} border-opacity-25 rounded-4 overflow-hidden shadow">

    {{-- Foto o ícono --}}
    <a href="{{ $enlace }}" class="d-block bg-{{ $color }} bg-opacity-25 text-center position-relative text-decoration-none {{ $p->imagen ? '' : 'py-5' }}">
        @if (! empty($nuevo))
            <span class="badge bg-warning text-dark position-absolute top-0 start-0 m-3">NUEVO</span>
        @endif
        <span class="badge rounded-pill bg-black bg-opacity-50 text-{{ $color }}-emphasis position-absolute top-0 end-0 m-3">
            {{ $p->categoria?->nombre }}
        </span>
        @if ($p->imagen)
            <img src="{{ $p->url_imagen }}" alt="{{ $p->nombre }}" height="260" class="w-100 object-fit-cover">
        @else
            <i class="fas {{ $icono }} fa-4x text-{{ $color }}-emphasis"></i>
        @endif
    </a>

    {{-- Datos --}}
    <div class="card-body p-4">
        <h5 class="fw-bold mb-1">
            <a href="{{ $enlace }}" class="link-light text-decoration-none">{{ $p->nombre }}</a>
        </h5>
        <p class="text-white-50 small mb-3">
            @if ($esLibro)
                <i class="fas fa-feather-pointed me-1"></i>{{ $p->autor?->nombres }} {{ $p->autor?->apellidos }}
                <span class="opacity-50">·</span> {{ $p->marca_editorial }}
            @else
                <i class="fas fa-tag me-1"></i>{{ $p->marca_editorial }}
                <span class="opacity-50">·</span> {{ $p->descripcion }}
            @endif
        </p>
        <div class="d-flex justify-content-between align-items-center">
            <span class="fs-3 fw-bold text-warning">S/ {{ number_format($p->precio_venta, 2) }}</span>
            @if ($p->stock > $p->stock_minimo)
                <span class="badge rounded-pill bg-success-subtle text-success-emphasis border border-success-subtle"><i class="fas fa-circle-check me-1"></i>Disponible</span>
            @elseif ($p->stock > 0)
                <span class="badge rounded-pill bg-warning-subtle text-warning-emphasis border border-warning-subtle"><i class="fas fa-hourglass-half me-1"></i>Últimas unidades</span>
            @else
                <span class="badge rounded-pill bg-danger-subtle text-danger-emphasis border border-danger-subtle"><i class="fas fa-circle-xmark me-1"></i>Agotado</span>
            @endif
        </div>
    </div>

    {{-- Acciones --}}
    <div class="card-footer bg-transparent border-top border-{{ $color }} border-opacity-25 d-flex gap-2 p-3">
        @if ($p->stock > 0)
            <form action="{{ route('carrito.agregar', $p->id) }}" method="POST" class="flex-grow-1">
                @csrf
                <input type="hidden" name="cantidad" value="1">
                <button type="submit" class="btn btn-warning rounded-pill w-100 fw-semibold" data-animar="fa-bounce">
                    <i class="fas fa-cart-plus me-1"></i>Agregar
                </button>
            </form>
        @else
            <button type="button" class="btn btn-outline-secondary rounded-pill flex-grow-1" disabled>
                <i class="fas fa-ban me-1"></i>Agotado
            </button>
        @endif
        <a href="{{ $enlace }}" class="btn btn-outline-warning rounded-pill px-3" data-animar="fa-beat"
           data-bs-toggle="tooltip" data-bs-title="Ver producto">
            <i class="fas fa-eye"></i>
        </a>
        <a href="{{ $wa('Hola, me interesa: ' . $p->nombre . ' (' . $p->codigo . ') a S/ ' . number_format($p->precio_venta, 2) . '. ¿Está disponible?') }}"
           target="_blank" rel="noopener" class="btn btn-success rounded-pill px-3" data-animar="fa-beat"
           data-bs-toggle="tooltip" data-bs-title="Consultar por WhatsApp">
            <i class="fas fa-comment-dots"></i>
        </a>
    </div>
</div>
