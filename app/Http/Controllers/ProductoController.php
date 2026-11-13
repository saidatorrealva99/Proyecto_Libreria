<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Categoria;
use App\Models\Autore;
use App\Http\Requests\ProductoRequest;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProductoController extends Controller
{
    public function index(Request $request): View
    {
        $productos = Producto::with(['categoria', 'autor'])->orderBy('codigo')->get();

        return view('producto.index', compact('productos'))->with('i', 0);
    }

    public function create(): View
    {
        $producto = new Producto();

        return view('producto.create', array_merge(compact('producto'), $this->listas()));
    }

    public function store(ProductoRequest $request): RedirectResponse
    {
        $datos = $this->prepararDatos($request);
        $datos['imagen'] = $this->guardarImagen($request, $datos['codigo'], $datos['nombre']);

        Producto::create($datos);

        return Redirect::route('productos.index')
            ->with('success', 'Producto registrado correctamente.');
    }

    public function show(string $id): View
    {
        $producto = Producto::with(['categoria', 'autor'])->findOrFail($id);

        return view('producto.show', compact('producto'));
    }

    public function edit(string $id): View
    {
        $producto = Producto::findOrFail($id);

        return view('producto.edit', array_merge(compact('producto'), $this->listas()));
    }

    public function update(ProductoRequest $request, Producto $producto): RedirectResponse
    {
        $datos = $this->prepararDatos($request);

        if ($request->hasFile('imagen')) {
            // Nueva foto: se borra la anterior y se guarda con el nombre del producto
            $this->borrarImagen($producto->imagen);
            $datos['imagen'] = $this->guardarImagen($request, $datos['codigo'], $datos['nombre']);
        } elseif ($request->boolean('quitar_imagen')) {
            $this->borrarImagen($producto->imagen);
            $datos['imagen'] = null;
        } elseif ($producto->imagen) {
            // Si cambió el código o el nombre, la foto se renombra para que coincida
            $ext   = pathinfo($producto->imagen, PATHINFO_EXTENSION);
            $nueva = 'productos/' . Producto::nombreArchivo($datos['codigo'], $datos['nombre']) . '.' . $ext;
            if ($nueva !== $producto->imagen && Storage::disk('public')->exists($producto->imagen)) {
                Storage::disk('public')->move($producto->imagen, $nueva);
                $datos['imagen'] = $nueva;
            }
        }

        $producto->update($datos);

        return Redirect::route('productos.index')
            ->with('success', 'Producto actualizado correctamente.');
    }

    public function destroy(string $id): RedirectResponse
    {
        try {
            $producto = Producto::findOrFail($id);
            $imagen = $producto->imagen;
            $producto->delete();
            $this->borrarImagen($imagen);

            return Redirect::route('productos.index')
                ->with('success', 'Producto eliminado correctamente.');
        } catch (\Illuminate\Database\QueryException $e) {
            return Redirect::route('productos.index')
                ->with('error', 'No se puede eliminar: el producto ya tiene ventas. Mejor márcalo como "Descontinuado".');
        }
    }

    // ---------------------------------------------------------------
    //  Ayudas
    // ---------------------------------------------------------------

    // Datos validados sin los campos de archivo (la foto se maneja aparte)
    private function prepararDatos(ProductoRequest $request): array
    {
        $datos = $request->safe()->except(['imagen', 'quitar_imagen']);
        $datos['codigo'] = strtoupper($datos['codigo']);

        return $datos;
    }

    // Guarda la foto como storage/app/public/productos/{codigo-nombre}.{ext}
    private function guardarImagen(ProductoRequest $request, string $codigo, string $nombre): ?string
    {
        if (! $request->hasFile('imagen')) {
            return null;
        }

        $archivo = $request->file('imagen');
        $nombreArchivo = Producto::nombreArchivo($codigo, $nombre) . '.' . strtolower($archivo->getClientOriginalExtension());

        return $archivo->storeAs('productos', $nombreArchivo, 'public');
    }

    private function borrarImagen(?string $ruta): void
    {
        if ($ruta && Storage::disk('public')->exists($ruta)) {
            Storage::disk('public')->delete($ruta);
        }
    }

    // Listas para los <select> del formulario
    private function listas(): array
    {
        return [
            'categorias' => Categoria::where('estado', 1)->orderBy('nombre')->pluck('nombre', 'id'),
            'autores'    => Autore::selectRaw("id, CONCAT(nombres, ' ', apellidos) AS nombre")
                ->orderBy('apellidos')
                ->pluck('nombre', 'id'),
        ];
    }
}
