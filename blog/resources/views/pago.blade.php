<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagar Productos</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <style>
        #tablaSeleccionados {
            table-layout: fixed;
            word-wrap: break-word;
        }

        #tablaSeleccionados td {
            overflow-wrap: break-word;
        }
    </style>
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
                <li><a href="{{ route('dashboard') }}"><i class="bi bi-speedometer2"></i> Inicio</a></li>
                <li><a href="{{ route('fiados.index') }}"><i class="bi bi-wallet-fill"></i> Fiar</a></li>
                <li><a href="{{ route('agregar-producto') }}"><i class="bi bi-plus-circle"></i> Agregar Producto</a>
                </li>
                <li><a href="{{ route('registro-ventas') }}"><i class="bi bi-clock-history"></i> Historial Ventas</a>
                </li>
                <li><a href="{{ route('inventario') }}"><i class="bi bi-box"></i> Inventario</a></li>
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
        <h1 class="text-center mb-4">Pagar Productos</h1>
        <div id="mensajeCompra" class="alert d-none"></div>
    
        <div class="row">
            <!-- Productos Disponibles -->
            <div class="col-lg-8 mb-4">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3>Productos Disponibles</h3>
                        <input type="text" id="buscarProducto" class="form-control w-50" placeholder="Buscar producto...">
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle" id="tablaProductos">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Producto</th>
                                        <th>Descripción</th>
                                        <th>Categoría</th>
                                        <th>Precio</th>
                                        <th>Stock</th>
                                        <th>Acción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($productos as $producto)
                                        <tr>
                                            <td>{{ $producto->nombre }}</td>
                                            <td>{{ $producto->descripcion }}</td>
                                            <td>{{ $producto->categoria->nombre }}</td>
                                            <td>{{ $producto->precio }}</td>
                                            <td class="stock">{{ $producto->stock }}</td>
                                            <td>
                                                <button class="btn btn-success btn-agregar" data-id="{{ $producto->id }}"
                                                    data-nombre="{{ $producto->nombre }}"
                                                    data-descripcion="{{ $producto->descripcion }}"
                                                    data-precio="{{ $producto->precio }}">
                                                    Agregar
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
    
            <!-- Productos Seleccionados -->
            <div class="col-lg-4 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h3>Productos Seleccionados</h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle" id="tablaSeleccionados">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Producto</th>
                                        <th>Precio</th>
                                        <th>Cantidad</th>
                                        <th>Acción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Aquí se añadirán los productos seleccionados -->
                                </tbody>
                            </table>
                        </div>
                        <p>Total: $<span id="total">0</span></p>
    
                       <!-- Botón de pago con PayPal -->
                      

                       <div id="paypal-button-container"></div>
<script src="https://www.paypal.com/sdk/js?client-id=ATweER_g8kZHnb1WiQiX-inYExHYm_LESs8veh8HjIf1m0dHPONvTZDrM4AOU1Qazq6JVrKNsWBvYgfC&currency=USD"></script>
<script>
    let productosSeleccionados = [];
    let total = 0;

    // Agregar productos seleccionados
    document.getElementById('tablaProductos').addEventListener('click', function(e) {
        if (e.target.classList.contains('btn-agregar')) {
            const button = e.target;
            const id = button.dataset.id;
            const nombre = button.dataset.nombre;
            const descripcion = button.dataset.descripcion;
            const precio = parseFloat(button.dataset.precio);
            const stockElement = button.closest('tr').querySelector('.stock');
            let stock = parseInt(stockElement.textContent);

            if (stock <= 0) {
                alert('No hay stock suficiente.');
                return;
            }

            const productoExistente = productosSeleccionados.find(p => p.id === id);
            if (productoExistente) {
                productoExistente.cantidad++;
            } else {
                productosSeleccionados.push({
                    id,
                    nombre,
                    descripcion,
                    precio,
                    cantidad: 1
                });
            }

            stock--;
            stockElement.textContent = stock;

            actualizarTablaSeleccionados();
            recalcularTotal();
        }
    });

    function actualizarTablaSeleccionados() {
        const tabla = document.querySelector('#tablaSeleccionados tbody');
        tabla.innerHTML = '';
        productosSeleccionados.forEach(producto => {
            const fila = document.createElement('tr');
            fila.innerHTML = `
                <td>${producto.nombre}</td>
                <td>${producto.precio.toFixed(2)}</td>
                <td><input type="number" class="form-control cantidad-input" value="${producto.cantidad}" min="1"></td>
                <td><button class="btn btn-danger btn-eliminar">Eliminar</button></td>
            `;
            tabla.appendChild(fila);

            fila.querySelector('.cantidad-input').addEventListener('input', function() {
                producto.cantidad = parseInt(this.value) || 1;
                recalcularTotal();
            });

            fila.querySelector('.btn-eliminar').addEventListener('click', function() {
                eliminarProducto(producto.id);
            });
        });
    }

    function recalcularTotal() {
        total = productosSeleccionados.reduce((acc, p) => acc + p.precio * p.cantidad, 0);
        document.getElementById('total').textContent = total.toFixed(2);

        // Actualiza el botón de PayPal cuando cambia el total
        renderPayPalButton();
    }

    function eliminarProducto(id) {
        productosSeleccionados = productosSeleccionados.filter(p => p.id !== id);
        actualizarTablaSeleccionados();
        recalcularTotal();
    }

    // Renderizar botón de PayPal
    function renderPayPalButton() {
        document.getElementById('paypal-button-container').innerHTML = ''; // Limpia el botón previo

        paypal.Buttons({
            createOrder: function(data, actions) {
                return actions.order.create({
                    purchase_units: [{
                        amount: {
                            value: total.toFixed(2) // Total dinámico con 2 decimales
                        }
                    }]
                });
            },
            onApprove: function(data, actions) {
                return actions.order.capture().then(function(details) {
                    alert('Pago completado por ' + details.payer.name.given_name);

                    // Enviar detalles al backend para guardar la venta
                    fetch('/guardar-venta', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            orderID: data.orderID,
                            details: details,
                            productos: productosSeleccionados
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            window.location.href = '/ventas'; // Redirige al historial de ventas
                        } else {
                            alert('Error al guardar la venta. Inténtelo de nuevo.');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Hubo un error al procesar la venta.');
                    });
                });
            },
            onError: function(err) {
                console.error('Error de PayPal:', err);
                alert('Hubo un problema con el pago. Inténtalo de nuevo.');
            }
        }).render('#paypal-button-container');
    }

    // Inicializa el botón de PayPal
    recalcularTotal();
    actualizarTablaSeleccionados();
</script>


                     
                    </div>
                </div>
            </div>
        </div>
    
        <!-- Registro de Ventas -->
        <h2 class="text-center my-4">Registro de Ventas</h2>
        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle" id="tablaVentas">
                <thead class="table-dark">
                    <tr>
                        <th>Referencia</th>
                        <th>Estado</th>
                        <th>Monto</th>
                        <th>Productos</th>
                        <th>Método de Pago</th>
                        <th>Fecha</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($ventas as $venta)
                        <tr>
                            <td>{{ $venta->external_reference }}</td>
                            <td>{{ $venta->status }}</td>
                            <td>{{ $venta->amount }}</td>
                            <td>
                                @foreach (json_decode($venta->productos) as $producto)
                                    {{ $producto->descripcion }} (x{{ $producto->cantidad }})<br>
                                @endforeach
                            </td>
                            <td>{{ $venta->metodo_pago }}</td>
                            <td>{{ $venta->created_at }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <script>
        let productosSeleccionados = [];
        let total = 0;
    
        // Agregar productos seleccionados
        document.getElementById('tablaProductos').addEventListener('click', function(e) {
            if (e.target.classList.contains('btn-agregar')) {
                const button = e.target;
                const id = button.dataset.id;
                const nombre = button.dataset.nombre;
                const descripcion = button.dataset.descripcion;
                const precio = parseFloat(button.dataset.precio);
                const stockElement = button.closest('tr').querySelector('.stock');
                let stock = parseInt(stockElement.textContent);
    
                if (stock <= 0) {
                    alert('No hay stock suficiente.');
                    return;
                }
    
                const productoExistente = productosSeleccionados.find(p => p.id === id);
                if (productoExistente) {
                    productoExistente.cantidad++;
                } else {
                    productosSeleccionados.push({ id, nombre, descripcion, precio, cantidad: 1 });
                }
    
                stock--;
                stockElement.textContent = stock;
    
                actualizarTablaSeleccionados();
                recalcularTotal();
            }
        });
    
        function actualizarTablaSeleccionados() {
            const tabla = document.querySelector('#tablaSeleccionados tbody');
            tabla.innerHTML = '';
            productosSeleccionados.forEach(producto => {
                const fila = document.createElement('tr');
                fila.innerHTML = `
                    <td>${producto.nombre}</td>
                    <td>${producto.precio.toFixed(2)}</td>
                    <td><input type="number" class="form-control cantidad-input" value="${producto.cantidad}" min="1"></td>
                    <td><button class="btn btn-danger btn-eliminar">Eliminar</button></td>
                `;
                tabla.appendChild(fila);
    
                fila.querySelector('.cantidad-input').addEventListener('input', function() {
                    producto.cantidad = parseInt(this.value) || 1;
                    recalcularTotal();
                });
    
                fila.querySelector('.btn-eliminar').addEventListener('click', function() {
                    eliminarProducto(producto.id);
                });
            });
        }
    
        function recalcularTotal() {
            total = productosSeleccionados.reduce((acc, p) => acc + p.precio * p.cantidad, 0);
            document.getElementById('total').textContent = total.toFixed(2);
        }
    
        function eliminarProducto(id) {
            productosSeleccionados = productosSeleccionados.filter(p => p.id !== id);
            actualizarTablaSeleccionados();
            recalcularTotal();
        }

        recalcularTotal();
        actualizarTablaSeleccionados();
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
    <style>
  
        body {
    background-color: #d4e7f5;
    /* Fondo azul claro */
}

#paypal-button-container {
    margin-top: 20px;
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
  
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  

</html>
