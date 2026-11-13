<?php

namespace App\Http\Controllers;

use App\Models\Autore;
use App\Models\Categoria;
use App\Models\Cliente;
use App\Models\Producto;
use App\Models\Resena;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class WelcomeController extends Controller
{
    /**
     * Portada pública de la librería
     * (la búsqueda y los filtros están en el catálogo: CatalogoController)
     */
    public function index(): View
    {
        // ---------- Novedades: los 6 últimos productos ----------
        $productos = Producto::with(['categoria', 'autor'])
            ->where('estado', 1)
            ->orderByDesc('id')
            ->take(6)
            ->get();

        // ---------- Cifras ----------
        $totalProductos  = Producto::where('estado', 1)->count();
        $totalLibros     = Producto::where('estado', 1)->whereNotNull('autor_id')->count();
        $totalAutores    = Autore::count();
        $totalCategorias = Categoria::where('estado', 1)->count();
        $totalClientes   = Cliente::count();

        // ---------- Categorías con productos (para las etiquetas de la portada) ----------
        $categorias = Categoria::where('estado', 1)
            ->withCount(['productos' => fn ($q) => $q->where('estado', 1)])
            ->orderBy('nombre')
            ->get()
            ->where('productos_count', '>', 0);

        // ---------- Reseñas de clientes ----------
        $resenas      = Resena::where('visible', 1)->latest()->take(6)->get();
        $totalResenas = Resena::where('visible', 1)->count();
        $promedio     = round((float) Resena::where('visible', 1)->avg('calificacion'), 1);

        return view('welcome', compact(
            'productos',
            'totalProductos', 'totalLibros', 'totalAutores', 'totalCategorias', 'totalClientes',
            'categorias', 'resenas', 'totalResenas', 'promedio'
        ));
    }

    /**
     * Guarda la reseña que deja un visitante en la portada
     */
    public function guardarResena(Request $request): RedirectResponse
    {
        $validador = Validator::make($request->all(), [
            'nombre'       => ['required', 'string', 'min:2', 'max:80'],
            'calificacion' => ['required', 'integer', 'between:1,5'],
            'comentario'   => ['required', 'string', 'min:10', 'max:500'],
        ], [
            'nombre.required'       => 'Escribe tu nombre.',
            'nombre.min'            => 'El nombre debe tener al menos 2 letras.',
            'calificacion.required' => 'Elige de 1 a 5 estrellas.',
            'calificacion.between'  => 'Elige de 1 a 5 estrellas.',
            'comentario.required'   => 'Cuéntanos tu experiencia.',
            'comentario.min'        => 'El comentario debe tener al menos 10 caracteres.',
            'comentario.max'        => 'El comentario no puede pasar de 500 caracteres.',
        ]);

        if ($validador->fails()) {
            return redirect(url('/') . '#resenas')->withErrors($validador)->withInput();
        }

        Resena::create($validador->validated());

        return redirect(url('/') . '#resenas')->with('resena_ok', '¡Gracias por tu reseña! Ya aparece en la página.');
    }
}
