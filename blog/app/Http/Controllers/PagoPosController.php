<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use App\Models\Producto;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PagoPosController extends Controller
{
    public function mostrarVistaPago()
    {
        // Obtener todos los productos (categoría compartida entre usuarios)
        $productos = Producto::with('categoria')->get();
        // Obtener solo las ventas del usuario autenticado
        $ventas = DB::table('ventas')->where('user_id', Auth::id())->get();

        return view('pago', compact('productos', 'ventas'));
    }

    public function procesarPagoPos(Request $request)
    {
        // Obtener el monto total de los productos seleccionados
        $productosSeleccionados = json_decode($request->productosSeleccionados, true);

        if (is_null($productosSeleccionados) || empty($productosSeleccionados)) {
            return response()->json(['success' => false, 'error' => 'No se seleccionaron productos para el pago']);
        }

        $total = 0;
        $productosParaVenta = [];

        foreach ($productosSeleccionados as $producto) {
            if (!isset($producto['precio']) || !is_numeric($producto['precio'])) {
                return response()->json(['success' => false, 'error' => 'Datos de producto no válidos']);
            }

            // Asegurarse de que siempre haya una cantidad asignada
            $producto['cantidad'] = $producto['cantidad'] ?? 1;

            // Calcular el total
            $total += $producto['precio'] * $producto['cantidad'];

            // Cambiar 'nombre' por 'descripcion'
            $productoModel = Producto::find($producto['id']);
            if ($productoModel) {
                $productosParaVenta[] = [
                    'id' => $producto['id'],
                    'descripcion' => $productoModel->descripcion,
                    'precio' => $producto['precio'],
                    'cantidad' => $producto['cantidad']
                ];
            } else {
                return response()->json(['success' => false, 'error' => 'Producto no encontrado']);
            }
        }

        $isSimulation = true; // Cambiar a false para un POS real en producción

        if ($isSimulation) {
            // Actualizar el stock de los productos en la base de datos
            foreach ($productosSeleccionados as $productoSeleccionado) {
                $producto = Producto::find($productoSeleccionado['id']);
                if ($producto) {
                    $producto->stock -= $productoSeleccionado['cantidad'];
                    $producto->save();
                }
            }

            // Generar datos de la venta simulada
            $externalReference = uniqid();
            $status = 'approved';

            // Guardar los datos de la venta en la tabla `ventas`
            DB::table('ventas')->insert([
                'external_reference' => $externalReference,
                'status' => $status,
                'amount' => $total,
                'productos' => json_encode($productosParaVenta),
                'metodo_pago' => 'POS',
                'user_id' => Auth::id(), // Asocia la venta al usuario autenticado
                'created_at' => now()->setTimezone('America/Santiago'), // Guardar la fecha y hora actual (Zona Horaria Chile)
                'updated_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Pago POS simulado correctamente.',
                'data' => [
                    'external_reference' => $externalReference,
                    'status' => $status,
                    'amount' => $total,
                    'fecha' => now()->setTimezone('America/Santiago')->format('Y-m-d H:i:s'), // Incluir la fecha y hora actual en el JSON de respuesta
                    'productos' => $productosParaVenta,
                ]
            ])->header('Content-Type', 'application/json');
        }
    }

    public function pagarEnEfectivo(Request $request)
    {
        $productosSeleccionados = json_decode($request->productosSeleccionados, true);

        if (is_null($productosSeleccionados) || empty($productosSeleccionados)) {
            return response()->json(['success' => false, 'error' => 'No se seleccionaron productos para el pago']);
        }

        $total = 0;
        $productosParaVenta = [];

        foreach ($productosSeleccionados as $producto) {
            if (!isset($producto['precio']) || !is_numeric($producto['precio'])) {
                return response()->json(['success' => false, 'error' => 'Datos de producto no válidos']);
            }

            // Asegurarse de que siempre haya una cantidad asignada
            $producto['cantidad'] = $producto['cantidad'] ?? 1;

            // Calcular el total
            $total += $producto['precio'] * $producto['cantidad'];

            // Cambiar 'nombre' por 'descripcion'
            $productoModel = Producto::find($producto['id']);
            if ($productoModel) {
                $productosParaVenta[] = [
                    'id' => $producto['id'],
                    'descripcion' => $productoModel->descripcion,
                    'precio' => $producto['precio'],
                    'cantidad' => $producto['cantidad']
                ];
            } else {
                return response()->json(['success' => false, 'error' => 'Producto no encontrado']);
            }
        }

        // Actualizar el stock de los productos en la base de datos
        foreach ($productosSeleccionados as $productoSeleccionado) {
            $producto = Producto::find($productoSeleccionado['id']);
            if ($producto) {
                $producto->stock -= $productoSeleccionado['cantidad'];
                $producto->save();
            }
        }

        // Generar datos de la venta en efectivo
        $externalReference = uniqid();
        $status = 'approved';

        // Guardar los datos de la venta en la tabla `ventas`
        DB::table('ventas')->insert([
            'external_reference' => $externalReference,
            'status' => $status,
            'amount' => $total,
            'productos' => json_encode($productosParaVenta),
            'metodo_pago' => 'Efectivo',
            'user_id' => Auth::id(), // Asocia la venta al usuario autenticado
            'created_at' => now()->setTimezone('America/Santiago'), // Guardar la fecha y hora actual (Zona Horaria Chile)
            'updated_at' => now()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pago en efectivo registrado correctamente.',
            'data' => [
                'external_reference' => $externalReference,
                'status' => $status,
                'amount' => $total,
                'fecha' => now()->setTimezone('America/Santiago')->format('Y-m-d H:i:s'), // Incluir la fecha y hora actual en el JSON de respuesta
                'productos' => $productosParaVenta,
            ]
        ])->header('Content-Type', 'application/json');
    }
}
