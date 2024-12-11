<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

    <title>Reporte de Ventas</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .text-center {
            text-align: center;
        }

        .table {
            width: 100%;
            margin: 20px 0;
            border-collapse: collapse;
        }

        .table,
        .table th,
        .table td {
            border: 1px solid black;
            padding: 8px;
        }

        .mt-5 {
            margin-top: 3rem;
        }
    </style>
</head>

<body>
    <h1 class="text-center">Reporte de Ventas</h1>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Referencia</th>
                <th>Estado</th>
                <th>Monto</th>
                <th>Productos</th>
                <th>Método de Pago</th>
                <th>Fecha de Compra</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($ventas as $venta)
                <tr>
                    <td>{{ $venta->external_reference }}</td>
                    <td>{{ $venta->status }}</td>
                    <td>{{ $venta->amount }}</td>
                    <td>
                        @foreach (json_decode($venta->productos) as $producto)
                            {{ $producto->descripcion ?? 'Producto sin descripción' }}
                            (x{{ $producto->cantidad ?? 1 }})
                            <br>
                        @endforeach
                    </td>
                    <td>{{ $venta->metodo_pago }}</td>
                    <td>{{ \Carbon\Carbon::parse($venta->created_at)->format('d-m-Y H:i:s') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="mt-5">
        <strong>Total de Ganancias: </strong> ${{ $totalGanancias }}
    </div>
</body>

</html>
