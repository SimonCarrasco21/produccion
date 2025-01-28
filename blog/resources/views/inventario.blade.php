<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

    <title>Inventario de Productos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
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

    <div class="container">
        <h2 class="text-center">Inventario de Productos</h2>

        <!-- Botones -->
        <div class="action-buttons mb-3">
            <a href="{{ route('agregar-producto') }}" class="btn btn-success"><i class="bi bi-plus-circle"></i> Agregar
                Producto</a>
            <button type="submit" form="formEliminarSeleccionados" class="btn btn-danger"
                onclick="return confirm('¿Estás seguro de que deseas eliminar los productos seleccionados?');">
                <i class="bi bi-trash-fill"></i> Eliminar Seleccionados
            </button>
        </div>

        <!-- Filtros -->
        <div class="row mb-3">
            <div class="col-md-4">
                <input type="text" id="buscarProducto" class="form-control"
                    placeholder="Buscar por nombre o descripción...">
            </div>
            <div class="col-md-3">
                <select id="filtroCategoria" class="form-select">
                    <option value="">Todas las Categorías</option>
                    @foreach ($categorias as $categoria)
                        <option value="{{ $categoria->nombre }}">{{ $categoria->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <input type="number" id="filtroPrecio" class="form-control" placeholder="Filtrar por precio...">
            </div>
            <div class="col-md-2">
                <select id="filtroStock" class="form-select">
                    <option value="">Todos los Stocks</option>
                    <option value="bajo">Bajo</option>
                    <option value="suficiente">Suficiente</option>
                    <option value="alto">Alto</option>
                </select>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 mt-3">
                <label>
                    <input type="checkbox" id="filtroProximoAVencer"
                        {{ request('proximo_a_vencer') ? 'checked' : '' }}>
                    Mostrar solo productos próximos a vencer
                </label>
            </div>
        </div>

        <!-- Tabla -->
        <form id="formEliminarSeleccionados" action="{{ route('eliminarProductosSeleccionados') }}" method="POST">
            @csrf
            <div class="table-responsive">
                <table class="table table-bordered table-hover shadow-sm">
                    <thead class="table-success">
                        <tr>
                            <th><input type="checkbox" id="selectAll"></th>
                            <th>ID Producto</th>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th>Precio</th>
                            <th>Stock</th>
                            <th>Categoría</th>
                            <th>Fecha de Vencimiento</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="tablaProductos">
                        @foreach ($productos as $producto)
                            <tr>
                                <td><input type="checkbox" name="productos[]" value="{{ $producto->id }}"></td>
                                <td>{{ $producto->id }}</td>
                                <td>{{ $producto->nombre }}</td>
                                <td>{{ $producto->descripcion }}</td>
                                <td>{{ number_format($producto->precio, 2) }} $</td>
                                <td>
                                    {{ $producto->stock }}
                                    @if ($producto->stock <= 5)
                                        <span class="badge bg-danger text-light">Stock bajo</span>
                                    @endif
                                </td>
                                <td>{{ $producto->categoria->nombre }}</td>
                                <td>
                                    {{ $producto->fecha_vencimiento ?? 'Sin fecha de vencimiento' }}
                                    @if ($producto->proximo_a_vencer)
                                        <span class="badge bg-warning text-dark">Próximo a vencer</span>
                                    @endif
                                </td>
                                <td><a href="{{ route('editarProducto', $producto->id) }}"
                                        class="btn btn-warning">Editar</a></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </form>

    </div>

    <!-- Scripts -->
    <script>
        // Define un objeto para almacenar los valores de los filtros
        const filtros = {
            nombre: '', // Filtro por nombre o descripción
            categoria: '', // Filtro por categoría
            precio: 0, // Filtro por precio
            stock: '' // Filtro por nivel de stock (bajo, suficiente, alto)
        };

        // Función que actualiza los filtros en la tabla
        const actualizarFiltros = () => {
            // Obtiene todas las filas de la tabla de productos
            const rows = document.querySelectorAll('#tablaProductos tr');
            rows.forEach(row => {
                // Extrae datos de cada fila para evaluar los filtros
                const nombre = row.querySelector('td:nth-child(3)').textContent
                    .toLowerCase(); // Nombre del producto
                const descripcion = row.querySelector('td:nth-child(4)').textContent
                    .toLowerCase(); // Descripción del producto
                const categoria = row.querySelector('td:nth-child(7)').textContent.toLowerCase(); // Categoría
                const precio = parseFloat(row.querySelector('td:nth-child(5)').textContent.replace('$', '')) ||
                    0; // Precio
                const stock = parseInt(row.querySelector('td:nth-child(6)').textContent); // Nivel de stock

                // Aplica cada filtro según los valores del objeto "filtros"
                const cumpleNombre = filtros.nombre === '' || nombre.includes(filtros.nombre) || descripcion
                    .includes(filtros.nombre); // Filtro por nombre/descripción
                const cumpleCategoria = filtros.categoria === '' || categoria.includes(filtros
                    .categoria); // Filtro por categoría
                const cumplePrecio = filtros.precio === 0 || precio <= filtros.precio; // Filtro por precio
                const cumpleStock = filtros.stock === '' || // Filtro por stock
                    (filtros.stock === 'bajo' && stock <= 5) || // Stock bajo
                    (filtros.stock === 'suficiente' && stock > 5 && stock <= 20) || // Stock suficiente
                    (filtros.stock === 'alto' && stock > 20); // Stock alto

                // Muestra u oculta la fila según si cumple con todos los filtros
                row.style.display = cumpleNombre && cumpleCategoria && cumplePrecio && cumpleStock ? '' :
                    'none';
            });
        };

        // Eventos que actualizan los valores de los filtros y aplican los cambios a la tabla
        document.getElementById('buscarProducto').addEventListener('input', function() {
            filtros.nombre = this.value.toLowerCase(); // Actualiza el filtro de nombre
            actualizarFiltros(); // Aplica los filtros
        });

        document.getElementById('filtroCategoria').addEventListener('change', function() {
            filtros.categoria = this.value.toLowerCase(); // Actualiza el filtro de categoría
            actualizarFiltros(); // Aplica los filtros
        });

        document.getElementById('filtroPrecio').addEventListener('input', function() {
            filtros.precio = parseFloat(this.value) || 0; // Actualiza el filtro de precio
            actualizarFiltros(); // Aplica los filtros
        });

        document.getElementById('filtroStock').addEventListener('change', function() {
            filtros.stock = this.value.toLowerCase(); // Actualiza el filtro de stock
            actualizarFiltros(); // Aplica los filtros
        });
        document.getElementById('filtroProximoAVencer').addEventListener('change', function() {
            const url = new URL(window.location.href); // Obtiene la URL actual
            if (this.checked) {
                url.searchParams.set('proximo_a_vencer', '1'); // Agrega el parámetro al URL
            } else {
                url.searchParams.delete('proximo_a_vencer'); // Elimina el parámetro si se desactiva el checkbox
            }
            window.location.href = url.toString(); // Redirige con los nuevos parámetros
        });

        // Seleccionar o deseleccionar todos los checkboxes
        document.getElementById('selectAll').addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('#tablaProductos input[type="checkbox"]');
            checkboxes.forEach(checkbox => checkbox.checked = this.checked);
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
    </script>s
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

<style>
    .action-buttons {
        display: flex;
        gap: 5px;
        justify-content: center;
        margin-bottom: 1rem;
        margin-top: 1rem;
    }

    .action-buttons .btn {
        flex: 1;
        max-width: 650px;
        text-transform: uppercase;
        font-weight: bold;
        padding: 10px;
        background-color: #4CAF50;
        border-color: #4CAF50;
        color: #ffffff;
        transition: background-color 0.3s ease, color 0.3s ease;
    }

    .action-buttons .btn-danger {
        background-color: #dc3545;
        border-color: #dc3545;
    }

    .action-buttons .btn:hover {
        background-color: #45a049;
        color: #f9f9f9;
    }

    .action-buttons .btn-danger:hover {
        background-color: #c82333;
        color: #f9f9f9;
    }

    .table-container {
        margin-top: 20px;
    }

    .table thead th {
        font-weight: bold;
    }

    .table {
        border-radius: 8px;
        overflow: hidden;
    }

    .form-control {
        margin-bottom: 10px;
    }

    h2 {
        font-weight: bold;
        margin-top: 20px;
    }
</style>

</html>
