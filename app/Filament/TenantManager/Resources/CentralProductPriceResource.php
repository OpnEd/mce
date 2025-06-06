<?php

namespace App\Filament\TenantManager\Resources;

use App\Filament\TenantManager\Resources\CentralProductPriceResource\Pages;
use App\Filament\TenantManager\Resources\CentralProductPriceResource\RelationManagers;
use App\Models\CentralProductPrice;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CentralProductPriceResource extends Resource
{
    protected static ?string $model = CentralProductPrice::class;

    protected static ?string $navigationGroup = 'Products';
    protected static ?string $navigationIcon = 'phosphor-currency-dollar-simple';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('product_id')
                    ->relationship(name: 'product', titleAttribute: 'code')
                    ->searchable()
                    ->preload(),
                Forms\Components\TextInput::make('min')
                    ->required()
                    ->numeric()
                    ->default(0.00),
                Forms\Components\TextInput::make('price')
                    ->required()
                    ->numeric()
                    ->default(0.00)
                    ->prefix('$'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('product.code')
                    ->label('Code')
                    ->searchable(),
                Tables\Columns\TextColumn::make('product.name')
                    ->label('Name')
                    ->searchable(),
                Tables\Columns\TextInputColumn::make('min'),
                Tables\Columns\TextInputColumn::make('price'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
            'index' => Pages\ListCentralProductPrices::route('/'),
            'create' => Pages\CreateCentralProductPrice::route('/create'),
            'edit' => Pages\EditCentralProductPrice::route('/{record}/edit'),
        ];
    }
}
