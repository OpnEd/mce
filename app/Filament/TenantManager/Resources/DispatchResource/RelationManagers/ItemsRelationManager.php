<?php

namespace App\Filament\TenantManager\Resources\DispatchResource\RelationManagers;

use App\Models\CentralProductPrice;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Tables\Actions\ReplicateAction;
use Filament\Forms\Components\Select;
use App\Models\Stock;
use App\Models\Batch;

class ItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'items';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('batch_id')
                    ->relationship('batch', 'code')
                    ->searchable()
                    ->options(function (Get $get): array {
                        // Obtén el product_id vía purchaseItem
                        $purchaseItemId = $get('purchase_item_id');
                        if (! $purchaseItemId) {
                            return [];
                        }
                        $productId = \App\Models\PurchaseItem::find($purchaseItemId)?->product_id;
                        if (! $productId) {
                            return [];
                        }
                        // Consulta sólo stocks con quantity > 0
                        return Stock::query()
                            ->where('product_id', $productId)
                            ->where('quantity', '>', 0)
                            ->with('batch')
                            ->get()
                            ->pluck('batch.code', 'batch.id')
                            ->toArray();
                    })
                    ->required()
                    ->live(),
                Forms\Components\TextInput::make('quantity')
                    ->minValue(1)
                    ->maxValue(
                        fn(Get $get) => Stock::where([
                            ['batch_id', $get('batch_id')],
                            // puedes chequear product_id también si lo deseas
                        ])->value('quantity') ?? 0
                    )
                    ->helperText(
                        fn(Get $get) => 'Stock disponible: '
                            . (Stock::where('batch_id', $get('batch_id'))->value('quantity') ?? 0) . 'unidades...'
                    )
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('purchaseItem.product.name')
                    ->label('Producto')
                    ->searchable(),
                Tables\Columns\TextColumn::make('batch.code'),
                Tables\Columns\TextColumn::make('quantity'),
                Tables\Columns\TextInputColumn::make('price'),
                Tables\Columns\TextColumn::make('total')
                    ->label('Total')
                    ->getStateUsing(function ($record) {
                        return number_format(($record->quantity ?? 0) * ($record->price ?? 0), 2);
                    })
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    ReplicateAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ])
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
