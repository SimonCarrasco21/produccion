<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagar Productos</title>
    <!-- Enlace a Bootstrap y Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
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
                <button class="dropdown-btn" type="button" id="dropdownMenuButton" onclick="toggleDropdown()">
                    <i class="bi bi-person-circle"></i> Perfil
                </button>
                <ul class="dropdown-menu" id="dropdownMenu" aria-labelledby="dropdownMenuButton">
                    <li><a class="dropdown-item" href="{{ route('perfil') }}"><i class="bi bi-eye"></i> Ver Perfil</a>
                    </li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}"
                            onsubmit="return confirm('¿Estás seguro de que deseas cerrar sesión?');">
                            @csrf
                            <button type="submit" class="dropdown-item"><i class="bi bi-box-arrow-right"></i> Cerrar
                                Sesión</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
        <div class="navbar-right">
            <ul>
                <li><a href="{{ route('dashboard') }}"><i class="bi bi-speedometer2"></i> Inicio</a></li>
                <li><a href="{{ route('fiados.index') }}"><i class="bi bi-wallet-fill"></i> Fiar</a></li>
                <li><a href="{{ route('agregar-producto') }}"><i class="bi bi-plus-circle"></i> Agregar Producto</a>
                </li>
                <li><a href="#"><i class="bi bi-clock-history"></i> Ver Historial Ventas</a></li>
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
                            placeholder="Buscar por nombre, descripcion o categoria...">
                    </div>
                    <div class="card-body">
                        <table class="table table-responsive" id="tablaProductos">
                            <thead>
                                <tr>
                                    <th>Producto</th>
                                    <th>Descripcion</th>
                                    <th>Categoria</th>
                                    <th>Precio</th>
                                    <th>Stock</th>
                                    <th>Accion</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($productos as $producto)
                                    <tr data-id="{{ $producto->id }}">
                                        <td>{{ $producto->nombre }}</td>
                                        <td>{{ $producto->descripcion }}</td>
                                        <td>{{ $producto->categoria->nombre }}</td>
                                        <td>{{ $producto->precio }}</td>
                                        <td class="stock">{{ $producto->stock }}</td>
                                        <td>
                                            <button class="btn btn-success btn-agregar" data-id="{{ $producto->id }}"
                                                data-nombre="{{ $producto->nombre }}"
                                                data-precio="{{ $producto->precio }}"
                                                data-stock="{{ $producto->stock }}">Agregar</button>
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
                                    <th>Accion</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Aquí se añadirán las filas con los productos seleccionados -->
                            </tbody>
                        </table>
                        <p>Total: $<span id="total">0</span></p>

                        <!-- Formulario para pago con POS -->
                        <form id="productosForm" action="{{ route('payments.pay.pos') }}" method="POST">
                            @csrf
                            <input type="hidden" name="productosSeleccionados" id="productosSeleccionados">
                            <button type="submit" class="btn btn-primary w-100 mb-2">Pagar con POS</button>
                        </form>

                        <button class="btn btn-success w-100" onclick="pagarEfectivo()">Pagar en Efectivo</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Agregar tabla de registro de ventas -->
        <h2 class="text-center my-4">Registro de Ventas</h2>
        <table class="table table-striped" id="tablaVentas">
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
                @foreach ($ventas as $venta)
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
                @endforeach
            </tbody>
        </table>
    </div>

    <script>
        let total = 0;
        let productosSeleccionados = [];

        document.getElementById('buscarProducto').addEventListener('keyup', function() {
            const filtro = this.value.toLowerCase();
            document.querySelectorAll('#tablaProductos tbody tr').forEach(row => {
                const nombre = row.cells[0].textContent.toLowerCase();
                const descripcion = row.cells[1].textContent.toLowerCase();
                const categoria = row.cells[2].textContent.toLowerCase();
                row.style.display = nombre.includes(filtro) || descripcion.includes(filtro) || categoria
                    .includes(filtro) ? '' : 'none';
            });
        });

        document.querySelectorAll('.btn-agregar').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.dataset.id;
                const nombre = this.dataset.nombre;
                const precio = parseFloat(this.dataset.precio);
                const stockElement = this.closest('tr').querySelector('.stock');
                let stock = parseInt(stockElement.textContent);

                if (stock <= 0) {
                    alert('Stock no disponible');
                    return;
                }

                const productoExistente = productosSeleccionados.find(p => p.id == id);
                if (productoExistente) {
                    productoExistente.cantidad += 1;
                } else {
                    productosSeleccionados.push({
                        id: id,
                        nombre: nombre,
                        precio: precio,
                        cantidad: 1
                    });
                    agregarFilaSeleccionados(id, nombre, precio);
                }
                stock -= 1;
                stockElement.textContent = stock;
                recalcularTotal();
            });
        });

        function agregarFilaSeleccionados(id, nombre, precio) {
            const tablaSeleccionados = document.querySelector('#tablaSeleccionados tbody');
            const fila = document.createElement('tr');
            fila.setAttribute('data-id', id);
            fila.innerHTML = `
                <td>${nombre}</td>
                <td>${precio}</td>
                <td><input type="number" min="1" class="form-control cantidad-input" value="1"></td>
                <td><button class="btn btn-danger btn-eliminar">Eliminar</button></td>
            `;
            tablaSeleccionados.appendChild(fila);

            fila.querySelector('.cantidad-input').addEventListener('input', function() {
                actualizarTotalCantidad(id, parseInt(this.value));
            });

            fila.querySelector('.btn-eliminar').addEventListener('click', function() {
                eliminarProducto(id, parseInt(fila.querySelector('.cantidad-input').value));
                fila.remove();
                recalcularTotal();
            });
        }

        function actualizarTotalCantidad(id, nuevaCantidad) {
            const producto = productosSeleccionados.find(p => p.id == id);
            const stockElement = document.querySelector(`#tablaProductos tbody tr[data-id="${id}"] .stock`);
            const stockActual = parseInt(stockElement.textContent);

            if (producto) {
                const diferencia = nuevaCantidad - producto.cantidad;
                if (diferencia > stockActual) {
                    alert('Stock insuficiente');
                    return;
                }
                producto.cantidad = nuevaCantidad;
                stockElement.textContent = stockActual - diferencia;
            }
            recalcularTotal();
        }

        function recalcularTotal() {
            total = productosSeleccionados.reduce((acc, p) => acc + p.precio * p.cantidad, 0);
            document.getElementById('total').textContent = total.toFixed(2);
        }

        function eliminarProducto(id, cantidad) {
            const productoIndex = productosSeleccionados.findIndex(p => p.id == id);
            const stockElement = document.querySelector(`#tablaProductos tbody tr[data-id="${id}"] .stock`);

            if (productoIndex > -1) {
                const producto = productosSeleccionados[productoIndex];
                const stockActual = parseInt(stockElement.textContent);
                stockElement.textContent = stockActual + cantidad;
                productosSeleccionados.splice(productoIndex, 1);
            }
        }

        function pagarEfectivo() {
            if (confirm("¿Se realizó la venta en efectivo?")) {
                const data = {
                    productosSeleccionados: JSON.stringify(productosSeleccionados),
                    metodo_pago: 'Efectivo',
                    total: total
                };

                fetch('{{ route('pago.efectivo') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify(data)
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            agregarFilaTablaVentas(data.data, 'Efectivo');
                            mostrarMensaje(data.message, 'alert-success');
                            productosSeleccionados = [];
                            document.querySelector('#tablaSeleccionados tbody').innerHTML = '';
                            recalcularTotal();
                        } else {
                            mostrarMensaje(data.error, 'alert-danger');
                        }
                    })
                    .catch(error => console.error('Error:', error));
            } else {
                mostrarMensaje("La venta ha sido cancelada", "alert-danger");
            }
        }

        function agregarFilaTablaVentas(data, metodoPago) {
            const tablaVentas = document.getElementById('tablaVentas').getElementsByTagName('tbody')[0];
            const fila = tablaVentas.insertRow();

            fila.insertCell(0).textContent = data.external_reference;
            fila.insertCell(1).textContent = data.status;
            fila.insertCell(2).textContent = `$${data.amount.toFixed(2)}`;
            fila.insertCell(3).textContent = data.productos.map(p => `${p.nombre} (x${p.cantidad})`).join(', ');
            fila.insertCell(4).textContent = metodoPago;
        }

        function mostrarMensaje(mensaje, clase) {
            const mensajeCompra = document.getElementById('mensajeCompra');
            mensajeCompra.textContent = mensaje;
            mensajeCompra.className = `alert ${clase}`;
            mensajeCompra.classList.remove('d-none');
        }

        // Guardar productos seleccionados en JSON
        function enviarProductos() {
            document.getElementById('productosSeleccionados').value = JSON.stringify(productosSeleccionados);
        }

        document.getElementById('productosForm').addEventListener('submit', enviarProductos);
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
