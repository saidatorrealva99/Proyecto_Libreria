@extends('layouts.app')

@section('content')
    <div class="container py-3">
        <form method="POST" action="{{ route('productos.update', $producto->id) }}" role="form" enctype="multipart/form-data">
            @csrf
            @method('PATCH')
            @include('producto.form')
        </form>
    </div>
@endsection
