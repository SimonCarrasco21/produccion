<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página Principal</title>
    <!-- Enlace a Bootstrap y Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}"> <!-- Enlace al CSS separado -->
</head>
<body>
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
                <li><a href="#"><i class="bi bi-wallet-fill"></i> Fiar</a></li>
                <li><a href="{{ route('agregar-producto') }}"><i class="bi bi-plus-circle"></i> Agregar Producto</a></li>
                <li><a href="#"><i class="bi bi-clock-history"></i> Ver Historial Ventas</a></li>
                <li><a href="#"><i class="bi bi-box"></i> Inventario</a></li>
                <li><button class="btn-pagar"><i class="bi bi-credit-card"></i> Pagar</button></li>
            </ul>
        </div>
    </nav>

    <!-- Sección de últimos registros de ventas -->
    <div class="container mt-4">
        <h2 class="text-center">Últimos Registros de Ventas</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID Venta</th>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Precio</th>
                    <th>Fecha</th>
                </tr>
            </thead>
            <tbody>
                <!-- Aquí se llenarán los datos desde la base de datos -->
            </tbody>
        </table>
    </div>

    <!-- Sección de productos agregados -->
    <div class="container mt-4">
        <h2 class="text-center">Productos Agregados</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID Producto</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Precio</th>
                    <th>Stock</th>
                    <th>Categoría</th>
                    <th>Fecha de Elaboración</th>
                    <th>Fecha de Vencimiento</th>
                </tr>
            </thead>
            <tbody>
                <!-- Aquí se llenarán los datos desde la base de datos -->
            </tbody>
        </table>
    </div>

<!-- Catálogo de Productos -->
<div class="container mt-4">
    <h2 class="text-center">Catálogo de Productos</h2>
    <div class="product-grid">
        <div class="product-category">
            <span class="material-icons" style="font-size: 3rem; color: #4CAF50;">local_grocery_store</span>
            <p>Lácteos</p>
        </div>
        <div class="product-category">
            <span class="material-icons" style="font-size: 3rem; color: #4CAF50;">spa</span>
            <p>Granos</p>
        </div>
        <div class="product-category">
            <span class="material-icons" style="font-size: 3rem; color: #4CAF50;">cleaning_services</span>
            <p>Productos de Limpieza</p>
        </div>
        <div class="product-category">
            <span class="material-icons" style="font-size: 3rem; color: #4CAF50;">cookie</span>
            <p>Galletas</p>
        </div>
        <div class="product-category">
            <span class="material-icons" style="font-size: 3rem; color: #4CAF50;">local_cafe</span>
            <p>Bebidas</p>
        </div>
        <div class="product-category">
            <span class="material-icons" style="font-size: 3rem; color: #4CAF50;">bakery_dining</span>
            <p>Panadería</p>
        </div>
        <div class="product-category">
            <span class="material-icons" style="font-size: 3rem; color: #4CAF50;">shopping_basket</span>
            <p>Frutas y Verduras</p>
        </div>
        <div class="product-category">
            <span class="material-icons" style="font-size: 3rem; color: #4CAF50;">set_meal</span>
            <p>Embutidos</p>
        </div>
        <div class="product-category">
            <span class="material-icons" style="font-size: 3rem; color: #4CAF50;">medical_services</span>
            <p>Productos de Aseo Personal</p>
        </div>
    </div>
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
