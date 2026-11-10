@extends('layouts.app')

@section('content')
    <div class="container py-3">
        <form method="POST" action="{{ route('detalle-ventas.update', $detalleVenta->id) }}" role="form" enctype="multipart/form-data">
            @csrf
            @method('PATCH')
            @include('detalle-venta.form')
        </form>
    </div>
@endsection
