<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

    <title>CATEGORIA : {{ $categoria->nombre }}</title>
    <!-- Enlaces a Bootstrap e íconos -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

</head>

<body>

    <!-- Barra de navegación -->
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
                <li><a href="{{ route('fiados.index') }}"><i class="bi bi-cart-fill"></i> Carrito de compra</a></li>

                <li><a href="{{ route('agregar-producto') }}"><i class="bi bi-plus-circle"></i> Agregar Producto</a>
                </li>
                <li><a href="{{ route('registro-ventas') }}"><i class="bi bi-clock-history"></i> Historial Ventas</a>
                </li>
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
    <!-- Título y resumen de categoría -->
    <div class="container my-5">
        <h2 class="text-center page-title mb-4">Categoría: {{ $categoria->nombre }}</h2>

        <!-- Resumen de la categoría -->
        <div class="row mb-4">
            <div class="col-sm-6 col-md-3">
                <div class="card shadow-sm text-center">
                    <div class="card-body">
                        <h6>Total de Productos</h6>
                        <h4>{{ $productos->count() }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-3">
                <div class="card shadow-sm text-center">
                    <div class="card-body">
                        <h6>Promedio de Precio</h6>
                        <h4>${{ number_format($productos->avg('precio'), 2) }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-3">
                <div class="card shadow-sm text-center">
                    <div class="card-body">
                        <h6>Stock Bajo</h6>
                        <h4>{{ $productos->where('stock', '<=', 5)->count() }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-3">
                <div class="card shadow-sm text-center">
                    <div class="card-body">
                        <h6>Próximos a Vencer</h6>
                        <h4>{{ $productos->where('proximo_a_vencer', true)->count() }}</h4>
                    </div>
                </div>
            </div>
        </div>

        <!-- Buscador y tabla -->
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
            <div class="input-group w-100 w-md-50 shadow-sm">
                <span class="input-group-text bg-light text-dark"><i class="bi bi-search"></i></span>
                <input type="text" id="buscarProducto" class="form-control" placeholder="Buscar producto...">
            </div>
        </div>

        <!-- Tabla responsiva -->
        <div class="table-responsive shadow-sm rounded">
            <table class="table table-hover align-middle" id="tablaProductos">
                <thead class="table-dark">
                    <tr>
                        <th class="sortable" data-col="id">ID</th>
                        <th class="sortable" data-col="nombre">Nombre</th>
                        <th class="sortable" data-col="descripcion">Descripción</th>
                        <th class="sortable" data-col="precio">Precio</th>
                        <th class="sortable" data-col="stock">Stock</th>
                        <th class="sortable" data-col="fecha_vencimiento">Fecha Vencimiento</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($productos->isEmpty())
                        <tr>
                            <td colspan="6" class="text-center text-muted">No hay productos en esta categoría.</td>
                        </tr>
                    @else
                        @foreach ($productos as $producto)
                            <tr>
                                <td>{{ $producto->id }}</td>
                                <td>{{ $producto->nombre }}</td>
                                <td>{{ $producto->descripcion }}</td>
                                <td>${{ number_format($producto->precio, 2) }}</td>
                                <td>
                                    {{ $producto->stock }}
                                    @if ($producto->stock <= 5)
                                        <span class="badge bg-danger text-light">Bajo</span>
                                    @endif
                                </td>
                                <td>{{ $producto->fecha_vencimiento ?? 'N/A' }}</td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    <script>
        // Búsqueda dinámica
        document.getElementById('buscarProducto').addEventListener('keyup', function() {
            const filtro = this.value.toLowerCase();
            const filas = document.querySelectorAll('#tablaProductos tbody tr');

            filas.forEach(fila => {
                const celdas = Array.from(fila.children);
                fila.style.display = celdas.some(celda =>
                    celda.textContent.toLowerCase().includes(filtro)
                ) ? '' : 'none';
            });
        });

        // Ordenamiento de columnas
        document.querySelectorAll('.sortable').forEach(th => {
            th.addEventListener('click', function() {
                const table = th.closest('table');
                const tbody = table.querySelector('tbody');
                const rows = Array.from(tbody.rows);
                const colIndex = Array.from(th.parentNode.children).indexOf(th);
                const isAscending = th.classList.toggle('asc');

                rows.sort((a, b) => {
                    const aText = a.cells[colIndex].textContent.trim();
                    const bText = b.cells[colIndex].textContent.trim();
                    return isAscending ?
                        aText.localeCompare(bText, undefined, {
                            numeric: true
                        }) :
                        bText.localeCompare(aText, undefined, {
                            numeric: true
                        });
                });

                tbody.append(...rows);
            });
        });
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
            background-color: #000000;
            /* Fondo azul oscuro */
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
            background-color: #ffffff;
            /* Fondo blanco */
            color: #001f3f;
            /* Texto azul oscuro */
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
            background-color: #0074d9;
            /* Azul brillante */
            border-radius: 12px;
            box-shadow: 0px 5px 10px rgba(0, 0, 0, 0.2);
            transition: background-color 0.3s ease, transform 0.3s ease;
            display: inline-block;
            border: none;
        }
    
        .navbar-right ul li a:hover,
        .navbar-right ul li button:hover {
            background-color: #0056b3;
            /* Azul más oscuro */
            transform: translateY(-2px);
        }
    
        .dropdown-btn {
            background-color: #0074d9;
            /* Azul brillante */
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
            background-color: #0056b3;
            /* Azul más oscuro */
            transform: translateY(-2px);
        }
    
        .dropdown-menu {
            background-color: #f0f8ff;
            /* Azul claro */
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
            color: #001f3f;
            /* Texto azul oscuro */
            padding: 10px;
            text-decoration: none;
            border-radius: 5px;
            display: block;
        }
    
        .dropdown-menu a:hover,
        .dropdown-menu button:hover {
            background-color: #dceffe;
            /* Azul más claro */
        }
    </style>

</body>

</html>
<style>
    .page-title {
        margin-top: 30px;
        font-weight: bold;
    }

    .table-container {
        margin-top: 30px;
    }

    .btn-container {
        margin-top: 30px;
        display: flex;
        justify-content: center;
        margin-bottom: 30px;
    }

    .btn-agregar {
        width: 100%;
        max-width: 2000px;
        /* Mantén el mismo ancho que la tabla */
        padding: 15px 0;
        /* Botón más delgado */
        font-size: 20px;
        font-weight: bold;
    }
</style>
