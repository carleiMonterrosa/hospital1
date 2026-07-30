<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Código de Verificación</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #28a745;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }
        .header h1 {
            color: #2e7d32;
            margin: 0;
        }
        .codigo {
            text-align: center;
            font-size: 48px;
            font-weight: bold;
            color: #28a745;
            background: #e8f5e9;
            padding: 20px;
            border-radius: 10px;
            letter-spacing: 10px;
            margin: 20px 0;
        }
        .info {
            color: #666;
            text-align: center;
            font-size: 14px;
            margin-top: 20px;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            color: #999;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🔐 Código de Verificación</h1>
            <p style="color: #666;">Recuperación de contraseña</p>
        </div>

        <p>Hola,</p>
        <p>Has solicitado recuperar tu contraseña. Utiliza el siguiente código de verificación:</p>

        <div class="codigo">{{ $codigo }}</div>

        <p><strong>Este código expirará en 15 minutos.</strong></p>

        <p>Si no solicitaste este cambio, ignora este mensaje.</p>

        <div class="info">
            <p>📧 Correo: {{ $email }}</p>
        </div>

        <div class="footer">
            <p>Este es un mensaje automático, por favor no responder.</p>
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. Todos los derechos reservados.</p>
        </div>
    </div>
</body>
</html>