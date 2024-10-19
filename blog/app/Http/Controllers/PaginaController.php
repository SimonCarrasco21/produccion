<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use App\Models\Categoria;
use Illuminate\Support\Facades\DB;


class PaginaController extends Controller
{





    public function mostrarPagina()
    {
        $productos = Producto::all();
        $categorias = Categoria::all();
        return view('agregar-producto', compact('productos', 'categorias'));
    }

    public function guardarProducto(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'precio' => 'required|numeric',
            'stock' => 'required|integer',
            'categoria_id' => 'required|exists:categorias,id',
            'fecha_vencimiento' => 'nullable|date',
        ]);

        Producto::create([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'precio' => $request->precio,
            'stock' => $request->stock,
            'categoria_id' => $request->categoria_id,
            'fecha_vencimiento' => $request->fecha_vencimiento,
        ]);

        return redirect('/agregar-producto')->with('success', 'Producto agregado correctamente.');
    }

    public function mostrarProductosPorCategoria($id)
    {
        $categoria = Categoria::findOrFail($id);
        $productos = Producto::where('categoria_id', $id)->get();
        return view('productos-por-categoria', compact('categoria', 'productos'));
    }

    public function eliminarProducto($id)
    {
        $producto = Producto::findOrFail($id);
        $producto->delete();

        return redirect('/agregar-producto')->with('success', 'Producto eliminado correctamente.');
    }

    public function eliminarProductosSeleccionados(Request $request)
    {
        // Validar que se envíen productos seleccionados
        $request->validate([
            'productos' => 'required|array',
            'productos.*' => 'integer|exists:productos,id',
        ]);

        // Obtener los IDs de los productos seleccionados
        $productosIds = $request->input('productos');

        // Eliminar los productos seleccionados
        Producto::whereIn('id', $productosIds)->delete();

        return redirect('/inventario')->with('success', 'Productos eliminados correctamente.');
    }

    public function editarProducto($id)
    {
        $producto = Producto::findOrFail($id);
        $categorias = Categoria::all();
        return view('editar-producto', compact('producto', 'categorias'));
    }


    public function actualizarProducto(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'precio' => 'required|numeric',
            'stock' => 'required|integer',
            'categoria_id' => 'required|exists:categorias,id',
            'fecha_vencimiento' => 'nullable|date',
        ]);

        $producto = Producto::findOrFail($id);
        $producto->update([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'precio' => $request->precio,
            'stock' => $request->stock,
            'categoria_id' => $request->categoria_id,
            'fecha_vencimiento' => $request->fecha_vencimiento,
        ]);

        return redirect('/agregar-producto')->with('success', 'Producto actualizado correctamente.');
    }

    public function buscarProductos(Request $request)
    {
        $query = $request->input('query');
        $productos = Producto::where('nombre', 'LIKE', "%{$query}%")->get();
        $categorias = Categoria::all();
        return view('inventario', compact('productos', 'categorias'))->with('query', $query);
    }
}