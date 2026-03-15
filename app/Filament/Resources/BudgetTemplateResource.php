<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BudgetTemplateResource\Pages;
use App\Models\BudgetTemplate;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;

class BudgetTemplateResource extends Resource
{
    protected static ?string $model = BudgetTemplate::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-duplicate';

    protected static ?string $navigationGroup = 'Ventas';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Datos de la Plantilla')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nombre de la Plantilla')
                            ->required()
                            ->placeholder('ej: Instalación Cocina Completa'),
                        
                        Textarea::make('description')
                            ->label('Descripción')
                            ->placeholder('Descripción de la plantilla...')
                            ->rows(2),
                        
                        TextInput::make('iva_percent')
                            ->label('IVA (%)')
                            ->numeric()
                            ->default(21)
                            ->minValue(0)
                            ->maxValue(100)
                            ->suffix('%'),
                        
                        Textarea::make('notes')
                            ->label('Notas por defecto')
                            ->placeholder('Notas que aparecerán en todos los presupuestos...')
                            ->rows(3),
                        
                        Toggle::make('is_active')
                            ->label('Activa')
                            ->default(true)
                            ->helperText('Las plantillas inactivas no se mostrarán al crear presupuestos'),
                    ])->columns(2),
                
                Section::make('Conceptos de la Plantilla')
                    ->schema([
                        Repeater::make('lines')
                            ->relationship('lines')
                            ->label('Líneas')
                            ->schema([
                                Select::make('material_id')
                                    ->relationship('material', 'name')
                                    ->label('Material (opcional)')
                                    ->searchable()
                                    ->preload()
                                    ->live()
                                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                        if ($state) {
                                            $material = \App\Models\Material::find($state);
                                            if ($material) {
                                                $set('description', $material->name);
                                                $set('unit_price', $material->price);
                                            }
                                        }
                                    }),
                                
                                TextInput::make('description')
                                    ->label('Concepto')
                                    ->required()
                                    ->maxLength(255),
                                
                                TextInput::make('quantity')
                                    ->label('Cantidad')
                                    ->numeric()
                                    ->default(1)
                                    ->minValue(0.01)
                                    ->step(0.01)
                                    ->live()
                                    ->required(),
                                
                                TextInput::make('unit_price')
                                    ->label('Precio Unitario (€)')
                                    ->numeric()
                                    ->minValue(0)
                                    ->step(0.01)
                                    ->prefix('€')
                                    ->live()
                                    ->required(),
                                
                                TextInput::make('subtotal')
                                    ->label('Subtotal (€)')
                                    ->numeric()
                                    ->readOnly()
                                    ->dehydrated()
                                    ->prefix('€')
                                    ->afterStateHydrated(function (callable $set, callable $get) {
                                        $qty = $get('quantity') ?? 0;
                                        $price = $get('unit_price') ?? 0;
                                        $set('subtotal', round($qty * $price, 2));
                                    })
                                    ->live(onBlur: true),
                                
                                TextInput::make('order')
                                    ->label('Orden')
                                    ->numeric()
                                    ->default(0)
                                    ->helperText('Orden de aparición en el presupuesto'),
                            ])
                            ->columns(3)
                            ->createItemButtonLabel('➕ Añadir concepto')
                            ->reorderable()
                            ->minItems(1),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable(),
                
                TextColumn::make('description')
                    ->label('Descripción')
                    ->limit(50)
                    ->toggleable(isToggledHiddenByDefault: true),
                
                TextColumn::make('iva_percent')
                    ->label('IVA %')
                    ->sortable(),
                
                TextColumn::make('lines_count')
                    ->label('Conceptos')
                    ->counts('lines')
                    ->badge()
                    ->color('info'),
                
                IconColumn::make('is_active')
                    ->label('Activa')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('gray'),
                
                TextColumn::make('created_at')
                    ->label('Creada')
                    ->dateTime('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Estado')
                    ->trueLabel('Activas')
                    ->falseLabel('Inactivas'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListBudgetTemplates::route('/'),
            'create' => Pages\CreateBudgetTemplate::route('/create'),
            'edit' => Pages\EditBudgetTemplate::route('/{record}/edit'),
        ];
    }

    public static function getNavigationLabel(): string { return 'Plantillas Presupuesto'; }
    public static function getPluralModelLabel(): string { return 'Plantillas'; }
    public static function getModelLabel(): string { return 'Plantilla Presupuesto'; }
}