<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cambiar Contraseña</title>
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
            /* Ancho mayor para coherencia */
            text-align: center;
            animation: slideIn 1s ease;
        }

        @keyframes slideIn {
            from {
                transform: translateY(-70px);
            }

            to {
                transform: translateY(0);
            }
        }

        h1 {
            font-size: 32px;
            color: #2E3B55;
            margin-bottom: 15px;
            font-weight: bold;
        }

        .instruction {
            font-size: 18px;
            color: #495867;
            margin-bottom: 30px;
        }

        .email-display {
            background-color: #f9f9f9;
            padding: 10px;
            border-radius: 10px;
            font-size: 16px;
            color: #333;
            margin-bottom: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .email-display i {
            margin-right: 10px;
            color: #4CAF50;
        }

        .form-control {
            padding: 15px;
            font-size: 16px;
            margin-bottom: 20px;
            border-radius: 10px;
        }

        .btn {
            width: 100%;
            padding: 15px;
            border-radius: 10px;
            font-size: 18px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.15);
            transition: background-color 0.3s, transform 0.3s;
        }

        .btn:hover {
            background-color: #45a049;
            transform: translateY(-2px);
        }

        .form-icon {
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: #4CAF50;
        }

        .input-group {
            position: relative;
        }

        .input-group i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 20px;
            color: #4CAF50;
        }

        .form-control::placeholder {
            color: #999;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Cambiar Contraseña</h1>
        <p class="instruction">Ingresa tu nueva contraseña para continuar.</p>

        <form method="POST" action="{{ route('password.store') }}">
            @csrf

            <!-- Token oculto para resetear contraseña -->
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <!-- Mostrar correo como texto -->
            <div class="email-display">
                <i class="bi bi-envelope-fill"></i>
                <span>{{ $request->email }}</span>
            </div>
            <!-- Enviar correo como campo oculto -->
            <input type="hidden" name="email" value="{{ $request->email }}">

            <!-- Campo de nueva contraseña -->
            <div class="input-group">
                <i class="bi bi-lock-fill form-icon"></i>
                <input type="password" id="password" name="password" class="form-control"
                    placeholder="Nueva Contraseña" required autocomplete="new-password">
            </div>

            <!-- Confirmar nueva contraseña -->
            <div class="input-group">
                <i class="bi bi-shield-lock-fill form-icon"></i>
                <input type="password" id="password_confirmation" name="password_confirmation" class="form-control"
                    placeholder="Confirmar Contraseña" required autocomplete="new-password">
            </div>

            <!-- Botón para cambiar la contraseña -->
            <button type="submit" class="btn">Cambiar Contraseña</button>
        </form>
    </div>

    <!-- Cargar Bootstrap JS (opcional para más interacción) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
