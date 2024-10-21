<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto; // Asegúrate de tener este modelo para acceder a los productos
use App\Models\Fiado;

class FiadoController extends Controller
{
    // Método para mostrar la vista de fiados con los productos disponibles y los fiados registrados
    public function index()
    {
        $productos = Producto::all(); // Obtiene todos los productos
        $fiados = Fiado::all(); // Obtiene todos los fiados registrados
        return view('fiados', compact('productos', 'fiados'));
    }

    // Método para almacenar un nuevo fiado en la base de datos
    public function store(Request $request)
    {
        // Validar los datos recibidos del formulario
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

        // Verificar si el producto existe
        if (!$producto) {
            return back()->with('error', 'El producto seleccionado no existe.');
        }

        // Verificar si el producto tiene stock disponible
        if ($producto->stock <= 0) {
            return back()->with('error', 'No hay stock disponible para el producto seleccionado.');
        }

        // Verificar que la cantidad solicitada no sea mayor al stock disponible
        if ($request->cantidad > $producto->stock) {
            return back()->with('error', 'No puedes agregar esta cantidad. Stock insuficiente.');
        }

        // Verificar que el cliente no tenga más de 5 productos fiados
        $fiadosCount = Fiado::where('id_cliente', $request->id_cliente)->count();
        if ($fiadosCount >= 5) {
            return back()->with('error', 'El cliente ya tiene 5 productos fiados y no puede fiar más.');
        }

        // Reducir el stock del producto en función de la cantidad fiada
        $producto->stock -= $request->cantidad;
        $producto->save();

        // Registrar el fiado en la base de datos
        Fiado::create([
            'id_cliente' => $request->id_cliente,
            'nombre_cliente' => $request->nombre_cliente,
            'producto' => $request->producto,
            'cantidad' => $request->cantidad,
            'precio' => $request->precio,
            'fecha_compra' => $request->fecha_compra
        ]);

        return back()->with('success', 'Fiado registrado correctamente.');
    }

    // Método para eliminar un fiado registrado
    public function destroy($id)
    {
        $fiado = Fiado::findOrFail($id);

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
