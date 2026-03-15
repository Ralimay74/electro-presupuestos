<?php

namespace App\Filament\Widgets;

use App\Models\Client;
use App\Models\Budget;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class TopClients extends BaseWidget
{
    protected static ?string $heading = '🏆 Top 5 Clientes';
    
    protected static ?int $sort = 4;
    
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Client::query()
                    ->withCount(['budgets' => fn ($q) => $q->where('status', 'approved')])
                    ->withSum(['budgets' => fn ($q) => $q->where('status', 'approved')], 'total')
                    ->orderByDesc('budgets_sum_total')
                    ->limit(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Cliente')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('budgets_count')
                    ->label('Presupuestos Aprobados')
                    ->badge()
                    ->color('success'),
                
                Tables\Columns\TextColumn::make('budgets_sum_total')
                    ->label('Total Facturado')
                    ->money('EUR')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->copyable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->paginated(false);
    }
}