<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use App\Models\Categoria;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PaginaController extends Controller
{
    public function mostrarPagina()
    {
        // Obtener solo los productos del usuario autenticado
        $productos = Producto::where('user_id', Auth::id())->get();
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

        // Crear el producto asociado al usuario autenticado
        Producto::create([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'precio' => $request->precio,
            'stock' => $request->stock,
            'categoria_id' => $request->categoria_id,
            'fecha_vencimiento' => $request->fecha_vencimiento,
            'user_id' => Auth::id(), // Asociar el producto al usuario autenticado
        ]);

        return redirect('/agregar-producto')->with('success', 'Producto agregado correctamente.');
    }

    public function mostrarProductosPorCategoria($id)
    {
        $categoria = Categoria::findOrFail($id);
        // Obtener solo los productos de la categoría para el usuario autenticado
        $productos = Producto::where('categoria_id', $id)
            ->where('user_id', Auth::id())
            ->get();
        return view('productos-por-categoria', compact('categoria', 'productos'));
    }

    public function eliminarProducto($id)
    {
        // Encontrar el producto y asegurarse de que pertenezca al usuario autenticado
        $producto = Producto::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
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

        // Obtener los IDs de los productos seleccionados del usuario autenticado
        $productosIds = $request->input('productos');

        // Eliminar solo los productos que pertenecen al usuario autenticado
        Producto::whereIn('id', $productosIds)
            ->where('user_id', Auth::id())
            ->delete();

        return redirect('/inventario')->with('success', 'Productos eliminados correctamente.');
    }

    public function editarProducto($id)
    {
        // Encontrar el producto y asegurarse de que pertenezca al usuario autenticado
        $producto = Producto::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
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

        // Encontrar el producto del usuario autenticado
        $producto = Producto::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

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
        // Buscar solo los productos del usuario autenticado
        $productos = Producto::where('nombre', 'LIKE', "%{$query}%")
            ->where('user_id', Auth::id())
            ->get();
        $categorias = Categoria::all();
        return view('inventario', compact('productos', 'categorias'))->with('query', $query);
    }
}
