<?php

namespace App\Filament\Resources\InvoiceResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'items';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('sale_item_id')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('sale_item_id')
            ->columns([
                Tables\Columns\TextColumn::make('sale_item.inventory.product.name')
                    ->label(__('Product Name'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('sale_item.inventory.batch.code')
                    ->label(__('Batch Code'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('sale_item.quantity')
                    ->label(__('Quantity')),
                Tables\Columns\TextColumn::make('sale_item.sale_price')
                    ->label(__('Sale Price'))
                    ->money()
                    ->sortable(),
                Tables\Columns\TextColumn::make('sale_item.total')
                    ->label('Total')
                    ->money()
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
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
}
