<?php

/*
|--------------------------------------------------------------------------
| DATOS DE LA LIBRERÍA  
|--------------------------------------------------------------------------
| Se usan en la portada, el catálogo, la página de producto, el carrito
| y el pie de página. En cualquier vista: config('libreria.whatsapp')
| Después de cambiar algo: php artisan optimize:clear
*/

return [

    'nombre'     => 'Mundo Letras',
    'eslogan'    => 'Lee · Estudia · Crea',
    'duena'      => 'Saida Torrealva',                    // tu nombre completo como propietaria
    'ruc'        => '20000000000',
    'fundacion'  => '2026',

    'whatsapp'   => '51920909780',              // código de país + número, TODO JUNTO: sin +, sin espacios
    'telefono'   => '920 909 780',
    'correo'     => 'contacto@libreria.pe',
    'direccion'  => 'Av. Principal 123, Lima',
    'horario'    => 'Lun - Sáb: 9:00 a.m. - 8:00 p.m.',

    // Pagos: Yape y Plin se muestran con su código QR (no con el número)
    // Guarda las imágenes en la carpeta "public" del proyecto con estos nombres:
    'yape_qr'    => 'yape-qr.png',              // C:\xampp\htdocs\libreria\public\yape-qr.png
    'plin_qr'    => 'plin-qr.png',              // C:\xampp\htdocs\libreria\public\plin-qr.png
    'titular'    => 'Saida Torrealva',
    'banco'      => 'BCP',
    'cuenta'     => '191-0000000-0-00',
    'cci'        => '002-191-000000000000-00',

    // Envíos
    'costo_envio'         => 10.00,             // S/ por pedido con envío a domicilio
    'envio_gratis_desde'  => 150.00,            // S/ a partir de este subtotal el envío es gratis
    'adelanto_recojo'     => 0.50,              // 50 % de adelanto si recoge en tienda
    'horas_para_pagar'    =>24,

    // Color e ícono de cada categoría (para etiquetas, tarjetas y la barra de útiles)
    'estilo_categorias' => [
        'Novela'             => ['info',    'fa-book-open'],
        'Poesía'             => ['danger',  'fa-feather-pointed'],
        'Literatura peruana' => ['success', 'fa-landmark'],
        'Cuento'             => ['warning', 'fa-book'],
        'Informática'        => ['primary', 'fa-laptop-code'],
        'Matemáticas'        => ['info',    'fa-square-root-variable'],
        'Historia'           => ['warning', 'fa-scroll'],
        'Infantil'           => ['danger',  'fa-child-reaching'],
        'Útiles escolares'   => ['success', 'fa-pencil-ruler'],
        'Papelería'          => ['light',   'fa-folder-open'],
    ],
];
