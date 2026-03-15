<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\CreateAction;
use Filament\Resources\Resource;
use App\Models\Client;
use App\Filament\Resources\ClientResource\Pages;

class ClientResource extends Resource
{
    protected static ?string $model = Client::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
{
    return $form
        ->schema([
            TextInput::make('name')
                ->label('Nombre del cliente')
                ->placeholder('Ej: Empresa Eléctrica SL')
                ->required()
                ->maxLength(255),
            
            TextInput::make('email')
                ->label('Correo electrónico')
                ->placeholder('cliente@ejemplo.com')
                ->email()
                ->maxLength(255),
            
            TextInput::make('phone')
                ->label('Teléfono')
                ->placeholder('600 123 456')
                ->tel()
                ->maxLength(20),
            
            Textarea::make('address')
                ->label('Dirección completa')
                ->placeholder('Calle, número, código postal, ciudad')
                ->rows(3)
                ->maxLength(500),
            
            TextInput::make('nif_cif')
                ->label('NIF / CIF')
                ->placeholder('B12345678')
                ->maxLength(20)
                ->helperText('Documento fiscal del cliente'),
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
            
            TextColumn::make('email')
                ->label('Email')
                ->searchable(),
            
            TextColumn::make('phone')
                ->label('Teléfono'),
            
            TextColumn::make('nif_cif')
                ->label('NIF/CIF'),
            
            TextColumn::make('created_at')
                ->label('Registrado el')
                ->dateTime('d/m/Y H:i')
                ->sortable(),
        ])
        ->filters([
            // Filtros opcionales
        ])
        ->actions([
            Tables\Actions\EditAction::make()
                ->label('Editar'),
            Tables\Actions\DeleteAction::make()
                ->label('Eliminar'),
        ])
        ->bulkActions([
            Tables\Actions\BulkActionGroup::make([
                Tables\Actions\DeleteBulkAction::make()
                    ->label('Eliminar seleccionados'),
            ]),
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
            'index' => Pages\ListClients::route('/'),
            'create' => Pages\CreateClient::route('/create'),
            'edit' => Pages\EditClient::route('/{record}/edit'),
        ];
    }
    public static function getNavigationLabel(): string
    {
    return 'Clientes';
    }

    public static function getPluralModelLabel(): string
    {
    return 'Clientes';
    }

    public static function getModelLabel(): string
    {
    return 'Cliente';
    }

    public static function getNavigationGroup(): string
    {
    return 'Gestión';
    }

    public static function getNavigationIcon(): string
    {
    return 'heroicon-o-users';
    }
}
