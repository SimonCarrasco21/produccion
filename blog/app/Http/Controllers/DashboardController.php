<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Categoria; // Importar el modelo Categoria
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function mostrarDashboard()
    {
        // Obtener solo los productos del usuario autenticado, 5 por página
        $productos = Producto::with('categoria')
            ->where('user_id', Auth::id())
            ->paginate(5);

        // Obtener solo las ventas del usuario autenticado, 5 por página
        $ventas = DB::table('ventas')
            ->where('user_id', Auth::id())
            ->paginate(5);

        return view('dashboard', compact('productos', 'ventas'));
    }

    public function mostrarInventario(Request $request)
    {
        $userId = Auth::id();

        // Query base de productos
        $productosQuery = Producto::with('categoria')
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc');

        // Filtro: Mostrar solo productos próximos a vencer
        if ($request->has('proximo_a_vencer') && $request->proximo_a_vencer == '1') {
            $productosQuery->whereNotNull('fecha_vencimiento')
                ->whereDate('fecha_vencimiento', '<=', now()->addDays(7));
        }

        // Obtener productos y calcular mensajes
        $productos = $productosQuery->get();
        foreach ($productos as $producto) {
            if (empty($producto->fecha_vencimiento)) {
                $producto->estado_vencimiento = 'Sin fecha de vencimiento';
            } elseif (\Carbon\Carbon::parse($producto->fecha_vencimiento)->diffInDays(now()) <= 7) {
                $producto->estado_vencimiento = 'Próximo a vencer';
            } else {
                $producto->estado_vencimiento = null; // Sin mensaje
            }
        }

        $categorias = Categoria::all();

        return view('inventario', compact('productos', 'categorias'));
    }

    public function eliminarProductosSeleccionados(Request $request)
    {
        $ids = $request->input('productos');
        if ($ids) {
            Producto::whereIn('id', $ids)->where('user_id', Auth::id())->delete();
            return redirect()->back()->with('success', 'Productos eliminados correctamente.');
        }
        return redirect()->back()->with('error', 'No se seleccionaron productos.');
    }

    public function productosPorVencer()
    {
        $hoy = now();
        $proximosDias = $hoy->addDays(7);

        $productosPorVencer = Producto::whereBetween('fecha_vencimiento', [now(), $proximosDias])
            ->where('user_id', Auth::id())
            ->get(['descripcion', 'fecha_vencimiento']); // Seleccionar solo las columnas necesarias

        return response()->json($productosPorVencer);
    }

    public function productosConStockBajo()
    {
        $productosConStockBajo = Producto::where('stock', '<', 5) // Stock bajo
            ->where('user_id', Auth::id()) // Filtrar por usuario autenticado
            ->get(['descripcion', 'stock']); // Seleccionar solo las columnas necesarias

        return response()->json($productosConStockBajo);
    }
}
