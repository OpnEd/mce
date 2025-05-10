<?php

namespace App\Filament\Resources\PurchaseResource\RelationManagers;

use App\Models\CentralProductPrice;
use App\Models\Purchase;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Illuminate\Support\Facades\DB;

class ItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'items';
    protected static ?string $model = Purchase::class;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('product_id')
                    ->relationship(name: 'product', titleAttribute: 'name')
                    ->searchable()
                    ->live()
                    ->required(),
                Forms\Components\TextInput::make('quantity')
                    ->required()
                    ->numeric()
                    ->default(1)
                    ->afterStateUpdated(function (?string $state, Set $set, Get $get) {
                        // Cálculo en tiempo real del total
                        $price = CentralProductPrice::find($get('product_id'))?->price ?? 0;
                        $set('price', $price);
                        $set('total', $state * $price);
                    })
                    ->live(),
                Forms\Components\TextInput::make('price')
                    ->readOnly()
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('total')
                    ->readOnly()
                    ->numeric()
                    ->default(0),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('product_id')
            ->columns([
                Tables\Columns\TextColumn::make('product.name'),
                Tables\Columns\TextColumn::make('quantity')
                    ->sortable()
                    ->numeric(),
                Tables\Columns\TextColumn::make('price')
                    ->sortable()
                    ->numeric()
                    ->prefix('$'),
                Tables\Columns\TextColumn::make('total')
                    ->sortable()
                    ->numeric()
                    ->prefix('$'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make()
                    ->visible(fn(): bool => $this->ownerRecord->status === 'pending')
                    ->after(function ($record) {
                        DB::transaction(function () use ($record) {
                            $record->purchase->updatePurchaseTotal();
                            $this->dispatch('purchaseTotalUpdated');
                        });
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->after(function ($record) {
                        DB::transaction(function () use ($record) {
                            $record->purchase->updatePurchaseTotal();
                            $this->dispatch('purchaseTotalUpdated');
                        });
                    }),
                Tables\Actions\DeleteAction::make()
                    ->after(function ($record) {
                        DB::transaction(function () use ($record) {
                            $record->purchase->updatePurchaseTotal();
                            $this->dispatch('purchaseTotalUpdated');
                        });
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
