<?php

namespace App\Filament\Resources\Quality\Records\Products;

use App\Filament\Resources\Quality\Records\Products\MissingProductResource\Pages;
use App\Models\Product;
use App\Models\Quality\Records\Products\MissingProduct;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Get;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Fieldset;
use Filament\Tables\Enums\ActionsPosition;

class MissingProductResource extends Resource
{
    protected static ?string $model = MissingProduct::class;

    protected static ?string $navigationGroup = 'Registros Diarios';
    protected static ?string $navigationLabel = 'Faltantes';
    protected static ?string $pluralModelLabel = 'Faltantes';
    protected static ?string $modelLabel = 'Faltante';
    protected static ?string $slug = 'faltantes';
    protected static ?string $tenantOwnershipRelationshipName = 'team';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Fieldset::make()
                    ->schema([
                        Forms\Components\Select::make('product_id')
                            ->label(__('fields.product'))
                            ->searchable()
                            ->relationship('product', 'name')
                            ->getSearchResultsUsing(
                                fn(string $search) => Product::withoutGlobalScopes()
                                    ->where('name', 'like', "%{$search}%")
                                    ->pluck('name', 'id')
                                    ->toArray()
                            )
                            ->required()
                            ->columnSpanFull(),

                        Forms\Components\Toggle::make('requested_by_user')
                            ->label('Solicitado por usuario')
                            ->helperText('A. Marca esta casilla solo si se trata de un producto de alta rotación (es decir, un producto "seleccionado") que ha sido solicitado por el usuario y no se encuentra en el inventario, es decir, le dijimos al usuario algo como "por el momento no tengo veci, para mañana se lo tengo"')
                            ->default(false)
                            ->live()
                            ->afterStateHydrated(function (bool $state, Get $get, Set $set): void {
                                if ($state) {
                                    $set('stock_status', MissingProduct::STOCK_STATUS_OUT_OF_STOCK);
                                    if ($get('is_selected') !== false) {
                                        $set('is_selected', true);
                                    }
                                }
                            })
                            ->afterStateUpdated(function (bool $state, Get $get, Set $set): void {
                                if ($state) {
                                    $set('stock_status', MissingProduct::STOCK_STATUS_OUT_OF_STOCK);
                                    if ($get('is_selected') !== false) {
                                        $set('is_selected', true);
                                    }
                                }
                            })
                            ->dehydratedWhenHidden()
                            ->visible(fn(Get $get): bool => $get('is_selected') !== false),

                        Forms\Components\Toggle::make('is_selected')
                            ->label('Seleccionado (listado basico)')
                            ->helperText('B. Desmarca esta casilla si se trata de un producto de baja rotación (es decir, un producto "no seleccionado", o sea que no hace parte del listado básico, porque que te lo piden muy poco y por esa razón no lo tienes), que ha sido solicitado por el usuario y le dijimos algo como "no lo manejamos veci, pero si quiere se lo traigo por encargo"')
                            ->default(true)
                            ->live()
                            ->afterStateHydrated(function (bool $state, Set $set): void {
                                if ($state === false) {
                                    $set('requested_by_user', true);
                                    $set('stock_status', MissingProduct::STOCK_STATUS_OUT_OF_STOCK);
                                }
                            })
                            ->afterStateUpdated(function (bool $state, Set $set): void {
                                if ($state === false) {
                                    $set('requested_by_user', true);
                                    $set('stock_status', MissingProduct::STOCK_STATUS_OUT_OF_STOCK);
                                }
                            })
                            ->dehydratedWhenHidden()
                            ->visible(fn(Get $get): bool => ! $get('requested_by_user') || $get('is_selected') === false),

                        Forms\Components\Select::make('stock_status')
                            ->label('Estado de existencias')
                            ->options(MissingProduct::getStockStatuses())
                            ->default(MissingProduct::STOCK_STATUS_UNKNOWN)
                            ->helperText('Especifica si el producto actualmente cuenta aún con existencias o no')
                            ->required()
                            ->dehydratedWhenHidden()
                            ->visible(fn(Get $get): bool => ! $get('requested_by_user') && $get('is_selected') !== false),

                        Forms\Components\Placeholder::make('missing_class')
                            ->label('Clase')
                            ->content(fn(?MissingProduct $record) => $record?->missing_class ?? '-'),

                        Forms\Components\Placeholder::make('purchase_item')
                            ->label('Orden de compra')
                            ->content(function (?MissingProduct $record) {
                                $code = $record?->purchaseItem?->purchase?->code;
                                return $code ?: 'Sin asignar';
                            }),
                    ]),
            ])
            ->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Fecha de registro')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                Tables\Columns\TextColumn::make('product.name')
                    ->label('Producto')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('missing_class')
                    ->label('Clase')
                    ->badge()
                    ->color(fn(?string $state) => $state === 'A' ? 'success' : ($state === 'B' ? 'warning' : 'gray')),
                Tables\Columns\IconColumn::make('is_selected')
                    ->label('Seleccionado')
                    ->boolean(),
                Tables\Columns\IconColumn::make('requested_by_user')
                    ->label('Solicitado')
                    ->boolean(),
                Tables\Columns\TextColumn::make('stock_status')
                    ->label('Stock')
                    ->formatStateUsing(fn(?string $state) => MissingProduct::getStockStatuses()[$state] ?? $state)
                    ->badge()
                    ->color(fn(?string $state) => match ($state) {
                        MissingProduct::STOCK_STATUS_IN_STOCK => 'success',
                        MissingProduct::STOCK_STATUS_OUT_OF_STOCK => 'danger',
                        MissingProduct::STOCK_STATUS_UNKNOWN => 'gray',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('purchaseItem.purchase.code')
                    ->label('Orden')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\Filter::make('open')
                    ->label('Sin asignar')
                    ->query(fn(Builder $query) => $query->whereNull('purchase_item_id')),
                Tables\Filters\SelectFilter::make('stock_status')
                    ->label('Stock')
                    ->options(MissingProduct::getStockStatuses()),
                Tables\Filters\TernaryFilter::make('is_selected')
                    ->label('Seleccionado'),
                Tables\Filters\TernaryFilter::make('requested_by_user')
                    ->label('Solicitado'),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ])
            ], position: ActionsPosition::BeforeColumns)
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMissingProducts::route('/'),
            'create' => Pages\CreateMissingProduct::route('/create'),
            'edit' => Pages\EditMissingProduct::route('/{record}/edit'),
        ];
    }
}
