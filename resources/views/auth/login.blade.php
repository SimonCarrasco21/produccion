<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
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
            padding: 60px;
            border-radius: 30px;
            box-shadow: 0px 20px 30px rgba(0, 0, 0, 0.2); 
            width: 450px; 
            text-align: center;
            animation: slideIn 1s ease;
        }

        @keyframes slideIn {
            from { transform: translateY(-70px); }
            to { transform: translateY(0); }
        }

        .login-container h1 {
            font-size: 32px;
            color: #2E3B55;
            margin-bottom: 15px;
        }

        .login-container p {
            font-size: 18px;
            color: #495867;
            margin-bottom: 30px;
        }

        .login-container input {
    width: 100%;
    padding: 15px;
    margin-bottom: 20px;
    border: 1px solid #ddd;
    border-radius: 10px;
    background-color: #f9f9f9;
    box-shadow: inset 0px 4px 8px rgba(0, 0, 0, 0.05);
    font-size: 16px;
    transition: all 0.3s ease;
    box-sizing: border-box; /* Asegura que el padding no altere el ancho total */
}

        .login-container input:focus {
            outline: none;
            border-color: #4CAF50;
            background-color: #E8F8E0;
            box-shadow: inset 0px 4px 8px rgba(0, 0, 0, 0.1);
        }

        .login-container button {
    width: 100%; /* Asegura que el botón tenga el mismo ancho que los inputs */
    padding: 15px;
    margin-top: 10px; /* Ajusta el espacio entre el input y el botón */
    border-radius: 10px;
    font-size: 18px;
    background-color: #4CAF50;
    color: white;
    border: none;
    cursor: pointer;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.15);
    transition: background-color 0.3s, transform 0.3s;
}

        .login-container button:hover {
            background-color: #45a049;
            transform: translateY(-2px);
        }

        .login-container a {
            display: block;
            margin-top: 20px;
            font-size: 16px;
            color: #4CAF50;
            text-decoration: none;
            transition: color 0.3s;
        }

        .login-container a:hover {
            text-decoration: underline;
            color: #45a049;
        }

        .error-message {
            color: #bd0808;
            font-size: 14px;
            margin-bottom: 20px;
            display: none; 
        }

        .success-message {
            color: #0b351c;
            font-size: 14px;
            margin-bottom: 20px;
            display: none;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h1>Login</h1>
        <p>Inicia sesión en tu cuenta</p>

        <!-- Mensaje de error para contraseña incorrecta -->
        <div class="error-message" id="error-message">
            Contraseña incorrecta, inténtalo de nuevo.
        </div>

        <!-- Mensaje de éxito si la contraseña es correcta -->
        <div class="success-message" id="success-message">
            Inicio de sesión exitoso.
        </div>

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Campo de usuario -->
            <input type="text" id="email" name="email" placeholder="Correo Electrónico o Usuario" required autofocus>

            <!-- Campo de contraseña -->
            <input type="password" id="password" name="password" placeholder="Contraseña" required>

            <!-- Botón para iniciar sesión -->
            <button type="submit">Iniciar sesión</button>

            <!-- Enlace para recuperar contraseña y registrarse -->
            <a href="{{ route('password.request') }}">¿Olvidaste tu contraseña?</a>
            <a href="{{ route('register') }}">¿No tienes cuenta? Regístrate</a>
        </form>
    </div>

    <script>
        // Simulación de mostrar el mensaje de error
        const errorMessage = document.getElementById('error-message');
        const successMessage = document.getElementById('success-message');
        @if ($errors->any())
            errorMessage.style.display = 'block';
        @endif

        // Simulación de mostrar mensaje de éxito (puedes adaptarlo a tu lógica de Laravel)
        @if (session('status'))
            successMessage.style.display = 'block';
        @endif
    </script>
</body>
</html>




