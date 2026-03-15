<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Placeholder;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\IconColumn;
use Filament\Resources\Resource;
use App\Models\Budget;
use App\Filament\Resources\BudgetResource\Pages;
use Filament\Tables\Actions\ExportAction;
use App\Filament\Exports\BudgetExporter;

class BudgetResource extends Resource
{
    protected static ?string $model = Budget::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('📋 Usar Plantilla (Opcional)')
    ->description('Selecciona una plantilla para rellenar automáticamente los conceptos')
    ->schema([
        Select::make('template_id')
            ->label('Seleccionar Plantilla')
            ->placeholder('Selecciona una plantilla o deja vacío para crear desde cero')
            ->options(
                \App\Models\BudgetTemplate::where('is_active', true)
                    ->pluck('name', 'id')
            )
            ->live()
            ->afterStateUpdated(function ($state, callable $set, callable $get) {
                if ($state) {
                    $template = \App\Models\BudgetTemplate::with('lines')->find($state);
                    if ($template) {
                        // Establecer IVA
                        $set('iva_percent', $template->iva_percent);
                        
                        // Establecer notas
                        $set('notes', $template->notes);
                        
                        // Establecer líneas
                        $lines = [];
                        foreach ($template->lines as $line) {
                            $lines[] = [
                                'material_id' => $line->material_id,
                                'description' => $line->description,
                                'quantity' => $line->quantity,
                                'unit_price' => $line->unit_price,
                                'subtotal' => $line->quantity * $line->unit_price,
                            ];
                        }
                        $set('lines', $lines);
                    }
                }
            })
            ->helperText('Al seleccionar, se rellenará automáticamente el IVA, notas y conceptos'),
    ])->collapsible(),

                // === Datos del presupuesto ===
                Section::make('Datos del presupuesto')
                    ->schema([
                        Select::make('client_id')
                            ->relationship('client', 'name')
                            ->label('Cliente')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->createOptionForm([
                                TextInput::make('name')->label('Nombre')->required(),
                                TextInput::make('email')->label('Email')->email(),
                                TextInput::make('phone')->label('Teléfono'),
                            ])
                            ->createOptionAction(fn ($action) => $action->label('Nuevo cliente')),
                        
                        TextInput::make('number')
                            ->label('Número de presupuesto')
                            ->default(fn () => 'PRES-' . date('Y') . '-' . str_pad(random_int(1, 9999), 4, '0', STR_PAD_LEFT))
                            ->readOnly()
                            ->helperText('Se genera automáticamente'),
                        
                        DatePicker::make('date')
                            ->label('Fecha de emisión')
                            ->default(now())
                            ->required()
                            ->displayFormat('d/m/Y'),
                        
                        Select::make('status')
                            ->label('Estado')
                            ->options([
                                'draft' => '📝 Borrador',
                                'sent' => '📤 Enviado',
                                'approved' => '✅ Aprobado',
                                'rejected' => '❌ Rechazado',
                            ])
                            ->default('draft')
                            ->required(),
                        
                        Textarea::make('notes')
                            ->label('Observaciones')
                            ->placeholder('Notas adicionales para el cliente...')
                            ->rows(3),
                        
                        TextInput::make('iva_percent')
                            ->label('IVA (%)')
                            ->numeric()
                            ->default(21)
                            ->minValue(0)
                            ->maxValue(100)
                            ->suffix('%'),
                    ])->columns(2),
                
                // === Líneas del presupuesto ===
                Section::make('Conceptos del presupuesto')
                    ->schema([
                        Repeater::make('lines')
                            ->relationship('lines')
                            ->label('Líneas')
                            ->schema([
                                Select::make('material_id')
                                    ->relationship('material', 'name')
                                    ->label('Material del catálogo')
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
                                    })
                                    ->createOptionForm([
                                        TextInput::make('name')->label('Nombre')->required(),
                                        TextInput::make('price')->label('Precio')->numeric()->required(),
                                    ]),
                                
                                TextInput::make('description')
                                    ->label('Concepto')
                                    ->placeholder('Descripción del concepto')
                                    ->required()
                                    ->maxLength(255)
                                    ->columnSpanFull(),
                                
                                TextInput::make('quantity')
                                    ->label('Cantidad')
                                    ->numeric()
                                    ->default(1)
                                    ->minValue(0.01)
                                    ->step(0.01)
                                    ->live()
                                    ->required(),
                                
                                TextInput::make('unit_price')
                                    ->label('Precio unitario (€)')
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
                            ])
                            ->columns(4)
                            ->createItemButtonLabel('➕ Añadir concepto')
                            ->reorderable()
                            ->required()
                            ->minItems(1),
                    ]),
                
                // === Resumen de totales ===
                Section::make('Resumen')
                    ->schema([
                        Placeholder::make('calculated_total')
                            ->label('Total con IVA')
                            ->content(function ($get) {
                                $lines = $get('lines') ?? [];
                                $subtotal = 0;
                                foreach ($lines as $line) {
                                    $subtotal += ($line['quantity'] ?? 0) * ($line['unit_price'] ?? 0);
                                }
                                $ivaPercent = $get('iva_percent') ?? 21;
                                $iva = $subtotal * ($ivaPercent / 100);
                                $total = $subtotal + $iva;
                                return number_format($total, 2, ',', '.') . ' €';
                            })
                            ->extraAttributes(['class' => 'text-2xl font-bold text-primary-600']),
                    ])->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('number')
                    ->label('Nº Presupuesto')
                    ->searchable()
                    ->copyable()
                    ->sortable()
                    ->toggleable(),
                
                TextColumn::make('client.name')
                    ->label('Cliente')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                
                TextColumn::make('date')
                    ->label('Fecha')
                    ->date('d/m/Y')
                    ->sortable()
                    ->toggleable(),
                
                TextColumn::make('status')
                    ->label('Estado')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'draft' => 'gray',
                        'sent' => 'info',
                        'approved' => 'success',
                        'rejected' => 'danger',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'draft' => '📝 Borrador',
                        'sent' => '📤 Enviado',
                        'approved' => '✅ Aprobado',
                        'rejected' => '❌ Rechazado',
                        default => $state,
                    }),
                
                TextColumn::make('total')
                    ->label('Total')
                    ->money('EUR')
                    ->sortable()
                    ->toggleable(),
                
                TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime('d/m/Y H:i')
                    ->toggleable(isToggledHiddenByDefault: true),

                IconColumn::make('is_public')
                    ->label('Público')
                    ->boolean()
                    ->trueIcon('heroicon-o-globe-alt')
                    ->falseIcon('heroicon-o-lock-closed')
                    ->trueColor('success')
                    ->falseColor('gray'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Filtrar por estado')
                    ->options([
                        'draft' => 'Borrador',
                        'sent' => 'Enviado',
                        'approved' => 'Aprobado',
                        'rejected' => 'Rechazado',
                    ]),
                Tables\Filters\Filter::make('date')
                    ->form([
                        DatePicker::make('created_from')->label('Desde'),
                        DatePicker::make('created_until')->label('Hasta'),
                    ])
                    ->query(function ($query, array $data): void {
                        $query
                            ->when(
                                $data['created_from'],
                                fn ($q, $date) => $q->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn ($q, $date) => $q->whereDate('created_at', '<=', $date),
                            );
                    }),
            ])
            ->headerActions([
    Tables\Actions\ExportAction::make()
        ->label('📊 Exportar Excel')
        ->color('success')
        ->icon('heroicon-m-arrow-down-tray')
        ->exporter(BudgetExporter::class)
        ,  // ← Exportación inmediata
])
            ->actions([
                // 🔗 Compartir Enlace Público
                Action::make('share_public')
                    ->label('🔗 Compartir Enlace')
                    ->icon('heroicon-m-share')
                    ->color('warning')
                    ->modalHeading('Compartir Presupuesto')
                    ->modalDescription('Genera un enlace público para que el cliente vea este presupuesto online.')
                    ->form([
                        \Filament\Forms\Components\Toggle::make('is_public')
                            ->label('Hacer público')
                            ->default(fn ($record) => $record->is_public ?? false)
                            ->helperText('Si activas esta opción, se generará un enlace único que cualquiera puede ver.'),
                    ])
                    ->action(function ($record, $data): void {
                        if ($data['is_public']) {
                            if (!$record->public_token) {
                                $record->generatePublicToken();
                            }
                        } else {
                            $record->update([
                                'is_public' => false,
                                'public_token' => null,
                            ]);
                        }
                    })
                    ->successNotificationTitle('✅ Enlace generado correctamente')
                    ->visible(fn ($record) => in_array($record->status, ['draft', 'sent'])),
                
                // 📧 Enviar por Email
                Action::make('send_email')
                    ->label('📧 Enviar por Email')
                    ->icon('heroicon-m-envelope')
                    ->color('info')
                    ->modalHeading('Enviar Presupuesto por Email')
                    ->modalDescription('El PDF del presupuesto se adjuntará automáticamente al email.')
                    ->form([
                        \Filament\Forms\Components\TextInput::make('email')
                            ->label('Email del cliente')
                            ->email()
                            ->required()
                            ->default(fn ($record) => $record->client->email ?? ''),
                        
                        \Filament\Forms\Components\TextInput::make('client_name')
                            ->label('Nombre del cliente')
                            ->required()
                            ->default(fn ($record) => $record->client->name ?? ''),
                        
                        \Filament\Forms\Components\Textarea::make('custom_message')
                            ->label('Mensaje adicional (opcional)')
                            ->placeholder('Escriba un mensaje personalizado para el cliente...')
                            ->rows(3),
                    ])
                    ->action(function ($record, $data): void {
                        \Illuminate\Support\Facades\Mail::to($data['email'])
                            ->send(new \App\Mail\BudgetEmail(
                                $record,
                                $data['email'],
                                $data['client_name'],
                                $data['custom_message'] ?? null
                            ));
                        
                        if ($record->status === 'draft') {
                            $record->update(['status' => 'sent']);
                        }
                    })
                    ->successNotificationTitle('✅ Email enviado correctamente')
                    ->failureNotificationTitle('❌ Error al enviar el email')
                    ->visible(fn ($record) => in_array($record->status, ['draft', 'sent', 'approved'])),

                // 🔄 Duplicar Presupuesto
                Action::make('duplicate')
                    ->label('🔄 Duplicar')
                    ->icon('heroicon-m-document-duplicate')
                    ->color('primary')
                    ->requiresConfirmation()
                    ->modalHeading('🔄 Duplicar Presupuesto')
                    ->modalDescription('¿Estás seguro de que quieres duplicar este presupuesto? Se creará una copia con un nuevo número.')
                    ->action(function ($record): void {
                        // Clonar el presupuesto
                        $newBudget = $record->replicate([
                            'number',
                            'created_at',
                            'updated_at',
                        ]);
                        
                        // Generar nuevo número
                        $newBudget->number = 'PRES-' . date('Y') . '-' . str_pad(random_int(1, 9999), 4, '0', STR_PAD_LEFT);
                        $newBudget->status = 'draft';
                        $newBudget->public_token = null;
                        $newBudget->is_public = false;
                        $newBudget->public_token_expires_at = null;
                        $newBudget->save();
                        
                        // Clonar las líneas del presupuesto
                        foreach ($record->lines as $line) {
                            $newLine = $line->replicate();
                            $newLine->budget_id = $newBudget->id;
                            $newLine->save();
                        }
                    })
                    ->successNotificationTitle('✅ Presupuesto duplicado correctamente')
                    ->failureNotificationTitle('❌ Error al duplicar el presupuesto')
                    ->visible(fn ($record) => true),
                
                // 📄 Descargar PDF
                Action::make('download_pdf')
                    ->label('📄 Descargar PDF')
                    ->icon('heroicon-m-arrow-down-tray')
                    ->color('success')
                    ->url(fn ($record) => route('budgets.pdf', $record))
                    ->openUrlInNewTab()
                    ->visible(fn ($record) => in_array($record->status, ['sent', 'approved'])),
                
                // ✏️ Editar y 🗑️ Eliminar
                EditAction::make()->label('Editar'),
                DeleteAction::make()->label('Eliminar'),
            ])
            ->emptyStateHeading('No hay presupuestos registrados')
            ->emptyStateDescription('Crea un nuevo presupuesto para comenzar')
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()
                    ->label('Nuevo presupuesto')
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
            'index' => Pages\ListBudgets::route('/'),
            'create' => Pages\CreateBudget::route('/create'),
            'edit' => Pages\EditBudget::route('/{record}/edit'),
        ];
    }

    public static function getNavigationLabel(): string { return 'Presupuestos'; }
    public static function getPluralModelLabel(): string { return 'Presupuestos'; }
    public static function getModelLabel(): string { return 'Presupuesto'; }
    public static function getNavigationGroup(): string { return 'Ventas'; }
    public static function getNavigationIcon(): string { return 'heroicon-o-document-text'; }
}