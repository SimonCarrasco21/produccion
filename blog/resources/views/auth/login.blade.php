<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <!-- Enlace a Bootstrap y Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    S
</head>
<body>
    <div class="login-container">
        <h1>Login</h1>
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






