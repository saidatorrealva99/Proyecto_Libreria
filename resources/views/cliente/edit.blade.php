@extends('layouts.app')

@section('content')
    <div class="container py-3">
        <form method="POST" action="{{ route('clientes.update', $cliente->id) }}" role="form" enctype="multipart/form-data">
            @csrf
            @method('PATCH')
            @include('cliente.form')
        </form>
    </div>
@endsection

