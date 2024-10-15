<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use App\Models\Categoria; // Aseguramos que el modelo de Categoria esté incluido

class PaginaController extends Controller
{
    public function mostrarPagina()
    {
        $productos = Producto::all(); // Obtener todos los productos
        $categorias = Categoria::all(); // Obtener todas las categorías para el formulario
        return view('agregar-producto', compact('productos', 'categorias'));
    }

    public function guardarProducto(Request $request)
    {
        // Validar los datos
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'precio' => 'required|numeric',
            'stock' => 'required|integer',
            'categoria_id' => 'required|exists:categorias,id', // Asegurar que la categoría existe
            'fecha_vencimiento' => 'nullable|date', // La fecha de vencimiento es opcional
        ]);

        // Guardar los datos en la base de datos
        Producto::create([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'precio' => $request->precio,
            'stock' => $request->stock,
            'categoria_id' => $request->categoria_id, // Relación con la categoría seleccionada
            'fecha_vencimiento' => $request->fecha_vencimiento,
        ]);

        // Redireccionar después de guardar
        return redirect('/agregar-producto')->with('success', 'Producto agregado correctamente.');
    }


    public function mostrarProductosPorCategoria($id)
{
    $categoria = Categoria::findOrFail($id); // Busca la categoría por ID
    $productos = Producto::where('categoria_id', $id)->get(); // Busca productos que pertenezcan a esta categoría
    return view('productos-por-categoria', compact('categoria', 'productos'));
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
        $categorias = Categoria::all(); // Para mostrar las categorías disponibles al editar
        return view('editar-producto', compact('producto', 'categorias'));
    }

    public function actualizarProducto(Request $request, $id)
    {
        // Validar los datos
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'precio' => 'required|numeric',
            'stock' => 'required|integer',
            'categoria_id' => 'required|exists:categorias,id', // Validar que la categoría existe
            'fecha_vencimiento' => 'nullable|date',
        ]);

        // Buscar el producto y actualizarlo
        $producto = Producto::findOrFail($id);
        $producto->update([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'precio' => $request->precio,
            'stock' => $request->stock,
            'categoria_id' => $request->categoria_id, // Actualizar la categoría seleccionada
            'fecha_vencimiento' => $request->fecha_vencimiento,
        ]);

        // Redireccionar después de actualizar
        return redirect('/agregar-producto')->with('success', 'Producto actualizado correctamente.');
    }
}
