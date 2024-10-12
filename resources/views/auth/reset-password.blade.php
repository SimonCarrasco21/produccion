<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cambiar Contraseña</title>
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

        .container {
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

        .container h1 {
            font-size: 32px;
            color: #2E3B55;
            margin-bottom: 15px;
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
            border-radius: 10px;
            background-color: #f9f9f9;
            box-shadow: inset 0px 4px 8px rgba(0, 0, 0, 0.05);
            font-size: 16px;
            transition: all 0.3s ease;
            box-sizing: border-box;
        }

        .container input:focus {
            outline: none;
            border-color: #4CAF50;
            background-color: #E8F8E0;
            box-shadow: inset 0px 4px 8px rgba(0, 0, 0, 0.1);
        }

        .container button {
            width: 100%; 
            padding: 15px;
            margin-top: 10px; 
            border-radius: 10px;
            font-size: 18px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.15);
            transition: background-color 0.3s, transform 0.3s;
        }

        .container button:hover {
            background-color: #45a049;
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Cambiar Contraseña</h1>

        <form method="POST" action="{{ route('password.store') }}">
            @csrf

            <!-- Token oculto para resetear contraseña -->
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <!-- Campo de correo electrónico -->
            <input type="email" id="email" name="email" placeholder="Correo Electrónico" value="{{ old('email', $request->email) }}" required autofocus autocomplete="username">

            <!-- Campo de nueva contraseña -->
            <input type="password" id="password" name="password" placeholder="Nueva Contraseña" required autocomplete="new-password">

            <!-- Confirmar nueva contraseña -->
            <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Confirmar Contraseña" required autocomplete="new-password">

            <!-- Botón para cambiar la contraseña -->
            <button type="submit">Cambiar Contraseña</button>
        </form>
    </div>
</body>
</html>

