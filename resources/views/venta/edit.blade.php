@extends('layouts.app')

@section('content')
    <div class="container py-3">
        <form method="POST" action="{{ route('ventas.update', $venta->id) }}" role="form" enctype="multipart/form-data">
            @csrf
            @method('PATCH')
            @include('venta.form')
        </form>
    </div>
@endsection
