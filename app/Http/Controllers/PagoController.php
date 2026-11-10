<?php

namespace App\Http\Controllers;

use App\Models\Pago;
use App\Models\Venta;
use App\Http\Requests\PagoRequest;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class PagoController extends Controller
{
    public function index(Request $request): View
    {
        $pagos = Pago::with('venta')->latest('fecha_pago')->paginate();

        return view('pago.index', compact('pagos'))
            ->with('i', ($request->input('page', 1) - 1) * $pagos->perPage());
    }

    public function create(): View
    {
        $pago = new Pago();

        return view('pago.create', array_merge(compact('pago'), $this->listas()));
    }

    public function store(PagoRequest $request): RedirectResponse
    {
        Pago::create($request->validated());

        return Redirect::route('pagos.index')
            ->with('success', 'Pago registrado correctamente.');
    }

    public function show($id): View
    {
        $pago = Pago::with('venta')->findOrFail($id);

        return view('pago.show', compact('pago'));
    }

    public function edit($id): View
    {
        $pago = Pago::findOrFail($id);

        return view('pago.edit', array_merge(compact('pago'), $this->listas()));
    }

    public function update(PagoRequest $request, Pago $pago): RedirectResponse
    {
        $pago->update($request->validated());

        return Redirect::route('pagos.index')
            ->with('success', 'Pago actualizado correctamente.');
    }

    public function destroy($id): RedirectResponse
    {
        Pago::findOrFail($id)->delete();

        return Redirect::route('pagos.index')
            ->with('success', 'Pago eliminado correctamente.');
    }

    // Listas para los <select> del formulario
    private function listas(): array
    {
        return [
            'ventas' => Venta::where('estado', '!=', 'anulada')
                ->orderByDesc('id')
                ->selectRaw("id, CONCAT(numero_comprobante, '  —  S/ ', total) AS nombre")
                ->pluck('nombre', 'id'),
        ];
    }
}