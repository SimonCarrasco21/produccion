<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use App\Models\Categoria;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;

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
        // Validar si es un solo producto o una lista
        $productos = $request->has('productos') ? json_decode($request->productos, true) : [$request->all()];

        foreach ($productos as $producto) {
            // Validar cada producto individualmente
            $validator = Validator::make($producto, [
                'nombre' => 'required|string|max:255',
                'descripcion' => 'required|string|min:10',
                'precio' => 'required|numeric|min:0',
                'stock' => 'required|integer|min:0',
                'categoria_id' => 'required|exists:categorias,id',
                'fecha_vencimiento' => 'nullable|date|after_or_equal:today',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            // Crear el producto asociado al usuario autenticado
            Producto::create([
                'nombre' => $producto['nombre'],
                'descripcion' => $producto['descripcion'],
                'precio' => $producto['precio'],
                'stock' => $producto['stock'],
                'categoria_id' => $producto['categoria_id'],
                'fecha_vencimiento' => $producto['fecha_vencimiento'] ?? null,
                'user_id' => Auth::id(), // Asociar el producto al usuario autenticado
            ]);
        }

        return redirect('/agregar-producto')->with('success', 'Productos guardados correctamente.');
    }
    public function guardarProductoUnico(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string|min:10',
            'precio' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'categoria_id' => 'required|exists:categorias,id',
            'fecha_vencimiento' => 'nullable|date|after_or_equal:today',
        ]);

        Producto::create([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'precio' => $request->precio,
            'stock' => $request->stock,
            'categoria_id' => $request->categoria_id,
            'fecha_vencimiento' => $request->fecha_vencimiento,
            'user_id' => Auth::id(),
        ]);

        return redirect()->back()->with('success', 'Producto agregado correctamente.');
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

    public function mostrarFormulario()
    {
        $productos = Producto::where('user_id', Auth::id())->get(); // Obtiene los productos del usuario actual
        $categorias = Categoria::all(); // Obtiene las categorías disponibles
        return view('agregar-producto', compact('productos', 'categorias')); // Pasa las variables a la vista
    }


    public function obtenerDatosProducto(Request $request)
    {
        $codigoBarras = $request->codigo;

        // URL de la API Open Food Facts
        $url = "https://world.openfoodfacts.org/api/v0/product/{$codigoBarras}.json";

        // Realizar la consulta a la API
        $response = Http::get($url);

        // Verificar si el producto existe y la solicitud fue exitosa
        if ($response->ok() && $response->json('status') === 1) {
            $producto = $response->json('product');

            // Datos del producto
            $productoData = [
                'nombre' => $producto['product_name'] ?? 'Producto sin nombre',
                'descripcion' => $producto['categories'] ?? 'Descripción no disponible',
                'precio' => $producto['price'] ?? null, // Si la API incluye un precio
                'fecha_vencimiento' => $producto['expiration_date'] ?? null,
                'disponible_en' => $producto['countries_tags'] ?? [],
            ];

            return response()->json([
                'success' => true,
                'message' => 'Producto encontrado.',
                'producto' => $productoData,
                'raw_response' => $response->json() // Respuesta completa para pruebas
            ]);
        }

        // Respuesta en caso de que el producto no se encuentre
        return response()->json([
            'success' => false,
            'message' => 'Producto no encontrado en la base de datos.',
            'raw_response' => $response->json() // Respuesta completa para pruebas
        ]);
    }
}
