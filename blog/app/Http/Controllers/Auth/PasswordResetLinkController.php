<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    /**
     * Muestra la vista de solicitud de restablecimiento de contraseña.
     * 
     * Esta función retorna la vista donde el usuario introduce su correo para recibir un enlace
     * que le permitirá restablecer su contraseña.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Maneja la solicitud entrante para enviar el enlace de restablecimiento de contraseña.
     *
     * Esta función valida el correo ingresado y luego intenta enviar el enlace de restablecimiento de contraseña.
     * Si el correo es válido y se envía el enlace, redirige al usuario al login con un mensaje de éxito.
     * En caso de error, se mantiene en la misma página y muestra el error.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // Validación de que el campo "email" es obligatorio y debe ser un correo electrónico válido.
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        // Intenta enviar el enlace de restablecimiento de contraseña.
        // La función sendResetLink busca el email y envía el enlace si existe en la base de datos.
        $status = Password::sendResetLink(
            $request->only('email') // Solo envía el email para evitar datos innecesarios
        );

        // Si el enlace se envió exitosamente, redirige al login con un mensaje de estado.
        if ($status == Password::RESET_LINK_SENT) {
            // Redirige al login con un mensaje de éxito (usando la traducción del estado).
            return redirect()->route('login')->with('status', __($status));
        }

        // Si ocurre un error (por ejemplo, el correo no existe), se regresa con el error correspondiente.
        return back()->withInput($request->only('email'))
                    ->withErrors(['email' => __($status)]);
    }
}

