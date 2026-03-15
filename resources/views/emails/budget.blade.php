<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Presupuesto {{ $budget->number }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: #2563eb;
            color: white;
            padding: 20px;
            border-radius: 8px 8px 0 0;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .content {
            background: #f9fafb;
            padding: 30px;
            border: 1px solid #e5e7eb;
            border-top: none;
            border-radius: 0 0 8px 8px;
        }
        .budget-info {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            border: 1px solid #e5e7eb;
        }
        .budget-info table {
            width: 100%;
            border-collapse: collapse;
        }
        .budget-info table td {
            padding: 8px 0;
            border-bottom: 1px solid #f3f4f6;
        }
        .budget-info table tr:last-child td {
            border-bottom: none;
        }
        .budget-info table td:first-child {
            font-weight: bold;
            color: #6b7280;
            width: 40%;
        }
        .cta-button {
            display: inline-block;
            background: #2563eb;
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 6px;
            margin: 20px 0;
            font-weight: bold;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            font-size: 12px;
            color: #9ca3af;
        }
        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 4px;
            font-size: 12px;
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
    <div class="header">
        <h1>📋 Presupuesto {{ $budget->number }}</h1>
        <p>Gracias por confiar en nosotros</p>
    </div>
    
    <div class="content">
        <p>Hola <strong>{{ $clientName }}</strong>,</p>
        
        <p>Esperamos que este email le encuentre bien. Adjunto a este mensaje encontrará el presupuesto solicitado.</p>
        
        @if($customMessage)
        <div style="background: #fef3c7; padding: 15px; border-left: 4px solid #f59e0b; margin: 20px 0;">
            <p style="margin: 0;">📝 <strong>Nota adicional:</strong><br>{{ $customMessage }}</p>
        </div>
        @endif
        
        <div class="budget-info">
            <table>
                <tr>
                    <td>Nº Presupuesto:</td>
                    <td><strong>{{ $budget->number }}</strong></td>
                </tr>
                <tr>
                    <td>Fecha de emisión:</td>
                    <td>{{ $budget->date->format('d/m/Y') }}</td>
                </tr>
                <tr>
                    <td>Estado:</td>
                    <td>
                        <span class="status-badge status-{{ $budget->status }}">
                            @if($budget->status == 'draft') 📝 Borrador
                            @elseif($budget->status == 'sent') 📤 Enviado
                            @elseif($budget->status == 'approved') ✅ Aprobado
                            @elseif($budget->status == 'rejected') ❌ Rechazado
                            @endif
                        </span>
                    </td>
                </tr>
                <tr>
                    <td>Importe total:</td>
                    <td><strong style="font-size: 18px; color: #2563eb;">{{ number_format($budget->total, 2, ',', '.') }} €</strong></td>
                </tr>
                <tr>
                    <td>Validez:</td>
                    <td>30 días desde la fecha de emisión</td>
                </tr>
            </table>
        </div>
        
        <p>Quedamos a su entera disposición para cualquier consulta o modificación que desee realizar en el presupuesto.</p>
        
        <p style="margin-top: 30px;">
            Atentamente,<br>
            <strong>{{ config('app.name') }}</strong><br>
            📞 {{ config('mail.from.address') }}
        </p>
        
        <div class="footer">
            <p>Este email ha sido enviado automáticamente desde {{ config('app.name') }}</p>
            <p>Si tiene alguna duda, por favor contacte con nosotros respondiendo a este email.</p>
        </div>
    </div>
</body>
</html>