<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página Principal</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

    <!-- Enlace a Bootstrap y Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}"> <!-- Enlace al CSS separado -->
    
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
    <!-- script de navbar -->
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

  <!-- carrusel-->
  <div id="carouselExample" class="carousel slide mb-4" data-bs-ride="carousel">
    <div class="carousel-inner">
        <!-- Primera imagen -->
        <div class="carousel-item active">
            <img src="{{ asset('images/banner1.jpg') }}" class="d-block w-100" alt="Promoción 1">
            <div class="carousel-caption d-none d-md-block">
                <h5 class="fw-bold">¡Promoción Especial!</h5>
                <p>Disfruta de descuentos exclusivos en todos nuestros productos por tiempo limitado.</p>
                <a href="#" class="btn btn-primary">Explorar Ofertas</a>
            </div>
        </div>
        <!-- Segunda imagen -->
        <div class="carousel-item">
            <img src="{{ asset('images/banner2.jpg') }}" class="d-block w-100" alt="Nuevos Productos">
            <div class="carousel-caption d-none d-md-block">
                <h5 class="fw-bold">Descubre Novedades</h5>
                <p>Conoce nuestra última línea de productos diseñados para ti.</p>
                <a href="#" class="btn btn-success">Ver Más</a>
            </div>
        </div>
        <!-- Tercera imagen -->
        <div class="carousel-item">
            <img src="{{ asset('images/banner3.jpg') }}" class="d-block w-100" alt="Calidad Garantizada">
            <div class="carousel-caption d-none d-md-block">
                <h5 class="fw-bold">Calidad Garantizada</h5>
                <p>Confía en nosotros para productos de la mejor calidad a precios accesibles.</p>
                <a href="#" class="btn btn-warning">Más Información</a>
            </div>
        </div>
    </div>
    <!-- Controles del carrusel -->
    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Anterior</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Siguiente</span>
    </button>
</div>

    <!-- Catálogo de Productos -->
<div class="container mt-4">
    <h2 class="text-center">Elige tu Categoría de Productos</h2>
    <div class="product-grid">
        <a href="{{ route('productos.categoria', ['id' => 1]) }}">
            <div class="product-category">
                <i class="fas fa-wrench"></i>
                <p>Herramientas de Mano</p>
            </div>
        </a>
        <a href="{{ route('productos.categoria', ['id' => 2]) }}">
            <div class="product-category">
                <i class="fas fa-tools"></i>
                <p>Herramientas Eléctricas</p>
            </div>
        </a>
        <a href="{{ route('productos.categoria', ['id' => 3]) }}">
            <div class="product-category">
                <i class="fas fa-paint-roller"></i>
                <p>Pinturas y Acabados</p>
            </div>
        </a>
        <a href="{{ route('productos.categoria', ['id' => 4]) }}">
            <div class="product-category">
                <i class="fas fa-plug"></i>
                <p>Material Eléctrico</p>
            </div>
        </a>
        <a href="{{ route('productos.categoria', ['id' => 5]) }}">
            <div class="product-category">
                <i class="fas fa-hammer"></i>
                <p>Materiales de Construcción</p>
            </div>
        </a>
        <a href="{{ route('productos.categoria', ['id' => 6]) }}">
            <div class="product-category">
                <i class="fas fa-hard-hat"></i>
                <p>Equipos de Seguridad</p>
            </div>
        </a>
        <a href="{{ route('productos.categoria', ['id' => 7]) }}">
            <div class="product-category">
                <i class="fas fa-tint"></i>
                <p>Adhesivos y Selladores</p>
            </div>
        </a>
        <a href="{{ route('productos.categoria', ['id' => 8]) }}">
            <div class="product-category">
                <i class="fas fa-screwdriver"></i>
                <p>Tornillería</p>
            </div>
        </a>
        <a href="{{ route('productos.categoria', ['id' => 9]) }}">
            <div class="product-category">
                <i class="fas fa-faucet"></i>
                <p>Plomería</p>
            </div>
        </a>
        <a href="{{ route('productos.categoria', ['id' => 10]) }}">
            <div class="product-category">
                <i class="fas fa-leaf"></i>
                <p>Jardinería</p>
            </div>
        </a>
        <a href="{{ route('productos.categoria', ['id' => 11]) }}">
            <div class="product-category">
                <i class="fas fa-box"></i>
                <p>Almacenaje</p>
            </div>
        </a>
        <a href="{{ route('productos.categoria', ['id' => 12]) }}">
            <div class="product-category">
                <i class="fas fa-lightbulb"></i>
                <p>Iluminación</p>
            </div>
        </a>
        <a href="{{ route('productos.categoria', ['id' => 13]) }}">
            <div class="product-category">
                <i class="fas fa-cogs"></i>
                <p>Accesorios Industriales</p>
            </div>
        </a>
        <a href="{{ route('productos.categoria', ['id' => 14]) }}">
            <div class="product-category">
                <i class="fas fa-drafting-compass"></i>
                <p>Instrumentos de Medición</p>
            </div>
        </a>
        <a href="{{ route('productos.categoria', ['id' => 15]) }}">
            <div class="product-category">
                <i class="fas fa-anchor"></i>
                <p>Fijaciones</p>
            </div>
        </a>
    </div>
</div>

    <!-- Sección de últimos registros de ventas -->
    <div class="container mt-4">
        <h2 class="text-center">Últimos Registros de Ventas</h2>
        <div class="table-responsive">
            <table class="table table-bordered table-hover shadow-sm">
                <thead class="table-dark">
                    <tr>
                        <th>Código de Venta</th>
                        <th>Monto</th>
                        <th>Productos</th>
                        <th>Método de Pago</th>
                    </tr>
                </thead>
                <tbody id="ventas-list">
                    @forelse ($ventas as $venta)
                        <tr>
                            <td>{{ $venta->external_reference }}</td>
                            <td>{{ $venta->amount }}</td>
                            <td>
                                @foreach (json_decode($venta->productos) as $producto)
                                    {{ $producto->descripcion }} (x{{ $producto->cantidad ?? 1 }}) <br>
                                @endforeach
                            </td>
                            <td>{{ $venta->metodo_pago }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">No hay registros de ventas para mostrar.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Botón "Ver Más" para Ventas se agregará por JavaScript -->
        <div id="ventas-load-more-container" class="text-center mt-4"></div>
    </div>
    <!-- Sección de productos agregados -->
    <div class="container mt-4">
        <h2 class="text-center">Productos Agregados</h2>
        <div class="table-responsive">
            <table class="table table-bordered table-hover shadow-sm">
                <thead class="table-dark">
                    <tr>
                        <th>ID Producto</th>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Precio</th>
                        <th>Stock</th>
                        <th>Categoría</th>
                        <th>Fecha de Agregado</th>
                        <th>Fecha de Vencimiento del Producto</th>
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
        </div>

        <!-- Botón "Ver Más" para Productos se agregará por JavaScript -->
        <div id="product-load-more-container" class="text-center mt-4"></div>
    </div>
   
    <!-- Ventana para mesnaje de productos con bajo stock -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            fetch('/dashboard/productos-stock-bajo')
                .then(response => response.json())
                .then(data => {
                    if (data.length > 0) {
                        data.forEach(producto => {
                            mostrarNotificacionStockBajo(producto.descripcion, producto.stock);
                        });
                    }
                })
                .catch(error => console.error('Error al cargar productos con stock bajo:', error));
        });

        function mostrarNotificacionStockBajo(descripcion, stock) {
            const contenedor = document.createElement('div');
            contenedor.className = 'notificacion-stock-bajo';
            contenedor.innerHTML = `
            <div class="notificacion-icono">
                <i class="fa fa-box"></i>
            </div>
            <div class="notificacion-contenido">
                <p><strong>¡Stock Bajo!</strong></p>
                <p>El producto <strong>${descripcion}</strong> tiene solo <strong>${stock}</strong> unidades en inventario.</p>
                <button class="btn-ir-inventario">Ir a Inventario</button>
            </div>
            <button class="cerrar-notificacion">&times;</button>
        `;
            document.body.appendChild(contenedor);

            // Evento para cerrar la notificación
            contenedor.querySelector('.cerrar-notificacion').addEventListener('click', () => {
                contenedor.remove();
            });

            // Evento para redirigir al inventario
            contenedor.querySelector('.btn-ir-inventario').addEventListener('click', () => {
                window.location.href = '/inventario'; // Cambia esta ruta si es diferente
            });

            // Eliminar automáticamente después de 5 segundos
            setTimeout(() => contenedor.remove(), 5000);
        }
    </script>
  
    <!-- Aquí va el código JavaScript-->
    <script>
        let currentPageProductos = 1; // Página actual para productos
        let currentPageVentas = 1; // Página actual para ventas
        const initialRows = 5; // Número de filas iniciales a mostrar en ambas tablas

        // Función para crear y agregar el botón "Ver Más" / "Ver Menos"
        function createLoadMoreButton(containerId, buttonId, type) {
            const container = document.getElementById(containerId);
            let button = document.getElementById(buttonId);

            if (!button) {
                button = document.createElement('button');
                button.id = buttonId;
                button.className = 'btn-ver-mas';
                button.innerText = "Ver Más";
                container.appendChild(button);

                // Agregar el evento de clic
                button.addEventListener('click', function() {
                    if (button.innerText === "Ver Más") {
                        if (type === 'productos') {
                            currentPageProductos++;
                            fetchMoreData('productos', currentPageProductos);
                        } else {
                            currentPageVentas++;
                            fetchMoreData('ventas', currentPageVentas);
                        }
                    } else {
                        const listId = type === 'productos' ? 'product-list' : 'ventas-list';
                        collapseTable(listId, buttonId);
                    }
                });
            }
        }

        // Llamar a la función para crear botones en ambos contenedores
        createLoadMoreButton('product-load-more-container', 'load-more', 'productos');
        createLoadMoreButton('ventas-load-more-container', 'load-more-ventas', 'ventas');

        // Función para cargar más datos en la tabla de productos o ventas
        function fetchMoreData(type, page) {
            const url = type === 'productos' ?
                `{{ url('/dashboard?page=') }}${page}` :
                `{{ url('/dashboard?page=') }}${page}&type=ventas`;

            const listId = type === 'productos' ? 'product-list' : 'ventas-list';
            const loadMoreBtnId = type === 'productos' ? 'load-more' : 'load-more-ventas';

            fetch(url)
                .then(response => response.text())
                .then(data => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(data, 'text/html');

                    // Eliminar el mensaje "No hay registros..." si existe
                    const mensaje = document.getElementById(`${listId}-mensaje`);
                    if (mensaje) {
                        mensaje.remove();
                    }

                    // Extraer las nuevas filas de la respuesta
                    const newRows = doc.getElementById(listId).innerHTML;

                    // Verificar si hay nuevos datos
                    if (newRows.trim() !== "") {
                        document.getElementById(listId).insertAdjacentHTML('beforeend', newRows);
                        document.getElementById(loadMoreBtnId).innerText = "Ver Menos";
                    } else {
                        // Mostrar el mensaje "No hay registros..."
                        const nuevoMensaje = document.createElement('p');
                        nuevoMensaje.id = `${listId}-mensaje`;
                        nuevoMensaje.className = 'text-center text-muted mt-2';
                        nuevoMensaje.textContent = `No hay registros de ${type} para mostrar.`;
                        document.getElementById(listId).parentNode.appendChild(nuevoMensaje);
                    }
                })
                .catch(error => console.error(`Error al cargar más ${type}:`, error));
        }


        // Función para contraer la tabla y volver a las filas iniciales
        function collapseTable(listId, loadMoreBtnId) {
            const list = document.getElementById(listId);
            const rows = Array.from(list.getElementsByTagName('tr'));

            // Mantener solo las filas iniciales y eliminar las adicionales
            rows.slice(initialRows).forEach(row => row.remove());

            // Eliminar el mensaje "No hay registros..." si existe
            const mensaje = document.getElementById(`${listId}-mensaje`);
            if (mensaje) {
                mensaje.remove();
            }

            // Restablecer el botón a "Ver Más"
            document.getElementById(loadMoreBtnId).innerText = "Ver Más";

            // Reiniciar el contador de páginas según la tabla
            if (listId === 'product-list') {
                currentPageProductos = 1;
            } else {
                currentPageVentas = 1;
            }
        }
    </script>
    <!-- Estilos personalizados -->
    <style>
        body {
    background-color: #d4e7f5;
    /* Fondo azul claro */
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
    background-color: #3498db;
    border-radius: 12px;
    box-shadow: 0px 5px 10px rgba(0, 0, 0, 0.2);
    transition: background-color 0.3s ease, transform 0.3s ease;
    display: inline-block;
    border: none;
}

.navbar-right ul li a:hover,
.navbar-right ul li button:hover {
    background-color: #2d81c4;
    transform: translateY(-2px);
}

.dropdown-btn {
    background-color: #3498db;
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
    background-color: #2d81c4;
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
    color: #3498db;
}

.text-center {
    margin-top: 30px;
    font-weight: bold;
    margin-bottom: 30px;
}

.btn-ver-mas {
    background-color: #3498db;
    color: white;
    padding: 12px 25px;
    border-radius: 12px;
    border: none;
    font-size: 18px;
    box-shadow: 0px 5px 10px rgba(0, 0, 0, 0.1);
    transition: background-color 0.3s ease, transform 0.3s ease;
}

.btn-ver-mas:hover {
    background-color: #2d81c4;
    transform: translateY(-2px);
}

.btn-ver-mas:focus {
    outline: none;
    box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.5);
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
    color: #3498db;
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
    <!-- Estilos personalizados par la ventana emergente -->
    <style>
        .notificacion-stock-bajo {
            position: fixed;
            bottom: 20px;
            left: 20px;
            background-color: #e9f7ef;
            /* Verde claro */
            color: #155724;
            /* Verde oscuro */
            border: 1px solid #c3e6cb;
            /* Verde más claro */
            border-radius: 8px;
            padding: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            z-index: 1000;
            margin-bottom: 10px;
            max-width: 350px;
            display: flex;
            align-items: center;
            animation: fadeIn 0.5s;
        }

        .notificacion-stock-bajo .notificacion-icono {
            margin-right: 15px;
            font-size: 24px;
            color: #28a745;
            /* Verde vivo */
        }

        .notificacion-stock-bajo .notificacion-contenido p {
            margin: 5px 0;
            font-size: 14px;
        }

        .notificacion-stock-bajo .btn-ir-inventario {
            background-color: #28a745;
            /* Botón verde */
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 12px;
            margin-top: 5px;
        }

        .notificacion-stock-bajo .btn-ir-inventario:hover {
            background-color: #218838;
            /* Verde más oscuro */
        }

        .notificacion-stock-bajo button.cerrar-notificacion {
            background: none;
            border: none;
            color: #155724;
            font-size: 18px;
            font-weight: bold;
            cursor: pointer;
            margin-left: auto;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
   
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
</body>
<!-- Pie de página -->
<footer class="bg-dark text-white text-center text-lg-start mt-5">
    <div class="container p-4">
        <div class="row">
            <!-- Enlaces rápidos -->
            <div class="col-lg-3 col-md-6 mb-4 mb-md-0">
                <h5 class="text-uppercase fw-bold">Enlaces Rápidos</h5>
                <ul class="list-unstyled">
                    <li>
                        <a href="{{ route('dashboard') }}" class="text-white text-decoration-none">Inicio</a>
                    </li>
                    <li>
                        <a href="{{ route('inventario') }}" class="text-white text-decoration-none">Inventario</a>
                    </li>
                    <li>
                        <a href="{{ route('agregar-producto') }}" class="text-white text-decoration-none">Agregar Producto</a>
                    </li>
                    <li>
                        <a href="#" class="text-white text-decoration-none">Historial de Ventas</a>
                    </li>
                </ul>
            </div>

            <!-- Información de contacto -->
            <div class="col-lg-3 col-md-6 mb-4 mb-md-0">
                <h5 class="text-uppercase fw-bold">Contáctanos</h5>
                <ul class="list-unstyled">
                    <li><i class="fas fa-map-marker-alt"></i> Dirección: Av. Los Ferreteros 123, Santiago, Chile</li>
                    <li><i class="fas fa-phone"></i> Teléfono: +56 9 1122 3344</li>
                    <li><i class="fas fa-envelope"></i> Correo: <a href="mailto:contacto@ferremax.cl" class="text-white text-decoration-none">contacto@ferremax.cl</a></li>
                </ul>
            </div>

            <!-- Redes sociales -->
            <div class="col-lg-3 col-md-6 mb-4 mb-md-0">
                <h5 class="text-uppercase fw-bold">Síguenos</h5>
                <div class="d-flex justify-content-center">
                    <a href="#" class="text-white me-4">
                        <i class="fab fa-facebook fa-2x"></i>
                    </a>
                    <a href="#" class="text-white me-4">
                        <i class="fab fa-instagram fa-2x"></i>
                    </a>
                    <a href="#" class="text-white me-4">
                        <i class="fab fa-twitter fa-2x"></i>
                    </a>
                    <a href="#" class="text-white me-4">
                        <i class="fab fa-linkedin fa-2x"></i>
                    </a>
                </div>
            </div>

            <!-- Acerca de Ferremax -->
            <div class="col-lg-3 col-md-6 mb-4 mb-md-0">
                <h5 class="text-uppercase fw-bold">Sobre Ferremax</h5>
                <p>
                    En Ferremax encontrarás herramientas y materiales de construcción para cada proyecto. 
                    Nos esforzamos por ofrecer productos de calidad para profesionales y entusiastas del bricolaje.
                </p>
            </div>
        </div>
    </div>

    <div class="text-center p-3 bg-secondary">
        <p class="mb-0">© 2025 Ferremax. Todos los derechos reservados.</p>
        <p class="mb-0">
            Diseñado con <i class="fas fa-heart text-danger"></i> para los amantes de la construcción.
        </p>
    </div>
</footer>


</html>
