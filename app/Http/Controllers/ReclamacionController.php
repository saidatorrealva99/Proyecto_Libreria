<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReclamacionRequest;
use App\Models\Reclamacion;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\View\View;

/**
 * Libro de Reclamaciones virtual
 *   Público:   formulario, registro y constancia (hoja de reclamación)
 *   Personal:  lista de reclamos y respuesta (plazo: 15 días hábiles)
 */
class ReclamacionController extends Controller
{
    /* ===================== PARTE PÚBLICA ===================== */

    public function create(Request $request): View
    {
        /** @var \App\Models\User|null $usuario */
        $usuario = $request->user();

        // Si el cliente tiene sesión, se adelantan sus datos
        $ficha = $usuario?->clientes()->latest('updated_at')->first();

        return view('reclamacion.create', compact('usuario', 'ficha'));
    }

    public function store(ReclamacionRequest $request): RedirectResponse
    {
        $datos = $request->validated();

        $reclamacion = DB::transaction(function () use ($request, $datos) {
            return Reclamacion::create($datos + [
                'codigo'     => Reclamacion::siguienteCodigo(),
                'user_id'    => $request->user()?->id,
                'menor_edad' => $request->boolean('menor_edad'),
                'apoderado'  => $request->boolean('menor_edad') ? $datos['apoderado'] : null,
                'estado'     => 'pendiente',
            ]);
        });

        // Enlace firmado: solo quien tiene el enlace puede ver la hoja
        return redirect()->to(URL::signedRoute('reclamaciones.constancia', $reclamacion->codigo))
            ->with('reclamo_ok', true);
    }

    public function constancia(Reclamacion $reclamacion): View
    {
        return view('reclamacion.constancia', compact('reclamacion'));
    }

    /* ===================== PARTE DEL PERSONAL ===================== */

    public function index(Request $request): View
    {
        $estado = in_array($request->estado, ['pendiente', 'respondido', 'todos'], true) ? $request->estado : 'pendiente';

        $reclamaciones = Reclamacion::with('responsable')
            ->when($estado !== 'todos', fn ($q) => $q->where('estado', $estado))
            ->orderBy('estado')
            ->oldest()
            ->get();

        $conteo = Reclamacion::selectRaw('estado, COUNT(*) AS cantidad')->groupBy('estado')->pluck('cantidad', 'estado');

        return view('reclamacion.index', compact('reclamaciones', 'estado', 'conteo'))->with('i', 0);
    }

    public function show(Reclamacion $reclamacion): View
    {
        $enlaceCliente = URL::signedRoute('reclamaciones.constancia', $reclamacion->codigo);

        return view('reclamacion.show', compact('reclamacion', 'enlaceCliente'));
    }

    public function responder(Request $request, Reclamacion $reclamacion): RedirectResponse
    {
        $request->validate([
            'respuesta' => ['required', 'string', 'min:10', 'max:3000'],
        ], [], ['respuesta' => 'respuesta']);

        if ($reclamacion->estado === 'respondido') {
            return back()->with('error', 'Esta reclamación ya fue respondida.');
        }

        $reclamacion->update([
            'respuesta'      => $request->respuesta,
            'estado'         => 'respondido',
            'respondido_por' => $request->user()->id,
            'respondido_at'  => now(),
        ]);

        return back()->with('success', 'Respuesta registrada. Envíasela al consumidor por correo (botón "Enviar por correo").');
    }
}
