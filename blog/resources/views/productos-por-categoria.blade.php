<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

    <title>CATEGORIA : {{ $categoria->nombre }}</title>
    <!-- Enlaces a Bootstrap e íconos -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

</head>

<body>

    <!-- Barra de navegación -->
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
                <li><a href="{{ route('registro-ventas') }}"><i class="bi bi-clock-history"></i> Ver Historial
                        Ventas</a></li>
                <li><a href="{{ route('inventario') }}"></i> Inventario</a></li>
                <li><a href="{{ route('pagina.pago') }}" class="btn-pagar"><i class="bi bi-credit-card"></i> Pagar</a>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Título y resumen de categoría -->
    <div class="container my-5">
        <h2 class="text-center page-title mb-4">Categoría: {{ $categoria->nombre }}</h2>

        <!-- Resumen de la categoría -->
        <div class="row mb-4">
            <div class="col-sm-6 col-md-3">
                <div class="card shadow-sm text-center">
                    <div class="card-body">
                        <h6>Total de Productos</h6>
                        <h4>{{ $productos->count() }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-3">
                <div class="card shadow-sm text-center">
                    <div class="card-body">
                        <h6>Promedio de Precio</h6>
                        <h4>${{ number_format($productos->avg('precio'), 2) }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-3">
                <div class="card shadow-sm text-center">
                    <div class="card-body">
                        <h6>Stock Bajo</h6>
                        <h4>{{ $productos->where('stock', '<=', 5)->count() }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-3">
                <div class="card shadow-sm text-center">
                    <div class="card-body">
                        <h6>Próximos a Vencer</h6>
                        <h4>{{ $productos->where('proximo_a_vencer', true)->count() }}</h4>
                    </div>
                </div>
            </div>
        </div>

        <!-- Buscador y tabla -->
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
            <div class="input-group w-100 w-md-50 shadow-sm">
                <span class="input-group-text bg-light text-dark"><i class="bi bi-search"></i></span>
                <input type="text" id="buscarProducto" class="form-control" placeholder="Buscar producto...">
            </div>
        </div>

        <!-- Tabla responsiva -->
        <div class="table-responsive shadow-sm rounded">
            <table class="table table-hover align-middle" id="tablaProductos">
                <thead class="table-dark">
                    <tr>
                        <th class="sortable" data-col="id">ID</th>
                        <th class="sortable" data-col="nombre">Nombre</th>
                        <th class="sortable" data-col="descripcion">Descripción</th>
                        <th class="sortable" data-col="precio">Precio</th>
                        <th class="sortable" data-col="stock">Stock</th>
                        <th class="sortable" data-col="fecha_vencimiento">Fecha Vencimiento</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($productos->isEmpty())
                        <tr>
                            <td colspan="6" class="text-center text-muted">No hay productos en esta categoría.</td>
                        </tr>
                    @else
                        @foreach ($productos as $producto)
                            <tr>
                                <td>{{ $producto->id }}</td>
                                <td>{{ $producto->nombre }}</td>
                                <td>{{ $producto->descripcion }}</td>
                                <td>${{ number_format($producto->precio, 2) }}</td>
                                <td>
                                    {{ $producto->stock }}
                                    @if ($producto->stock <= 5)
                                        <span class="badge bg-danger text-light">Bajo</span>
                                    @endif
                                </td>
                                <td>{{ $producto->fecha_vencimiento ?? 'N/A' }}</td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    <script>
        // Búsqueda dinámica
        document.getElementById('buscarProducto').addEventListener('keyup', function() {
            const filtro = this.value.toLowerCase();
            const filas = document.querySelectorAll('#tablaProductos tbody tr');

            filas.forEach(fila => {
                const celdas = Array.from(fila.children);
                fila.style.display = celdas.some(celda =>
                    celda.textContent.toLowerCase().includes(filtro)
                ) ? '' : 'none';
            });
        });

        // Ordenamiento de columnas
        document.querySelectorAll('.sortable').forEach(th => {
            th.addEventListener('click', function() {
                const table = th.closest('table');
                const tbody = table.querySelector('tbody');
                const rows = Array.from(tbody.rows);
                const colIndex = Array.from(th.parentNode.children).indexOf(th);
                const isAscending = th.classList.toggle('asc');

                rows.sort((a, b) => {
                    const aText = a.cells[colIndex].textContent.trim();
                    const bText = b.cells[colIndex].textContent.trim();
                    return isAscending ?
                        aText.localeCompare(bText, undefined, {
                            numeric: true
                        }) :
                        bText.localeCompare(aText, undefined, {
                            numeric: true
                        });
                });

                tbody.append(...rows);
            });
        });
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

</body>

</html>
<style>
    .page-title {
        margin-top: 30px;
        font-weight: bold;
    }

    .table-container {
        margin-top: 30px;
    }

    .btn-container {
        margin-top: 30px;
        display: flex;
        justify-content: center;
        margin-bottom: 30px;
    }

    .btn-agregar {
        width: 100%;
        max-width: 2000px;
        /* Mantén el mismo ancho que la tabla */
        padding: 15px 0;
        /* Botón más delgado */
        font-size: 20px;
        font-weight: bold;
    }
</style>
