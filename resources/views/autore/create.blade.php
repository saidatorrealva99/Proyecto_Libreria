@extends('layouts.app')

@section('content')
    <div class="container py-3">
        <form method="POST" action="{{ route('autores.store') }}" role="form" enctype="multipart/form-data">
            @csrf
            @include('autore.form')
        </form>
    </div>
@endsection
