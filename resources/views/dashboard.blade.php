<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página Principal</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #e0f7e0; /* Color verde claro */
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 800px;
            margin: 50px auto;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        h1 {
            color: #2f6627; /* Verde oscuro */
        }

        p {
            font-size: 18px;
            color: #4a4a4a;
        }

        .logout-button {
            background-color: #28a745; /* Verde para el botón */
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        .logout-button:hover {
            background-color: #218838; /* Verde más oscuro al pasar el mouse */
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Página Principal</h1>
    <p>Bienvenido al Dashboard</p>

    <!-- Botón de Logout -->
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="btn btn-danger">Cerrar Sesión</button>
    </form>
</div>

</body>
</html>
