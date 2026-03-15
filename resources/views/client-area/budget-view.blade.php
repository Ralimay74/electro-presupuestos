<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Presupuesto {{ $budget->number }} - ElectroPresupuestos</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #f3f4f6;
            min-height: 100vh;
            padding: 20px;
        }
        .container {
            max-width: 900px;
            margin: 0 auto;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0 0 10px 0;
        }
        .content {
            padding: 40px;
        }
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-bottom: 30px;
        }
        .info-box {
            background: #f9fafb;
            padding: 20px;
            border-radius: 8px;
            border-left: 4px solid #667eea;
        }
        .info-box h3 {
            color: #667eea;
            margin-bottom: 15px;
        }
        .info-box p {
            margin: 8px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 30px 0;
        }
        table thead {
            background: #667eea;
            color: white;
        }
        table th, table td {
            padding: 12px;
            text-align: left;
        }
        table tbody tr:nth-child(even) {
            background: #f9fafb;
        }
        .totals {
            background: #f9fafb;
            padding: 20px;
            border-radius: 8px;
            margin-top: 20px;
        }
        .total-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #e5e7eb;
        }
        .total-row:last-child {
            border-bottom: none;
            font-size: 20px;
            font-weight: bold;
            color: #667eea;
        }
        .actions {
            display: flex;
            gap: 15px;
            margin-top: 30px;
            flex-wrap: wrap;
        }
        .btn {
            padding: 12px 25px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 600;
            cursor: pointer;
            border: none;
            font-size: 14px;
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
        .back-link {
            display: inline-block;
            margin-bottom: 20px;
            color: #667eea;
            text-decoration: none;
        }
        .status-badge {
            display: inline-block;
            padding: 6px 16px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
            text-transform: uppercase;
        }
        .status-draft { background: #e5e7eb; color: #374151; }
        .status-sent { background: #dbeafe; color: #1e40af; }
        .status-approved { background: #d1fae5; color: #065f46; }
        .status-rejected { background: #fee2e2; color: #991b1b; }
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
    <div class="container">
        <div class="header">
            <h1>📋 Presupuesto {{ $budget->number }}</h1>
            <p>Gracias por confiar en nosotros</p>
        </div>
        
        <div class="content">
            <a href="{{ route('client.dashboard') }}" class="back-link">← Volver a Mis Presupuestos</a>
            
            <div style="text-align: center; margin-bottom: 30px;">
                <span class="status-badge status-{{ $budget->status }}">
                    @if($budget->status == 'draft') 📝 Borrador
                    @elseif($budget->status == 'sent') 📤 Enviado
                    @elseif($budget->status == 'approved') ✅ Aprobado
                    @elseif($budget->status == 'rejected') ❌ Rechazado
                    @endif
                </span>
            </div>
            
            <div class="info-grid">
                <div class="info-box">
                    <h3>🏢 Empresa</h3>
                    <p><strong>RYM Soluciones Integrales</strong></p>
                    <p>Calle Ejemplo 123, 41020, Sevilla</p>
                    <p>Tel: 664301542</p>
                    <p>Email: raymar000@gmail.com</p>
                </div>
                
                <div class="info-box">
                    <h3>👤 Cliente</h3>
                    <p><strong>{{ $budget->client->name }}</strong></p>
                    @if($budget->client->nif_cif)
                    <p>NIF/CIF: {{ $budget->client->nif_cif }}</p>
                    @endif
                    @if($budget->client->phone)
                    <p>Tel: {{ $budget->client->phone }}</p>
                    @endif
                </div>
            </div>
            
            <div class="info-box" style="margin-bottom: 30px;">
                <h3>📄 Información del Presupuesto</h3>
                <p><strong>Nº Presupuesto:</strong> {{ $budget->number }}</p>
                <p><strong>Fecha de emisión:</strong> {{ $budget->date->format('d/m/Y') }}</p>
                <p><strong>IVA:</strong> {{ $budget->iva_percent }}%</p>
            </div>
            
            @if($budget->notes)
            <div style="background: #fef3c7; padding: 20px; border-left: 4px solid #f59e0b; border-radius: 8px; margin: 20px 0;">
                <h4 style="color: #92400e; margin-bottom: 10px;">📝 Observaciones</h4>
                <p style="margin: 0;">{{ $budget->notes }}</p>
            </div>
            @endif
            
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Concepto</th>
                        <th>Cantidad</th>
                        <th>Precio Unit.</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($budget->lines as $index => $line)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $line->description }}</td>
                        <td>{{ number_format($line->quantity, 2) }}</td>
                        <td>{{ number_format($line->unit_price, 2, ',', '.') }} €</td>
                        <td><strong>{{ number_format($line->subtotal, 2, ',', '.') }} €</strong></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            
            <div class="totals">
                @php
                    $subtotal = $budget->lines->sum('subtotal');
                    $ivaAmount = $subtotal * ($budget->iva_percent / 100);
                    $total = $subtotal + $ivaAmount;
                @endphp
                
                <div class="total-row">
                    <span>Base imponible:</span>
                    <span>{{ number_format($subtotal, 2, ',', '.') }} €</span>
                </div>
                <div class="total-row">
                    <span>IVA ({{ $budget->iva_percent }}%):</span>
                    <span>{{ number_format($ivaAmount, 2, ',', '.') }} €</span>
                </div>
                <div class="total-row">
                    <span>TOTAL:</span>
                    <span>{{ number_format($total, 2, ',', '.') }} €</span>
                </div>
            </div>
            
            <div class="actions">
                <a href="{{ route('client.budget.pdf', $budget->id) }}" class="btn btn-outline" target="_blank">
                    📄 Descargar PDF
                </a>
                
                @if($budget->status == 'sent')
                    <form action="{{ route('client.budget.accept', $budget->id) }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn btn-success">✅ Aceptar Presupuesto</button>
                    </form>
                    <form action="{{ route('client.budget.reject', $budget->id) }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn btn-danger">❌ Rechazar Presupuesto</button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</body>
</html>