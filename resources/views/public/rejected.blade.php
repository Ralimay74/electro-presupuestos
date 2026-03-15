<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Presupuesto Rechazado</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .container {
            background: white;
            padding: 60px;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            text-align: center;
            max-width: 600px;
        }
        
        .icon {
            font-size: 80px;
            margin-bottom: 20px;
        }
        
        h1 {
            color: #ef4444;
            margin-bottom: 20px;
        }
        
        p {
            color: #6b7280;
            margin-bottom: 30px;
            line-height: 1.8;
        }
        
        .info-box {
            background: #fef2f2;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #ef4444;
        }
        
        .btn {
            display: inline-block;
            background: #ef4444;
            color: white;
            padding: 15px 40px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            margin-top: 20px;
        }
        
        .btn:hover {
            background: #dc2626;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="icon">❌</div>
        <h1>Presupuesto Rechazado</h1>
        <p>Hemos registrado tu decisión de rechazar el presupuesto <strong>{{ $budget->number }}</strong>.</p>
        
        <div class="info-box">
            <p><strong>Nº Presupuesto:</strong> {{ $budget->number }}</p>
            <p><strong>Estado:</strong> Rechazado</p>
        </div>
        
        <p>Si cambias de opinión o tienes alguna consulta, no dudes en contactarnos.</p>
        
        <a href="{{ route('public.budget.show', $budget->public_token) }}" class="btn">
            ← Volver al Presupuesto
        </a>
    </div>
</body>
</html>