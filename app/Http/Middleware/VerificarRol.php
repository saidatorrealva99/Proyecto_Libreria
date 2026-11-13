<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerificarRol
{
    /**
     * Uso en rutas:  ->middleware('rol:admin,empleado')
     * Si el usuario no tiene uno de esos roles, se le envía a su zona.
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $usuario = $request->user();

        if (! $usuario) {
            return redirect()->route('login');
        }

        if (! in_array($usuario->rol, $roles, true)) {
            // Un cliente que intenta entrar al sistema interno va a "Mi cuenta"
            if ($usuario->rol === 'cliente') {
                return redirect()->route('mi-cuenta')
                    ->with('aviso', 'Esa sección es solo para el personal de la librería.');
            }
            abort(403, 'No tienes permiso para entrar a esta sección.');
        }

        return $next($request);
    }
}