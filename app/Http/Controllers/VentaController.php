<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use App\Models\Cliente;
use App\Models\Empleado;
use App\Http\Requests\VentaRequest;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class VentaController extends Controller
{
    public function index(Request $request): View
    {
        $ventas = Venta::with(['cliente', 'empleado'])->latest('fecha_venta')->paginate();

        return view('venta.index', compact('ventas'))
            ->with('i', ($request->input('page', 1) - 1) * $ventas->perPage());
    }

    public function create(): View
    {
        $venta = new Venta();

        return view('venta.create', array_merge(compact('venta'), $this->listas()));
    }

    public function store(VentaRequest $request): RedirectResponse
    {
        Venta::create($request->validated());

        return Redirect::route('ventas.index')
            ->with('success', 'Venta registrada correctamente.');
    }

    public function show($id): View
    {
        $venta = Venta::with(['cliente', 'empleado'])->findOrFail($id);

        return view('venta.show', compact('venta'));
    }

    public function edit($id): View
    {
        $venta = Venta::findOrFail($id);

        return view('venta.edit', array_merge(compact('venta'), $this->listas()));
    }

    public function update(VentaRequest $request, Venta $venta): RedirectResponse
    {
        $venta->update($request->validated());

        return Redirect::route('ventas.index')
            ->with('success', 'Venta actualizada correctamente.');
    }

    public function destroy($id): RedirectResponse
    {
        try {
            Venta::findOrFail($id)->delete();
            return Redirect::route('ventas.index')
                ->with('success', 'Venta eliminada correctamente.');
        } catch (\Illuminate\Database\QueryException $e) {
            return Redirect::route('ventas.index')
                ->with('error', 'No se puede eliminar: la venta tiene pagos registrados. Mejor cámbiala a "Anulada".');
        }
    }

    // Listas para los <select> del formulario
    private function listas(): array
    {
        return [
            'clientes'  => Cliente::selectRaw("id, CONCAT(numero_documento, ' - ', nombres, ' ', IFNULL(apellidos, '')) AS nombre")
                ->orderBy('nombres')
                ->pluck('nombre', 'id'),
            'empleados' => Empleado::where('estado', 1)
                ->selectRaw("id, CONCAT(nombres, ' ', apellidos, ' (', cargo, ')') AS nombre")
                ->orderBy('nombres')
                ->pluck('nombre', 'id'),
        ];
    }
}