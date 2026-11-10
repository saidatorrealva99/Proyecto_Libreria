@extends('layouts.app')

@section('content')
    <div class="container py-3">
        <form method="POST" action="{{ route('pagos.store') }}" role="form" enctype="multipart/form-data">
            @csrf
            @include('pago.form')
        </form>
    </div>
@endsection

