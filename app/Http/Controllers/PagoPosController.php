<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use App\Models\Fiado;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PagoPosController extends Controller
{

    public function buscarProductos(Request $request)
    {
        $query = $request->input('query');

        $productos = Producto::with('categoria')
            ->where('user_id', Auth::id())
            ->where(function ($q) use ($query) {
                $q->where('nombre', 'like', "%$query%")
                    ->orWhere('descripcion', 'like', "%$query%")
                    ->orWhereHas('categoria', function ($q2) use ($query) {
                        $q2->where('nombre', 'like', "%$query%");
                    });
            })
            ->get();

        return response()->json($productos);
    }
    public function mostrarVistaPago(Request $request)
    {
        $productos = Producto::with('categoria')->get();
        $ventas = DB::table('ventas')->where('user_id', Auth::id())->get();

        $productosFiados = json_decode($request->input('productosFiados', '[]'), true);
        $totalFiado = $request->input('totalFiado', 0);
        $idFiado = $request->input('id_fiado', null);
        $idCliente = $request->input('id_cliente', null);
        $nombreCliente = $request->input('nombre_cliente', null);

        return view('pago', compact(
            'productos',
            'ventas',
            'productosFiados',
            'totalFiado',
            'idFiado',
            'idCliente',
            'nombreCliente'
        ));
    }

    public function procesarPagoPos(Request $request)
    {
        // Procesar el pago como POS utilizando la función común
        $response = $this->procesarPago($request, 'POS');

        // Verificar si el pago fue exitoso antes de continuar
        if ($response->getData()->success) {
            // Eliminar el fiado si existe
            $idFiado = $request->input('id_fiado');
            if ($idFiado) {
                Fiado::where('id', $idFiado)
                    ->where('user_id', Auth::id())
                    ->delete();
            }
        }

        // Retornar la respuesta del pago (como JSON en este caso)
        return $response;
    }


    public function pagarEnEfectivo(Request $request)
    {
        // Verificar si se seleccionaron productos
        $productosSeleccionados = json_decode($request->productosSeleccionados, true);

        if (is_null($productosSeleccionados) || empty($productosSeleccionados)) {
            return redirect()->back()->withErrors(['error' => 'No se seleccionaron productos para el pago.']);
        }

        $total = 0;
        $productosParaVenta = [];

        foreach ($productosSeleccionados as $producto) {
            $productoModel = Producto::find($producto['id']);

            if (!$productoModel || $productoModel->stock < $producto['cantidad']) {
                return redirect()->back()->withErrors(['error' => 'Producto no disponible o stock insuficiente.']);
            }

            // Reducir el stock del producto
            $productoModel->stock -= $producto['cantidad'];
            $productoModel->save();

            $total += $producto['precio'] * $producto['cantidad'];
            $productosParaVenta[] = [
                'id' => $producto['id'],
                'descripcion' => $productoModel->descripcion,
                'precio' => $producto['precio'],
                'cantidad' => $producto['cantidad']
            ];
        }

        // Si hay fiado, eliminarlo
        $idFiado = $request->input('id_fiado');
        if ($idFiado) {
            Fiado::where('id', $idFiado)
                ->where('user_id', Auth::id())
                ->delete();
        }

        // Registrar venta
        DB::table('ventas')->insert([
            'external_reference' => uniqid(),
            'status' => 'approved',
            'amount' => $total,
            'productos' => json_encode($productosParaVenta),
            'metodo_pago' => 'Efectivo',
            'user_id' => Auth::id(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Redirigir con éxito
        return redirect()->route('pagina.pago')->with('success', 'Pago en efectivo registrado correctamente.');
    }

    private function procesarPago(Request $request, $metodoPago)
    {
        $productosSeleccionados = json_decode($request->productosSeleccionados, true);

        if (is_null($productosSeleccionados) || empty($productosSeleccionados)) {
            return response()->json(['success' => false, 'error' => 'No se seleccionaron productos para el pago']);
        }

        $total = 0;
        $productosParaVenta = [];

        foreach ($productosSeleccionados as $producto) {
            $productoModel = Producto::find($producto['id']);

            if (!$productoModel || $productoModel->stock < $producto['cantidad']) {
                return response()->json(['success' => false, 'error' => 'Producto no disponible o stock insuficiente']);
            }

            $productoModel->stock -= $producto['cantidad'];
            $productoModel->save();

            $total += $producto['precio'] * $producto['cantidad'];
            $productosParaVenta[] = [
                'id' => $producto['id'],
                'descripcion' => $productoModel->descripcion,
                'precio' => $producto['precio'],
                'cantidad' => $producto['cantidad']
            ];
        }

        DB::table('ventas')->insert([
            'external_reference' => uniqid(),
            'status' => 'approved',
            'amount' => $total,
            'productos' => json_encode($productosParaVenta),
            'metodo_pago' => $metodoPago,
            'user_id' => Auth::id(),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return response()->json([
            'success' => true,
            'message' => "Pago $metodoPago registrado correctamente.",
            'data' => [
                'external_reference' => uniqid(),
                'status' => 'approved',
                'amount' => $total,
                'productos' => $productosParaVenta,
                'fecha' => now()->format('Y-m-d H:i:s'),
            ],
        ]);
    }

    public function pagoFiado($id)
    {
        $fiado = Fiado::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $productos = json_decode($fiado->productos, true);

        $productosTransformados = array_map(function ($producto) {
            return [
                'id' => $producto['id'],
                'descripcion' => $producto['nombre'],
                'precio' => $producto['precio_unitario'],
                'cantidad' => $producto['cantidad'],
            ];
        }, $productos);

        return redirect()->route('pagina.pago', [
            'productosFiados' => json_encode($productosTransformados),
            'totalFiado' => $fiado->total_precio,
            'id_fiado' => $fiado->id,
            'id_cliente' => $fiado->id_cliente,
            'nombre_cliente' => $fiado->nombre_cliente,
        ]);
    }
}
