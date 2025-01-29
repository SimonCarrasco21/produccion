<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

    <title>Registro</title>
    <!-- Bootstrap y Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #4A90E2, #1E3A8A);
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            animation: fadeIn 1s ease-in-out;
        }
    
        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }
    
        .container {
            background-color: #ffffff;
            padding: 60px;
            border-radius: 30px;
            box-shadow: 0px 20px 30px rgba(0, 0, 0, 0.2);
            width: 550px;
            text-align: center;
            animation: zoomIn 0.7s ease;
            transition: transform 0.3s ease;
        }
    
        @keyframes zoomIn {
            from {
                transform: scale(0.8);
            }
            to {
                transform: scale(1);
            }
        }
    
        .container h1 {
            font-size: 40px;
            font-weight: bold;
            color: #1E3A8A;
            margin-bottom: 20px;
        }
    
        .container input {
            width: 100%;
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid #ccd6f6;
            border-radius: 15px;
            background-color: #f0f4ff;
            box-shadow: inset 0px 4px 8px rgba(0, 0, 0, 0.05);
            font-size: 16px;
            transition: all 0.3s ease;
        }
    
        .container input:focus {
            outline: none;
            border-color: #2563EB;
            background-color: #E0ECFF;
            box-shadow: inset 0px 4px 8px rgba(0, 0, 0, 0.1);
        }
    
        .container button {
            background-color: #2563EB;
            color: white;
            padding: 15px;
            border: none;
            border-radius: 15px;
            font-size: 20px;
            width: 100%;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.15);
            transition: background-color 0.3s, transform 0.3s;
        }
    
        .container button:hover {
            background-color: #1E40AF;
            transform: translateY(-2px);
        }
    
        .container a {
            display: block;
            margin-top: 20px;
            font-size: 16px;
            color: #2563EB;
            text-decoration: none;
            transition: color 0.3s;
        }
    
        .container a:hover {
            text-decoration: underline;
            color: #1E40AF;
        }
    </style>
    
</head>

<body>
    <div class="container">
        <h1><i class="bi bi-pencil-square"></i> Registrarse</h1>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <!-- Nombre -->
            <input type="text" id="name" name="name" placeholder="Nombre Completo"
                value="{{ old('name') }}" required autofocus>

            <!-- Correo Electrónico -->
            <input type="email" id="email" name="email" placeholder="Correo Electrónico"
                value="{{ old('email') }}" required>

            <!-- Contraseña -->
            <input type="password" id="password" name="password" placeholder="Contraseña" required>

            <!-- Confirmar Contraseña -->
            <input type="password" id="password_confirmation" name="password_confirmation"
                placeholder="Confirmar Contraseña" required>

            <!-- Botón para registrarse -->
            <button type="submit"><i class="bi bi-person-plus-fill"></i> Registrate</button>

            <!-- Enlace para iniciar sesión -->
            <a href="{{ route('login') }}"><i class="bi bi-box-arrow-in-right"></i> ¿Ya tienes una cuenta? Inicia
                sesión</a>
        </form>
    </div>

    <!-- Bootstrap JS y Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
