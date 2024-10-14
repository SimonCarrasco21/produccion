<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // Datos estáticos para el ejemplo
        $validUsername = 'simon';
        $validPassword = '1234';

        // Obtener los datos del formulario
        $username = $request->input('username');
        $password = $request->input('password');

        // Verificar si el usuario y la contraseña son correctos
        if ($username === $validUsername && $password === $validPassword) {
            return redirect('/home'); // Página principal (crearemos esta vista después)
        } else {
            return back()->with('error', 'Usuario o contraseña incorrectos');
        }
    }
}
