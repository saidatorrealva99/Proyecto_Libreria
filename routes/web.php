<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\AutoreController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\EmpleadoController;
use App\Http\Controllers\VentaController;
use App\Http\Controllers\DetalleVentaController;
use App\Http\Controllers\PagoController;

use App\Models\Autore;
use App\Models\Categoria;
use App\Models\Cliente;
use App\Models\Producto;

/*
|--------------------------------------------------------------------------
| Portada pública (welcome) con datos reales
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('welcome', [
        'totalProductos'  => Producto::where('estado', 1)->count(),
        'totalAutores'    => Autore::count(),
        'totalCategorias' => Categoria::where('estado', 1)->count(),
        'totalClientes'   => Cliente::count(),
        'categorias'      => Categoria::where('estado', 1)->withCount('productos')->orderBy('nombre')
                                ->get()->where('productos_count', '>', 0),
        'novedades'       => Producto::with(['categoria', 'autor'])->where('estado', 1)
                                ->orderByDesc('id')->take(6)->get(),
    ]);
});

/*
|--------------------------------------------------------------------------
| Login, registro, cerrar sesión (laravel/ui)
|--------------------------------------------------------------------------
*/
Auth::routes();

/*
|--------------------------------------------------------------------------
| Sistema (solo con sesión iniciada)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    Route::get('/home', [HomeController::class, 'index'])->name('home');

    // Catálogos
    Route::resource('autores',        AutoreController::class);
    Route::resource('categorias',     CategoriaController::class);
    Route::resource('productos',      ProductoController::class);
    Route::resource('clientes',       ClienteController::class);
    Route::resource('empleados',      EmpleadoController::class);

    // Procesos
    Route::resource('ventas',         VentaController::class);
    Route::resource('detalle-ventas', DetalleVentaController::class);
    Route::resource('pagos',          PagoController::class);
});