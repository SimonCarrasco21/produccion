<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventario de Productos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
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
                    <a href="#"><i class="bi bi-eye"></i> Ver Perfil</a>
                    <form method="POST" action="{{ route('logout') }}" onsubmit="return confirmLogout()">
                        @csrf
                        <button type="submit" class="logout-button"><i class="bi bi-box-arrow-right"></i> Cerrar Sesión</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="navbar-right">
            <ul>
                <li><a href="{{ route('dashboard') }}"><i class="bi bi-speedometer2"></i> Inicio</a></li>
                <li><a href="#"><i class="bi bi-wallet-fill"></i> Fiar</a></li>
                <li><a href="#"><i class="bi bi-clock-history"></i> Ver Historial Ventas</a></li>
                <li><button class="btn-pagar"><i class="bi bi-credit-card"></i> Pagar</button></li>
            </ul>
        </div>
    </nav>

    <!-- Formulario de búsqueda y tabla de productos -->
    <div class="container">
        <h2 class="text-center">Inventario de Productos</h2>
        <div class="action-buttons">
            <a href="{{ route('agregar-producto') }}" class="btn btn-success"><i class="bi bi-plus-circle"></i> Agregar Producto</a>
            <button type="submit" form="formEliminarSeleccionados" class="btn btn-danger"><i class="bi bi-trash-fill"></i> Eliminar Seleccionados</button>
        </div>
        
        <div class="mb-3">
            <input type="text" id="buscarProducto" class="form-control" placeholder="Buscar producto por nombre...">
        </div>

        <div class="table-container">
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
                        @foreach($productos as $producto)
                            <tr>
                                <td><input type="checkbox" name="productos[]" value="{{ $producto->id }}" class="checkboxProducto"></td>
                                <td>{{ $producto->id }}</td>
                                <td>{{ $producto->nombre }}</td>
                                <td>{{ $producto->descripcion }}</td>
                                <td>{{ number_format($producto->precio, 2) }} $</td>
                                <td>{{ $producto->stock }}</td>
                                <td>{{ $producto->categoria->nombre }}</td>
                                <td>{{ $producto->fecha_vencimiento }}</td>
                                <td>
                                    <a href="{{ route('editarProducto', $producto->id) }}" class="btn btn-warning">Editar</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </form>
        </div>
    </div>

    <!-- Script para búsqueda y selección de productos -->
    <script>
        document.getElementById('buscarProducto').addEventListener('keyup', function() {
            const value = this.value.toLowerCase();
            const rows = document.querySelectorAll('#tablaProductos tr');
            rows.forEach(row => {
                const nombreProducto = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
                row.style.display = nombreProducto.includes(value) ? '' : 'none';
            });
        });

        document.getElementById('selectAll').addEventListener('click', function() {
            const checkboxes = document.querySelectorAll('.checkboxProducto');
            checkboxes.forEach(checkbox => checkbox.checked = this.checked);
        });

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
</html>
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