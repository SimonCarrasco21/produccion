<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use App\Models\Fiado;
use App\Models\ProductoImagen;
use Illuminate\Support\Facades\Auth;

class FiadoController extends Controller
{
    // Mostrar el carrito de compras
    public function index()
    {
        // Obtener los registros del carrito del usuario
        $fiados = Fiado::where('user_id', Auth::id())
            ->whereNull('pagado') // Solo productos no pagados
            ->get();

        // Calcular el total del carrito
        $total = $fiados->sum('total_precio');

        return view('fiados', compact('fiados', 'total'));
    }

    // Agregar un producto al carrito
    public function agregar(Request $request)
{
    $producto = Producto::findOrFail($request->producto_id);

    // Verificar si el producto ya está en el carrito
    $carritoItem = Fiado::where('user_id', Auth::id())
        ->whereNull('pagado')
        ->where('productos', 'LIKE', '%"id":' . $producto->id . '%')
        ->first();

    if ($carritoItem) {
        // Actualizar cantidad y precio en el JSON
        $productos = json_decode($carritoItem->productos, true);
        foreach ($productos as &$item) {
            if ($item['id'] == $producto->id) {
                $item['cantidad'] += 1;
                $item['precio_total'] += $producto->precio;
            }
        }
        $carritoItem->productos = json_encode($productos);
        $carritoItem->total_precio += $producto->precio;
        $carritoItem->save();
    } else {
        // Crear un nuevo registro en la tabla fiados
        Fiado::create([
            'id_cliente' => 1, // Valor temporal o predeterminado
            'nombre_cliente' => 'Cliente Genérico', // Nombre predeterminado
            'productos' => json_encode([
                [
                    'id' => $producto->id,
                    'nombre' => $producto->nombre,
                    'cantidad' => 1,
                    'precio_unitario' => $producto->precio,
                    'precio_total' => $producto->precio,
                ]
            ]),
            'total_precio' => $producto->precio,
            'fecha_compra' => now(),
            'user_id' => Auth::id(),
            'pagado' => null,
        ]);
    }

    return back()->with('success', 'Producto agregado al carrito.');
}

    // Eliminar un producto del carrito
    public function eliminar($id)
    {
        // Obtener el registro del carrito
        $carritoItem = Fiado::where('id', $id)
            ->where('user_id', Auth::id())
            ->whereNull('pagado')
            ->firstOrFail();

        // Eliminar el producto del carrito
        $carritoItem->delete();

        return back()->with('success', 'Producto eliminado del carrito.');
    }

    // Proceder al pago
    public function pagar()
{
    // Obtener los registros del carrito del usuario
    $carrito = Fiado::where('user_id', Auth::id())
        ->whereNull('pagado')
        ->get();

    if ($carrito->isEmpty()) {
        return back()->with('error', 'El carrito está vacío.');
    }

    $productos = [];
    $total = 0;

    foreach ($carrito as $item) {
        $itemProductos = json_decode($item->productos, true);
        foreach ($itemProductos as $producto) {
            $productos[] = [
                'id' => $producto['id'],
                'nombre' => $producto['nombre'],
                'cantidad' => $producto['cantidad'],
                'precio_unitario' => $producto['precio_unitario'],
                'precio_total' => $producto['precio_total'],
            ];
            $total += $producto['precio_total'];
        }
    }

    // Redirigir a la vista de pago con los datos en la sesión flash
    return redirect()->route('pagina.pago')
        ->with([
            'productos' => $productos,
            'total' => $total,
        ]);
}

}
