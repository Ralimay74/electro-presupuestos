<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Área - ElectroPresupuestos</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #f3f4f6;
            min-height: 100vh;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .logout-btn {
            background: white;
            color: #667eea;
            padding: 10px 20px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 600;
        }
        .container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
        }
        .welcome {
            background: white;
            padding: 30px;
            border-radius: 12px;
            margin-bottom: 30px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .budget-card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            margin-bottom: 20px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .budget-info h3 {
            margin: 0 0 10px 0;
            color: #1f2937;
        }
        .budget-info p {
            margin: 5px 0;
            color: #6b7280;
        }
        .budget-actions {
            display: flex;
            gap: 10px;
        }
        .btn {
            padding: 10px 20px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            border: none;
        }
        .btn-primary {
            background: #667eea;
            color: white;
        }
        .btn-success {
            background: #10b981;
            color: white;
        }
        .btn-danger {
            background: #ef4444;
            color: white;
        }
        .btn-outline {
            background: white;
            color: #667eea;
            border: 2px solid #667eea;
        }
        .status-badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }
        .status-draft { background: #e5e7eb; color: #374151; }
        .status-sent { background: #dbeafe; color: #1e40af; }
        .status-approved { background: #d1fae5; color: #065f46; }
        .status-rejected { background: #fee2e2; color: #991b1b; }
        .alert {
            background: #d1fae5;
            color: #065f46;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .empty {
            text-align: center;
            padding: 60px;
            background: white;
            border-radius: 12px;
            color: #6b7280;
        }
    </style>
     <!-- PWA Meta Tags -->
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#667eea">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="ElectroPres">
    <link rel="apple-touch-icon" href="/icons/icon-192x192.png">
    
    <!-- Service Worker -->
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', function() {
                navigator.serviceWorker.register('/sw.js')
                    .then(function(registration) {
                        console.log('ServiceWorker registrado:', registration.scope);
                    })
                    .catch(function(err) {
                        console.log('ServiceWorker falló:', err);
                    });
            });
        }
    </script>
</head>
<body>
    <div class="header">
        <h1>🏠 Mi Área de Cliente</h1>
        <form action="{{ route('client.logout') }}" method="POST" style="display: inline;">
            @csrf
            <button type="submit" class="logout-btn">🚪 Cerrar Sesión</button>
        </form>
    </div>
    
    <div class="container">
        <div class="welcome">
            <h2>👋 Bienvenido, {{ auth()->guard('client')->user()->name }}</h2>
            <p>Aquí puedes ver todos tus presupuestos y gestionarlos.</p>
        </div>

        @if(session('success'))
            <div class="alert">{{ session('success') }}</div>
        @endif
        
        @if($budgets->count() > 0)
            @foreach($budgets as $budget)
                <div class="budget-card">
                    <div class="budget-info">
                        <h3>📋 Presupuesto {{ $budget->number }}</h3>
                        <p><strong>Fecha:</strong> {{ $budget->date->format('d/m/Y') }}</p>
                        <p><strong>Estado:</strong> 
                            <span class="status-badge status-{{ $budget->status }}">
                                @if($budget->status == 'draft') 📝 Borrador
                                @elseif($budget->status == 'sent') 📤 Enviado
                                @elseif($budget->status == 'approved') ✅ Aprobado
                                @elseif($budget->status == 'rejected') ❌ Rechazado
                                @endif
                            </span>
                        </p>
                        <p><strong>Total:</strong> {{ number_format($budget->total, 2, ',', '.') }} €</p>
                    </div>
                    <div class="budget-actions">
                        <a href="{{ route('client.budget.view', $budget->id) }}" class="btn btn-primary">👁️ Ver</a>
                        <a href="{{ route('client.budget.pdf', $budget->id) }}" class="btn btn-outline" target="_blank">📄 PDF</a>
                        @if($budget->status == 'sent')
                            <form action="{{ route('client.budget.accept', $budget->id) }}" method="POST" style="display: inline;">
                                @csrf
                                <button type="submit" class="btn btn-success">✅ Aceptar</button>
                            </form>
                            <form action="{{ route('client.budget.reject', $budget->id) }}" method="POST" style="display: inline;">
                                @csrf
                                <button type="submit" class="btn btn-danger">❌ Rechazar</button>
                            </form>
                        @endif
                    </div>
                </div>
            @endforeach
            
            <div style="margin-top: 30px;">
                {{ $budgets->links() }}
            </div>
        @else
            <div class="empty">
                <h2>📭 No tienes presupuestos</h2>
                <p>Cuando tengas presupuestos, aparecerán aquí.</p>
            </div>
        @endif
    </div>
</body>
</html>