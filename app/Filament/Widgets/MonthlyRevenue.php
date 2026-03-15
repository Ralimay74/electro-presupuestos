<?php

namespace App\Filament\Widgets;

use App\Models\Budget;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class MonthlyRevenue extends ChartWidget
{
    protected static ?string $heading = 'Facturación por Mes (Últimos 6 meses)';
    
    protected static ?int $sort = 3;
    
    protected int | string | array $columnSpan = 'full';

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getData(): array
    {
        // Obtener facturación de los últimos 6 meses
        $monthlyData = Budget::where('status', 'approved')
            ->selectRaw('DATE_FORMAT(date, "%Y-%m") as month, SUM(total) as total')
            ->groupBy('month')
            ->orderBy('month', 'desc')
            ->limit(6)
            ->get()
            ->reverse()
            ->pluck('total', 'month');

        // Labels con formato mes/año
        $labels = $monthlyData->keys()->map(function ($month) {
            $parts = explode('-', $month);
            return $parts[1] . '/' . substr($parts[0], -2);
        })->toArray();

        return [
            'datasets' => [
                [
                    'label' => 'Facturación (€)',
                    'data' => $monthlyData->values()->toArray(),
                    'backgroundColor' => '#10b981',
                    'borderColor' => '#059669',
                    'borderWidth' => 1,
                    'borderRadius' => 4,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getOptions(): array
    {
        return [
            'responsive' => true,
            'maintainAspectRatio' => false,
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'callback' => 'function(value) { return value + " €"; }',
                    ],
                ],
            ],
            'plugins' => [
                'legend' => [
                    'display' => false,
                ],
            ],
        ];
    }
}