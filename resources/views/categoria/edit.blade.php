@extends('layouts.app')

@section('content')
    <div class="container py-3">
        <form method="POST" action="{{ route('categorias.update', $categoria->id) }}" role="form" enctype="multipart/form-data">
            @csrf
            @method('PATCH')
            @include('categoria.form')
        </form>
    </div>
@endsection
