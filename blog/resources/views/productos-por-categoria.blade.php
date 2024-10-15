<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos de {{ $categoria->nombre }}</title>
    <!-- Enlaces a Bootstrap e íconos -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}"> <!-- Enlace al archivo CSS separado -->
</head>
<body>

    <!-- Barra de navegación -->
    <nav class="navbar">
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
                <li><a href="{{ route('agregar-producto') }}"><i class="bi bi-plus-circle"></i> Agregar Producto</a></li>
                <li><a href="#"><i class="bi bi-clock-history"></i> Ver Historial Ventas</a></li>
                <li><a href="#"><i class="bi bi-box"></i> Inventario</a></li>
                <li><button class="btn-pagar"><i class="bi bi-credit-card"></i> Pagar</button></li>
            </ul>
        </div>
    </nav>

    <!-- Tabla de productos por categoría -->
    <div class="container">
        <h2 class="text-center">Productos de {{ $categoria->nombre }}</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID Producto</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Precio</th>
                    <th>Stock</th>
                    <th>Fecha de Vencimiento</th>
                </tr>
            </thead>
            <tbody>
                @if($productos->isEmpty())
                    <tr>
                        <td colspan="6" class="text-center">No hay productos en esta categoría.</td>
                    </tr>
                @else
                    @foreach($productos as $producto)
                        <tr>
                            <td>{{ $producto->id }}</td>
                            <td>{{ $producto->nombre }}</td>
                            <td>{{ $producto->descripcion }}</td>
                            <td>{{ number_format($producto->precio, 2) }} $</td>
                            <td>{{ $producto->stock }}</td>
                            <td>{{ $producto->fecha_vencimiento }}</td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>

    <div class="container mt-4">
        <a href="{{ route('agregar-producto') }}" class="btn btn-success mb-4"><i class="bi bi-plus-circle"></i> Agregar Nuevo Producto</a>
    </div>

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
</html>
