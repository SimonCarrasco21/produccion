<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto; // Modelo para acceder a los productos
use App\Models\Fiado;
use Illuminate\Support\Facades\Auth;

class FiadoController extends Controller
{
    // Mostrar la vista de fiados con los productos disponibles y los fiados del usuario autenticado
    public function index()
    {
        // Obtener solo los productos y fiados asociados al usuario autenticado
        $productos = Producto::all(); // Todos los productos (la tabla de categorías es compartida)
        $fiados = Fiado::where('user_id', Auth::id())->get(); // Fiados del usuario autenticado
        return view('fiados', compact('productos', 'fiados'));
    }

    // Almacenar un nuevo fiado en la base de datos
    public function store(Request $request)
    {
        // Validación de datos recibidos del formulario
        $request->validate([
            'id_cliente' => 'required',
            'nombre_cliente' => 'required',
            'producto' => 'required',
            'cantidad' => 'required|integer|min:1',
            'precio' => 'required|numeric',
            'fecha_compra' => 'required|date'
        ]);

        // Buscar el producto en la base de datos
        $producto = Producto::where('nombre', $request->producto)->first();

        // Verificar que el producto exista y tenga stock disponible
        if (!$producto) {
            return back()->with('error', 'El producto seleccionado no existe.');
        }
        if ($producto->stock <= 0) {
            return back()->with('error', 'No hay stock disponible para el producto seleccionado.');
        }
        if ($request->cantidad > $producto->stock) {
            return back()->with('error', 'No puedes agregar esta cantidad. Stock insuficiente.');
        }

        // Limitar la cantidad de fiados a un máximo de 5 por cliente
        $fiadosCount = Fiado::where('id_cliente', $request->id_cliente)
            ->where('user_id', Auth::id())
            ->count();
        if ($fiadosCount >= 5) {
            return back()->with('error', 'El cliente ya tiene 5 productos fiados y no puede fiar más.');
        }

        // Reducir el stock del producto en función de la cantidad fiada
        $producto->stock -= $request->cantidad;
        $producto->save();

        // Registrar el fiado en la base de datos, asociado al usuario autenticado
        Fiado::create([
            'id_cliente' => $request->id_cliente,
            'nombre_cliente' => $request->nombre_cliente,
            'producto' => $request->producto,
            'cantidad' => $request->cantidad,
            'precio' => $request->precio,
            'fecha_compra' => $request->fecha_compra,
            'user_id' => Auth::id(), // Asociar el fiado al usuario autenticado
        ]);

        return back()->with('success', 'Fiado registrado correctamente.');
    }

    // Eliminar un fiado registrado
    public function destroy($id)
    {
        // Encontrar el fiado y verificar que pertenezca al usuario autenticado
        $fiado = Fiado::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        // Buscar el producto correspondiente y restaurar el stock
        $producto = Producto::where('nombre', $fiado->producto)->first();
        if ($producto) {
            $producto->stock += $fiado->cantidad;
            $producto->save();
        }

        // Eliminar el registro del fiado
        $fiado->delete();

        return back()->with('success', 'Fiado eliminado correctamente. Stock restaurado.');
    }
}
