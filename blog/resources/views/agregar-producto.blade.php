<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Producto</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/agregar-editar.css') }}"> <!-- Enlace al archivo CSS -->
</head>
<body>

<h1>Agregar Producto</h1>

@if(session('success'))
    <p style="color: green; text-align: center;">{{ session('success') }}</p>
@endif

<form action="{{ route('guardarProducto') }}" method="POST">
    @csrf
    <div>
        <label for="nombre">Nombre:</label>
        <input type="text" name="nombre" id="nombre" required>
    </div>
    <div>
        <label for="descripcion">Descripción:</label>
        <textarea name="descripcion" id="descripcion" required></textarea>
    </div>
    <div>
        <label for="precio">Precio:</label>
        <input type="number" name="precio" id="precio" required step="0.01">
    </div>
    <div>
        <label for="stock">Stock:</label>
        <input type="number" name="stock" id="stock" required>
    </div>
    <div>
        <button type="submit">Agregar Producto</button>
    </div>
</form>

<!-- Tabla para mostrar los productos -->
<div class="table-container">
    <h2>Lista de Productos</h2>
    <table>
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Precio</th>
                <th>Stock</th>
                <th>Acciones</th>
            </tr>
        </thead>
<!-- Tabla de productos -->
<tbody>
    @foreach($productos as $producto)
        <tr>
            <td>{{ $producto->nombre }}</td>
            <td>{{ $producto->descripcion }}</td>
            <td>{{ number_format($producto->precio, 2) }} $</td>
            <td>{{ $producto->stock }}</td>
            <td class="action-buttons">
                <form action="{{ route('eliminarProducto', $producto->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este producto?');" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="delete-button">Eliminar</button>
                </form>
                <a href="{{ route('editarProducto', $producto->id) }}" class="edit-button">Editar</a>
            </td>
        </tr>
    @endforeach
</tbody>


    </table>
</div>

</body>
</html>
