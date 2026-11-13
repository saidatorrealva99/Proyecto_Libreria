<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    /**
     * A dónde ir después de iniciar sesión, según la opción elegida en el login:
     *
     *  - "Soy cliente"  -> vuelve a donde estaba (carrito, compra...) o a la tienda.
     *                      El admin y el empleado también pueden entrar así para comprar.
     *  - "Soy personal" -> al Panel de Control (solo admin y empleado).
     */
    protected function authenticated(Request $request, $user)
    {
        /** @var \App\Models\User $user */

        if ($request->input('acceso') === 'personal') {

            if (! $user->esPersonal()) {
                $request->session()->forget('url.intended');
                return redirect()->route('mi-cuenta')
                    ->with('aviso', 'Tu cuenta es de cliente. Te llevamos a tu zona de compras.');
            }

            $request->session()->forget('url.intended');
            return redirect()->route('home');
        }

        // Acceso como cliente
        $destino = $user->esPersonal() ? route('inicio') : route('mi-cuenta');

        // Si venía del sistema interno (/home, /ventas...) no lo devolvemos ahí
        $pendiente = (string) $request->session()->get('url.intended', '');
        if ($pendiente !== '' && ! $this->esZonaDeCompra($pendiente)) {
            $request->session()->forget('url.intended');
        }

        return redirect()->intended($destino);
    }

    /**
     * Respaldo por si se entra sin elegir opción
     */
    protected function redirectTo(): string
    {
        /** @var \App\Models\User $usuario */
        $usuario = Auth::user();

        return $usuario->esPersonal() ? route('home') : route('mi-cuenta');
    }

    // Páginas de la tienda a las que sí conviene volver después de entrar
    private function esZonaDeCompra(string $url): bool
    {
        $ruta = parse_url($url, PHP_URL_PATH) ?? '/';

        foreach (['/compra', '/carrito', '/mi-cuenta', '/comprobante', '/producto', '/catalogo'] as $inicio) {
            if (str_starts_with($ruta, $inicio)) {
                return true;
            }
        }

        return $ruta === '/';
    }
}
