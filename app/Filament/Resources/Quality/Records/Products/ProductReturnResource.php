<?php

namespace App\Filament\Resources\Quality\Records\Products;

use App\Filament\Resources\Quality\Records\Products\ProductReturnResource\Pages;
use App\Models\Quality\Records\Products\ProductReturn;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductReturnResource extends Resource
{
    protected static ?string $model = ProductReturn::class;

    protected static ?string $navigationGroup = 'Registros Diarios';
    protected static ?string $slug = 'devoluciones-proveedor';
    protected static ?string $pluralModelLabel = 'Devoluciones a Proveedor';
    protected static ?string $modelLabel = 'Devolucion a Proveedor';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Registro de devolucion')
                    ->schema([
                        Forms\Components\DateTimePicker::make('received_at')
                            ->label('Fecha de registro')
                            ->default(now())
                            ->required()
                            ->live()
                            ->afterStateUpdated(function ($state, callable $set, Get $get) {
                                $days = $get('response_time_limit_days');
                                if ($state && $days !== null && $days !== '') {
                                    $set('response_due_at', \Carbon\Carbon::parse($state)->addDays((int) $days));
                                }
                            }),
                        Forms\Components\Select::make('supplier_id')
                            ->label('Proveedor')
                            ->relationship('supplier', 'name')
                            ->searchable()
                            ->required(),
                        Forms\Components\Select::make('purchase_id')
                            ->label('Compra relacionada')
                            ->relationship('purchase', 'code')
                            ->searchable(),
                        Forms\Components\Select::make('type')
                            ->label('Tipo')
                            ->options(ProductReturn::getTypes())
                            ->required()
                            ->live()
                            ->afterStateUpdated(function ($state, callable $set, Get $get) {
                                $currentDays = $get('response_time_limit_days');
                                $defaultDays = ProductReturn::getDefaultResponseDaysByType($state);

                                if ($currentDays === null || $currentDays === '') {
                                    $set('response_time_limit_days', $defaultDays);
                                }

                                $receivedAt = $get('received_at');
                                if ($receivedAt && $defaultDays !== null) {
                                    $set('response_due_at', \Carbon\Carbon::parse($receivedAt)->addDays((int) ($currentDays ?: $defaultDays)));
                                }
                            }),
                        Forms\Components\Select::make('priority')
                            ->label('Prioridad')
                            ->options(ProductReturn::getPriorities())
                            ->default('media')
                            ->required(),
                        Forms\Components\Select::make('status')
                            ->label('Estado')
                            ->options(ProductReturn::getStatuses())
                            ->default('registrado')
                            ->required(),
                        Forms\Components\TextInput::make('response_time_limit_days')
                            ->label('Tiempo maximo de respuesta (dias)')
                            ->numeric()
                            ->minValue(0)
                            ->live()
                            ->afterStateUpdated(function ($state, callable $set, Get $get) {
                                $receivedAt = $get('received_at');
                                if ($receivedAt && $state !== null && $state !== '') {
                                    $set('response_due_at', \Carbon\Carbon::parse($receivedAt)->addDays((int) $state));
                                }
                            })
                            ->helperText('Define el limite interno para recibir respuesta del proveedor.'),
                        Forms\Components\DateTimePicker::make('response_due_at')
                            ->label('Fecha limite de respuesta')
                            ->helperText('Se calcula automaticamente si hay fecha y dias definidos.')
                            ->rule('after_or_equal:received_at'),
                        Forms\Components\TextInput::make('return_code')
                            ->label('Codigo de devolucion')
                            ->maxLength(100),
                        Forms\Components\TextInput::make('supplier_reference')
                            ->label('Referencia del proveedor')
                            ->maxLength(100),
                    ])
                    ->columns(3),

                Forms\Components\Section::make('Items devueltos')
                    ->schema([
                        Forms\Components\Repeater::make('items')
                            ->label('Detalle de items')
                            ->schema([
                                Forms\Components\TextInput::make('product_name')
                                    ->label('Producto')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('batch')
                                    ->label('Lote')
                                    ->maxLength(100),
                                Forms\Components\TextInput::make('quantity')
                                    ->label('Cantidad')
                                    ->numeric()
                                    ->minValue(1)
                                    ->required(),
                                Forms\Components\TextInput::make('reason')
                                    ->label('Motivo')
                                    ->maxLength(255),
                            ])
                            ->columns(2)
                            ->defaultItems(1)
                            ->addActionLabel('Agregar item'),
                    ]),

                Forms\Components\Section::make('Detalle')
                    ->schema([
                        Forms\Components\Textarea::make('reason')
                            ->label('Motivo general')
                            ->rows(3)
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('observations')
                            ->label('Observaciones')
                            ->rows(3)
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('total_items')
                            ->label('Total de items')
                            ->numeric()
                            ->minValue(0),
                        Forms\Components\TextInput::make('total_value')
                            ->label('Valor total')
                            ->numeric()
                            ->minValue(0),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Respuesta del proveedor')
                    ->schema([
                        Forms\Components\Textarea::make('supplier_response')
                            ->label('Respuesta')
                            ->rows(3)
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('authorization_code')
                            ->label('Codigo de autorizacion')
                            ->maxLength(100),
                        Forms\Components\TextInput::make('credit_note_number')
                            ->label('Numero de nota credito')
                            ->maxLength(100),
                        Forms\Components\DateTimePicker::make('responded_at')
                            ->label('Fecha de respuesta')
                            ->rule('after_or_equal:received_at'),
                        Forms\Components\DateTimePicker::make('closed_at')
                            ->label('Fecha de cierre')
                            ->rule('after_or_equal:received_at'),
                        Forms\Components\Toggle::make('requires_follow_up')
                            ->label('Requiere seguimiento')
                            ->default(false),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('received_at')
                    ->label('Registro')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('supplier.name')
                    ->label('Proveedor')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('type')
                    ->label('Tipo')
                    ->badge()
                    ->formatStateUsing(fn (?string $state) => ProductReturn::getTypes()[$state] ?? $state),
                Tables\Columns\TextColumn::make('status')
                    ->label('Estado')
                    ->badge()
                    ->formatStateUsing(fn (?string $state) => ProductReturn::getStatuses()[$state] ?? $state)
                    ->color(fn (?string $state): string => match ($state) {
                        'registrado' => 'gray',
                        'enviado' => 'info',
                        'en_revision' => 'warning',
                        'aprobado' => 'success',
                        'rechazado' => 'danger',
                        'cerrado' => 'success',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('priority')
                    ->label('Prioridad')
                    ->badge()
                    ->formatStateUsing(fn (?string $state) => ProductReturn::getPriorities()[$state] ?? $state)
                    ->color(fn (?string $state): string => match ($state) {
                        'baja' => 'gray',
                        'media' => 'warning',
                        'alta' => 'danger',
                        'critica' => 'danger',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('return_code')
                    ->label('Codigo')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('response_due_at')
                    ->label('Fecha limite')
                    ->dateTime()
                    ->color(fn (ProductReturn $record) => $record->is_overdue ? 'danger' : null)
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\IconColumn::make('is_overdue')
                    ->label('Vencida')
                    ->boolean()
                    ->trueColor('danger')
                    ->falseColor('gray')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('total_items')
                    ->label('Items')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('total_value')
                    ->label('Valor')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->label('Tipo')
                    ->options(ProductReturn::getTypes()),
                Tables\Filters\SelectFilter::make('status')
                    ->label('Estado')
                    ->options(ProductReturn::getStatuses()),
                Tables\Filters\SelectFilter::make('priority')
                    ->label('Prioridad')
                    ->options(ProductReturn::getPriorities()),
                Tables\Filters\SelectFilter::make('supplier_id')
                    ->label('Proveedor')
                    ->relationship('supplier', 'name'),
                Tables\Filters\TrashedFilter::make(),
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
            'index' => Pages\ListProductReturns::route('/'),
            'create' => Pages\CreateProductReturn::route('/create'),
            'edit' => Pages\EditProductReturn::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
