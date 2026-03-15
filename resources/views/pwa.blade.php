<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ElectroPresupuestos</title>
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#667eea">
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: white;
        }
        .container {
            padding: 40px;
        }
        h1 {
            font-size: 32px;
            margin-bottom: 20px;
        }
        .btn {
            display: inline-block;
            background: white;
            color: #667eea;
            padding: 15px 30px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            margin: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>⚡ ElectroPresupuestos</h1>
        <p>Gestión de presupuestos para electricistas</p>
        <a href="/client-login" class="btn">Acceso Clientes</a>
        <a href="/admin" class="btn">Panel Admin</a>
    </div>
</body>
</html>