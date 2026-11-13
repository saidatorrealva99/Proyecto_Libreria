<?php

use App\Services\AnularPedidosVencidos;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

/*
|--------------------------------------------------------------------------
| Anular pedidos web que no se pagaron a tiempo
|--------------------------------------------------------------------------
| A mano:        php artisan pedidos:anular-vencidos
| Automático:    en un servidor real se deja corriendo  php artisan schedule:work
|                (en XAMPP no hace falta: se revisa solo al abrir el panel o Pedidos web)
*/
Artisan::command('pedidos:anular-vencidos', function () {
    $cantidad = AnularPedidosVencidos::ejecutar();

    $cantidad > 0
        ? $this->info("Se anularon {$cantidad} pedido(s) vencido(s) y su stock fue devuelto.")
        : $this->comment('No hay pedidos vencidos.');
})->purpose('Anula los pedidos web sin pago confirmado y devuelve el stock');

Schedule::command('pedidos:anular-vencidos')->everyFifteenMinutes();
