<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Producto</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

    <!-- Bootstrap 5.3.3 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/editar-producto.css') }}">
</head>

<body>

    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card p-4 shadow-lg animate__animated animate__fadeInDown">
                    <h1 class="text-center mb-4"><span class="material-icons">edit</span> Editar Producto</h1>
    
                    <!-- Breve descripción del formulario -->
                    <p class="text-center text-muted mb-4">Modifica la información del producto seleccionado, incluyendo
                        su nombre, descripción, precio, stock, fecha de vencimiento y su imagen.</p>
    
                    @if (session('success'))
                        <p class="text-success text-center">{{ session('success') }}</p>
                    @endif
    
                    <form action="{{ route('actualizarProducto', $producto->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
    
                        <div class="mb-3 position-relative">
                            <label for="nombre" class="form-label"><span class="material-icons">label</span> Nombre:</label>
                            <input type="text" class="form-control" name="nombre" id="nombre"
                                value="{{ $producto->nombre }}" required>
                        </div>
    
                        <div class="mb-3 position-relative">
                            <label for="descripcion" class="form-label"><span class="material-icons">description</span>
                                Descripción:</label>
                            <textarea class="form-control" name="descripcion" id="descripcion" required>{{ $producto->descripcion }}</textarea>
                        </div>
    
                        <div class="mb-3 position-relative">
                            <label for="precio" class="form-label"><span class="material-icons">attach_money</span> Precio:</label>
                            <input type="number" class="form-control" name="precio" id="precio"
                                value="{{ $producto->precio }}" required step="0.01">
                        </div>
    
                        <div class="mb-3 position-relative">
                            <label for="stock" class="form-label"><span class="material-icons">inventory</span> Stock:</label>
                            <input type="number" class="form-control" name="stock" id="stock"
                                value="{{ $producto->stock }}" required>
                        </div>
    
                        <div class="mb-3 position-relative">
                            <label for="categoria" class="form-label"><span class="material-icons">category</span>
                                Categoría:</label>
                            <select class="form-select" name="categoria_id" id="categoria" required>
                                @foreach ($categorias as $categoria)
                                    <option value="{{ $categoria->id }}"
                                        {{ $producto->categoria_id == $categoria->id ? 'selected' : '' }}>
                                        {{ $categoria->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
    
                        <div class="mb-3 position-relative">
                            <label for="fecha_vencimiento" class="form-label"><span class="material-icons">event</span>
                                Fecha de Vencimiento:</label>
                            <input type="date" class="form-control" name="fecha_vencimiento" id="fecha_vencimiento"
                                value="{{ $producto->fecha_vencimiento }}">
                        </div>
    
                        <!-- Nueva opción para agregar o actualizar la imagen -->
                        <div class="mb-3 position-relative">
                            <label for="imagen" class="form-label"><span class="material-icons">image</span> Imagen del Producto:</label>
                            <input type="file" class="form-control" name="imagen" id="imagen" accept="image/*">
                            <small class="text-muted">Puedes cargar una nueva imagen para este producto (formatos permitidos: JPG, PNG).</small>
                        </div>
    
                        <div class="d-flex justify-content-between mt-4">
                            <button type="submit"
                                class="btn btn-success btn-lg w-45 shadow-sm animate__animated animate__pulse animate__infinite">Actualizar
                                el Producto</button>
                            <a href="{{ route('agregar-producto') }}"
                                class="btn btn-danger btn-lg w-45 shadow-sm">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>




</html>
