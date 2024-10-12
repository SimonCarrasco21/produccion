<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restablecer tu contraseña</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f0f4f8;
            margin: 0;
            padding: 20px;
        }

        .reset-container {
            background-color: #ffffff;
            max-width: 600px;
            margin: 0 auto;
            border-radius: 15px;
            padding: 40px;
            box-shadow: 0px 10px 30px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        h2 {
            color: #333333;
            font-size: 24px;
            margin-bottom: 20px;
        }

        p {
            color: #555555;
            font-size: 16px;
            line-height: 1.5;
            margin-bottom: 30px;
        }

        .reset-button {
            background-color: #4CAF50;
            color: white;
            padding: 15px 25px;
            border-radius: 10px;
            text-decoration: none;
            font-size: 18px;
            font-weight: bold;
            display: inline-block;
            transition: background-color 0.3s ease;
        }

        .reset-button:hover {
            background-color: #45a049;
        }

        .footer {
            margin-top: 30px;
            font-size: 12px;
            color: #888888;
        }
    </style>
</head>
<body>
    <div class="reset-container">
        <h2>Restablecer tu contraseña</h2>
        <p>Lamentamos que estés teniendo problemas para acceder a tu cuenta. Para restablecer tu contraseña, haz clic en el siguiente botón:</p>
        <a href="{{ $actionUrl }}" class="reset-button">Restablecer contraseña</a>
        <p>Si no solicitaste restablecer tu contraseña, por favor ignora este mensaje. Tu cuenta está segura.</p>
        <div class="footer">
            <p>Este es un correo generado automáticamente, por favor no respondas.</p>
        </div>
    </div>
</body>
</html>

