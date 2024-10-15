<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function mostrarDashboard()
    {
        // Paginamos los productos, 8 por página
        $productos = Producto::with('categoria')->paginate(8); 

        return view('dashboard', compact('productos'));
    }
}
