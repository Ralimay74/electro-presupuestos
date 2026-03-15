<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Presupuesto {{ $budget->number }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            line-height: 1.6;
            color: #333;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        
        .container {
            max-width: 900px;
            margin: 0 auto;
            background: white;
            border-radius: 12px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            overflow: hidden;
        }
        
        .header {
            background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
            color: white;
            padding: 40px;
            text-align: center;
        }
        
        .header h1 {
            font-size: 32px;
            margin-bottom: 10px;
        }
        
        .header p {
            font-size: 16px;
            opacity: 0.9;
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
            border-left: 4px solid #2563eb;
        }
        
        .info-box h3 {
            color: #2563eb;
            margin-bottom: 15px;
            font-size: 16px;
        }
        
        .info-box p {
            margin: 8px 0;
            font-size: 14px;
        }
        
        .info-box strong {
            color: #1f2937;
        }
        
        .status-badge {
            display: inline-block;
            padding: 6px 16px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
            text-transform: uppercase;
            margin: 10px 0;
        }
        
        .status-draft { background: #e5e7eb; color: #374151; }
        .status-sent { background: #dbeafe; color: #1e40af; }
        .status-approved { background: #d1fae5; color: #065f46; }
        .status-rejected { background: #fee2e2; color: #991b1b; }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 30px 0;
        }
        
        table thead {
            background: #2563eb;
            color: white;
        }
        
        table th {
            padding: 12px;
            text-align: left;
            font-weight: 600;
        }
        
        table td {
            padding: 12px;
            border-bottom: 1px solid #e5e7eb;
        }
        
        table tbody tr:nth-child(even) {
            background: #f9fafb;
        }
        
        .totals-section {
            background: #f9fafb;
            padding: 25px;
            border-radius: 8px;
            margin-top: 30px;
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
            color: #2563eb;
            margin-top: 10px;
            padding-top: 15px;
            border-top: 2px solid #2563eb;
        }
        
        .actions {
            display: flex;
            gap: 15px;
            margin-top: 40px;
            flex-wrap: wrap;
        }
        
        .btn {
            flex: 1;
            min-width: 200px;
            padding: 15px 30px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            text-align: center;
        }
        
        .btn-primary {
            background: #10b981;
            color: white;
        }
        
        .btn-primary:hover {
            background: #059669;
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(16, 185, 129, 0.3);
        }
        
        .btn-secondary {
            background: #ef4444;
            color: white;
        }
        
        .btn-secondary:hover {
            background: #dc2626;
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(239, 68, 68, 0.3);
        }
        
        .btn-outline {
            background: white;
            color: #2563eb;
            border: 2px solid #2563eb;
        }
        
        .btn-outline:hover {
            background: #2563eb;
            color: white;
        }
        
        .notes-box {
            background: #fef3c7;
            padding: 20px;
            border-left: 4px solid #f59e0b;
            border-radius: 8px;
            margin: 20px 0;
        }
        
        .notes-box h4 {
            color: #92400e;
            margin-bottom: 10px;
        }
        
        .footer {
            background: #f9fafb;
            padding: 30px 40px;
            text-align: center;
            border-top: 1px solid #e5e7eb;
            font-size: 13px;
            color: #6b7280;
        }
        
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }
        
        .modal.active {
            display: flex;
        }
        
        .modal-content {
            background: white;
            padding: 40px;
            border-radius: 12px;
            max-width: 500px;
            width: 90%;
        }
        
        .modal-content h2 {
            margin-bottom: 20px;
            color: #1f2937;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #374151;
        }
        
        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 14px;
        }
        
        .form-group textarea {
            resize: vertical;
            min-height: 100px;
        }
        
        @media (max-width: 768px) {
            .info-grid {
                grid-template-columns: 1fr;
            }
            
            .actions {
                flex-direction: column;
            }
            
            .btn {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>📋 Presupuesto {{ $budget->number }}</h1>
            <p>Gracias por confiar en nosotros</p>
        </div>
        
        <!-- Content -->
        <div class="content">
            <!-- Estado -->
            <div style="text-align: center; margin-bottom: 30px;">
                <span class="status-badge status-{{ $budget->status }}">
                    @if($budget->status == 'draft') 📝 Borrador
                    @elseif($budget->status == 'sent') 📤 Enviado
                    @elseif($budget->status == 'approved') ✅ Aprobado
                    @elseif($budget->status == 'rejected') ❌ Rechazado
                    @endif
                </span>
            </div>
            
            <!-- Info Grid -->
            <div class="info-grid">
                <!-- Datos de la empresa -->
                <div class="info-box">
                    <h3>🏢 Empresa</h3>
                    <p><strong>RYM Soluciones Integrales</strong></p>
                    <p>Calle Ejemplo 123, 41020, Sevilla</p>
                    <p>Tel: 664301542</p>
                    <p>Email: raymar000@gmail.com</p>
                </div>
                
                <!-- Datos del cliente -->
                <div class="info-box">
                    <h3>👤 Cliente</h3>
                    <p><strong>{{ $budget->client->name }}</strong></p>
                    @if($budget->client->nif_cif)
                    <p>NIF/CIF: {{ $budget->client->nif_cif }}</p>
                    @endif
                    @if($budget->client->phone)
                    <p>Tel: {{ $budget->client->phone }}</p>
                    @endif
                    @if($budget->client->email)
                    <p>Email: {{ $budget->client->email }}</p>
                    @endif
                </div>
            </div>
            
            <!-- Info del presupuesto -->
            <div class="info-box" style="margin-bottom: 30px;">
                <h3>📄 Información del Presupuesto</h3>
                <p><strong>Nº Presupuesto:</strong> {{ $budget->number }}</p>
                <p><strong>Fecha de emisión:</strong> {{ $budget->date->format('d/m/Y') }}</p>
                <p><strong>Validez:</strong> 30 días desde la fecha de emisión</p>
                <p><strong>IVA:</strong> {{ $budget->iva_percent }}%</p>
            </div>
            
            <!-- Observaciones -->
            @if($budget->notes)
            <div class="notes-box">
                <h4>📝 Observaciones</h4>
                <p>{{ $budget->notes }}</p>
            </div>
            @endif
            
            <!-- Tabla de conceptos -->
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Concepto</th>
                        <th style="text-align: center;">Cantidad</th>
                        <th style="text-align: right;">Precio Unit.</th>
                        <th style="text-align: right;">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($budget->lines as $index => $line)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $line->description }}</td>
                        <td style="text-align: center;">{{ number_format($line->quantity, 2) }}</td>
                        <td style="text-align: right;">{{ number_format($line->unit_price, 2, ',', '.') }} €</td>
                        <td style="text-align: right;"><strong>{{ number_format($line->subtotal, 2, ',', '.') }} €</strong></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            
            <!-- Totales -->
            <div class="totals-section">
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
            
            <!-- Acciones -->
            <div class="actions">
                <button class="btn btn-primary" onclick="openAcceptModal()">
                    ✅ Aceptar Presupuesto
                </button>
                
                <button class="btn btn-secondary" onclick="openRejectModal()">
                    ❌ Rechazar Presupuesto
                </button>
                
                <a href="{{ route('public.budget.pdf', $budget->public_token) }}" class="btn btn-outline" target="_blank">
                    📄 Descargar PDF
                </a>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="footer">
            <p>Presupuesto generado el {{ $budget->created_at->format('d/m/Y \a \l\a\s H:i') }}</p>
            <p>¿Tienes dudas? Contacta con nosotros: 664301542 | raymar000@gmail.com</p>
        </div>
    </div>
    
    <!-- Modal Aceptar -->
    <div id="acceptModal" class="modal">
        <div class="modal-content">
            <h2>✅ Aceptar Presupuesto</h2>
            <form action="{{ route('public.budget.accept', $budget->public_token) }}" method="POST">
                @csrf
                
                <div class="form-group">
                    <label>Nombre completo *</label>
                    <input type="text" name="client_name" required value="{{ $budget->client->name }}">
                </div>
                
                <div class="form-group">
                    <label>Email *</label>
                    <input type="email" name="client_email" required value="{{ $budget->client->email }}">
                </div>
                
                <div class="form-group">
                    <label>Comentarios (opcional)</label>
                    <textarea name="comments" placeholder="¿Alguna observación adicional?"></textarea>
                </div>
                
                <div style="display: flex; gap: 10px;">
                    <button type="submit" class="btn btn-primary" style="flex: 1;">
                        Confirmar Aceptación
                    </button>
                    <button type="button" class="btn btn-outline" onclick="closeAcceptModal()" style="flex: 1;">
                        Cancelar
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Modal Rechazar -->
    <div id="rejectModal" class="modal">
        <div class="modal-content">
            <h2>❌ Rechazar Presupuesto</h2>
            <form action="{{ route('public.budget.reject', $budget->public_token) }}" method="POST">
                @csrf
                
                <div class="form-group">
                    <label>Motivo (opcional)</label>
                    <textarea name="reason" placeholder="¿Por qué deseas rechazar este presupuesto?"></textarea>
                </div>
                
                <div style="display: flex; gap: 10px;">
                    <button type="submit" class="btn btn-secondary" style="flex: 1;">
                        Confirmar Rechazo
                    </button>
                    <button type="button" class="btn btn-outline" onclick="closeRejectModal()" style="flex: 1;">
                        Cancelar
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <script>
        function openAcceptModal() {
            document.getElementById('acceptModal').classList.add('active');
        }
        
        function closeAcceptModal() {
            document.getElementById('acceptModal').classList.remove('active');
        }
        
        function openRejectModal() {
            document.getElementById('rejectModal').classList.add('active');
        }
        
        function closeRejectModal() {
            document.getElementById('rejectModal').classList.remove('active');
        }
        
        // Cerrar modal al hacer clic fuera
        window.onclick = function(event) {
            if (event.target.classList.contains('modal')) {
                event.target.classList.remove('active');
            }
        }
    </script>
</body>
</html>