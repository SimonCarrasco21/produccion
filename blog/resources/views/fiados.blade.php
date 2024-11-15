<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Fiados</title>
    <!-- Enlace a Bootstrap y Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <style>
        body {
            background-color: #d4edda;
            font-size: 1.1em;
        }

        .card {
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .navbar-dark {
            background-color: #000 !important;
        }

        .navbar .btn-success {
            margin-right: 10px;
            border-radius: 25px;
        }

        .navbar .nav-link {
            border-radius: 25px;
            background-color: #28a745;
            color: white !important;
            margin-right: 10px;
            padding: 8px 15px;
            transition: background-color 0.3s ease;
        }

        .navbar .nav-link:hover {
            background-color: #218838;
        }

        .navbar .btn-success i {
            margin-right: 5px;
        }

        .footer {
            background-color: #f8f9fa;
            padding: 20px 0;
        }

        .dropdown-btn {
            background-color: #4CAF50;
            color: white;
            padding: 12px 25px;
            font-size: 16px;
            border: none;
            border-radius: 12px;
            cursor: pointer;
            box-shadow: 0px 5px 10px rgba(0, 0, 0, 0.2);
            display: flex;
            align-items: center;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .dropdown-btn:hover {
            background-color: #45a049;
            transform: translateY(-2px);
        }

        .dropdown-menu {
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        }

        .dropdown-menu a,
        .dropdown-menu button {
            color: #000;
            padding: 10px;
            text-decoration: none;
            border-radius: 5px;
        }

        .dropdown-menu a:hover,
        .dropdown-menu button:hover {
            background-color: #e9ecef;
        }

        /* Letras en negrita para la tabla */
        .table thead th,
        .table tbody td {
            font-weight: bold;
        }
    </style>
</head>

<body>
    <!-- Barra de Navegación -->
    <nav class="navbar">
        <div class="navbar-left">
            <h2><i class="bi bi-person-circle"></i> Usuario: {{ Auth::user()->name }}</h2>
            <div class="dropdown">
                <button class="dropdown-btn" type="button" id="dropdownMenuButton" onclick="toggleDropdown()">
                    <i class="bi bi-person-circle"></i> Perfil
                </button>
                <ul class="dropdown-menu" id="dropdownMenu" aria-labelledby="dropdownMenuButton">
                    <li><a class="dropdown-item" href="{{ route('perfil') }}"><i class="bi bi-eye"></i> Ver Perfil</a>
                    </li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}"
                            onsubmit="return confirm('¿Estás seguro de que deseas cerrar sesión?');">
                            @csrf
                            <button type="submit" class="dropdown-item"><i class="bi bi-box-arrow-right"></i> Cerrar
                                Sesión</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>

        <div class="navbar-right">
            <ul>
                <li><a href="{{ route('dashboard') }}"><i class="bi bi-speedometer2"></i> Inicio</a></li>
                <li><a href="{{ route('agregar-producto') }}"><i class="bi bi-plus-circle"></i> Agregar Producto</a>
                </li>
                <li><a href="{{ route('registro-ventas') }}"><i class="bi bi-clock-history"></i> Ver Historial
                        Ventas</a></li>
                <li><a href="{{ route('inventario') }}"><i class="bi bi-box"></i> Inventario</a></li>
                <li><a href="{{ route('pagina.pago') }}" class="btn-pagar"><i class="bi bi-credit-card"></i> Pagar</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container mt-5">
        <h1 class="text-center">Fiar Productos</h1>

        <!-- Mensajes de éxito y error -->
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <!-- Productos y formulario en la misma fila -->
        <div class="row">
            <!-- Lista de productos -->
            <div class="col-lg-8 mb-4">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span>Productos Disponibles</span>
                        <input type="text" id="buscarProducto" class="form-control w-50"
                            placeholder="Buscar producto...">
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Descripción</th>
                                    <th>Precio</th>
                                    <th>Stock</th>
                                    <th>Acción</th>
                                </tr>
                            </thead>
                            <tbody id="tablaProductos">
                                @foreach ($productos as $producto)
                                    <tr>
                                        <td>{{ $producto->nombre }}</td>
                                        <td>{{ $producto->descripcion }}</td>
                                        <td>{{ $producto->precio }}</td>
                                        <td>{{ $producto->stock }}</td>
                                        <td>
                                            <button class="btn btn-success btn-agregar" data-id="{{ $producto->id }}"
                                                data-nombre="{{ $producto->nombre }}"
                                                data-precio="{{ $producto->precio }}"
                                                data-stock="{{ $producto->stock }}">
                                                <i class="bi bi-cart-plus"></i> Agregar
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Formulario de fiados y productos seleccionados -->
            <div class="col-lg-4 mb-4">
                <div class="card">
                    <div class="card-header">
                        Registrar Fiados
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('fiados.store') }}"
                            onsubmit="return verificarLimiteFiados();">
                            @csrf
                            <div class="mb-3">
                                <label for="id_cliente" class="form-label">ID Cliente</label>
                                <input type="text" name="id_cliente" id="id_cliente" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="nombre_cliente" class="form-label">Nombre del Cliente</label>
                                <input type="text" name="nombre_cliente" id="nombre_cliente" class="form-control"
                                    required>
                            </div>
                            <!-- Tabla de productos seleccionados -->
                            <div class="mb-3">
                                <label class="form-label">Productos Seleccionados</label>
                                <table class="table table-bordered" id="tablaProductosSeleccionados">
                                    <thead>
                                        <tr>
                                            <th>Producto</th>
                                            <th>Cantidad</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                            <button type="submit" class="btn btn-primary w-100"><i class="bi bi-save"></i> Registrar
                                Fiado</button>
                            <input type="hidden" name="productos" id="productosSeleccionados">
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabla de fiados registrados -->
        <div class="card">
            <div class="card-header">
                Fiados Registrados
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID Cliente</th>
                            <th>Nombre del Cliente</th>
                            <th>Productos</th>
                            <th>Total Precio</th>
                            <th>Fecha de Compra</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($fiados as $fiado)
                            <tr>
                                <td>{{ $fiado->id_cliente }}</td>
                                <td>{{ $fiado->nombre_cliente }}</td>
                                <td>
                                    @php
                                        $productos = json_decode($fiado->productos, true);
                                    @endphp
                                    @if (is_array($productos))
                                        @foreach ($productos as $producto)
                                            {{ $producto['nombre'] }} - ${{ $producto['precio_total'] }}
                                            (x{{ $producto['cantidad'] }})
                                            <br>
                                        @endforeach
                                    @else
                                        Sin productos registrados.
                                    @endif
                                </td>
                                <td>${{ $fiado->total_precio }}</td>
                                <td>{{ $fiado->fecha_compra }}</td>
                                <td>
                                    <form method="POST" action="{{ route('fiados.destroy', $fiado->id) }}"
                                        style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger"><i class="bi bi-trash"></i>
                                            Eliminar</button>
                                    </form>
                                    <button class="btn btn-success"><i class="bi bi-cash"></i> Pagar</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        let productosSeleccionados = [];

        function actualizarTablaProductosSeleccionados() {
            const tabla = document.getElementById('tablaProductosSeleccionados').getElementsByTagName('tbody')[0];
            tabla.innerHTML = '';
            productosSeleccionados.forEach(producto => {
                const row = tabla.insertRow();
                row.insertCell(0).textContent = producto.nombre;
                row.insertCell(1).textContent = producto.cantidad;
                row.insertCell(2).textContent = `$${producto.precio_total}`;
            });
            document.getElementById('productosSeleccionados').value = JSON.stringify(productosSeleccionados);
        }

        document.getElementById('buscarProducto').addEventListener('keyup', function() {
            const filtro = this.value.toLowerCase();
            const filas = document.querySelectorAll('#tablaProductos tr');

            filas.forEach(fila => {
                const nombre = fila.cells[0].textContent.toLowerCase();
                const descripcion = fila.cells[1].textContent.toLowerCase();
                fila.style.display = nombre.includes(filtro) || descripcion.includes(filtro) ? '' : 'none';
            });
        });

        document.querySelectorAll('.btn-agregar').forEach(button => {
            button.addEventListener('click', () => {
                const id = button.dataset.id;
                const nombre = button.dataset.nombre;
                const precio = parseFloat(button.dataset.precio);

                const productoExistente = productosSeleccionados.find(p => p.id === id);
                if (productoExistente) {
                    productoExistente.cantidad++;
                    productoExistente.precio_total += precio;
                } else {
                    productosSeleccionados.push({
                        id,
                        nombre,
                        cantidad: 1,
                        precio_unitario: precio,
                        precio_total: precio
                    });
                }

                actualizarTablaProductosSeleccionados();
            });
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
