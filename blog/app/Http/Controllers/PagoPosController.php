<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use App\Models\Fiado;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PagoPosController extends Controller
{
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
        return $this->procesarPago($request, 'POS');
    }


    public function pagarEnEfectivo(Request $request)
    {
        // Verificar si se seleccionaron productos
        $productosSeleccionados = json_decode($request->productosSeleccionados, true);

        if (is_null($productosSeleccionados) || empty($productosSeleccionados)) {
            return response()->json([
                'success' => false,
                'error' => 'No se seleccionaron productos para el pago.'
            ]);
        }

        // Confirmación del usuario
        if (!$request->has('confirmacion') || $request->input('confirmacion') !== 'true') {
            return response()->json([
                'success' => false,
                'message' => 'Pago cancelado por el usuario.'
            ]);
        }

        $total = 0;
        $productosParaVenta = [];

        foreach ($productosSeleccionados as $producto) {
            if (!isset($producto['precio']) || !isset($producto['cantidad'])) {
                return response()->json([
                    'success' => false,
                    'error' => 'Datos de producto no válidos.'
                ]);
            }

            $total += $producto['precio'] * $producto['cantidad'];

            $productoModel = Producto::find($producto['id']);
            if ($productoModel) {
                $productosParaVenta[] = [
                    'id' => $producto['id'],
                    'descripcion' => $productoModel->descripcion,
                    'precio' => $producto['precio'],
                    'cantidad' => $producto['cantidad']
                ];

                // Reducir el stock del producto
                $productoModel->stock -= $producto['cantidad'];
                $productoModel->save();
            } else {
                return response()->json(['success' => false, 'error' => 'Producto no encontrado.']);
            }
        }

        $idFiado = $request->input('id_fiado');
        if ($idFiado) {
            Fiado::where('id', $idFiado)
                ->where('user_id', Auth::id())
                ->delete();
        }

        $externalReference = uniqid();
        DB::table('ventas')->insert([
            'external_reference' => $externalReference,
            'status' => 'approved',
            'amount' => $total,
            'productos' => json_encode($productosParaVenta),
            'metodo_pago' => 'Efectivo',
            'user_id' => Auth::id(),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pago en efectivo registrado correctamente.',
            'data' => [
                'external_reference' => $externalReference,
                'status' => 'approved',
                'amount' => $total,
                'fecha' => now()->format('Y-m-d H:i:s'),
                'productos' => $productosParaVenta
            ]
        ]);
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
            if (!isset($producto['precio']) || !isset($producto['cantidad'])) {
                return response()->json(['success' => false, 'error' => 'Datos de producto no válidos']);
            }

            $total += $producto['precio'] * $producto['cantidad'];

            $productoModel = Producto::find($producto['id']);
            if ($productoModel) {
                $productosParaVenta[] = [
                    'id' => $producto['id'],
                    'descripcion' => $productoModel->descripcion,
                    'precio' => $producto['precio'],
                    'cantidad' => $producto['cantidad'],
                ];

                // Reducir el stock del producto
                $productoModel->stock -= $producto['cantidad'];
                $productoModel->save();
            } else {
                return response()->json(['success' => false, 'error' => 'Producto no encontrado']);
            }
        }

        $idFiado = $request->input('id_fiado');
        if ($idFiado) {
            Fiado::where('id', $idFiado)
                ->where('user_id', Auth::id())
                ->delete();
        }

        $externalReference = uniqid();
        DB::table('ventas')->insert([
            'external_reference' => $externalReference,
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
                'external_reference' => $externalReference,
                'status' => 'approved',
                'amount' => $total,
                'fecha' => now()->format('Y-m-d H:i:s'),
                'productos' => $productosParaVenta,
            ]
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
                'descripcion' => $producto['nombre'], // Mapeo de 'nombre' a 'descripcion'
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
