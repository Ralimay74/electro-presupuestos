<?php

namespace App\Filament\Widgets;

use App\Models\Budget;
use Filament\Widgets\ChartWidget;

class BudgetsByStatus extends ChartWidget
{
    protected static ?string $heading = 'Presupuestos por Estado';
    
    protected static ?int $sort = 2;
    
    protected int | string | array $columnSpan = 'full';

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getData(): array
    {
        $data = [
            'draft' => Budget::where('status', 'draft')->count(),
            'sent' => Budget::where('status', 'sent')->count(),
            'approved' => Budget::where('status', 'approved')->count(),
            'rejected' => Budget::where('status', 'rejected')->count(),
        ];

        return [
            'datasets' => [
                [
                    'label' => 'Presupuestos',
                    'data' => array_values($data),
                    'backgroundColor' => [
                        '#9ca3af', // draft - gray
                        '#3b82f6', // sent - blue
                        '#10b981', // approved - green
                        '#ef4444', // rejected - red
                    ],
                    'borderColor' => '#ffffff',
                    'borderWidth' => 2,
                ],
            ],
            'labels' => [
                '📝 Borrador',
                '📤 Enviado',
                '✅ Aprobado',
                '❌ Rechazado',
            ],
        ];
    }

    protected function getOptions(): array
    {
        return [
            'responsive' => true,
            'maintainAspectRatio' => false,
            'plugins' => [
                'legend' => [
                    'position' => 'bottom',
                ],
            ],
        ];
    }
}