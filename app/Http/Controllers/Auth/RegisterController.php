<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    use RegistersUsers;

    /**
     * Quien se registra desde la web es cliente: va a "Mi cuenta"
     */
    protected function redirectTo(): string
    {
        return route('mi-cuenta');
    }

    /**
     * Después de registrarse: si estaba comprando, vuelve a su compra;
     * si no, va a "Mi cuenta".
     */
    protected function registered(Request $request, $user)
    {
        return redirect()->intended(route('mi-cuenta'))
            ->with('carrito_ok', '¡Bienvenido(a) a ' . config('libreria.nombre') . ', ' . $user->name . '! Tu cuenta fue creada.');
    }

    public function __construct()
    {
        $this->middleware('guest');
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    protected function create(array $data)
    {
        return User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
            'rol'      => 'cliente',      // nunca se puede registrar como personal desde la web
        ]);
    }
}
