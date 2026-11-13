{{-- resources/views/catalogo/privacidad.blade.php  (archivo NUEVO)
     Política de privacidad (Ley N.° 29733 de Protección de Datos Personales):  /privacidad
     Revisa los textos y ajústalos a tu librería. --}}
@extends('layouts.tienda')

@section('titulo', 'Política de privacidad')

@section('content')
@php
    $t = config('libreria');
    $secciones = [
        ['fa-building', 'Responsable de tus datos', [
            "{$t['titular']} ({$t['nombre']}), RUC {$t['ruc']}, con dirección en {$t['direccion']}. Contacto: {$t['correo']}.",
        ]],
        ['fa-list-check', 'Qué datos guardamos', [
            'Cuenta: nombre, correo y contraseña (la contraseña se guarda cifrada; nadie puede verla).',
            'Compras: DNI o RUC, nombres o razón social, celular, dirección de envío y los productos que compras.',
            'Pagos: método de pago y número de operación. No guardamos datos de tarjetas ni claves de Yape o Plin.',
            'Reseñas y reclamos: lo que escribes en esos formularios.',
        ]],
        ['fa-bullseye', 'Para qué los usamos', [
            'Registrar y entregar tus pedidos, y emitir tu boleta o factura.',
            'Comunicarnos contigo sobre tu pedido o tu reclamo por correo, llamada o WhatsApp.',
            'Cumplir obligaciones legales (tributarias y de protección al consumidor).',
            'No vendemos ni compartimos tus datos con otras empresas para publicidad.',
        ]],
        ['fa-clock-rotate-left', 'Cuánto tiempo los guardamos', [
            'Mientras tengas tu cuenta y el tiempo que exijan las normas tributarias y de protección al consumidor para conservar comprobantes y reclamos.',
        ]],
        ['fa-hand', 'Tus derechos', [
            'Puedes pedir acceder a tus datos, corregirlos, cancelarlos u oponerte a su uso (derechos ARCO).',
            "Escríbenos a {$t['correo']} indicando tu nombre, tu documento y lo que necesitas. Si no estás conforme con la respuesta, puedes acudir a la Autoridad Nacional de Protección de Datos Personales.",
        ]],
        ['fa-lock', 'Seguridad', [
            'Protegemos tu información con contraseñas cifradas y acceso restringido: solo el personal autorizado de la librería puede ver los datos de los pedidos.',
        ]],
    ];
@endphp
<section class="bg-black py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-9">
                <div class="text-center mb-5">
                    <span class="bg-warning text-dark rounded-circle d-inline-flex p-3 mb-3"><i class="fas fa-user-shield fa-2x fa-fw"></i></span>
                    <h1 class="fw-bold">Política de privacidad</h1>
                    <p class="text-white-50 mb-0">Conforme a la Ley N.° 29733, Ley de Protección de Datos Personales · Última actualización: {{ $t['terminos_actualizados'] }}</p>
                </div>

                @foreach ($secciones as [$icono, $titulo, $parrafos])
                    <div class="bg-primary-subtle border border-primary border-opacity-25 rounded-4 p-4 mb-3">
                        <h5 class="fw-bold mb-3"><i class="fas {{ $icono }} text-warning me-2"></i>{{ $loop->iteration }}. {{ $titulo }}</h5>
                        <ul class="mb-0 text-white-50">
                            @foreach ($parrafos as $p)
                                <li class="mb-1">{{ $p }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endforeach

                <div class="d-flex flex-wrap justify-content-center gap-2 mt-4">
                    <a href="{{ route('terminos') }}" class="btn btn-outline-warning rounded-pill px-4"><i class="fas fa-scale-balanced me-2"></i>Términos y condiciones</a>
                    <a href="{{ route('reclamaciones.create') }}" class="btn btn-warning rounded-pill px-4 fw-semibold"><i class="fas fa-book me-2"></i>Libro de Reclamaciones</a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
