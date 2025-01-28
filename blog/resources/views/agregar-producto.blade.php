<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Producto</title>
    <!-- Enlace a Bootstrap y Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}"> <!-- Enlace al CSS separado -->
</head>

<body>

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
                <li><a href="{{ route('fiados.index') }}"><i class="bi bi-cart-fill"></i> Carrito de compra</a></li>

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
    <!-- Formulario de agregar producto -->
    <div class="container mt-5">
        <h1 class="text-center fw-bold text-dark mb-4">Gestión de Productos</h1>
        <div class="card shadow-lg border-0">
            <div class="card-header bg-success text-white text-center">
                <h3 class="fw-bold mb-0">Agregar y Gestionar Productos</h3>
            </div>
            <div class="card-body bg-light p-4">
                <!-- Mensajes -->
                <div id="error-messages" class="alert alert-danger d-none" role="alert"></div>
                <div id="success-message" class="alert alert-success d-none" role="alert">Producto agregado
                    correctamente.</div>
    
                <!-- Formulario -->
                <form id="producto-form" action="{{ route('guardarProductoUnico') }}" method="POST" enctype="multipart/form-data">
                    @csrf
    
                    <!-- Bloque principal -->
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="p-4 bg-light border border-success rounded" style="height: 100%;">
                                <!-- Botones de escaneo -->
                                <div class="d-flex justify-content-center gap-3 mb-4">
                                    <button id="start-scan" type="button" class="btn btn-outline-success fw-bold px-5 py-3"
                                        style="font-size: 1.2rem;">
                                        <i class="bi bi-camera"></i> Activar Cámara
                                    </button>
                                    <button id="stop-scan" type="button"
                                        class="btn btn-outline-danger fw-bold px-5 py-3 d-none" style="font-size: 1.2rem;">
                                        <i class="bi bi-camera-off"></i> Detener Cámara
                                    </button>
                                </div>
    
                                <!-- Video para mostrar la cámara -->
                                <div class="video-container" style="height: calc(100% - 120px);">
                                    <!-- Ajuste dinámico para el cuadro -->
                                    <video id="barcode-video"
                                        class="rounded shadow-sm border border-success w-100 h-100" autoplay></video>
                                </div>
                                <div id="scan-result" class="alert alert-success mt-3 fw-bold d-none" role="alert">
                                </div>
                            </div>
                        </div>
    
                        <!-- Campos del formulario -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nombre" class="form-label fw-bold text-dark">Nombre:</label>
                                <input type="text" name="nombre" id="nombre"
                                    class="form-control border border-success" placeholder="Ej. Martillo" required>
                            </div>
                            <div class="mb-3">
                                <label for="descripcion" class="form-label fw-bold text-dark">Descripción:</label>
                                <textarea name="descripcion" id="descripcion"
                                    class="form-control border border-success" placeholder="Mínimo 10 caracteres" rows="4"
                                    required></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="precio" class="form-label fw-bold text-dark">Precio:</label>
                                <input type="number" name="precio" id="precio"
                                    class="form-control border border-success" placeholder="Ej. 12.50" step="0.01"
                                    required>
                            </div>
                            <div class="mb-3">
                                <label for="stock" class="form-label fw-bold text-dark">Stock:</label>
                                <input type="number" name="stock" id="stock"
                                    class="form-control border border-success" placeholder="Ej. 50" required>
                            </div>
                            <div class="mb-3">
                                <label for="categoria" class="form-label fw-bold text-dark">Categoría:</label>
                                <select name="categoria_id" id="categoria"
                                    class="form-select border border-success" required>
                                    <option value="" disabled selected>Seleccionar Categoría</option>
                                    @foreach ($categorias as $categoria)
                                        <option value="{{ $categoria->id }}">{{ $categoria->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="fecha_vencimiento" class="form-label fw-bold text-dark">Fecha de Ingreso:</label>
                                <input type="date" name="fecha_vencimiento" id="fecha_vencimiento"
                                    class="form-control border border-success">
                            </div>
                            <div class="mb-3">
                                <label for="imagen" class="form-label fw-bold text-dark">Foto del Producto:</label>
                                <input type="file" name="imagen" id="imagen"
                                    class="form-control border border-success" accept="image/*">
                            </div>
                        </div>
                    </div>
    
                    <!-- Botones -->
                    <div
                        class="d-flex flex-column flex-md-row justify-content-center align-items-center gap-3 mt-4">
                        <button type="submit" id="add-single" class="btn btn-success fw-bold w-100 w-md-auto">
                            <i class="bi bi-save"></i> Agregar Producto
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Tabla de productos -->
    <div class="container mt-5">
        <h2 class="text-center text-success fw-bold mb-4">Gestión Completa de Productos</h2>

        <div class="card shadow-lg border-0">
            <div class="card-header bg-gradient bg-success text-white text-center rounded-top">
                <h4 class="fw-bold mb-0">Lista de Productos</h4>
            </div>
            <div class="card-footer bg-success text-white text-center">
                <span>Total de Productos: <strong>{{ $productos->count() }}</strong></span>
            </div>
            <div class="card-body p-4 bg-light">
                <div class="table-responsive">
                    <table class="table align-middle table-bordered border-success table-hover">
                        <thead class="bg-success text-white text-center">
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Descripción</th>
                                <th>Precio</th>
                                <th>Stock</th>
                                <th>Categoría</th>
                                <th>Fecha de Vencimiento</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($productos as $producto)
                                <tr>
                                    <td class="text-center fw-bold">{{ $producto->id }}</td>
                                    <td>{{ $producto->nombre }}</td>
                                    <td>
                                        <div class="bg-white p-2 rounded shadow-sm"
                                            style="max-width: 350px; word-wrap: break-word;">
                                            {{ $producto->descripcion }}
                                        </div>
                                    </td>
                                    <td class="text-end">{{ number_format($producto->precio, 2) }} $</td>
                                    <td class="text-center">{{ $producto->stock }}</td>
                                    <td class="text-center">{{ $producto->categoria->nombre }}</td>
                                    <td class="text-center">
                                        {{ $producto->fecha_vencimiento ? $producto->fecha_vencimiento : 'N/A' }}
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-2">
                                            <form action="{{ route('eliminarProducto', $producto->id) }}"
                                                method="POST"
                                                onsubmit="return confirm('¿Estás seguro de que deseas eliminar este producto?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm fw-bold shadow">
                                                    <i class="bi bi-trash"></i> Eliminar
                                                </button>
                                            </form>
                                            <a href="{{ route('editarProducto', $producto->id) }}"
                                                class="btn btn-warning btn-sm fw-bold shadow">
                                                <i class="bi bi-pencil"></i> Editar
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- Script para confirmar la funcion de agregar un producto  -->
    <script>
        const productos = [];
        const productosTable = document.getElementById("productos-table");
        const productosData = document.getElementById("productos-data");
        const errorMessages = document.getElementById("error-messages");
        const successMessage = document.getElementById("success-message");

        const clearErrors = () => {
            errorMessages.classList.add("d-none");
            errorMessages.innerHTML = "";
        };

        const showErrors = (messages) => {
            errorMessages.classList.remove("d-none");
            errorMessages.innerHTML = messages.map((msg) => `<li>${msg}</li>`).join("");
        };

        const showSuccess = (message) => {
            successMessage.classList.remove("d-none");
            successMessage.innerHTML = message;
            setTimeout(() => successMessage.classList.add("d-none"), 3000);
        };

        // Validación y agregar a la lista temporal
        document.getElementById("add-to-list").addEventListener("click", () => {
            clearErrors();
            const nombre = document.getElementById("nombre").value.trim();
            const descripcion = document.getElementById("descripcion").value.trim();
            const precio = parseFloat(document.getElementById("precio").value.trim());
            const stock = parseInt(document.getElementById("stock").value.trim());
            const categoriaId = document.getElementById("categoria").value;
            const fechaVencimiento = document.getElementById("fecha_vencimiento").value;

            const errors = [];
            if (!nombre) errors.push("El nombre es obligatorio.");
            if (!descripcion || descripcion.length < 10) errors.push(
                "La descripción debe tener al menos 10 caracteres.");
            if (isNaN(precio) || precio <= 0) errors.push("El precio debe ser mayor a 0.");
            if (isNaN(stock) || stock < 0) errors.push("El stock no puede ser un número negativo.");
            if (!categoriaId) errors.push("La categoría es obligatoria.");
            if (fechaVencimiento && new Date(fechaVencimiento) < new Date()) {
                errors.push("La fecha de vencimiento no puede ser anterior al día actual.");
            }

            if (errors.length > 0) {
                showErrors(errors);
                return;
            }

            const producto = {
                nombre,
                descripcion,
                precio,
                stock,
                categoria_id: categoriaId,
                fecha_vencimiento: fechaVencimiento || "N/A",
            };
            productos.push(producto);

            const row = document.createElement("tr");
            row.innerHTML = `
         <td>${producto.nombre}</td>
        <td>${producto.descripcion}</td>
        <td>${producto.precio.toFixed(2)}</td>
        <td>${producto.stock}</td>
        <td>${document.querySelector(`#categoria option[value="${categoriaId}"]`).textContent}</td>
        <td>${producto.fecha_vencimiento}</td>
         <td class="text-center">
        <button type="button" class="btn btn-danger btn-sm fw-bold remove-row">Eliminar</button>
        </td>`;
            productosTable.appendChild(row);
            productosData.value = JSON.stringify(productos);
            document.getElementById("producto-form").reset();
            showSuccess("Producto agregado a la lista.");
        });

        // Eliminar de la lista temporal
        productosTable.addEventListener("click", (event) => {
            if (event.target.classList.contains("remove-row")) {
                const row = event.target.closest("tr");
                const index = Array.from(productosTable.children).indexOf(row);
                productos.splice(index, 1);
                row.remove();
                productosData.value = JSON.stringify(productos);
            }
        });

        // Mostrar/Ocultar tabla de productos
        document.getElementById("toggle-table").addEventListener("click", () => {
            const productosList = document.getElementById("productos-list");
            if (productosList.style.display === "none") {
                productosList.style.display = "block";
                productosList.style.opacity = 0;
                setTimeout(() => (productosList.style.opacity = 1), 50);
            } else {
                productosList.style.opacity = 0;
                setTimeout(() => (productosList.style.display = "none"), 300);
            }
        });
    </script>
    <!-- Script  y diseño para camara y escaneo   -->
    <script src="https://unpkg.com/@zxing/library@latest"></script>
    <script>
        const videoElement = document.getElementById('barcode-video');
        const startScanButton = document.getElementById('start-scan');
        const stopScanButton = document.getElementById('stop-scan');
        const scanResult = document.getElementById('scan-result');
        let stream;

        // Función para iniciar el escaneo
        startScanButton.addEventListener('click', async () => {
            try {
                stream = await navigator.mediaDevices.getUserMedia({
                    video: {
                        facingMode: "environment"
                    }
                });
                videoElement.srcObject = stream;
                startScanButton.classList.add('d-none');
                stopScanButton.classList.remove('d-none');

                const codeReader = new ZXing.BrowserBarcodeReader();
                codeReader.decodeOnceFromVideoDevice(undefined, videoElement).then(result => {
                    scanResult.textContent = `Código Escaneado: ${result.text}`;
                    scanResult.classList.remove('d-none');

                    // Llamar a la API con el código de barras
                    fetch('{{ route('productos.api') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                codigo: result.text
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                document.getElementById('nombre').value = data.producto.nombre ||
                                    '';
                                document.getElementById('descripcion').value = data.producto
                                    .descripcion || '';
                                document.getElementById('precio').value = data.producto.precio ||
                                    '';
                                document.getElementById('fecha_vencimiento').value = data.producto
                                    .fecha_vencimiento || '';
                            } else {
                                alert('Producto no encontrado.');
                            }
                        });
                });
            } catch (error) {
                alert('Error al acceder a la cámara: ' + error.message);
            }
        });

        // Función para detener el escaneo
        stopScanButton.addEventListener('click', () => {
            if (stream) {
                const tracks = stream.getTracks();
                tracks.forEach(track => track.stop());
            }
            videoElement.srcObject = null;
            startScanButton.classList.remove('d-none');
            stopScanButton.classList.add('d-none');
            scanResult.classList.add('d-none');
            scanResult.textContent = '';
        });
    </script>
    <style>
        .video-container {
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
            border-radius: 10px;
        }

        @media (max-width: 768px) {
            #barcode-video {
                max-height: 200px;
            }

            .btn {
                font-size: 0.9rem;
            }
        }
    </style>
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
    

</body>

</html>
