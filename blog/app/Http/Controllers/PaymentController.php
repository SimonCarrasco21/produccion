<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use Transbank\Webpay\WebpayPlus\Transaction;
use Log;

class PaymentController extends Controller
{
    public function mostrarVistaPago()
    {
        $productos = Producto::with('categoria')->get();
        return view('pago', compact('productos'));
    }

    public function payWithPOS(Request $request)
    {
        $productosSeleccionados = json_decode($request->input('productosSeleccionados', '[]'), true);
        if (!is_array($productosSeleccionados) || empty($productosSeleccionados)) {
            return redirect()->route('pagina.pago')->with('error', 'No se seleccionaron productos para el pago.');
        }

        // Calcular el total de la compra
        $total = 0;
        foreach ($productosSeleccionados as $productoSeleccionado) {
            if (!isset($productoSeleccionado['id'], $productoSeleccionado['cantidad'])) {
                return redirect()->route('pagina.pago')->with('error', 'Datos de producto no válidos.');
            }
            $producto = Producto::find($productoSeleccionado['id']);
            if ($producto) {
                $cantidad = (int) $productoSeleccionado['cantidad'];
                if ($cantidad > $producto->stock) {
                    return redirect()->route('pagina.pago')->with('error', 'Stock insuficiente para el producto: ' . $producto->nombre);
                }
                $total += $producto->precio * $cantidad;
                $producto->stock -= $cantidad;
                $producto->save();
            }
        }

        // Simular una conexión al POS y el proceso de pago
        $isConnectedToPOS = $this->simulatePOSConnection();

        if ($isConnectedToPOS) {
            $saleResponse = $this->simulateSale($total);
            if ($saleResponse['success']) {
                return redirect()->route('pagina.pago')->with('success', 'Pago realizado con éxito. Código de autorización: ' . $saleResponse['authorization_code']);
            } else {
                return redirect()->route('pagina.pago')->with('error', 'El pago fue rechazado: ' . $saleResponse['message']);
            }
        } else {
            return redirect()->route('pagina.pago')->with('error', 'No se pudo conectar a la máquina de pago.');
        }
    }

    private function simulatePOSConnection()
    {
        // Simulación de conexión al POS (50% de probabilidades de conectar)
        return (bool) rand(0, 1);
    }

    private function simulateSale($amount)
    {
        // Simular una respuesta de venta del POS
        if (rand(0, 1)) {
            return [
                'success' => true,
                'message' => 'Aprobado',
                'authorization_code' => strtoupper(uniqid()),
                'amount' => $amount
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Transacción rechazada por el banco'
            ];
        }
    }
}
