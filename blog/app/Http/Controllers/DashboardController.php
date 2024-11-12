<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function mostrarDashboard()
    {
        // Obtener solo los productos del usuario autenticado, 8 por página
        $productos = Producto::with('categoria')
            ->where('user_id', Auth::id())
            ->paginate(8);

        // Obtener solo las ventas del usuario autenticado
        $ventas = DB::table('ventas')
            ->where('user_id', Auth::id())
            ->get();

        return view('dashboard', compact('productos', 'ventas'));
    }

    public function mostrarInventario()
    {
        // Obtener solo los productos del usuario autenticado
        $productos = Producto::where('user_id', Auth::id())->get();
        return view('inventario', compact('productos'));
    }
}
