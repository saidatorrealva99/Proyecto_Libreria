{{-- resources/views/empleado/index.blade.php  (reemplaza TODO el archivo) --}}
@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-3">

    <div class="card border-0 shadow rounded-4 overflow-hidden">

        {{-- ========== ENCABEZADO ========== --}}
        <div class="card-header bg-primary bg-gradient text-white border-0 p-4 position-relative overflow-hidden">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 position-relative">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-white bg-opacity-25 rounded-3 shadow-sm p-3 lh-1">
                        <i class="fas fa-id-badge fa-2x fa-fw"></i>
                    </div>
                    <div class="vr opacity-50"></div>
                    <div>
                        <h3 class="fw-bold mb-1">Gestión de Empleados
                            <span class="badge bg-white text-primary rounded-pill fs-6 align-middle ms-1">{{ count($empleados) }}</span>
                        </h3>
                        <p class="mb-0 opacity-75">Personal de ventas, caja, almacén y administración</p>
                    </div>
                </div>
                <a href="{{ route('empleados.create') }}" class="btn btn-light btn-lg rounded-pill px-4 fw-semibold text-primary shadow-sm">
                    <i class="fas fa-plus-circle me-2"></i>Nuevo Empleado
                </a>
            </div>
            <i class="fas fa-id-badge fa-6x opacity-25 position-absolute top-50 end-0 translate-middle-y me-5 d-none d-xl-block"></i>
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
                <table id="example" data-titulo="Gestión de Empleados" class="table table-hover align-middle w-100">
                    <thead class="table-light text-uppercase small text-secondary">
                        <tr>
                            <th class="ps-4">#</th>
                            <th>DNI</th>
                            <th>Empleado</th>
                            <th>Cargo</th>
                            <th>Correo</th>
                            <th>Celular</th>
                            <th class="text-end">Sueldo</th>
                            <th>Ingreso</th>
                            <th>Estado</th>
                            <th class="text-end pe-4">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($empleados as $empleado)
                        <tr>
                            <td class="ps-4 text-muted">{{ ++$i }}</td>
                            <td><span class="badge bg-light text-dark border font-monospace">{{ $empleado->dni }}</span></td>
                            <td class="fw-semibold text-dark">{{ $empleado->nombres }} {{ $empleado->apellidos }}</td>
                            <td>@php
                                    $cargos = [
                                        'administrador' => ['bg-dark',      'fa-user-tie'],
                                        'vendedor'      => ['bg-primary',   'fa-book-reader'],
                                        'cajero'        => ['bg-success',   'fa-cash-register'],
                                        'almacenero'    => ['bg-warning text-dark', 'fa-boxes'],
                                    ];
                                    [$color, $icono] = $cargos[$empleado->cargo] ?? ['bg-secondary', 'fa-user'];
                                @endphp
                                <span class="badge rounded-pill {{ $color }}"><i class="fas {{ $icono }} me-1"></i>{{ ucfirst($empleado->cargo) }}</span></td>
                            <td><i class="fas fa-envelope text-muted me-1"></i>{{ $empleado->email }}</td>
                            <td>{{ $empleado->telefono ?? '—' }}</td>
                            <td class="text-end">S/ {{ number_format($empleado->sueldo, 2) }}</td>
                            <td>{{ $empleado->fecha_contratacion ? \Illuminate\Support\Carbon::parse($empleado->fecha_contratacion)->format('d/m/Y') : '—' }}</td>
                            <td>@if ($empleado->estado)<span class="badge rounded-pill bg-success-subtle text-success-emphasis"><i class="fas fa-check-circle me-1"></i>Activo</span>@else<span class="badge rounded-pill bg-secondary-subtle text-secondary-emphasis"><i class="fas fa-user-slash me-1"></i>Cesado</span>@endif</td>
                            <td class="text-end pe-4">
                                <form action="{{ route('empleados.destroy', $empleado->id) }}" method="POST" class="d-inline-flex gap-1">
                                    <a href="{{ route('empleados.show', $empleado->id) }}" class="btn btn-sm btn-outline-info rounded-circle" title="Ver detalle">
                                        <i class="fas fa-eye fa-fw"></i>
                                    </a>
                                    <a href="{{ route('empleados.edit', $empleado->id) }}" class="btn btn-sm btn-outline-success rounded-circle" title="Editar">
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
