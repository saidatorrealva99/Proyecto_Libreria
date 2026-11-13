{{-- resources/views/catalogo/_qr.blade.php  (archivo NUEVO)
     Muestra el código QR de Yape o Plin.
         @include('catalogo._qr', ['archivo' => $datos['yape_qr'], 'nombre' => 'Yape', 'color' => 'info'])
     Si la imagen todavía no está en la carpeta public, muestra un aviso. --}}
@if ($archivo && file_exists(public_path($archivo)))
    <a href="{{ asset($archivo) }}" target="_blank" rel="noopener" class="d-inline-block"
       data-bs-toggle="tooltip" data-bs-title="Ver el QR en grande">
        <img src="{{ asset($archivo) }}" alt="Código QR de {{ $nombre }}" width="170" height="170"
             class="bg-white rounded-3 border border-3 border-{{ $color }} shadow p-1 object-fit-contain">
    </a>
    <div class="small text-white-50 mt-2"><i class="fas fa-qrcode me-1"></i>Escanea con la app de {{ $nombre }}</div>
@else
    <div class="d-inline-flex flex-column align-items-center justify-content-center bg-black bg-opacity-50 border border-{{ $color }} border-opacity-50 rounded-3 px-4 py-4">
        <i class="fas fa-qrcode fa-3x text-{{ $color }} mb-2 opacity-50"></i>
        <small class="text-white-50">QR de {{ $nombre }} próximamente</small>
    </div>
@endif
