@extends('layouts.app')

@section('content')
    <div class="container py-3">
        <form method="POST" action="{{ route('autores.update', $autore->id) }}" role="form" enctype="multipart/form-data">
            @csrf
            @method('PATCH')
            @include('autore.form')
        </form>
    </div>
@endsection
