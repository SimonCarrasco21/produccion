<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\NewPasswordController;

// Redirigir la raíz del sitio al login
Route::get('/', function () {
    return redirect('/login');  
});

// Rutas de autenticación y sesión de usuario
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');  // Página principal después de iniciar sesión
    })->name('dashboard');
});

// Rutas de restablecimiento de contraseña
Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');
Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
Route::post('reset-password', [NewPasswordController::class, 'store'])->name('password.update');

// Incluir las rutas de autenticación generadas por Breeze
require __DIR__.'/auth.php';




