<?php

namespace App\Filament\Widgets;

use App\Models\Budget;
use App\Models\Client;
use App\Models\BudgetLine;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        // Total presupuestos
        $totalPresupuestos = Budget::count();
        
        // Presupuestos aprobados
        $aprobados = Budget::where('status', 'approved')->count();
        
        // Total facturado (suma de presupuestos aprobados)
        $totalFacturado = Budget::where('status', 'approved')->sum('total');
        
        // Total clientes
        $totalClientes = Client::count();
        
        // Presupuesto promedio
        $promedio = $totalPresupuestos > 0 
            ? Budget::where('status', 'approved')->avg('total') 
            : 0;

        return [
            Stat::make('📋 Total Presupuestos', $totalPresupuestos)
                ->description('Presupuestos registrados')
                ->descriptionIcon('heroicon-m-document-text')
                ->color('primary'),
            
            Stat::make('✅ Aprobados', $aprobados)
                ->description($totalPresupuestos > 0 ? round(($aprobados / $totalPresupuestos) * 100) . '% tasa aprobación' : 'Sin datos')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),
            
            Stat::make('💰 Total Facturado', number_format($totalFacturado, 2, ',', '.') . ' €')
                ->description('Solo presupuestos aprobados')
                ->descriptionIcon('heroicon-m-currency-euro')  // ← Icono correcto
                ->color('success'),
            
            Stat::make('👥 Clientes', $totalClientes)
                ->description('Clientes registrados')
                ->descriptionIcon('heroicon-m-user-group')  // ← Icono correcto
                ->color('info'),
            
            Stat::make('📈 Ticket Promedio', number_format($promedio, 2, ',', '.') . ' €')
                ->description('Por presupuesto aprobado')
                ->descriptionIcon('heroicon-m-chart-bar')
                ->color('warning'),
        ];
    }
}