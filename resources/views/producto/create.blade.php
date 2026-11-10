@extends('layouts.app')

@section('content')
    <div class="container py-3">
        <form method="POST" action="{{ route('productos.store') }}" role="form" enctype="multipart/form-data">
            @csrf
            @include('producto.form')
        </form>
    </div>
@endsection
