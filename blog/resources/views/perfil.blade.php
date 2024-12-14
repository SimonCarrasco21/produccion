<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

    <title>Perfil de Usuario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <style>
        .card-profile,
        .card-category {
            background: #ffffff;
            color: #000000;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease-in-out;
        }

        .card-profile {
            flex: 1;
        }

        .card-category {
            flex: 1;
            margin-left: 20px;
        }

        .profile-info h2 {
            margin-bottom: 1rem;
            font-weight: bold;
        }

        .edit-section {
            max-height: 0;
            opacity: 0;
            overflow: hidden;
            transition: max-height 0.5s ease, opacity 0.5s ease;
        }

        .edit-section.show {
            max-height: 600px;
            opacity: 1;
        }

        .btn-toggle {
            background-color: #4CAF50;
            border: none;
            color: #fff;
            padding: 10px 15px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
            margin-top: 15px;
        }

        .btn-toggle:hover {
            background-color: #45a049;
        }

        .btn-primary {
            background-color: #4CAF50;
            border-color: #4CAF50;
        }

        .btn-primary:hover {
            background-color: #45a049;
            border-color: #45a049;
        }

        .container {
            display: flex;
            gap: 20px;
            justify-content: center;
            margin-top: 60px;
        }

        .profile-info p {
            margin: 0.4rem 0;
        }

        .profile-picture img {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 15px;
        }

        ul {
            padding: 0;
            list-style-type: none;
        }

        ul li {
            margin: 0.5rem 0;
        }
    </style>
</head>

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
            <li><a href="{{ route('fiados.index') }}"><i class="bi bi-wallet-fill"></i> Fiar</a></li>
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

<body>
    <div class="container">
        <div class="container my-4">
            <div class="row g-4">
                <!-- Tarjeta de Perfil -->
                <div class="col-12 col-md-6">
                    <div class="card-profile">
                        <div class="profile-info text-center">
                            <h2>Perfil de Usuario</h2>

                            <!-- Foto de Perfil -->
                            <div class="profile-picture">
                                <img src="{{ $user->profile_picture ? asset('storage/' . $user->profile_picture) : asset('images/default-profile.png') }}"
                                    alt="Foto de Perfil">
                            </div>

                            <!-- Información Básica del Usuario -->
                            <p><strong>Nombre:</strong> {{ $user->name }}</p>
                            <p><strong>Correo Electrónico:</strong> {{ $user->email }}</p>
                            <p><strong>Fecha de Creación:</strong> {{ $user->created_at->format('d-m-Y') }}</p>

                            <!-- Botones de Acción -->
                            <button class="btn-toggle" onclick="toggleEditSection()">Editar Perfil</button>
                            <button class="btn-toggle" onclick="togglePasswordSection()">Cambiar Contraseña</button>
                        </div>

                        <div class="edit-section mt-4" id="editProfileSection">
                            <h4>Editar Información del Perfil</h4>
                            <form action="{{ route('perfil.update') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="mb-3">
                                    <label for="name" class="form-label">Nombre:</label>
                                    <input type="text" name="name" id="name" class="form-control"
                                        value="{{ $user->name }}" required>
                                </div>
                                <div class="mb-3">
                                    <label for="email" class="form-label">Correo Electrónico:</label>
                                    <input type="email" name="email" id="email" class="form-control"
                                        value="{{ $user->email }}" required>
                                </div>
                                <div class="mb-3">
                                    <label for="profile_picture" class="form-label">Foto de Perfil:</label>
                                    <input type="file" name="profile_picture" id="profile_picture"
                                        class="form-control" accept="image/*" required>
                                    <small class="form-text text-muted">Selecciona una imagen para tu perfil (formatos
                                        admitidos:
                                        JPG, PNG).</small>
                                </div>
                                <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                            </form>
                        </div>

                        <div class="edit-section mt-4" id="changePasswordSection">
                            <h4>Cambiar Contraseña</h4>
                            <form action="{{ route('perfil.cambiar-contrasena') }}" method="POST"
                                onsubmit="return validatePassword()">
                                @csrf
                                <div class="mb-3">
                                    <label for="current_password" class="form-label">Contraseña Actual:</label>
                                    <input type="password" name="current_password" id="current_password"
                                        class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label for="new_password" class="form-label">Nueva Contraseña:</label>
                                    <input type="password" name="new_password" id="new_password"
                                        class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label for="new_password_confirmation" class="form-label">Confirmar Nueva
                                        Contraseña:</label>
                                    <input type="password" name="new_password_confirmation"
                                        id="new_password_confirmation" class="form-control" required>
                                </div>
                                <button type="submit" class="btn btn-warning">Guardar Contraseña</button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Tarjeta de Categoría -->
                <div class="col-12 col-md-6">
                    <div class="card-category">
                        <h3>Cantidad de Productos por Categoría:</h3>
                        <ul>
                            @foreach ($productosPorCategoria as $categoria)
                                <li><strong>{{ $categoria->nombre }}:</strong> {{ $categoria->cantidad }} productos
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <style>
            /* Responsividad para apilar las tarjetas */
            @media (max-width: 768px) {
                .row.g-4 .col-12 {
                    flex: 0 0 100%;
                    max-width: 100%;
                }
            }
        </style>

    </div>
    <div>
        <div class="container mt-4">
            <div class="row g-4">
                <!-- Ventas por Día -->
                <div class="col-md-6">
                    <div class="card shadow border-0">
                        <div class="card-header bg-primary text-white text-center">
                            <h5 class="mb-0">Ventas por Día</h5>
                        </div>
                        <div class="card-body d-flex justify-content-center align-items-center">
                            @if ($ventasPorDia->isNotEmpty())
                                <canvas id="ventasPorDiaChart" class="w-100" style="max-height: 300px;"></canvas>
                            @else
                                <p class="text-center text-muted">No hay datos disponibles para las ventas diarias.</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Productos con Stock Bajo -->
                <div class="col-md-6">
                    <div class="card shadow border-0">
                        <div class="card-header bg-danger text-white text-center">
                            <h5 class="mb-0">Productos con Stock Bajo</h5>
                        </div>
                        <div class="card-body d-flex justify-content-center align-items-center">
                            @if ($productosConStockBajo->isNotEmpty())
                                <canvas id="productosConStockBajoChart" class="w-100"
                                    style="max-height: 300px;"></canvas>
                            @else
                                <p class="text-center text-muted">No hay productos con stock bajo.</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Métodos de Pago Más Utilizados -->
                <div class="col-md-6">
                    <div class="card shadow border-0">
                        <div class="card-header bg-success text-white text-center">
                            <h5 class="mb-0">Métodos de Pago Más Utilizados</h5>
                        </div>
                        <div class="card-body d-flex justify-content-center align-items-center">
                            @if ($metodosDePago->isNotEmpty())
                                <canvas id="metodosDePagoChart" class="w-100" style="max-height: 300px;"></canvas>
                            @else
                                <p class="text-center text-muted">No hay datos disponibles para los métodos de pago.
                                </p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Ganancias por Categoría de Producto -->
                <div class="col-md-6">
                    <div class="card shadow border-0">
                        <div class="card-header bg-info text-white text-center">
                            <h5 class="mb-0">Ganancias por Categoría de Producto</h5>
                        </div>
                        <div class="card-body d-flex justify-content-center align-items-center">
                            @if ($gananciasPorCategoria->isNotEmpty())
                                <canvas id="gananciasPorCategoriaChart" class="w-100"
                                    style="max-height: 300px;"></canvas>
                            @else
                                <p class="text-center text-muted">No hay datos disponibles para las ganancias por
                                    categoría.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            // Gráfico de Ventas por Día
            const ventasPorDiaLabels = @json($ventasPorDia->pluck('fecha'));
            const ventasPorDiaData = @json($ventasPorDia->pluck('total_ganancias'));

            const ctxVentasPorDia = document.getElementById('ventasPorDiaChart').getContext('2d');
            new Chart(ctxVentasPorDia, {
                type: 'bar',
                data: {
                    labels: ventasPorDiaLabels,
                    datasets: [{
                        label: 'Ganancias Diarias',
                        data: ventasPorDiaData,
                        backgroundColor: 'rgba(54, 162, 235, 0.7)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: {
                            title: {
                                display: true,
                                text: 'Fecha',
                                font: {
                                    size: 14
                                }
                            }
                        },
                        y: {
                            title: {
                                display: true,
                                text: 'Ganancias',
                                font: {
                                    size: 14
                                }
                            },
                            beginAtZero: true
                        }
                    },
                    plugins: {
                        legend: {
                            display: true
                        }
                    }
                }
            });

            // Gráfico de Productos con Stock Bajo
            const productosConStockBajoLabels = @json($productosConStockBajo->pluck('nombre'));
            const productosConStockBajoData = @json($productosConStockBajo->pluck('stock'));

            const ctxProductosConStockBajo = document.getElementById('productosConStockBajoChart').getContext('2d');
            new Chart(ctxProductosConStockBajo, {
                type: 'bar',
                data: {
                    labels: productosConStockBajoLabels,
                    datasets: [{
                        label: 'Stock Actual',
                        data: productosConStockBajoData,
                        backgroundColor: 'rgba(255, 99, 132, 0.7)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: {
                            title: {
                                display: true,
                                text: 'Producto',
                                font: {
                                    size: 14
                                }
                            }
                        },
                        y: {
                            title: {
                                display: true,
                                text: 'Cantidad en Stock',
                                font: {
                                    size: 14
                                }
                            },
                            beginAtZero: true
                        }
                    },
                    plugins: {
                        legend: {
                            display: true
                        }
                    }
                }
            });

            // Gráfico de Métodos de Pago Más Utilizados
            const metodosDePagoLabels = @json($metodosDePago->pluck('metodo_pago'));
            const metodosDePagoData = @json($metodosDePago->pluck('cantidad'));

            const ctxMetodosDePago = document.getElementById('metodosDePagoChart').getContext('2d');
            new Chart(ctxMetodosDePago, {
                type: 'pie',
                data: {
                    labels: metodosDePagoLabels,
                    datasets: [{
                        label: 'Métodos de Pago',
                        data: metodosDePagoData,
                        backgroundColor: [
                            'rgba(75, 192, 192, 0.7)',
                            'rgba(255, 206, 86, 0.7)',
                            'rgba(153, 102, 255, 0.7)',
                            'rgba(255, 159, 64, 0.7)',
                            'rgba(255, 99, 132, 0.7)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                font: {
                                    size: 14
                                }
                            }
                        }
                    }
                }
            });

            // Gráfico de Ganancias por Categoría
            const gananciasPorCategoriaLabels = @json($gananciasPorCategoria->pluck('nombre'));
            const gananciasPorCategoriaData = @json($gananciasPorCategoria->pluck('total_ganancias'));

            const ctxGananciasPorCategoria = document.getElementById('gananciasPorCategoriaChart').getContext('2d');
            new Chart(ctxGananciasPorCategoria, {
                type: 'bar',
                data: {
                    labels: gananciasPorCategoriaLabels,
                    datasets: [{
                        label: 'Ganancias Totales',
                        data: gananciasPorCategoriaData,
                        backgroundColor: 'rgba(54, 162, 235, 0.7)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: {
                            title: {
                                display: true,
                                text: 'Categoría de Producto',
                                font: {
                                    size: 14
                                }
                            }
                        },
                        y: {
                            title: {
                                display: true,
                                text: 'Ganancias',
                                font: {
                                    size: 14
                                }
                            },
                            beginAtZero: true
                        }
                    },
                    plugins: {
                        legend: {
                            display: true
                        }
                    }
                }
            });
        </script>


    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleEditSection() {
            const editSection = document.getElementById('editProfileSection');
            const passwordSection = document.getElementById('changePasswordSection');
            passwordSection.classList.remove('show');
            editSection.classList.toggle('show');
        }

        function togglePasswordSection() {
            const editSection = document.getElementById('editProfileSection');
            const passwordSection = document.getElementById('changePasswordSection');
            editSection.classList.remove('show');
            passwordSection.classList.toggle('show');
        }

        function validatePassword() {
            const newPassword = document.getElementById('new_password').value;
            const confirmPassword = document.getElementById('new_password_confirmation').value;

            if (newPassword !== confirmPassword) {
                alert('Las contraseñas no coinciden.');
                return false;
            }
            return true;
        }

        function toggleDropdown() {
            const dropdownMenu = document.getElementById('dropdownMenu');
            dropdownMenu.classList.toggle('show');
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
</body>

</html>
