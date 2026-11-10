@extends('layouts.app')

@section('content')
    <div class="container py-3">
        <form method="POST" action="{{ route('pagos.update', $pago->id) }}" role="form" enctype="multipart/form-data">
            @csrf
            @method('PATCH')
            @include('pago.form')
        </form>
    </div>
@endsection
