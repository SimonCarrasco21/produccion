<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;

class PaginaController extends Controller
{
    public function mostrarPagina()
    {
        $productos = Producto::all(); // Obtener todos los productos
        return view('agregar-producto', compact('productos'));
    }

    public function guardarProducto(Request $request)
    {
        // Validar los datos
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'precio' => 'required|numeric',
            'stock' => 'required|integer',
        ]);

        // Guardar los datos en la base de datos
        Producto::create([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'precio' => $request->precio,
            'stock' => $request->stock,
        ]);

        // Redireccionar después de guardar
        return redirect('/agregar-producto')->with('success', 'Producto agregado correctamente.');
    }

    public function eliminarProducto($id)
    {
        $producto = Producto::findOrFail($id);
        $producto->delete();

        return redirect('/agregar-producto')->with('success', 'Producto eliminado correctamente.');
    }

    public function editarProducto($id)
    {
        $producto = Producto::findOrFail($id);
        return view('editar-producto', compact('producto'));
    }

    public function actualizarProducto(Request $request, $id)
    {
        // Validar los datos
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'precio' => 'required|numeric',
            'stock' => 'required|integer',
        ]);

        // Buscar el producto y actualizarlo
        $producto = Producto::findOrFail($id);
        $producto->update([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'precio' => $request->precio,
            'stock' => $request->stock,
        ]);

        // Redireccionar después de actualizar
        return redirect('/agregar-producto')->with('success', 'Producto actualizado correctamente.');
    }
}