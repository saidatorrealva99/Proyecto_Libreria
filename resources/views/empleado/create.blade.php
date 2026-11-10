@extends('layouts.app')

@section('content')
    <div class="container py-3">
        <form method="POST" action="{{ route('empleados.store') }}" role="form" enctype="multipart/form-data">
            @csrf
            @include('empleado.form')
        </form>
    </div>
@endsection
