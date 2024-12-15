<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();

        // Obtener el conteo de productos del usuario autenticado, agrupado por categoría
        $productosPorCategoria = DB::table('categorias')
            ->leftJoin('productos', function ($join) {
                $join->on('productos.categoria_id', '=', 'categorias.id')
                    ->where('productos.user_id', Auth::id());
            })
            ->select('categorias.nombre', DB::raw('COUNT(productos.id) as cantidad'))
            ->groupBy('categorias.nombre')
            ->get();

        // Ganancias por Categoría de Producto
        $gananciasPorCategoria = DB::table('ventas')
            ->join('productos', 'ventas.productos', 'like', DB::raw("CONCAT('%', productos.id, '%')"))
            ->join('categorias', 'productos.categoria_id', '=', 'categorias.id')
            ->select('categorias.nombre', DB::raw('SUM(ventas.amount) as total_ganancias'))
            ->groupBy('categorias.nombre')
            ->orderByDesc('total_ganancias')
            ->get();

        // Ventas por día (Ganancias diarias)
        $ventasPorDia = DB::table('ventas')
            ->select(DB::raw('DATE(created_at) as fecha'), DB::raw('SUM(amount) as total_ganancias'))
            ->groupBy('fecha')
            ->orderBy('fecha')
            ->get();

        // Productos con stock bajo (usando un umbral arbitrario, por ejemplo, < 5 unidades)
        $productosConStockBajo = DB::table('productos')
            ->where('stock', '<', 5)
            ->select('nombre', 'stock')
            ->get();

        // Métodos de pago más utilizados
        $metodosDePago = DB::table('ventas')
            ->select('metodo_pago', DB::raw('COUNT(*) as cantidad'))
            ->groupBy('metodo_pago')
            ->orderByDesc('cantidad')
            ->get();

        return view('perfil', compact(
            'user',
            'productosPorCategoria',
            'gananciasPorCategoria',
            'ventasPorDia',
            'productosConStockBajo',
            'metodosDePago'
        ));
    }





    public function updateProfile(Request $request)
    {
        // Validar los datos del perfil
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . Auth::id(),
            'profile_picture' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Obtener el usuario autenticado
        $user = Auth::user();

        // Manejo de la foto de perfil
        if ($request->hasFile('profile_picture')) {
            // Eliminar la foto anterior si existe
            if ($user->profile_picture && Storage::exists('public/' . $user->profile_picture)) {
                Storage::delete('public/' . $user->profile_picture);
            }

            // Guardar la nueva foto
            $file = $request->file('profile_picture');
            $path = $file->store('profile_pictures', 'public');
            $user->profile_picture = $path;
        }

        // Actualizar otros datos
        $user->name = $request->name;
        $user->email = $request->email;

        // Guardar los cambios
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
        $user->save();

        return redirect()->route('perfil')->with('success', '¡Contraseña cambiada correctamente!');
    }
}
