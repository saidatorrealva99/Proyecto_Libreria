@extends('layouts.app')

@section('content')
    <div class="container py-3">
        <form method="POST" action="{{ route('ventas.store') }}" role="form" enctype="multipart/form-data">
            @csrf
            @include('venta.form')
        </form>
    </div>
@endsection
