{{-- resources/views/cliente/index.blade.php  (reemplaza TODO el archivo) --}}
@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-3">

    <div class="card border-0 shadow rounded-4 overflow-hidden">

        {{-- ========== ENCABEZADO ========== --}}
        <div class="card-header bg-primary bg-gradient text-white border-0 p-4 position-relative overflow-hidden">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 position-relative">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-white bg-opacity-25 rounded-3 shadow-sm p-3 lh-1">
                        <i class="fas fa-users fa-2x fa-fw"></i>
                    </div>
                    <div class="vr opacity-50"></div>
                    <div>
                        <h3 class="fw-bold mb-1">Gestión de Clientes
                            <span class="badge bg-white text-primary rounded-pill fs-6 align-middle ms-1">{{ count($clientes) }}</span>
                        </h3>
                        <p class="mb-0 opacity-75">Personas y empresas que compran en la librería</p>
                    </div>
                </div>
                <a href="{{ route('clientes.create') }}" class="btn btn-light btn-lg rounded-pill px-4 fw-semibold text-primary shadow-sm">
                    <i class="fas fa-plus-circle me-2"></i>Nuevo Cliente
                </a>
            </div>
            <i class="fas fa-users fa-6x opacity-25 position-absolute top-50 end-0 translate-middle-y me-5 d-none d-xl-block"></i>
        </div>

        {{-- ========== MENSAJE DE ÉXITO ========== --}}
        @if ($message = Session::get('success'))
            <div class="alert alert-success border-0 rounded-0 d-flex align-items-center gap-2 mb-0">
                <i class="fas fa-check-circle fs-5"></i><span>{{ $message }}</span>
            </div>
        @endif
        @if ($message = Session::get('error'))
            <div class="alert alert-danger border-0 rounded-0 d-flex align-items-center gap-2 mb-0">
                <i class="fas fa-exclamation-circle fs-5"></i><span>{{ $message }}</span>
            </div>
        @endif

        {{-- ========== TABLA ========== --}}
        <div class="card-body bg-white p-4">
            <div class="table-responsive">
                <table id="example" data-titulo="Gestión de Clientes" class="table table-hover align-middle w-100">
                    <thead class="table-light text-uppercase small text-secondary">
                        <tr>
                            <th class="ps-4">#</th>
                            <th>Documento</th>
                            <th>Cliente / Razón social</th>
                            <th>Correo</th>
                            <th>Teléfono</th>
                            <th>Dirección</th>
                            <th class="text-center">Compras</th>
                            <th class="text-end pe-4">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($clientes as $cliente)
                        <tr>
                            <td class="ps-4 text-muted">{{ ++$i }}</td>
                            <td><span class="badge {{ $cliente->tipo_documento === 'RUC' ? 'bg-dark' : 'bg-secondary' }} me-1">{{ $cliente->tipo_documento }}</span><span class="font-monospace">{{ $cliente->numero_documento }}</span></td>
                            <td class="fw-semibold text-dark"><i class="fas {{ $cliente->tipo_documento === 'RUC' ? 'fa-building' : 'fa-user' }} text-primary me-2"></i>{{ $cliente->nombres }} {{ $cliente->apellidos }}</td>
                            <td>@if ($cliente->email)<i class="fas fa-envelope text-muted me-1"></i>{{ $cliente->email }}@else<span class="text-muted">—</span>@endif</td>
                            <td>@if ($cliente->telefono)<i class="fas fa-phone-alt text-muted me-1"></i>{{ $cliente->telefono }}@else<span class="text-muted">—</span>@endif</td>
                            <td class="small text-muted">{{ $cliente->direccion ?? '—' }}</td>
                            <td class="text-center"><span class="badge rounded-pill bg-primary-subtle text-primary-emphasis">{{ $cliente->ventas()->count() }}</span></td>
                            <td class="text-end pe-4">
                                <form action="{{ route('clientes.destroy', $cliente->id) }}" method="POST" class="d-inline-flex gap-1">
                                    <a href="{{ route('clientes.show', $cliente->id) }}" class="btn btn-sm btn-outline-info rounded-circle" title="Ver detalle">
                                        <i class="fas fa-eye fa-fw"></i>
                                    </a>
                                    <a href="{{ route('clientes.edit', $cliente->id) }}" class="btn btn-sm btn-outline-success rounded-circle" title="Editar">
                                        <i class="fas fa-pen fa-fw"></i>
                                    </a>
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger rounded-circle" title="Eliminar"
                                            onclick="event.preventDefault(); confirm('¿Seguro que deseas eliminar este registro?') ? this.closest('form').submit() : false;">
                                        <i class="fas fa-trash fa-fw"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
@endsection
