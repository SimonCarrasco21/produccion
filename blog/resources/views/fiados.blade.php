<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito de compras</title>
    <!-- Enlace a Bootstrap y Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

</head>

<body>
    <!-- Barra de Navegación -->
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
                <li><a href="{{ route('agregar-producto') }}"><i class="bi bi-plus-circle"></i> Agregar Producto</a>
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

    <div class="container mt-5">
        <h1 class="text-center">Carrito de Compras</h1>
    
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
    
        @if ($fiados->isEmpty())
            <p class="text-center">Tu carrito está vacío.</p>
        @else
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Imagen</th>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Precio Unitario</th>
                        <th>Total</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($fiados as $item)
                        @php
                            $productos = json_decode($item->productos, true);
                        @endphp
                        @foreach ($productos as $producto)
                            <tr>
                                <td>
                                    @if ($item->imagenes->isNotEmpty())
                                        <img src="{{ asset('storage/' . $item->imagenes->first()->imagen) }}" alt="Imagen" width="50">
                                    @else
                                        <span>Sin Imagen</span>
                                    @endif
                                </td>
                                <td>{{ $producto['nombre'] }}</td>
                                <td>{{ $producto['cantidad'] }}</td>
                                <td>${{ number_format($producto['precio_unitario'], 2) }}</td>
                                <td>${{ number_format($producto['precio_total'], 2) }}</td>
                                <td>
                                    <form action="{{ route('fiados.eliminar', $item->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>
    
            <div class="d-flex justify-content-between mt-4">
                <h4>Total: ${{ number_format($total, 2) }}</h4>
                <form action="{{ route('fiados.pagar') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-primary">Proceder al Pago</button>
                </form>
            </div>
        @endif
    </div>
    
    
    
    

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
    


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
