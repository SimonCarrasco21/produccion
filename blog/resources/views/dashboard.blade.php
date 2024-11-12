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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}"> <!-- Enlace al CSS separado -->
</head>

<body>
    <nav class="navbar">
        <div class="navbar-left">
            <h2><i class="bi bi-person-circle"></i> Usuario: {{ Auth::user()->name }}</h2>
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

        <div class="navbar-right">
            <ul>
                <li><a href="{{ route('fiados.index') }}"><i class="bi bi-wallet-fill"></i> Fiar</a></li>
                <li><a href="{{ route('agregar-producto') }}"><i class="bi bi-plus-circle"></i> Agregar Producto</a>
                </li>
                <li><a href="#"><i class="bi bi-clock-history"></i> Ver Historial Ventas</a></li>
                <li><a href="{{ route('inventario') }}"><i class="bi bi-box"></i> Inventario</a></li>
                <li><a href="{{ route('pagina.pago') }}" class="btn-pagar"><i class="bi bi-credit-card"></i> Pagar</a>
                </li>


            </ul>
        </div>
    </nav>

    <!-- Sección de últimos registros de ventas -->
    <div class="container mt-4">
        <h2 class="text-center">Últimos Registros de Ventas</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Referencia</th>
                    <th>Estado</th>
                    <th>Monto</th>
                    <th>Productos</th>
                    <th>Método de Pago</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($ventas as $venta)
                    <tr>
                        <td>{{ $venta->external_reference }}</td>
                        <td>{{ $venta->status }}</td>
                        <td>{{ $venta->amount }}</td>
                        <td>
                            @foreach (json_decode($venta->productos) as $producto)
                                {{ $producto->nombre }} (x{{ $producto->cantidad }}) <br>
                            @endforeach
                        </td>
                        <td>{{ $venta->metodo_pago }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">No hay registros de ventas para mostrar.</td>
                    </tr>
                @endforelse
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
            <tbody id="product-list">
                @foreach ($productos as $producto)
                    <tr>
                        <td>{{ $producto->id }}</td>
                        <td>{{ $producto->nombre }}</td>
                        <td>{{ $producto->descripcion }}</td>
                        <td>{{ number_format($producto->precio, 2) }} $</td>
                        <td>{{ $producto->stock }}</td>
                        <td>{{ $producto->categoria->nombre }}</td>
                        <td>{{ $producto->created_at->format('d-m-Y') }}</td>
                        <td>{{ $producto->fecha_vencimiento }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Botón "Ver más" -->
        @if ($productos->hasMorePages())
            <div class="text-center mt-4">
                <button id="load-more" class="btn-ver-mas">Ver más</button>
            </div>
        @endif
    </div>
    <!-- Catálogo de Productos -->
    <div class="container mt-4">
        <h2 class="text-center">Catálogo de Productos</h2>
        <div class="product-grid">
            <a href="{{ route('productos.categoria', ['id' => 1]) }}">
                <div class="product-category">
                    <i class="fa-solid fa-cheese"></i>
                    <p>Lácteos</p>
                </div>
            </a>
            <a href="{{ route('productos.categoria', ['id' => 2]) }}">
                <div class="product-category">
                    <i class="fa-solid fa-seedling"></i>
                    <p>Granos</p>
                </div>
            </a>
            <a href="{{ route('productos.categoria', ['id' => 3]) }}">
                <div class="product-category">
                    <i class="fa-solid fa-soap"></i>
                    <p>Productos de Limpieza</p>
                </div>
            </a>
            <a href="{{ route('productos.categoria', ['id' => 4]) }}">
                <div class="product-category">
                    <i class="fa-solid fa-cookie"></i>
                    <p>Galletas</p>
                </div>
            </a>
            <a href="{{ route('productos.categoria', ['id' => 5]) }}">
                <div class="product-category">
                    <i class="fa-solid fa-bottle-water"></i>
                    <p>Bebidas</p>
                </div>
            </a>
            <a href="{{ route('productos.categoria', ['id' => 6]) }}">
                <div class="product-category">
                    <i class="fa-solid fa-bread-slice"></i>
                    <p>Panadería</p>
                </div>
            </a>
            <a href="{{ route('productos.categoria', ['id' => 7]) }}">
                <div class="product-category">
                    <i class="fa-solid fa-apple-alt"></i>
                    <p>Frutas y Verduras</p>
                </div>
            </a>
            <a href="{{ route('productos.categoria', ['id' => 8]) }}">
                <div class="product-category">
                    <i class="fa-solid fa-drumstick-bite"></i>
                    <p>Embutidos</p>
                </div>
            </a>
            <a href="{{ route('productos.categoria', ['id' => 9]) }}">
                <div class="product-category">
                    <i class="fa-solid fa-hand-sparkles"></i>
                    <p>Productos de Aseo Personal</p>
                </div>
            </a>
            <a href="{{ route('productos.categoria', ['id' => 10]) }}">
                <div class="product-category">
                    <i class="fa-solid fa-paw"></i>
                    <p>Productos para Mascotas</p>
                </div>
            </a>
        </div>
    </div>



    <!-- Aquí va el código JavaScript-->
    <script>
        let currentPage = 1;

        document.getElementById('load-more').addEventListener('click', function() {
            currentPage++; // Aumentar la página actual
            fetchMoreProducts(currentPage);
        });

        function fetchMoreProducts(page) {
            fetch(`{{ url('/dashboard?page=') }}${page}`)
                .then(response => response.text())
                .then(data => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(data, 'text/html');
                    const newProducts = doc.getElementById('product-list').innerHTML;
                    document.getElementById('product-list').insertAdjacentHTML('beforeend', newProducts);

                    // Si ya no hay más páginas, ocultar el botón "Ver más"
                    if (!doc.querySelector('#load-more')) {
                        document.getElementById('load-more').style.display = 'none';
                    }
                })
                .catch(error => console.error('Error al cargar más productos:', error));
        }
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
    <!-- Estilos personalizados -->
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

        .footer {
            background-color: #f8f9fa;
            padding: 20px 0;
            box-shadow: 0px -5px 10px rgba(0, 0, 0, 0.1);
            border-top: 1px solid #e9ecef;
        }

        .footer .col {
            text-align: left;
        }

        .footer a {
            color: #000;
            text-decoration: none;
        }

        .footer a:hover {
            color: #4CAF50;
        }

        .text-center {
            margin-top: 30px;
            font-weight: bold;
            margin-bottom: 30px;
        }

        .btn-ver-mas {
            background-color: #4CAF50;
            color: white;
            padding: 12px 25px;
            border-radius: 12px;
            border: none;
            font-size: 18px;
            box-shadow: 0px 5px 10px rgba(0, 0, 0, 0.1);
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .btn-ver-mas:hover {
            background-color: #45a049;
            transform: translateY(-2px);
        }

        .btn-ver-mas:focus {
            outline: none;
            box-shadow: 0 0 0 0.2rem rgba(72, 173, 67, 0.5);
        }

        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .product-category {
            background-color: #ffffff;
            border-radius: 12px;
            padding: 20px;
            text-align: center;
            box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s, box-shadow 0.2s;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 150px;
        }

        .product-category:hover {
            transform: translateY(-5px);
            box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.15);
        }

        .product-category i {
            font-size: 2.5rem;
            color: #4CAF50;
            margin-bottom: 10px;
        }

        .product-category p {
            font-size: 18px;
            font-weight: bold;
            color: #495057;
            margin: 0;
        }

        @media (max-width: 768px) {
            .product-category {
                height: auto;
                padding: 15px;
            }

            .product-category i {
                font-size: 2rem;
            }

            .product-category p {
                font-size: 16px;
            }
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
<!-- Pie de página -->
<footer class="bg-light text-center text-lg-start mt-5">
    <div class="container p-4">
        <!-- Enlaces rápidos -->
        <div class="row">
            <div class="col-lg-4 col-md-6 mb-4 mb-md-0">
                <h5 class="text-uppercase">Enlaces Rápidos</h5>
                <ul class="list-unstyled">
                    <li>
                        <a href="{{ route('dashboard') }}" class="text-dark">Inicio</a>
                    </li>
                    <li>
                        <a href="{{ route('inventario') }}" class="text-dark">Inventario</a>
                    </li>
                    <li>
                        <a href="{{ route('agregar-producto') }}" class="text-dark">Agregar Producto</a>
                    </li>
                    <li>
                        <a href="#" class="text-dark">Historial de Ventas</a>
                    </li>
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
