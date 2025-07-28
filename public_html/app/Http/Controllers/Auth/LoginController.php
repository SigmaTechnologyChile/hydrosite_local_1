<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request; // Agrega esta importación

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Redirige a la URL externa después del login
     */
    protected $redirectTo = '/dashboard';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    public function logout() {
        \Auth::logout();
        return redirect('/');
    }

    /**
     * Opcional: Si necesitas lógica de redirección más compleja
     */
    protected function authenticated(Request $request, $user)
    {
        // Redirige a todos los usuarios a la URL externa
        return redirect()->route('account.dashboard');
    }
}
