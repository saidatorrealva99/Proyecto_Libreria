@extends('layouts.app')

@section('content')
    <div class="container py-3">
        <form method="POST" action="{{ route('categorias.store') }}" role="form" enctype="multipart/form-data">
            @csrf
            @include('categoria.form')
        </form>
    </div>
@endsection
