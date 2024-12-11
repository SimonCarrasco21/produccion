<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

    <title>Recuperar Contraseña</title>
    <!-- Bootstrap y Bootstrap Icons -->
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

        .container h1 {
            font-size: 36px;
            font-weight: bold;
            color: #2E3B55;
            margin-bottom: 20px;
        }

        .container p {
            font-size: 18px;
            color: #495867;
            margin-bottom: 30px;
        }

        .container input {
            width: 100%;
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 15px;
            background-color: #f9f9f9;
            box-shadow: inset 0px 4px 8px rgba(0, 0, 0, 0.05);
            font-size: 16px;
            transition: all 0.3s ease;
        }

        .container input:focus {
            outline: none;
            border-color: #4CAF50;
            background-color: #E8F8E0;
            box-shadow: inset 0px 4px 8px rgba(0, 0, 0, 0.1);
        }

        .container button {
            background-color: #4CAF50;
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
            background-color: #45a049;
            transform: translateY(-2px);
        }

        .container i {
            margin-right: 8px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1><i class="bi bi-envelope"></i> Recuperar Contraseña</h1>
        <p>¿Olvidaste tu contraseña? Introduce tu correo electrónico y te enviaremos un enlace para que la restablezcas.
        </p>

        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            <!-- Campo de correo electrónico -->
            <input type="email" id="email" name="email" placeholder="Correo Electrónico" required autofocus>

            <!-- Botón para enviar enlace -->
            <button type="submit"><i class="bi bi-send-fill"></i> Enviar enlace de restablecimiento</button>
        </form>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
