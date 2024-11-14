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
    <link rel="stylesheet" href="{{ asset('css/style.css') }}"> <!-- Enlace al CSS separado -->
    <style>
        body {
            background-color: #d4edda;
            /* Fondo verde claro */
        }

        .card {
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            /* Añadir sombra para efecto de elevación */
        }

        .navbar-dark {
            background-color: #000 !important;
            /* Barra de navegación negra */
        }

        .navbar .btn-success {
            margin-right: 10px;
            border-radius: 25px;
            /* Bordes redondeados para los botones de la barra */
        }

        .navbar .nav-link {
            border-radius: 25px;
            background-color: #28a745;
            /* Color verde */
            color: white !important;
            margin-right: 10px;
            padding: 8px 15px;
            transition: background-color 0.3s ease;
        }

        .navbar .nav-link:hover {
            background-color: #218838;
            /* Color verde oscuro al pasar el ratón */
        }

        .navbar .btn-success i {
            margin-right: 5px;
            /* Espacio entre el ícono y el texto */
        }

        .footer {
            background-color: #f8f9fa;
            padding: 20px 0;
        }

        .footer .col {
            text-align: left;
        }

        .footer a {
            color: #000;
            text-decoration: none;
        }

        /* Estilo ajustado del botón de Perfil */
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
                <!-- Botón de Dashboard -->
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

    <h1 class="text-center" style="font-weight: bold; margin-top: 20px;">Fiar Producto</h1>

    <div class="container mt-5">
        <!-- Mostrar Mensajes de Éxito o Error -->
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <!-- Fila que contiene la tabla de productos y el formulario de registrar fiado -->
        <div class="row">
            <!-- Lista de Productos Disponibles para Fiar -->
            <div class="col-lg-8 mb-4">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3>Productos Disponibles</h3>
                        <input type="text" id="buscarProducto" class="form-control w-50"
                            placeholder="Buscar producto por nombre o detalle...">
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered" id="tablaProductos">
                            <thead class="table-dark">
                                <tr>
                                    <th>Nombre</th>
                                    <th>Descripción</th>
                                    <th>Precio</th>
                                    <th>Stock</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($productos as $producto)
                                    <tr>
                                        <td>{{ $producto->nombre }}</td>
                                        <td>{{ $producto->descripcion }}</td>
                                        <td>{{ $producto->precio }}</td>
                                        <td>{{ $producto->stock }}</td>
                                        <td>
                                            <button class="btn btn-success"
                                                onclick="agregarAlFiado('{{ $producto->id }}', '{{ $producto->nombre }}', '{{ $producto->precio }}')">
                                                <i class="bi bi-cart-plus"></i> Agregar al Fiado
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Formulario para Registrar un Nuevo Fiado -->
            <div class="col-lg-4 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h3>Registrar Fiado</h3>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('fiados.store') }}">
                            @csrf
                            <div class="mb-3">
                                <label for="id_cliente" class="form-label">ID Cliente:</label>
                                <input type="text" name="id_cliente" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label for="nombre_cliente" class="form-label">Nombre del Cliente:</label>
                                <input type="text" name="nombre_cliente" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label for="producto" class="form-label">Producto:</label>
                                <input type="text" name="producto" id="producto" class="form-control" readonly>
                            </div>

                            <div class="mb-3">
                                <label for="cantidad" class="form-label">Cantidad:</label>
                                <input type="number" name="cantidad" id="cantidad" class="form-control" min="1"
                                    required oninput="calcularPrecioTotal()">
                            </div>

                            <div class="mb-3">
                                <label for="precio" class="form-label">Precio:</label>
                                <input type="text" name="precio" id="precio" class="form-control" readonly>
                            </div>

                            <div class="mb-3">
                                <label for="fecha_compra" class="form-label">Fecha de Compra:</label>
                                <input type="date" name="fecha_compra" class="form-control" required>
                            </div>

                            <button type="submit" class="btn btn-success w-100"><i class="bi bi-save"></i> Agregar
                                Fiado</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabla para Visualizar los Fiados Registrados -->
        <div class="card mb-4">
            <div class="card-header">
                <h3>Fiados Registrados</h3>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th>ID Cliente</th>
                            <th>Nombre del Cliente</th>
                            <th>Producto</th>
                            <th>Cantidad</th>
                            <th>Precio Total</th>
                            <th>Fecha de Compra</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($fiados as $fiado)
                            <tr>
                                <td>{{ $fiado->id_cliente }}</td>
                                <td>{{ $fiado->nombre_cliente }}</td>
                                <td>{{ $fiado->producto }}</td>
                                <td>{{ $fiado->cantidad }}</td>
                                <td>{{ $fiado->precio }}</td>
                                <td>{{ $fiado->fecha_compra }}</td>
                                <td>
                                    <form method="POST" action="{{ route('fiados.destroy', $fiado->id) }}"
                                        style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-success"><i class="bi bi-trash"></i>
                                            Eliminar</button>
                                    </form>
                                    <button type="button" class="btn btn-success"><i class="bi bi-cash"></i>
                                        Pagar</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Script para Manejar la Selección de Producto y Filtro de Búsqueda -->
    <script>
        let precioUnitario = 0;

        function agregarAlFiado(id, nombre, precio) {
            // Rellenar los campos del producto y precio en el formulario de fiados
            document.getElementById('producto').value = nombre;
            precioUnitario = parseFloat(precio); // Guardar el precio unitario del producto
            document.getElementById('precio').value = precioUnitario; // Mostrar el precio unitario inicialmente
            calcularPrecioTotal();
        }

        function calcularPrecioTotal() {
            const cantidad = parseInt(document.getElementById('cantidad').value) || 1;
            const precioTotal = precioUnitario * cantidad;
            document.getElementById('precio').value = precioTotal.toFixed(2); // Mostrar el precio total según la cantidad
        }

        // Script para el filtro de búsqueda en la tabla de productos
        document.getElementById('buscarProducto').addEventListener('keyup', function() {
            const filtro = this.value.toLowerCase();
            const filas = document.querySelectorAll('#tablaProductos tbody tr');

            filas.forEach(fila => {
                const nombre = fila.querySelector('td:nth-child(1)').textContent.toLowerCase();
                const descripcion = fila.querySelector('td:nth-child(2)').textContent.toLowerCase();
                if (nombre.includes(filtro) || descripcion.includes(filtro)) {
                    fila.style.display = '';
                } else {
                    fila.style.display = 'none';
                }
            });
        });
    </script>

    <script>
        function toggleDropdown() {
            const dropdownMenu = document.getElementById('dropdownMenu');
            dropdownMenu.style.display = dropdownMenu.style.display === 'block' ? 'none' : 'block';
        }

        window.onclick = function(event) {
            if (!event.target.matches('.dropdown-btn')) {
                const dropdowns = document.getElementsByClassName("dropdown-menu");
                for (let i = 0; i < dropdowns.length; i++) {
                    const openDropdown = dropdowns[i];
                    if (openDropdown.style.display === 'block') {
                        openDropdown.style.display = 'none';
                    }
                }
            }
        }
    </script>
    <!-- Enlaces a los scripts de Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

<!-- Pie de Página -->
<footer class="bg-light text-center text-lg-start mt-5">
    <div class="container p-4">
        <div class="row">
            <!-- Enlaces rápidos -->
            <div class="col-lg-4 col-md-6 mb-4 mb-md-0">
                <h5 class="text-uppercase">Enlaces Rápidos</h5>
                <ul class="list-unstyled">
                    <li><a href="{{ route('dashboard') }}" class="text-dark">Inicio</a></li>
                    <li><a href="{{ route('inventario') }}" class="text-dark">Inventario</a></li>
                    <li><a href="{{ route('agregar-producto') }}" class="text-dark">Agregar Producto</a></li>
                    <li><a href="#" class="text-dark">Historial de Ventas</a></li>
                </ul>
            </div>

            <!-- Información de contacto -->
            <div class="col-lg-4 col-md-6 mb-4 mb-md-0">
                <h5 class="text-uppercase">Contáctanos</h5>
                <p>
                    <i class="fas fa-map-marker-alt"></i> Dirección: Melipilla,Ortusa 250<br>
                    <i class="fas fa-phone"></i> Teléfono: +56 9 1334 5618<br>
                    <i class="fas fa-envelope"></i> Correo: Si.carrasco@duocuc.cl
                </p>
            </div>

            <!-- Información adicional -->
            <div class="col-lg-4 col-md-12 mb-4 mb-md-0">
                <h5 class="text-uppercase">Sobre Nosotros</h5>
                <p>
                    Este es una aplicacion dedicada a proporcionar la mejor experiencia de gestión de inventarios para
                    pequeños y medianos negocios. Nuestro objetivo es facilitar la administración de tus productos de
                    manera simple y eficiente.
                </p>
            </div>
        </div>
    </div>
</footer>

</html>
