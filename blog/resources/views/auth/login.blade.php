<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <!-- Enlace a Bootstrap y Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #A6E6A0, #19eb9a);
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            animation: fadeIn 1s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .login-container {
            background-color: #ffffff;
            padding: 80px;
            border-radius: 30px;
            box-shadow: 0px 20px 40px rgba(0, 0, 0, 0.2); 
            width: 550px; /* Cambiado a 550px */
            text-align: center;
            animation: zoomIn 0.7s ease;
            transition: transform 0.3s ease;
        }

        @keyframes zoomIn {
            from { transform: scale(0.8); }
            to { transform: scale(1); }
        }

        .login-container h1 {
            font-size: 48px;
            font-weight: 700;
            color: #2E3B55;
            margin-bottom: 20px;
        }

        .login-container p {
            font-size: 20px;
            color: #495867;
            margin-bottom: 30px;
        }

        .login-container input {
            width: 100%;
            padding: 20px;
            margin-bottom: 25px;
            border: 1px solid #ddd;
            border-radius: 15px;
            background-color: #f9f9f9;
            box-shadow: inset 0px 4px 8px rgba(0, 0, 0, 0.05);
            font-size: 18px;
            transition: all 0.3s ease;
        }

        .login-container input:focus {
            outline: none;
            border-color: #4CAF50;
            background-color: #E8F8E0;
            box-shadow: inset 0px 4px 8px rgba(0, 0, 0, 0.1);
        }

        .login-container button {
            background-color: #4CAF50;
            color: white;
            padding: 20px;
            border: none;
            border-radius: 15px;
            font-size: 24px;
            width: 100%;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
            transition: background-color 0.3s, transform 0.3s;
        }

        .login-container button:hover {
            background-color: #45a049;
            transform: translateY(-2px);
        }

        .login-container a {
            display: block;
            margin-top: 20px;
            font-size: 18px;
            color: #4CAF50;
            text-decoration: none;
            transition: color 0.3s;
        }

        .login-container a:hover {
            text-decoration: underline;
            color: #45a049;
        }

        .error-message, .success-message {
            font-size: 18px;
            margin-bottom: 20px;
            display: none;
        }

        .error-message {
            color: #ff4d4d;
        }

        .success-message {
            color: #0b351c;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h1>Hola</h1>
        <p>Inicia sesión en tu cuenta</p>

        <!-- Mensaje de error para contraseña incorrecta -->
        <div class="error-message" id="error-message">
            <i class="bi bi-exclamation-triangle"></i> Contraseña incorrecta o Usuario no Registrado,inténtalo de nuevo.
        </div>

        <!-- Mensaje de éxito si la contraseña es correcta -->
        <div class="success-message" id="success-message">
            <i class="bi bi-check-circle"></i> Inicio de sesión exitoso.
        </div>

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Campo de usuario -->
            <div class="mb-3">
                <input type="text" id="email" name="email" class="form-control" placeholder="Correo Electrónico o Usuario" required autofocus>
            </div>

            <!-- Campo de contraseña -->
            <div class="mb-3">
                <input type="password" id="password" name="password" class="form-control" placeholder="Contraseña" required>
            </div>

            <!-- Botón para iniciar sesión -->
            <button type="submit" class="btn btn-success w-100">Iniciar sesión</button>

            <!-- Enlace para recuperar contraseña y registrarse -->
            <a href="{{ route('password.request') }}">¿Olvidaste tu contraseña?</a>
            <a href="{{ route('register') }}">¿No tienes cuenta? Regístrate</a>
        </form>
    </div>

    <script>
        const errorMessage = document.getElementById('error-message');
        const successMessage = document.getElementById('success-message');
        @if ($errors->any())
            errorMessage.style.display = 'block';
        @endif

        @if (session('status'))
            successMessage.style.display = 'block';
        @endif
    </script>

    <!-- Bootstrap JS y Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>






