<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\ProductoRequest;
use Illuminate\Support\Facades\Redirect;
use App\Models\Categoria;
use App\Models\Autore;
use Illuminate\View\View;

class ProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $productos = Producto::paginate();

        return view('producto.index', compact('productos'))
            ->with('i', ($request->input('page', 1) - 1) * $productos->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
{
    $producto   = new Producto();
    $categorias = Categoria::where('estado', 1)->pluck('nombre', 'id');
    $autores    = Autore::selectRaw("id, CONCAT(nombres, ' ', apellidos) AS nombre")->pluck('nombre', 'id');

    return view('producto.create', compact('producto', 'categorias', 'autores'));
}

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductoRequest $request): RedirectResponse
    {
        Producto::create($request->validated());

        return Redirect::route('productos.index')
            ->with('success', 'Producto created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $producto = Producto::find($id);

        return view('producto.show', compact('producto'));
    }

    /**
     * Show the form for editing the specified resource.
     */
   public function edit($id)
{
    $producto   = Producto::find($id);
    $categorias = Categoria::where('estado', 1)->pluck('nombre', 'id');
    $autores    = Autore::selectRaw("id, CONCAT(nombres, ' ', apellidos) AS nombre")->pluck('nombre', 'id');

    return view('producto.edit', compact('producto', 'categorias', 'autores'));
}

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductoRequest $request, Producto $producto): RedirectResponse
    {
        $producto->update($request->validated());

        return Redirect::route('productos.index')
            ->with('success', 'Producto updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        Producto::find($id)->delete();

        return Redirect::route('productos.index')
            ->with('success', 'Producto deleted successfully');
    }
}
