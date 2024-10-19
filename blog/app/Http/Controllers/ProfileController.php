<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();

        // Obtener todas las categorías con su conteo de productos, incluyendo categorías sin productos
        $productosPorCategoria = DB::table('categorias')
            ->leftJoin('productos', 'productos.categoria_id', '=', 'categorias.id')
            ->select('categorias.nombre', DB::raw('COUNT(productos.id) as cantidad'))
            ->groupBy('categorias.nombre')
            ->get();

        return view('perfil', compact('user', 'productosPorCategoria'));
    }

    public function updateProfile(Request $request)
    {
        // Validar los datos del perfil
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . Auth::id(),
        ]);

        // Obtener el usuario autenticado y actualizar los datos
        $user = Auth::user();
        $user->name = $request->name;
        $user->email = $request->email;

        // Guardar los cambios
        // @phpstan-ignore-next-line
        $user->save();

        return redirect()->route('perfil')->with('success', 'Perfil actualizado correctamente.');
    }

    public function updatePassword(Request $request)
    {
        // Validar la contraseña actual y la nueva
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        $user = Auth::user();

        // Verificar si la contraseña actual es correcta
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'La contraseña actual no es correcta.']);
        }

        // Actualizar la contraseña
        $user->password = Hash::make($request->new_password);

        // Guardar los cambios
        // @phpstan-ignore-next-line
        $user->save();

        return redirect()->route('perfil')->with('success', '¡Contraseña cambiada correctamente!');
    }
}