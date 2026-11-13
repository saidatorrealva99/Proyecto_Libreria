{{-- resources/views/catalogo/terminos.blade.php  (archivo NUEVO)
     Términos y condiciones de compra:  /terminos
     Revisa los textos y ajústalos a cómo trabaja tu librería. --}}
@extends('layouts.tienda')

@section('titulo', 'Términos y condiciones')

@section('content')
@php
    $t = config('libreria');
    $adelanto = (int) round($t['adelanto_recojo'] * 100);
    $secciones = [
        ['fa-store', 'Sobre nosotros', [
            "{$t['nombre']} es una librería que vende libros, textos académicos, útiles escolares y papelería. Razón social: {$t['titular']}, RUC {$t['ruc']}, con dirección en {$t['direccion']}.",
            "Al comprar en esta tienda en línea aceptas estos términos. Si tienes dudas, escríbenos a {$t['correo']} o al {$t['telefono']}.",
        ]],
        ['fa-user', 'Tu cuenta', [
            'Para comprar necesitas crear una cuenta con tu nombre, correo y una contraseña. Eres responsable de mantener tu contraseña en secreto.',
            'Los datos que ingreses para el comprobante (DNI o RUC, nombre, dirección) deben ser verdaderos.',
        ]],
        ['fa-tags', 'Precios y stock', [
            'Los precios están en soles (S/) e incluyen el IGV.',
            'El stock se descuenta al registrar tu pedido. Si un producto se agota mientras compras, el sistema te avisará antes de confirmar.',
        ]],
        ['fa-qrcode', 'Pagos', [
            'Aceptamos Yape, Plin y transferencia bancaria. Al pagar, ingresa el número de operación para que podamos verificarlo.',
            "Recojo en tienda: pagas un adelanto del {$adelanto} % al hacer el pedido y el saldo al recoger.",
            'Envío a domicilio: pagas el 100 % del pedido, incluido el envío, al hacer el pedido.',
            "Si no podemos confirmar tu pago dentro de {$t['horas_para_pagar']} horas, el pedido se anula automáticamente y los productos vuelven a la tienda. Si tu pago sí se realizó, te lo devolvemos.",
        ]],
        ['fa-truck-fast', 'Entregas', [
            'Envío a domicilio: S/ ' . number_format($t['costo_envio'], 2) . '. Es gratis para compras desde S/ ' . number_format($t['envio_gratis_desde'], 2) . '.',
            "Recojo en tienda: en {$t['direccion']}, en el horario {$t['horario']}. Te avisaremos cuando tu pedido esté listo.",
            'Para recoger o recibir el pedido se puede pedir el documento de identidad de quien compró.',
        ]],
        ['fa-rotate-left', 'Cambios y devoluciones', [
            "Si recibes un producto con fallas o distinto al que pediste, comunícate con nosotros dentro de los {$t['dias_cambio']} días siguientes a la entrega, con tu comprobante.",
            'El producto debe estar en el estado en que lo recibiste. Revisaremos el caso y te ofreceremos el cambio o la devolución del dinero.',
        ]],
        ['fa-file-invoice', 'Comprobantes', [
            'Emitimos boleta o factura según elijas al comprar. Puedes descargarla desde "Mi cuenta" cuando confirmemos tu pago.',
        ]],
        ['fa-book', 'Reclamos', [
            'Si no estás conforme con un producto o con la atención, puedes registrar tu reclamo o queja en nuestro Libro de Reclamaciones virtual. Te responderemos en un plazo máximo de 15 días hábiles.',
        ]],
    ];
@endphp
<section class="bg-black py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-9">
                <div class="text-center mb-5">
                    <span class="bg-warning text-dark rounded-circle d-inline-flex p-3 mb-3"><i class="fas fa-scale-balanced fa-2x fa-fw"></i></span>
                    <h1 class="fw-bold">Términos y condiciones</h1>
                    <p class="text-white-50 mb-0">Última actualización: {{ $t['terminos_actualizados'] }}</p>
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
                    <a href="{{ route('privacidad') }}" class="btn btn-outline-warning rounded-pill px-4"><i class="fas fa-user-shield me-2"></i>Política de privacidad</a>
                    <a href="{{ route('reclamaciones.create') }}" class="btn btn-warning rounded-pill px-4 fw-semibold"><i class="fas fa-book me-2"></i>Libro de Reclamaciones</a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
