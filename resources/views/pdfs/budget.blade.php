<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Presupuesto {{ $budget->number }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            line-height: 1.4;
            color: #333;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #2563eb;
            padding-bottom: 15px;
        }
        
        .header h1 {
            color: #2563eb;
            margin: 0 0 10px 0;
            font-size: 24px;
        }
        
        .header .company-info {
            font-size: 10px;
            color: #666;
        }
        
        .budget-info {
            display: table;
            width: 100%;
            margin-bottom: 25px;
        }
        
        .budget-info-row {
            display: table-row;
        }
        
        .budget-info-cell {
            display: table-cell;
            width: 50%;
            vertical-align: top;
            padding: 5px 0;
        }
        
        .budget-info-cell.right {
            text-align: right;
        }
        
        .client-box {
            background: #f3f4f6;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        
        .client-box h3 {
            margin: 0 0 10px 0;
            color: #2563eb;
            font-size: 14px;
        }
        
        .client-box p {
            margin: 5px 0;
        }
        
        .notes-box {
            background: #fef3c7;
            padding: 15px;
            border-left: 4px solid #f59e0b;
            margin: 20px 0;
        }
        
        .notes-box h4 {
            margin: 0 0 10px 0;
            color: #92400e;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 25px 0;
        }
        
        table thead th {
            background: #2563eb;
            color: white;
            padding: 10px 8px;
            text-align: left;
            font-size: 11px;
        }
        
        table tbody td {
            padding: 8px;
            border-bottom: 1px solid #e5e7eb;
        }
        
        table tbody tr:nth-child(even) {
            background: #f9fafb;
        }
        
        .text-right {
            text-align: right;
        }
        
        .text-center {
            text-align: center;
        }
        
        .totals-table {
            width: 350px;
            float: right;
            margin: 20px 0;
        }
        
        .totals-table td {
            padding: 8px;
        }
        
        .totals-table .total-row {
            background: #2563eb;
            color: white;
            font-size: 14px;
            font-weight: bold;
        }
        
        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 9px;
            color: #999;
            border-top: 1px solid #e5e7eb;
            padding: 10px 0;
        }
        
        .status-badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .status-draft { background: #e5e7eb; color: #374151; }
        .status-sent { background: #dbeafe; color: #1e40af; }
        .status-approved { background: #d1fae5; color: #065f46; }
        .status-rejected { background: #fee2e2; color: #991b1b; }
    </style>
</head>
<body>
    <!-- Encabezado -->
    <div class="header">
        <h1>{{ $company['name'] }}</h1>
        <div class="company-info">
            {{ $company['address'] }} | Tel: {{ $company['phone'] }} | Email: {{ $company['email'] }}<br>
            NIF: {{ $company['nif'] }} | Web: {{ $company['web'] }}
        </div>
    </div>
    
    <!-- Información del presupuesto -->
    <div class="budget-info">
        <div class="budget-info-row">
            <div class="budget-info-cell">
                <strong>Presupuesto Nº:</strong> {{ $budget->number }}<br>
                <strong>Fecha de emisión:</strong> {{ $budget->date->format('d/m/Y') }}<br>
                <strong>Estado:</strong> 
                <span class="status-badge status-{{ $budget->status }}">
                    @if($budget->status == 'draft') 📝 Borrador
                    @elseif($budget->status == 'sent') 📤 Enviado
                    @elseif($budget->status == 'approved') ✅ Aprobado
                    @elseif($budget->status == 'rejected') ❌ Rechazado
                    @endif
                </span>
            </div>
            <div class="budget-info-cell right">
                <strong>Válido hasta:</strong> {{ $budget->date->addDays(30)->format('d/m/Y') }}<br>
                <strong>Forma de pago:</strong> Transferencia bancaria<br>
                <strong>Plazo de entrega:</strong> 5-7 días laborables
            </div>
        </div>
    </div>
    
    <!-- Datos del cliente -->
    <div class="client-box">
        <h3>📋 Datos del Cliente</h3>
        <p><strong>Nombre:</strong> {{ $budget->client->name }}</p>
        @if($budget->client->nif_cif)
        <p><strong>NIF/CIF:</strong> {{ $budget->client->nif_cif }}</p>
        @endif
        @if($budget->client->address)
        <p><strong>Dirección:</strong> {{ $budget->client->address }}</p>
        @endif
        @if($budget->client->phone)
        <p><strong>Teléfono:</strong> {{ $budget->client->phone }}</p>
        @endif
        @if($budget->client->email)
        <p><strong>Email:</strong> {{ $budget->client->email }}</p>
        @endif
    </div>
    
    <!-- Observaciones -->
    @if($budget->notes)
    <div class="notes-box">
        <h4>📝 Observaciones</h4>
        <p style="margin: 0;">{!! nl2br(e($budget->notes)) !!}</p>
    </div>
    @endif
    
    <!-- Tabla de conceptos -->
    <table>
        <thead>
            <tr>
                <th style="width: 5%;">#</th>
                <th style="width: 45%;">Concepto</th>
                <th style="width: 15%; text-align: center;">Cantidad</th>
                <th style="width: 15%; text-align: right;">Precio Unit.</th>
                <th style="width: 20%; text-align: right;">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($budget->lines as $index => $line)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $line->description }}</td>
                <td class="text-center">{{ number_format($line->quantity, 2, ',', '.') }}</td>
                <td class="text-right">{{ number_format($line->unit_price, 2, ',', '.') }} €</td>
                <td class="text-right"><strong>{{ number_format($line->subtotal, 2, ',', '.') }} €</strong></td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    <!-- Tabla de totales -->
    <table class="totals-table">
        <tr>
            <td><strong>Base imponible:</strong></td>
            <td class="text-right">{{ number_format($subtotal, 2, ',', '.') }} €</td>
        </tr>
        <tr>
            <td><strong>IVA ({{ $budget->iva_percent }}%):</strong></td>
            <td class="text-right">{{ number_format($ivaAmount, 2, ',', '.') }} €</td>
        </tr>
        <tr class="total-row">
            <td><strong>TOTAL PRESUPUESTO:</strong></td>
            <td class="text-right">{{ number_format($total, 2, ',', '.') }} €</td>
        </tr>
    </table>
    
    <div style="clear: both;"></div>
    
    <!-- Pie de página -->
    <div class="footer">
        Presupuesto generado el {{ now()->format('d/m/Y \a \l\a\s H:i') }} | 
        {{ $company['name'] }} | Tel: {{ $company['phone'] }} | {{ $company['email'] }}
    </div>
</body>
</html>