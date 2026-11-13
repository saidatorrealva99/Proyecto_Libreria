{{-- resources/views/catalogo/_barra-utiles.blade.php  (archivo NUEVO)
     Barra superior de útiles (accesos a categorías). Se usa en la portada y el catálogo.
     Si una categoría no tiene productos, el ícono se apaga y dice "Próximamente". --}}
<div class="bg-black bg-opacity-25 border-bottom border-primary border-opacity-25">
    <div class="container d-flex flex-wrap justify-content-around align-items-center gap-2 py-3">
        @foreach ($barraSuperior as [$icono, $color, $ayuda, $enlace])
            @if ($enlace)
                <a href="{{ $enlace }}" data-animar="fa-bounce"
                   data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="{{ $ayuda }}"
                   class="bg-{{ $color }} {{ $loop->first ? 'bg-opacity-25 border-warning shadow' : 'bg-opacity-10 border-opacity-25 border-' . $color }} border rounded-3 p-2 p-md-3 lh-1 text-decoration-none">
                    <i class="fas {{ $icono }} fa-xl fa-fw text-{{ $color }}"></i>
                </a>
            @else
                {{-- Categoría sin productos: apagada --}}
                <span tabindex="0" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="{{ $ayuda }}"
                      class="bg-secondary bg-opacity-10 border border-secondary border-opacity-25 rounded-3 p-2 p-md-3 lh-1 opacity-50">
                    <i class="fas {{ $icono }} fa-xl fa-fw text-secondary"></i>
                </span>
            @endif
        @endforeach
    </div>
</div>
