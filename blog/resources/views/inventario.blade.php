<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventario de Productos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>

<body>
    <!-- Barra de navegación -->
    <nav class="navbar mb-4">
        <div class="navbar-left">
            <h2><i class="bi bi-person-circle"></i> Usuario: {{ Auth::user()->name }}</h2>
            <div class="dropdown">
                <button class="dropdown-btn"><i class="bi bi-person-circle"></i> Perfil</button>
                <div class="dropdown-content" id="dropdown-menu" style="display: none;">
                    <a href="{{ route('perfil') }}"><i class="bi bi-eye"></i> Ver Perfil</a>
                    <form method="POST" action="{{ route('logout') }}" onsubmit="return confirmLogout()">
                        @csrf
                        <button type="submit" class="logout-button"><i class="bi bi-box-arrow-right"></i> Cerrar
                            Sesión</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="navbar-right">
            <ul>
                <li><a href="{{ route('dashboard') }}"><i class="bi bi-speedometer2"></i> Inicio</a></li>
                <li><a href="{{ route('fiados.index') }}"><i class="bi bi-wallet-fill"></i> Fiar</a></li>
                <li><a href="{{ route('registro-ventas') }}"><i class="bi bi-clock-history"></i> Ver Historial
                        Ventas</a></li>
                <li><a href="{{ route('pagina.pago') }}" class="btn-pagar"><i class="bi bi-credit-card"></i> Pagar</a>
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
            <table class="table table-striped shadow rounded">
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

    <!-- Script para confirmar la acción de cerrar sesión y mostrar/ocultar el menú del perfil -->
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
