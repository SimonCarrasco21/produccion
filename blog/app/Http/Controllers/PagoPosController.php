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

    public function procesarPagoPaypal(Request $request)
{
    // Obtener los productos seleccionados
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

        $total += $producto['precio'] * $producto['cantidad']; // Usa el precio directamente con formato 1000.00
        $productosParaVenta[] = [
            'id' => $producto['id'],
            'descripcion' => $productoModel->descripcion,
            'precio' => $producto['precio'], // Respetando el formato original
            'cantidad' => $producto['cantidad']
        ];
    }

    // Crear la orden de PayPal
    $provider = new \Srmklive\PayPal\Services\PayPal();
    $provider->setApiCredentials(config('paypal'));
    $provider->getAccessToken();

    $response = $provider->createOrder([
        "intent" => "CAPTURE",
        "application_context" => [
            "return_url" => route('paypal.success'),
            "cancel_url" => route('paypal.cancel'),
        ],
        "purchase_units" => [
            0 => [
                "amount" => [
                    "currency_code" => 'USD', // Asegúrate de usar USD si PayPal no soporta CLP
                    "value" => number_format($total, 2, '.', ''), // El total siempre con dos decimales
                ],
                "description" => "Pago en Mi Almacén",
            ],
        ],
    ]);

    if (isset($response['id']) && $response['status'] == "CREATED") {
        // Guardar la orden en la base de datos
        DB::table('ventas')->insert([
            'external_reference' => $response['id'],
            'status' => 'pending',
            'amount' => number_format($total, 2, '.', ''), // Guardamos el total formateado
            'productos' => json_encode($productosParaVenta),
            'metodo_pago' => 'PayPal',
            'user_id' => Auth::id(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Redirigir al usuario al enlace de aprobación de PayPal
        foreach ($response['links'] as $link) {
            if ($link['rel'] === 'approve') {
                return redirect()->away($link['href']);
            }
        }
    }

    return redirect()->back()->withErrors(['error' => 'No se pudo completar el pago. Inténtelo de nuevo.']);
}

public function guardarVenta(Request $request)
{
    $validated = $request->validate([
        'orderID' => 'required|string',
        'details' => 'required|array',
        'productos' => 'required|array',
    ]);

    // Procesar los productos seleccionados
    $productosParaGuardar = [];
    foreach ($validated['productos'] as $producto) {
        $productoModel = Producto::find($producto['id']);

        if (!$productoModel || $productoModel->stock < $producto['cantidad']) {
            return response()->json(['success' => false, 'message' => 'Stock insuficiente para el producto ' . $producto['nombre']]);
        }

        // Reducir el stock del producto
        $productoModel->stock -= $producto['cantidad'];
        $productoModel->save();

        // Agregar el producto al arreglo para guardar
        $productosParaGuardar[] = [
            'id' => $producto['id'],
            'nombre' => $producto['nombre'],
            'descripcion' => $producto['descripcion'],
            'precio' => $producto['precio'],
            'cantidad' => $producto['cantidad'],
        ];
    }

    // Guardar la venta en la base de datos
    DB::table('ventas')->insert([
        'external_reference' => $validated['orderID'],
        'status' => 'approved',
        'amount' => $validated['details']['purchase_units'][0]['amount']['value'],
        'productos' => json_encode($productosParaGuardar), // Guarda los productos incluyendo el nombre
        'metodo_pago' => 'PayPal',
        'user_id' => Auth::id(),
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    return response()->json(['success' => true]);
}



    public function pagoExitoso(Request $request)
    {
        $provider = new \Srmklive\PayPal\Services\PayPal();
        $provider->setApiCredentials(config('paypal'));
        $paypalToken = $provider->getAccessToken();

        $response = $provider->capturePaymentOrder($request->query('token'));

        if (isset($response['status']) && $response['status'] == 'COMPLETED') {
            // Actualizar el estado de la venta en la base de datos
            DB::table('ventas')
                ->where('external_reference', $response['id'])
                ->update([
                    'status' => 'approved',
                    'updated_at' => now(),
                ]);

            return redirect()->route('pagina.pago')->with('success', 'Pago completado con éxito.');
        }

        return redirect()->route('pagina.pago')->withErrors(['error' => 'El pago no pudo ser procesado.']);
    }

    public function pagoCancelado()
    {
        return redirect()->route('pagina.pago')->withErrors(['error' => 'El pago fue cancelado.']);
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
