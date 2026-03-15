<?php

namespace App\Filament\Exports;

use App\Models\Budget;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class BudgetExporter extends Exporter
{
    protected static ?string $model = Budget::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('number')
                ->label('Nº Presupuesto'),
            
            ExportColumn::make('client.name')
                ->label('Cliente'),
            
            ExportColumn::make('date')
                ->label('Fecha')
                ->formatStateUsing(fn ($state) => $state ? \Carbon\Carbon::parse($state)->format('d/m/Y') : ''),
            
            ExportColumn::make('status')
                ->label('Estado')
                ->formatStateUsing(fn (string $state): string => match ($state) {
                    'draft' => 'Borrador',
                    'sent' => 'Enviado',
                    'approved' => 'Aprobado',
                    'rejected' => 'Rechazado',
                    default => $state,
                }),
            
            ExportColumn::make('total')
                ->label('Total (€)')
                ->formatStateUsing(fn ($state) => $state ? number_format($state, 2, ',', '.') . ' €' : '0,00 €'),
            
            ExportColumn::make('iva_percent')
                ->label('IVA %'),
            
            ExportColumn::make('notes')
                ->label('Observaciones'),
            
            ExportColumn::make('created_at')
                ->label('Creado')
                ->formatStateUsing(fn ($state) => $state ? \Carbon\Carbon::parse($state)->format('d/m/Y H:i') : ''),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Tu exportación de presupuestos se ha completado.';
        
        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= " {$failedRowsCount} fila(s) fallaron.";
        }
        
        return $body;
    }
    
     
   
}