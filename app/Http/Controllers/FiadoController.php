<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use App\Models\Fiado;
use Illuminate\Support\Facades\Auth;

class FiadoController extends Controller
{
    // Mostrar la vista de fiados con los productos disponibles y los fiados del usuario autenticado
    public function index()
    {
        $productos = Producto::all(); // Obtener todos los productos
        $fiados = Fiado::where('user_id', Auth::id())->get(); // Obtener los fiados del usuario autenticado
        return view('fiados', compact('productos', 'fiados'));
    }

    // Redirigir a la vista de pagos con los datos del fiado seleccionado
    public function pagar($id)
    {
        // Encontrar el fiado y verificar que pertenezca al usuario autenticado
        $fiado = Fiado::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        // Decodificar los productos del fiado para pasarlos a la vista de pago
        $productos = json_decode($fiado->productos, true);

        // Redirigir a la vista de pagos con los datos del fiado
        return view('pago', [
            'productos' => $productos,
            'total_precio' => $fiado->total_precio,
            'id_cliente' => $fiado->id_cliente,
            'nombre_cliente' => $fiado->nombre_cliente,
        ]);
    }

    // Almacenar un nuevo fiado en la base de datos
    public function store(Request $request)
    {
        $request->validate([
            'id_cliente' => 'required',
            'nombre_cliente' => 'required',
            'productos' => 'required|json', // Validar que productos sea un JSON válido
        ]);

        // Contar la cantidad de fiados existentes para este cliente y usuario
        $fiadosCount = Fiado::where('id_cliente', $request->id_cliente)
            ->where('user_id', Auth::id())
            ->count();

        // Validar si el cliente ya tiene 2 fiados
        if ($fiadosCount >= 2) {
            return back()->with('error', 'El cliente ya tiene 2 fiados y no puede registrar más.');
        }

        // Decodificar los productos seleccionados
        $productosSeleccionados = json_decode($request->productos, true);
        $total_precio = 0;

        // Validar stock y calcular el total
        foreach ($productosSeleccionados as $productoData) {
            $producto = Producto::find($productoData['id']);
            if (!$producto) {
                return back()->with('error', 'Uno de los productos seleccionados no existe.');
            }
            if ($producto->stock < $productoData['cantidad']) {
                return back()->with('error', "Stock insuficiente para el producto {$producto->nombre}.");
            }
            $total_precio += $productoData['precio_total'];
        }

        // Registrar el fiado y reducir el stock de los productos
        foreach ($productosSeleccionados as $productoData) {
            $producto = Producto::find($productoData['id']);
            $producto->stock -= $productoData['cantidad'];
            $producto->save();
        }

        // Crear el registro de fiado
        Fiado::create([
            'id_cliente' => $request->id_cliente,
            'nombre_cliente' => $request->nombre_cliente,
            'productos' => json_encode($productosSeleccionados), // Guardar los productos como JSON
            'total_precio' => $total_precio,
            'fecha_compra' => now(),
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

        // Eliminar el registro del fiado
        $fiado->delete();

        return back()->with('success', 'Fiado eliminado correctamente.');
    }
}
