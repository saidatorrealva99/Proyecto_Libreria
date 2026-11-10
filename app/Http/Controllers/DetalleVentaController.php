<?php

namespace App\Http\Controllers;

use App\Models\DetalleVenta;
use App\Models\Venta;
use App\Models\Producto;
use App\Http\Requests\DetalleVentaRequest;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class DetalleVentaController extends Controller
{
    /**
     * Listado
     */
    public function index(Request $request): View
    {
        $detalleVentas = DetalleVenta::with(['venta', 'producto'])->orderBy('venta_id')->paginate();

        return view('detalle-venta.index', compact('detalleVentas'))
            ->with('i', ($request->input('page', 1) - 1) * $detalleVentas->perPage());
    }

    /**
     * Formulario para registrar
     */
    public function create(): View
    {
        $detalleVenta = new DetalleVenta();

        return view('detalle-venta.create', array_merge(compact('detalleVenta'), $this->listas()));
    }

    /**
     * Guardar
     */
    public function store(DetalleVentaRequest $request): RedirectResponse
    {
        DetalleVenta::create($request->validated());

        return Redirect::route('detalle-ventas.index')
            ->with('success', 'Producto agregado a la venta correctamente.');
    }

    /**
     * Ver un registro
     */
    public function show($id): View
    {
        $detalleVenta = DetalleVenta::with(['venta', 'producto'])->findOrFail($id);

        return view('detalle-venta.show', compact('detalleVenta'));
    }

    /**
     * Formulario para editar
     */
    public function edit($id): View
    {
        $detalleVenta = DetalleVenta::findOrFail($id);

        return view('detalle-venta.edit', array_merge(compact('detalleVenta'), $this->listas()));
    }

    /**
     * Actualizar
     */
    public function update(DetalleVentaRequest $request, $id): RedirectResponse
    {
        $detalleVenta = DetalleVenta::findOrFail($id);
        $detalleVenta->update($request->validated());

        return Redirect::route('detalle-ventas.index')
            ->with('success', 'Detalle actualizado correctamente.');
    }

    /**
     * Eliminar
     */
    public function destroy($id): RedirectResponse
    {
        DetalleVenta::findOrFail($id)->delete();

        return Redirect::route('detalle-ventas.index')
            ->with('success', 'Detalle eliminado correctamente.');
    }

    /**
     * Listas para los <select> del formulario
     */
    private function listas(): array
    {
        return [
            'ventas'    => Venta::where('estado', '!=', 'anulada')
                ->orderByDesc('id')
                ->pluck('numero_comprobante', 'id'),

            // Colección completa: el formulario necesita precio y stock de cada producto
            'productos' => Producto::where('estado', 1)
                ->orderBy('nombre')
                ->get(['id', 'codigo', 'nombre', 'precio_venta', 'stock']),
        ];
    }
}