<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use App\Models\Producto;

class PagoPosController extends Controller
{
    public function mostrarVistaPago()
    {
        $productos = Producto::with('categoria')->get();
        return view('pago', compact('productos'));
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

            return response()->json([
                'success' => true,
                'message' => 'Pago POS simulado correctamente.',
                'data' => [
                    'external_reference' => uniqid(),
                    'status' => 'approved',
                    'amount' => $total,
                    'productos' => $productosSeleccionados,
                ]
            ])->header('Content-Type', 'application/json');
        }

        // Configurar el entorno (sandbox o producción)
        $isSandbox = true; // Cambia a false para producción
        $accessToken = $isSandbox ? 'TEST-8674287677447453-110619-f017c9086cd648f01550e47e44154eab-803284671' : 'APP_USR-8674287677447453-110619-f017c9086cd648f01550e47e44154eab';

        // Crear un POS virtual si no existe un POS ID
        $posId = $this->obtenerPosVirtual($accessToken);
        if (!$posId) {
            return response()->json(['success' => false, 'error' => 'No se pudo crear un POS virtual para la simulación.']);
        }

        // Datos para la solicitud de pago POS
        $collectorId = '8674287677447453'; // Reemplazado con el Client ID proporcionado
        $endpoint = "https://api.mercadopago.com/instore/orders/qr/seller/collectors/$collectorId/pos/$posId/qrs";

        // Crear una instancia de Guzzle
        $client = new Client();

        try {
            // Realizar la solicitud a la API de Mercado Pago
            $response = $client->post($endpoint, [
                'headers' => [
                    'Authorization' => "Bearer $accessToken",
                    'Content-Type'  => 'application/json',
                ],
                'json' => [
                    'external_reference' => uniqid(), // Referencia única para rastrear el pago
                    'notification_url' => 'https://tu-sitio.com/notificacion', // URL de notificación
                    'items' => [
                        [
                            'title' => 'Pago de productos seleccionados',
                            'quantity' => 1,
                            'unit_price' => $total,
                        ]
                    ]
                ],
            ]);

            // Decodificar la respuesta de la API
            $responseBody = json_decode($response->getBody(), true);

            // Actualizar el stock de los productos en la base de datos si el pago es exitoso
            foreach ($productosSeleccionados as $productoSeleccionado) {
                $producto = Producto::find($productoSeleccionado['id']);
                if ($producto) {
                    $producto->stock -= $productoSeleccionado['cantidad'];
                    $producto->save();
                }
            }

            return response()->json(['success' => true, 'message' => 'Pago POS iniciado correctamente.', 'data' => $responseBody]);
        } catch (\Exception $e) {
            // En caso de error, devolver un mensaje al usuario
            Log::error('Error en la solicitud de Mercado Pago: ' . $e->getMessage());
            return response()->json(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    private function obtenerPosVirtual($accessToken)
    {
        // Crear un POS virtual para pruebas
        $endpoint = "https://api.mercadopago.com/pos";

        $client = new Client();

        try {
            $response = $client->post($endpoint, [
                'headers' => [
                    'Authorization' => "Bearer $accessToken",
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'name' => 'POS Virtual Test',
                    'fixed_amount' => false,
                    'category' => 621102, // Código de categoría del negocio
                    'external_id' => 'POS_' . uniqid(), // ID externo del POS
                    'store_id' => '123456', // Puedes personalizar este valor si tienes varias tiendas
                ],
            ]);

            $responseBody = json_decode($response->getBody(), true);

            return $responseBody['id'] ?? null;
        } catch (\Exception $e) {
            Log::error('Error al crear POS virtual: ' . $e->getMessage());
            return null;
        }
    }
}
