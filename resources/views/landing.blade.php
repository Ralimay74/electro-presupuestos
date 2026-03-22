<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ElectroPresupuestos Pro - Gestión Profesional para Electricistas</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: white;
            padding: 2rem;
        }

        .container {
            text-align: center;
            max-width: 1000px;
            animation: fadeIn 0.8s ease-in;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .logo {
            margin-bottom: 2rem;
        }

        .logo img {
            width: 150px;
            height: 150px;
            object-fit: contain;
            filter: drop-shadow(0 10px 20px rgba(0,0,0,0.3));
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }

        h1 {
            font-size: 3rem;
            margin-bottom: 1rem;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }

        .subtitle {
            font-size: 1.3rem;
            margin-bottom: 3rem;
            opacity: 0.95;
        }

        .features {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 2rem;
            margin: 3rem 0;
        }

        .feature {
            background: rgba(255,255,255,0.1);
            backdrop-filter: blur(10px);
            padding: 2rem;
            border-radius: 15px;
            border: 1px solid rgba(255,255,255,0.2);
            transition: transform 0.3s;
        }

        .feature:hover {
            transform: translateY(-5px);
            background: rgba(255,255,255,0.15);
        }

        .feature-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
        }

        .feature h3 {
            font-size: 1.2rem;
            margin-bottom: 0.5rem;
        }

        .feature p {
            font-size: 0.9rem;
            opacity: 0.9;
        }

        .buttons {
            display: flex;
            gap: 1.5rem;
            justify-content: center;
            flex-wrap: wrap;
            margin-top: 3rem;
        }

        .btn {
            padding: 1rem 2.5rem;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-primary {
            background: white;
            color: #667eea;
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        }

        .btn-secondary {
            background: rgba(255,255,255,0.2);
            color: white;
            border: 2px solid white;
        }

        .btn-secondary:hover {
            background: white;
            color: #667eea;
        }

        .footer {
            margin-top: 3rem;
            opacity: 0.8;
            font-size: 0.9rem;
        }

        @media (max-width: 768px) {
            h1 { font-size: 2rem; }
            .subtitle { font-size: 1rem; }
            .buttons { flex-direction: column; }
            .btn { width: 100%; justify-content: center; }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- LOGO -->
        <div class="logo">
            <img src="/images/logo.png" alt="ElectroPresupuestos Logo">
        </div>

        <!-- TÍTULO -->
        <h1>ElectroPresupuestos Pro</h1>
        <p class="subtitle">Gestión profesional de presupuestos para electricistas</p>

        <!-- CARACTERÍSTICAS -->
        <div class="features">
            <div class="feature">
                <div class="feature-icon">📋</div>
                <h3>Presupuestos</h3>
                <p>Crea y gestiona presupuestos profesionales</p>
            </div>
            <div class="feature">
                <div class="feature-icon">📧</div>
                <h3>Email</h3>
                <p>Envío automático de PDFs a clientes</p>
            </div>
            <div class="feature">
                <div class="feature-icon">👥</div>
                <h3>Clientes</h3>
                <p>Área privada para aceptación online</p>
            </div>
            <div class="feature">
                <div class="feature-icon">📊</div>
                <h3>Dashboard</h3>
                <p>Estadísticas en tiempo real</p>
            </div>
        </div>

        <!-- BOTONES DE ACCESO -->
        <div class="buttons">
            <a href="/admin" class="btn btn-primary">
                🔐 Acceso Administrador
            </a>
            <a href="/client-login" class="btn btn-secondary">
                👤 Área de Clientes
            </a>
        </div>

        <!-- FOOTER -->
        <div class="footer">
            <p>&copy; {{ date('Y') }} ElectroPresupuestos Pro - Desarrollado por Rubén Alimay</p>
        </div>
    </div>
</body>
</html>