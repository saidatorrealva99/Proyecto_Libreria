@extends('layouts.app')

@section('content')
    <div class="container py-3">
        <form method="POST" action="{{ route('clientes.store') }}" role="form" enctype="multipart/form-data">
            @csrf
            @include('cliente.form')
        </form>
    </div>
@endsection
