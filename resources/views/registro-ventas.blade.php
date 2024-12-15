<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

    <title>Registro de Ventas</title>
    <!-- Enlace a Bootstrap y Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <style>
        /* Estilos personalizados */
        .total-ganancias {
            font-weight: bold;
            font-size: 1.5rem;
            color: #0a2e0f;
        }

        .ganancias-box {
            border: 2px solid #000000;
            padding: 15px;
            border-radius: 8px;
            background-color: #f9f9f9;
            text-align: center;
        }
    </style>
</head>

<body>
    <nav class="navbar">
        <!-- Navbar izquierda -->
        <div class="navbar-left">
            <h2>
                <img src="{{ Auth::user()->profile_picture && file_exists(storage_path('app/public/' . Auth::user()->profile_picture))
                    ? asset('storage/' . Auth::user()->profile_picture)
                    : asset('images/default-profile.png') }}"
                    alt="Foto de Perfil" style="width: 40px; height: 40px; border-radius: 50%; margin-right: 10px;">
                Usuario: {{ Auth::user()->name }}
            </h2>
            <div class="dropdown">
                <button class="dropdown-btn"><i class="bi bi-person-circle"></i> Perfil</button>
                <div class="dropdown-content" id="dropdown-menu" style="display: none;">
                    <a href="{{ route('perfil') }}"><i class="bi bi-eye"></i> Ver Perfil</a>
                    <!-- Enlace a la vista del perfil -->
                    <form method="POST" action="{{ route('logout') }}" onsubmit="return confirmLogout()">
                        @csrf
                        <button type="submit" class="logout-button"><i class="bi bi-box-arrow-right"></i> Cerrar
                            Sesión</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Botón hamburguesa -->
        <input type="checkbox" id="nav-check" class="nav-check">
        <div class="nav-btn">
            <label for="nav-check">
                <span></span>
                <span></span>
                <span></span>
            </label>
        </div>

        <!-- Navbar derecha -->
        <div class="navbar-right">
            <ul class="nav-list">
                <li><a href="{{ route('dashboard') }}"><i class="bi bi-speedometer2"></i> Inicio</a></li>
                <li><a href="{{ route('fiados.index') }}"><i class="bi bi-wallet-fill"></i> Fiar</a></li>
                <li><a href="{{ route('agregar-producto') }}"><i class="bi bi-plus-circle"></i> Agregar Producto</a>
                <li><a href="{{ route('inventario') }}"><i class="bi bi-box"></i> Inventario</a></li>
                <li><a href="{{ route('pagina.pago') }}" class="btn-pagar"><i class="bi bi-credit-card"></i> Pagar</a>
                </li>
                <!-- Botones adicionales dentro del menú hamburguesa -->
                <li class="hamburger-only">
                    <a href="{{ route('perfil') }}"><i class="bi bi-eye"></i> Ver Perfil</a>
                </li>
                <li class="hamburger-only">
                    <form method="POST" action="{{ route('logout') }}" onsubmit="return confirmLogout()">
                        @csrf
                        <button type="submit" class="btn btn-logout"><i class="bi bi-box-arrow-right"></i> Cerrar
                            Sesión</button>
                    </form>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container mt-5">
        <h1 class="text-center mb-4">Registro de Ventas</h1>

        <!-- Bloque agrupado -->

        <!-- Filtro de fechas -->
        <div class="card p-4 mb-4 shadow-sm">
            <form action="{{ route('ventas.historial') }}" method="GET">
                <div class="row">
                    <!-- Campo Fecha de Inicio -->
                    <div class="col-md-4">
                        <label for="fechaInicio" class="form-label">Fecha de Inicio:</label>
                        <input type="date" id="fechaInicio" name="fecha_inicio"
                            value="{{ request('fecha_inicio') }}" class="form-control">
                    </div>
                    <!-- Campo Fecha de Fin -->
                    <div class="col-md-4">
                        <label for="fechaFin" class="form-label">Fecha de Fin:</label>
                        <input type="date" id="fechaFin" name="fecha_fin" value="{{ request('fecha_fin') }}"
                            class="form-control">
                    </div>
                    <!-- Botón Filtrar -->
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-funnel"></i> Filtrar
                        </button>
                    </div>
                </div>
            </form>





            <hr class="text-secondary my-3">

            <!-- Botones de acciones -->
            <div class="row text-center">
                <div class="col-md-4 mb-3">
                    <form action="{{ route('ventas.imprimir') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger w-100">
                            <i class="bi bi-file-earmark-pdf"></i> Generar PDF
                        </button>
                    </form>
                </div>
                <div class="col-md-4 mb-3">
                    <form action="{{ route('ventas.guardar') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-outline-success w-100">
                            <i class="bi bi-save"></i> Guardar Registro
                        </button>
                    </form>
                </div>
                <div class="col-md-4 mb-3">
                    <form action="{{ route('ventas.eliminar') }}" method="POST"
                        onsubmit="return confirmEliminarVentas()">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger w-100">
                            <i class="bi bi-trash3-fill"></i> Eliminar Todas las Ventas
                        </button>
                    </form>
                </div>
            </div>

            <hr class="text-secondary my-3">

            <!-- Total de ganancias -->
            <div class="ganancias-box mt-3"
                style="background-color: #f9f9f9; border: 2px solid #0a2e0f; border-radius: 10px;">
                <h3 class="total-ganancias">Total de Ganancias: ${{ number_format($totalGanancias, 2) }}</h3>
            </div>
        </div>

        <!-- Tabla de Registro de Ventas -->
        <h2 class="text-center my-4">Historial de Ventas</h2>
        <div class="table-responsive">
            <table class="table table-bordered table-hover shadow-sm">
                <thead class="table-dark">
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
        </div>

        <!-- Tabla de Registros Guardados -->
        <h3 class="text-center my-4">Registros Descargados</h3>
        <div class="table-responsive">
            <table class="table table-bordered table-hover shadow-sm">
                <thead class="table-dark">
                    <tr>
                        <th>ID Registro</th>
                        <th>Fecha de Generación</th>
                        <th>Total de Ganancias</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($registrosReporte as $registro)
                        <tr>
                            <td>{{ $registro->id }}</td>
                            <td>{{ \Carbon\Carbon::parse($registro->fecha_generacion)->format('d-m-Y H:i:s') }}</td>
                            <td>${{ $registro->total_ganancias }}</td>
                            <td>
                                <a href="{{ route('ventas.reporte.descargar', $registro->id) }}"
                                    class="btn btn-info">
                                    <i class="bi bi-download"></i> Descargar PDF
                                </a>
                                <form action="{{ route('ventas.reporte.eliminar', $registro->id) }}" method="POST"
                                    style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger"
                                        onclick="return confirm('¿Estás seguro de que deseas eliminar este registro?');">
                                        <i class="bi bi-trash"></i> Eliminar
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>

    <script>
        function toggleDropdown() {
            const dropdownMenu = document.getElementById('dropdownMenu');
            dropdownMenu.style.display = dropdownMenu.style.display === 'block' ? 'none' : 'block';
        }

        function confirmEliminarVentas() {
            return confirm("¿Estás seguro de que deseas eliminar todas las ventas? Esta acción no se puede deshacer.");
        }
    </script>

    <!-- Script para confirmar la acción de cerrar sesión y mostrar/ocultar el menú del perfil y las funciones del navbar -->
    <script>
        // Confirmar cierre de sesión
        function confirmLogout() {
            return confirm('¿Estás seguro de que deseas cerrar sesión?');
        }

        // Controlar Menú Hamburguesa
        document.addEventListener('DOMContentLoaded', function() {
            const navCheck = document.querySelector('.nav-check');
            const navbarRight = document.querySelector('.navbar-right');

            navCheck.addEventListener('change', function() {
                navbarRight.style.display = navCheck.checked ? 'flex' : 'none';
            });

            window.addEventListener('resize', function() {
                if (window.innerWidth > 768) {
                    navbarRight.style.display = 'flex';
                    navCheck.checked = false;
                } else {
                    navbarRight.style.display = 'none';
                }
            });
        });
    </script>
    <script>
        function confirmLogout() {
            return confirm('¿Estás seguro de que quieres cerrar sesión?');
        }

        const dropdownBtn = document.querySelector('.dropdown-btn');
        const dropdownMenu = document.querySelector('#dropdown-menu');

        dropdownBtn.addEventListener('click', function() {
            dropdownMenu.style.display = dropdownMenu.style.display === 'none' ? 'block' : 'none';
        });
    </script>
    <!-- Estilos personalizados  navbar -->
    <style>
        .navbar {
            background-color: #000;
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: white;
        }

        .navbar-left {
            display: flex;
            align-items: center;
        }

        .navbar-left h2 {
            margin: 0;
            font-size: 24px;
            font-weight: normal;
            background-color: #f4f4f4;
            color: #333;
            padding: 10px 20px;
            border-radius: 15px;
            box-shadow: 0px 5px 10px rgba(0, 0, 0, 0.1);
            margin-right: 10px;
            display: flex;
            align-items: center;
        }

        .navbar-left h2 i {
            margin-right: 10px;
        }

        .navbar-right {
            display: flex;
            align-items: center;
        }

        .navbar-right ul {
            list-style-type: none;
            margin: 0;
            padding: 0;
            display: flex;
            gap: 15px;
        }

        .navbar-right ul li a,
        .navbar-right ul li button {
            color: white;
            text-decoration: none;
            font-size: 18px;
            padding: 12px 25px;
            background-color: #4CAF50;
            border-radius: 12px;
            box-shadow: 0px 5px 10px rgba(0, 0, 0, 0.2);
            transition: background-color 0.3s ease, transform 0.3s ease;
            display: inline-block;
            border: none;
        }

        .navbar-right ul li a:hover,
        .navbar-right ul li button:hover {
            background-color: #45a049;
            transform: translateY(-2px);
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
            display: none;
            position: absolute;
            top: 100%;
            left: 0;
            z-index: 1;
            margin-top: 5px;
        }

        .dropdown-menu a,
        .dropdown-menu button {
            color: #000;
            padding: 10px;
            text-decoration: none;
            border-radius: 5px;
            display: block;
        }

        .dropdown-menu a:hover,
        .dropdown-menu button:hover {
            background-color: #e9ecef;
        }
    </style>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
