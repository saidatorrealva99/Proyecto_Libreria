@extends('layouts.app')

@section('content')
    <div class="container py-3">
        <form method="POST" action="{{ route('empleados.update', $empleado->id) }}" role="form" enctype="multipart/form-data">
            @csrf
            @method('PATCH')
            @include('empleado.form')
        </form>
    </div>
@endsection
