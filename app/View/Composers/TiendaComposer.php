<?php

namespace App\View\Composers;

use App\Models\Categoria;
use App\Services\Carrito;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

/**
 * Comparte con TODAS las vistas de la tienda (portada, catálogo, producto...)
 * los datos de la librería, los enlaces de WhatsApp y las barras de útiles.
 */
class TiendaComposer
{
    // Se calcula una sola vez por página aunque se usen varias vistas
    private static ?array $cache = null;

    public function compose(View $view): void
    {
        $view->with(self::$cache ??= $this->datos());
    }

    private function datos(): array
    {
        $datos = config('libreria');

        // Enlace de WhatsApp con mensaje listo
        $wa   = fn (string $mensaje) => 'https://wa.me/' . $datos['whatsapp'] . '?text=' . rawurlencode($mensaje);
        $mapa = 'https://www.google.com/maps/search/?api=1&query=' . rawurlencode($datos['direccion']);

        // Color e ícono de una categoría
        $estilos = $datos['estilo_categorias'];
        $estilo  = fn (?string $nombre) => $estilos[$nombre] ?? ['primary', 'fa-book'];

        // A dónde va cada usuario según su rol
        /** @var \App\Models\User|null $usuario */
        $usuario     = Auth::user();
        $miZona      = $usuario ? ($usuario->esPersonal() ? route('home') : route('mi-cuenta')) : route('login');
        $miZonaTexto = $usuario ? ($usuario->esPersonal() ? 'Ir al panel' : 'Mi cuenta') : 'Ingresar';

        // Categorías activas: id y cantidad de productos activos (para la barra inteligente)
        $categoriasTienda = Categoria::where('estado', 1)
            ->withCount(['productos' => fn ($q) => $q->where('estado', 1)])
            ->get()
            ->keyBy('nombre');

        // Barra superior: una categoría por ícono. Sin productos = "Próximamente"
        $acceso = function (string $icono, string $color, string $texto, ?string $categoria, array $filtro = []) use ($categoriasTienda) {
            if ($categoria) {
                $cat = $categoriasTienda->get($categoria);
                $hay = $cat && $cat->productos_count > 0;
                return [
                    $icono, $color,
                    $hay ? "{$texto} ({$cat->productos_count})" : "{$texto} · Próximamente",
                    $hay ? route('catalogo', ['categoria' => $cat->id]) : null,
                ];
            }
            return [$icono, $color, $texto, route('catalogo', $filtro)];
        };

        $barraSuperior = [
            $acceso('fa-pencil',          'warning', 'Útiles y papelería', null, ['tipo' => 'utiles']),
            $acceso('fa-book',            'light',   'Todos los libros',   null, ['tipo' => 'libros']),
            $acceso('fa-book-open',       'info',    'Novelas',            'Novela'),
            $acceso('fa-feather-pointed', 'danger',  'Poesía',             'Poesía'),
            $acceso('fa-landmark',        'success', 'Literatura peruana', 'Literatura peruana'),
            $acceso('fa-scroll',          'warning', 'Historia',           'Historia'),
            $acceso('fa-laptop-code',     'primary', 'Informática',        'Informática'),
            $acceso('fa-ruler',           'info',    'Matemáticas',        'Matemáticas'),
            $acceso('fa-palette',         'light',   'Infantil',           'Infantil'),
        ];

        $barraInferior = [
            ['fa-layer-group',      'warning', 'Catálogo completo',       route('catalogo')],
            ['fa-tag',              'danger',  'Precios más bajos',       route('catalogo', ['orden' => 'precio_asc'])],
            ['fa-star',             'warning', 'Reseñas de clientes',     url('/') . '#resenas'],
            ['fa-school',           'success', 'Pedidos para colegios',   url('/') . '#servicios'],
            ['fa-credit-card',      'primary', 'Métodos de pago',         url('/') . '#pagos'],
            ['fa-location-dot',     'danger',  'Contacto y ubicación',    '#contacto'],
            ['fa-comment-dots',     'success', 'Escríbenos por WhatsApp', $wa('Hola, quisiera información sobre sus productos.')],
            ['fa-right-to-bracket', 'info',    $usuario ? $miZonaTexto : 'Acceso al sistema', $miZona],
            ['fa-user-plus',        'light',   'Crear cuenta',            route('register')],
        ];

        // Cantidad de productos en el carrito (para el contador del menú)
        $carritoCantidad = app(Carrito::class)->cantidad();

        return compact('datos', 'wa', 'mapa', 'estilo', 'miZona', 'miZonaTexto', 'barraSuperior', 'barraInferior', 'categoriasTienda', 'carritoCantidad');
    }
}
