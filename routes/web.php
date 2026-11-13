<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\CatalogoController;
use App\Http\Controllers\CarritoController;
use App\Http\Controllers\CompraController;
use App\Http\Controllers\ComprobanteController;
use App\Http\Controllers\MiCuentaController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AutoreController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\EmpleadoController;
use App\Http\Controllers\VentaController;
use App\Http\Controllers\DetalleVentaController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\PedidoWebController;
use App\Http\Controllers\ReclamacionController;

/*
|--------------------------------------------------------------------------
| Zona pública (cualquier visitante)
|--------------------------------------------------------------------------
*/
Route::get('/', [WelcomeController::class, 'index'])->name('inicio');

// Catálogo completo y página propia de cada producto (por su código: /producto/LIB-0001)
Route::get('/catalogo', [CatalogoController::class, 'index'])->name('catalogo');
Route::get('/producto/{producto:codigo}', [CatalogoController::class, 'show'])->name('producto.ver');

// Carrito de compras (se guarda en la sesión; no hace falta iniciar sesión)
Route::get('/carrito',                     [CarritoController::class, 'index'])->name('carrito');
Route::post('/carrito/agregar/{producto}', [CarritoController::class, 'agregar'])->name('carrito.agregar');
Route::patch('/carrito/{producto}',        [CarritoController::class, 'actualizar'])->name('carrito.actualizar');
Route::delete('/carrito/{producto}',       [CarritoController::class, 'quitar'])->name('carrito.quitar');
Route::delete('/carrito',                  [CarritoController::class, 'vaciar'])->name('carrito.vaciar');

Route::post('/resenas', [WelcomeController::class, 'guardarResena'])
    ->middleware('throttle:5,1')
    ->name('resenas.store');

// Libro de Reclamaciones virtual (no hace falta iniciar sesión)
Route::get('/libro-de-reclamaciones',  [ReclamacionController::class, 'create'])->name('reclamaciones.create');
Route::post('/libro-de-reclamaciones', [ReclamacionController::class, 'store'])->middleware('throttle:3,1')->name('reclamaciones.store');
Route::get('/libro-de-reclamaciones/{reclamacion:codigo}', [ReclamacionController::class, 'constancia'])
    ->middleware('signed')                       // solo con el enlace que recibe el consumidor
    ->name('reclamaciones.constancia');

// Páginas legales
Route::view('/terminos',   'catalogo.terminos')->name('terminos');
Route::view('/privacidad', 'catalogo.privacidad')->name('privacidad');

/*
|--------------------------------------------------------------------------
| Login, registro, cerrar sesión (laravel/ui)
|--------------------------------------------------------------------------
*/
Auth::routes();

/*
|--------------------------------------------------------------------------
| Zona del CLIENTE (cualquier usuario con sesión)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/mi-cuenta', [MiCuentaController::class, 'index'])->name('mi-cuenta');

    // Proceso de compra
    Route::get('/compra',  [CompraController::class, 'create'])->name('compra');
    Route::post('/compra', [CompraController::class, 'store'])->middleware('throttle:10,1')->name('compra.store');

    // Detalle de un pedido (también es la página "¡Gracias por tu compra!")
    Route::get('/mi-cuenta/pedido/{numero}', [CompraController::class, 'pedido'])->name('pedido.ver');

    // Boleta / factura en PDF (la ve el dueño del pedido o el personal)
    Route::get('/comprobante/{numero}', [ComprobanteController::class, 'pdf'])->name('comprobante.pdf');
});

/*
|--------------------------------------------------------------------------
| Sistema interno: SOLO personal (admin y empleado)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'rol:admin,empleado'])->group(function () {

    Route::get('/home', [HomeController::class, 'index'])->name('home');

    // Catálogos
    Route::resource('autores',        AutoreController::class);
    Route::resource('categorias',     CategoriaController::class);
    Route::resource('productos',      ProductoController::class);
    Route::resource('clientes',       ClienteController::class);
    Route::resource('empleados',      EmpleadoController::class);

    // Pedidos de la tienda web (confirmar pago, preparar, entregar, anular)
    Route::get('/pedidos-web',                   [PedidoWebController::class, 'index'])->name('pedidos-web.index');
    Route::get('/pedidos-web/{venta}',           [PedidoWebController::class, 'show'])->name('pedidos-web.show');
    Route::patch('/pedidos-web/{venta}/confirmar', [PedidoWebController::class, 'confirmar'])->name('pedidos-web.confirmar');
    Route::patch('/pedidos-web/{venta}/estado',  [PedidoWebController::class, 'estado'])->name('pedidos-web.estado');
    Route::post('/pedidos-web/{venta}/saldo',    [PedidoWebController::class, 'saldo'])->name('pedidos-web.saldo');
    Route::patch('/pedidos-web/{venta}/anular',  [PedidoWebController::class, 'anular'])->name('pedidos-web.anular');

    // Libro de Reclamaciones (responder en 15 días hábiles)
    Route::get('/reclamos',                         [ReclamacionController::class, 'index'])->name('reclamos.index');
    Route::get('/reclamos/{reclamacion}',           [ReclamacionController::class, 'show'])->name('reclamos.show');
    Route::patch('/reclamos/{reclamacion}/responder', [ReclamacionController::class, 'responder'])->name('reclamos.responder');

    // Procesos
    Route::resource('ventas',         VentaController::class);
    Route::resource('detalle-ventas', DetalleVentaController::class);
    Route::resource('pagos',          PagoController::class);
});
