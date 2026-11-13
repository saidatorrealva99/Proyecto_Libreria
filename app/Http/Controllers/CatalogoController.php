<?php

namespace App\Http\Controllers;

use App\Models\Autore;
use App\Models\Categoria;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CatalogoController extends Controller
{
    /**
     * Catálogo completo con filtros y paginación
     *   /catalogo?q=vargas&tipo=libros&categoria=1&autor=2&min=10&max=100&orden=precio_asc&stock=1
     */
    public function index(Request $request): View
    {
        // ---------- Filtros que llegan por la URL ----------
        $filtros = [
            'q'         => trim((string) $request->input('q', '')),
            'tipo'      => in_array($request->input('tipo'), ['libros', 'utiles'], true) ? $request->input('tipo') : '',
            'categoria' => (int) $request->input('categoria', 0),
            'autor'     => (int) $request->input('autor', 0),
            'min'       => is_numeric($request->input('min')) ? (float) $request->input('min') : null,
            'max'       => is_numeric($request->input('max')) ? (float) $request->input('max') : null,
            'stock'     => $request->boolean('stock'),
            'orden'     => in_array($request->input('orden'), ['recientes', 'precio_asc', 'precio_desc', 'nombre'], true)
                               ? $request->input('orden') : 'recientes',
        ];

        // ---------- Consulta ----------
        $productos = Producto::with(['categoria', 'autor'])
            ->where('estado', 1)
            ->when($filtros['q'] !== '', function ($consulta) use ($filtros) {
                $q = $filtros['q'];
                $consulta->where(function ($w) use ($q) {
                    $w->where('nombre', 'like', "%{$q}%")
                      ->orWhere('codigo', 'like', "%{$q}%")
                      ->orWhere('marca_editorial', 'like', "%{$q}%")
                      ->orWhere('isbn', 'like', "%{$q}%")
                      ->orWhereHas('autor', fn ($a) => $a->where('nombres', 'like', "%{$q}%")
                                                         ->orWhere('apellidos', 'like', "%{$q}%"))
                      ->orWhereHas('categoria', fn ($c) => $c->where('nombre', 'like', "%{$q}%"));
                });
            })
            ->when($filtros['tipo'] === 'libros', fn ($c) => $c->whereNotNull('autor_id'))
            ->when($filtros['tipo'] === 'utiles', fn ($c) => $c->whereNull('autor_id'))
            ->when($filtros['categoria'] > 0, fn ($c) => $c->where('categoria_id', $filtros['categoria']))
            ->when($filtros['autor'] > 0, fn ($c) => $c->where('autor_id', $filtros['autor']))
            ->when($filtros['min'] !== null, fn ($c) => $c->where('precio_venta', '>=', $filtros['min']))
            ->when($filtros['max'] !== null, fn ($c) => $c->where('precio_venta', '<=', $filtros['max']))
            ->when($filtros['stock'], fn ($c) => $c->where('stock', '>', 0));

        match ($filtros['orden']) {
            'precio_asc'  => $productos->orderBy('precio_venta'),
            'precio_desc' => $productos->orderByDesc('precio_venta'),
            'nombre'      => $productos->orderBy('nombre'),
            default       => $productos->orderByDesc('id'),
        };

        $productos = $productos->paginate(12)->withQueryString();

        // ---------- Datos para los filtros ----------
        $categorias = Categoria::where('estado', 1)
            ->withCount(['productos' => fn ($q) => $q->where('estado', 1)])
            ->orderBy('nombre')
            ->get();

        $autores = Autore::whereHas('productos', fn ($q) => $q->where('estado', 1))
            ->orderBy('apellidos')
            ->get();

        $precioMax = (float) Producto::where('estado', 1)->max('precio_venta');

        // ¿Hay algún filtro activo? (para mostrar "Limpiar filtros")
        $hayFiltros = $filtros['q'] !== '' || $filtros['tipo'] !== '' || $filtros['categoria'] || $filtros['autor']
                   || $filtros['min'] !== null || $filtros['max'] !== null || $filtros['stock'];

        return view('catalogo.index', compact('productos', 'filtros', 'categorias', 'autores', 'precioMax', 'hayFiltros'));
    }

    /**
     * Página propia de un producto:  /producto/LIB-0001
     */
    public function show(Producto $producto): View
    {
        // Los productos descontinuados no se muestran en la tienda
        abort_unless($producto->estado, 404);

        $producto->load(['categoria', 'autor']);

        // Productos relacionados: misma categoría (o mismo autor), sin repetir
        $relacionados = Producto::with(['categoria', 'autor'])
            ->where('estado', 1)
            ->where('id', '!=', $producto->id)
            ->where(function ($q) use ($producto) {
                $q->where('categoria_id', $producto->categoria_id);
                if ($producto->autor_id) {
                    $q->orWhere('autor_id', $producto->autor_id);
                }
            })
            ->inRandomOrder()
            ->take(4)
            ->get();

        return view('catalogo.show', compact('producto', 'relacionados'));
    }
}