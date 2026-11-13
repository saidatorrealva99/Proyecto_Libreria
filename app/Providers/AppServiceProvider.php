<?php

namespace App\Providers;

use App\View\Composers\TiendaComposer;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Paginación con estilo Bootstrap 5
        Paginator::useBootstrapFive();

        // Datos de la librería disponibles en todas las vistas de la tienda
        View::composer(['layouts.tienda', 'welcome', 'catalogo.*'], TiendaComposer::class);
    }
}
