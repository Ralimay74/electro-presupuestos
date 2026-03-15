<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Resources\Resource;
use App\Models\Material;
use App\Filament\Resources\MaterialResource\Pages;
class MaterialResource extends Resource
{
    protected static ?string $model = Material::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
{
    return $form
        ->schema([
            TextInput::make('name')
                ->label('Nombre del material')
                ->placeholder('Ej: Cable Unipolar 2.5mm')
                ->required()
                ->maxLength(255),
            
            Textarea::make('description')
                ->label('Descripción técnica')
                ->placeholder('Detalles del material, especificaciones...')
                ->rows(2)
                ->maxLength(500),
            
            TextInput::make('price')
                ->label('Precio unitario (€)')
                ->numeric()
                ->prefix('€')
                ->minValue(0)
                ->step(0.01)
                ->required(),
            
            TextInput::make('category')
                ->label('Categoría')
                ->placeholder('Ej: cableado, cuadros, iluminación')
                ->maxLength(100)
                ->suggestions([
                    'cableado',
                    'cuadros eléctricos',
                    'iluminación',
                    'protección',
                    'conexiones',
                    'herramientas',
                ]),
            
            TextInput::make('stock')
                ->label('Stock disponible')
                ->numeric()
                ->minValue(0)
                ->default(0)
                ->helperText('Cantidad en almacén'),
        ]);
}
public static function table(Table $table): Table
{
    return $table
        ->columns([
            TextColumn::make('name')
                ->label('Material')
                ->searchable()
                ->sortable(),
            
            TextColumn::make('category')
                ->label('Categoría')
                ->badge()
                ->color('primary'),
            
            TextColumn::make('price')
                ->label('Precio')
                ->money('EUR')
                ->sortable(),
            
            TextColumn::make('stock')
                ->label('Stock')
                ->badge()
                ->color(fn (string $state): string => match (true) {
                    $state == 0 => 'danger',
                    $state < 50 => 'warning',
                    default => 'success',
                }),
            
            TextColumn::make('updated_at')
                ->label('Actualizado')
                ->dateTime('d/m/Y')
                ->sortable(),
        ])
        ->filters([
            Tables\Filters\SelectFilter::make('category')
                ->label('Filtrar por categoría')
                ->options([
                    'cableado' => 'Cableado',
                    'cuadros eléctricos' => 'Cuadros',
                    'iluminación' => 'Iluminación',
                    'protección' => 'Protección',
                ]),
        ])
        ->actions([
            Tables\Actions\EditAction::make()->label('Editar'),
            Tables\Actions\DeleteAction::make()->label('Eliminar'),
        ])
        ->emptyStateHeading('No hay clientes registrados')
        ->emptyStateDescription('Crea un nuevo cliente para comenzar')
        ->emptyStateActions([
        Tables\Actions\CreateAction::make()
        ->label('Nuevo cliente')
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
            'index' => Pages\ListMaterials::route('/'),
            'create' => Pages\CreateMaterial::route('/create'),
            'edit' => Pages\EditMaterial::route('/{record}/edit'),
        ];
    }
    public static function getNavigationLabel(): string { return 'Materiales'; }
    public static function getPluralModelLabel(): string { return 'Materiales'; }
    public static function getModelLabel(): string { return 'Material'; }
    public static function getNavigationGroup(): string { return 'Catálogo'; }
    public static function getNavigationIcon(): string { return 'heroicon-o-wrench-screwdriver'; }

}
