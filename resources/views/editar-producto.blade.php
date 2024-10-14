<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Producto</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/agregar-editar.css') }}"> <!-- Enlace al archivo CSS -->
</head>
<body>

<h1>Editar Producto</h1>

@if(session('success'))
    <p style="color: green; text-align: center;">{{ session('success') }}</p>
@endif

<form action="{{ route('actualizarProducto', $producto->id) }}" method="POST">
    @csrf
    @method('PUT')
    <div>
        <label for="nombre">Nombre:</label>
        <input type="text" name="nombre" id="nombre" value="{{ $producto->nombre }}" required>
    </div>
    <div>
        <label for="descripcion">Descripción:</label>
        <textarea name="descripcion" id="descripcion" required>{{ $producto->descripcion }}</textarea>
    </div>
    <div>
        <label for="precio">Precio:</label>
        <input type="number" name="precio" id="precio" value="{{ $producto->precio }}" required step="0.01">
    </div>
    <div>
        <label for="stock">Stock:</label>
        <input type="number" name="stock" id="stock" value="{{ $producto->stock }}" required>
    </div>
    <div>
        <button type="submit">Actualizar Producto</button>
    </div>
</form>

</body>
</html>
