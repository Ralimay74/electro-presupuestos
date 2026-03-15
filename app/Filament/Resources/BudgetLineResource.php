<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BudgetLineResource\Pages;
use App\Filament\Resources\BudgetLineResource\RelationManagers;
use App\Models\BudgetLine;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BudgetLineResource extends Resource
{
    protected static ?string $model = BudgetLine::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBudgetLines::route('/'),
            'create' => Pages\CreateBudgetLine::route('/create'),
            'edit' => Pages\EditBudgetLine::route('/{record}/edit'),
        ];
    }

    public static function getNavigationLabel(): string
{
    return 'Líneas de Presupuesto';
}

public static function getPluralModelLabel(): string
{
    return 'Líneas de Presupuesto';
}

public static function getModelLabel(): string
{
    return 'Línea de Presupuesto';
}

public static function getNavigationGroup(): string
{
    return 'Ventas';  // O 'Gestión' si prefieres
}

public static function getNavigationIcon(): string
{
    return 'heroicon-o-document-text';
}

// Opcional: Ocultar del menú si solo se gestionan desde el presupuesto
public static function shouldRegisterNavigation(): bool
{
    return false;  // Esto lo oculta del menú principal

}

}
