<?php

namespace App\Filament\Widgets;

use App\Models\Material;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Tables;
use Filament\Tables\Table;

class TopMaterials extends BaseWidget
{
    protected static ?string $heading = '🔧 Materiales Más Vendidos';
    
    protected static ?int $sort = 5;
    
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Material::query()
                    ->withCount('budgetLines')
                    ->orderByDesc('budget_lines_count')
                    ->limit(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Material')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('category')
                    ->label('Categoría')
                    ->badge()
                    ->color('primary'),
                
                Tables\Columns\TextColumn::make('budget_lines_count')
                    ->label('Veces Vendido')
                    ->badge()
                    ->color('success'),
                
                Tables\Columns\TextColumn::make('price')
                    ->label('Precio')
                    ->money('EUR')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('stock')
                    ->label('Stock')
                    ->badge()
                    ->color(fn (string $state): string => match (true) {
                        $state == 0 => 'danger',
                        $state < 50 => 'warning',
                        default => 'success',
                    }),
            ])
            ->paginated(false);
    }
}