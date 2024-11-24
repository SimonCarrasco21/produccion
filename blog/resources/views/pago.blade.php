<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagar Productos</title>
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
                <li><a href="{{ route('dashboard') }}"><i class="bi bi-speedometer2"></i> Inicio</a></li>
                <li><a href="{{ route('fiados.index') }}"><i class="bi bi-wallet-fill"></i> Fiar</a></li>
                <li><a href="{{ route('agregar-producto') }}"><i class="bi bi-plus-circle"></i> Agregar Producto</a>
                </li>
                <li><a href="{{ route('registro-ventas') }}"><i class="bi bi-clock-history"></i> Ver Historial
                        Ventas</a></li>
                <li><a href="{{ route('inventario') }}"><i class="bi bi-box"></i> Inventario</a></li>
            </ul>
        </div>
    </nav>

    <div class="container mt-5">
        <h1 class="text-center mb-4">Pagar Productos</h1>
        <div id="mensajeCompra" class="alert d-none"></div>

        <div class="row">

            <div class="col-lg-8 mb-4">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3>Productos Disponibles</h3>
                        <input type="text" id="buscarProducto" class="form-control w-50"
                            placeholder="Buscar producto...">
                    </div>
                    <div class="card-body">
                        <table class="table table-responsive" id="tablaProductos">
                            <thead>
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

            <div class="col-lg-4 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h3>Productos Seleccionados</h3>
                    </div>
                    <div class="card-body">
                        <table class="table table-responsive" id="tablaSeleccionados">
                            <thead>
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
                        <p>Total: $<span id="total">{{ $totalFiado ?? 0 }}</span></p>

                        <!-- Formulario para "Pagar con POS" -->
                        <form id="productosFormPos" action="{{ route('payments.pay.pos') }}" method="POST">
                            @csrf
                            <input type="hidden" name="productosSeleccionados" id="productosSeleccionadosPos">
                            <input type="hidden" name="id_fiado" value="{{ $idFiado ?? '' }}">
                            <button type="submit" class="btn btn-primary w-100 mb-2">Pagar con POS</button>
                        </form>

                        <!-- Formulario para "Pagar en Efectivo" -->
                        <form id="productosFormEfectivo" action="{{ route('pago.efectivo') }}" method="POST">
                            @csrf
                            <input type="hidden" name="productosSeleccionados" id="productosSeleccionadosEfectivo">
                            <input type="hidden" name="id_fiado" value="{{ $idFiado ?? '' }}">
                            <button type="submit" class="btn btn-success w-100">Pagar en Efectivo</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <h2 class="text-center my-4">Registro de Ventas</h2>
        <table class="table table-striped" id="tablaVentas">
            <thead>
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

    <script>
        // Lista inicial de productos seleccionados
        let productosSeleccionados = @json($productosFiados ?? []);
        let total = {{ $totalFiado ?? 0 }}; // Total inicial

        // Recalcula el total y actualiza los formularios con los productos seleccionados
        function recalcularTotal() {
            total = productosSeleccionados.reduce((acc, p) => acc + p.precio * p.cantidad, 0);
            document.getElementById('total').textContent = total.toFixed(2);

            // Actualiza los campos ocultos para enviar productos seleccionados en ambos métodos de pago
            document.getElementById('productosSeleccionadosPos').value = JSON.stringify(productosSeleccionados);
            document.getElementById('productosSeleccionadosEfectivo').value = JSON.stringify(productosSeleccionados);
        }

        // Evento para agregar productos desde la tabla de productos disponibles
        document.getElementById('tablaProductos').addEventListener('click', function(e) {
            if (e.target.classList.contains('btn-agregar')) {
                const button = e.target;
                const id = button.dataset.id; // ID del producto
                const descripcion = button.dataset.descripcion; // Descripción
                const precio = parseFloat(button.dataset.precio); // Precio
                const stockElement = button.closest('tr').querySelector('.stock');
                let stock = parseInt(stockElement.textContent);

                if (stock <= 0) {
                    alert('Stock no disponible');
                    return;
                }

                // Si el producto ya está en la lista, incrementa la cantidad; si no, lo agrega
                const productoExistente = productosSeleccionados.find(p => p.id == id);
                if (productoExistente) {
                    productoExistente.cantidad++;
                } else {
                    productosSeleccionados.push({
                        id,
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

        // Actualiza la tabla de productos seleccionados
        function actualizarTablaSeleccionados() {
            const tabla = document.querySelector('#tablaSeleccionados tbody');
            tabla.innerHTML = '';
            productosSeleccionados.forEach(producto => {
                const fila = document.createElement('tr');
                fila.innerHTML = `
            <td>${producto.descripcion}</td>
            <td>${producto.precio.toFixed(2)}</td>
            <td><input type="number" class="form-control cantidad-input" value="${producto.cantidad}" min="1"></td>
            <td><button class="btn btn-danger btn-eliminar">Eliminar</button></td>
        `;
                tabla.appendChild(fila);

                // Modifica la cantidad del producto
                fila.querySelector('.cantidad-input').addEventListener('input', function() {
                    producto.cantidad = parseInt(this.value) || 1;
                    recalcularTotal();
                });

                // Elimina el producto de la lista
                fila.querySelector('.btn-eliminar').addEventListener('click', function() {
                    eliminarProducto(producto.id);
                    recalcularTotal();
                });
            });
        }

        // Elimina un producto de la lista seleccionada
        function eliminarProducto(id) {
            productosSeleccionados = productosSeleccionados.filter(p => p.id != id);
            actualizarTablaSeleccionados();
        }

        // Inicializa la tabla y el total
        recalcularTotal();
        actualizarTablaSeleccionados();
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
