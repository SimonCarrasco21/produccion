<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use App\Models\Producto;
use Illuminate\Support\Facades\DB; // Añadido para utilizar la clase DB

class PagoPosController extends Controller
{
    public function mostrarVistaPago()
    {
        $productos = Producto::with('categoria')->get();
        $ventas = DB::table('ventas')->get(); // Obtener todas las ventas desde la base de datos

        return view('pago', compact('productos', 'ventas')); // Pasar ventas a la vista
    }

    public function procesarPagoPos(Request $request)
    {
        // Obtener el monto total de los productos seleccionados del JSON enviado por la vista
        $productosSeleccionados = json_decode($request->productosSeleccionados, true);

        // Validar que los productos seleccionados no sean nulos
        if (is_null($productosSeleccionados)) {
            return response()->json(['success' => false, 'error' => 'Productos seleccionados no válidos']);
        }

        $total = 0;
        foreach ($productosSeleccionados as $producto) {
            // Validar que el precio y la cantidad sean valores numéricos
            if (!isset($producto['precio']) || !is_numeric($producto['precio']) || !isset($producto['cantidad']) || !is_numeric($producto['cantidad'])) {
                return response()->json(['success' => false, 'error' => 'Datos de producto no válidos']);
            }
            $total += $producto['precio'] * $producto['cantidad'];
        }

        // Simulación del pago si no existe un POS físico
        $isSimulation = true; // Cambia a false para producción con un POS real

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
                'productos' => json_encode($productosSeleccionados),
                'metodo_pago' => 'POS',
                'created_at' => now(),
                'updated_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Pago POS simulado correctamente.',
                'data' => [
                    'external_reference' => $externalReference,
                    'status' => $status,
                    'amount' => $total,
                    'productos' => $productosSeleccionados,
                ]
            ])->header('Content-Type', 'application/json');
        }
    }

    public function pagarEnEfectivo(Request $request)
    {
        // Obtener el monto total de los productos seleccionados del JSON enviado por la vista
        $productosSeleccionados = json_decode($request->productosSeleccionados, true);

        // Validar que los productos seleccionados no sean nulos
        if (is_null($productosSeleccionados)) {
            return response()->json(['success' => false, 'error' => 'Productos seleccionados no válidos']);
        }

        $total = 0;
        foreach ($productosSeleccionados as $producto) {
            // Validar que el precio y la cantidad sean valores numéricos
            if (!isset($producto['precio']) || !is_numeric($producto['precio']) || !isset($producto['cantidad']) || !is_numeric($producto['cantidad'])) {
                return response()->json(['success' => false, 'error' => 'Datos de producto no válidos']);
            }
            $total += $producto['precio'] * $producto['cantidad'];
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
            'productos' => json_encode($productosSeleccionados),
            'metodo_pago' => 'Efectivo',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pago en efectivo registrado correctamente.',
            'data' => [
                'external_reference' => $externalReference,
                'status' => $status,
                'amount' => $total,
                'productos' => $productosSeleccionados,
            ]
        ])->header('Content-Type', 'application/json');
    }
}
