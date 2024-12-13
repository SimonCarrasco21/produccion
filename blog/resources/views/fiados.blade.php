<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Fiados</title>
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
        <div class="navbar-left">
            <h2>
                <!-- Foto de perfil -->
                <img src="{{ Auth::user()->profile_picture && file_exists(storage_path('app/public/' . Auth::user()->profile_picture))
                    ? asset('storage/' . Auth::user()->profile_picture)
                    : asset('images/default-profile.png') }}"
                    alt="Foto de Perfil" style="width: 40px; height: 40px; border-radius: 50%; margin-right: 10px;">

                <!-- Icono y nombre del usuario -->
                <i></i> Usuario: {{ Auth::user()->name }}
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

        <div class="navbar-right">
            <ul>
                <li><a href="{{ route('dashboard') }}"><i class="bi bi-speedometer2"></i> Inicio</a></li>
                <li><a href="{{ route('agregar-producto') }}"><i class="bi bi-plus-circle"></i> Agregar Producto</a>
                </li>
                <li><a href="{{ route('registro-ventas') }}"><i class="bi bi-clock-history"></i> Ver Historial
                        Ventas</a></li>
                <li><a href="{{ route('inventario') }}"><i class="bi bi-box"></i> Inventario</a></li>
                <li><a href="{{ route('pagina.pago') }}" class="btn-pagar"><i class="bi bi-credit-card"></i> Pagar</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container mt-5">
        <h1 class="text-center">Fiar Productos</h1>

        <!-- Mensajes de éxito y error -->
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <!-- Productos y formulario en la misma fila -->
        <div class="row">
            <!-- Lista de productos -->
            <div class="col-lg-8 mb-4">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span>Productos Disponibles</span>
                        <input type="text" id="buscarProducto" class="form-control w-50"
                            placeholder="Buscar producto...">
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover shadow-sm">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Nombre</th>
                                        <th class="d-none d-md-table-cell">Descripción</th>
                                        <th class="d-none d-lg-table-cell">Precio</th>
                                        <th class="d-none d-lg-table-cell">Stock</th>
                                        <th>Acción</th>
                                    </tr>
                                </thead>
                                <tbody id="tablaProductos">
                                    @foreach ($productos as $producto)
                                        <tr>
                                            <td>{{ $producto->nombre }}</td>
                                            <td class="d-none d-md-table-cell">{{ $producto->descripcion }}</td>
                                            <td class="d-none d-lg-table-cell">{{ $producto->precio }}</td>
                                            <td class="d-none d-lg-table-cell">{{ $producto->stock }}</td>
                                            <td>
                                                <button class="btn btn-success btn-agregar"
                                                    data-id="{{ $producto->id }}"
                                                    data-nombre="{{ $producto->nombre }}"
                                                    data-precio="{{ $producto->precio }}"
                                                    data-stock="{{ $producto->stock }}">
                                                    <i class="bi bi-cart-plus"></i> Agregar
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

            <!-- Formulario de fiados y productos seleccionados -->
            <div class="col-lg-4 mb-4">
                <div class="card">
                    <div class="card-header">
                        Registrar Fiados
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('fiados.store') }}"
                            onsubmit="return verificarLimiteFiados();">
                            @csrf
                            <div class="mb-3">
                                <label for="id_cliente" class="form-label">ID Cliente</label>
                                <input type="text" name="id_cliente" id="id_cliente" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="nombre_cliente" class="form-label">Nombre del Cliente</label>
                                <input type="text" name="nombre_cliente" id="nombre_cliente" class="form-control"
                                    required>
                            </div>
                            <!-- Tabla de productos seleccionados -->
                            <div class="mb-3">
                                <label class="form-label">Productos Seleccionados</label>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover shadow-sm"
                                        id="tablaProductosSeleccionados">
                                        <thead class="table-dark">
                                            <tr>
                                                <th>Producto</th>
                                                <th>Cantidad</th>
                                                <th>Total</th>
                                                <th>Acción</th> <!-- Nueva columna para el botón eliminar -->
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary w-100"><i class="bi bi-save"></i> Registrar
                                Fiado</button>
                            <input type="hidden" name="productos" id="productosSeleccionados">
                        </form>
                    </div>
                </div>
            </div>
        </div>


        <!-- Tabla de fiados registrados -->
        <div class="card">
            <div class="card-header">
                Fiados Registrados
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover shadow-sm">
                        <thead class="table-dark">
                            <tr>
                                <th>ID Cliente</th>
                                <th>Nombre del Cliente</th>
                                <th>Productos</th>
                                <th>Total Precio</th>
                                <th>Fecha de Compra</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($fiados as $fiado)
                                <tr>
                                    <td>{{ $fiado->id_cliente }}</td>
                                    <td>{{ $fiado->nombre_cliente }}</td>
                                    <td>
                                        @php
                                            $productos = json_decode($fiado->productos, true);
                                        @endphp
                                        @if (is_array($productos))
                                            @foreach ($productos as $producto)
                                                {{ $producto['nombre'] }} - ${{ $producto['precio_total'] }}
                                                (x{{ $producto['cantidad'] }})
                                                <br>
                                            @endforeach
                                        @else
                                            Sin productos registrados.
                                        @endif
                                    </td>
                                    <td>${{ $fiado->total_precio }}</td>
                                    <td>{{ $fiado->fecha_compra }}</td>
                                    <td>
                                        <form method="POST" action="{{ route('fiados.destroy', $fiado->id) }}"
                                            style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger">
                                                <i class="bi bi-trash"></i> Eliminar
                                            </button>
                                        </form>
                                        <a href="{{ route('pago.fiado', ['id' => $fiado->id]) }}"
                                            class="btn btn-success">
                                            <i class="bi bi-cash"></i> Pagar
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

    <script>
        let productosSeleccionados = [];

        function actualizarTablaProductosSeleccionados() {
            const tabla = document.getElementById('tablaProductosSeleccionados').getElementsByTagName('tbody')[0];
            tabla.innerHTML = '';
            productosSeleccionados.forEach((producto, index) => {
                const row = tabla.insertRow();
                row.insertCell(0).textContent = producto.nombre;
                row.insertCell(1).textContent = producto.cantidad;
                row.insertCell(2).textContent = `$${producto.precio_total.toFixed(2)}`;

                // Botón Eliminar
                const accionCell = row.insertCell(3);
                const eliminarButton = document.createElement('button');
                eliminarButton.textContent = 'Eliminar';
                eliminarButton.className = 'btn btn-danger btn-sm';
                eliminarButton.addEventListener('click', () => eliminarProducto(index));
                accionCell.appendChild(eliminarButton);
            });
            document.getElementById('productosSeleccionados').value = JSON.stringify(productosSeleccionados);
        }

        function eliminarProducto(index) {
            productosSeleccionados.splice(index, 1); // Elimina el producto del arreglo
            actualizarTablaProductosSeleccionados(); // Actualiza la tabla
        }

        document.getElementById('buscarProducto').addEventListener('keyup', function() {
            const filtro = this.value.toLowerCase();
            const filas = document.querySelectorAll('#tablaProductos tr');

            filas.forEach(fila => {
                const nombre = fila.cells[0].textContent.toLowerCase();
                const descripcion = fila.cells[1].textContent.toLowerCase();
                fila.style.display = nombre.includes(filtro) || descripcion.includes(filtro) ? '' : 'none';
            });
        });

        document.querySelectorAll('.btn-agregar').forEach(button => {
            button.addEventListener('click', () => {
                const id = button.dataset.id;
                const nombre = button.dataset.nombre;
                const precio = parseFloat(button.dataset.precio);

                const productoExistente = productosSeleccionados.find(p => p.id === id);
                if (productoExistente) {
                    productoExistente.cantidad++;
                    productoExistente.precio_total += precio;
                } else {
                    productosSeleccionados.push({
                        id,
                        nombre,
                        cantidad: 1,
                        precio_unitario: precio,
                        precio_total: precio
                    });
                }

                actualizarTablaProductosSeleccionados();
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
