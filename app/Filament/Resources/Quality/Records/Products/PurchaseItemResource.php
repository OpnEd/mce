<?php

namespace App\Filament\Resources\Quality\Records\Products;

use App\Filament\Resources\Quality\Records\Products\PurchaseItemResource\Pages;
use App\Models\PurchaseItem;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PurchaseItemResource extends Resource
{
    protected static ?string $model = PurchaseItem::class;

    protected static ?string $navigationGroup = 'POS';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $tenantOwnershipRelationshipName = 'purchase';
    protected static ?string $slug = 'parangaricutirimicuarelamemte';

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('purchase_id')
                    ->relationship('purchase', 'id')
                    ->required(),
                Forms\Components\TextInput::make('product_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('quantity')
                    ->required()
                    ->numeric()
                    ->default(1),
                Forms\Components\TextInput::make('price')
                    ->required()
                    ->numeric()
                    ->default(0)
                    ->prefix('$'),
                Forms\Components\TextInput::make('total')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\Select::make('type')
                    ->label('Tipo de Faltante')
                    ->options([
                        'faltante_ordinario' => 'Faltante Ordinario',
                        'faltante_efectivo' => 'Faltante Efectivo',
                        'faltante_baja_rotacion' => 'Faltante Baja Rotacion',
                    ])
                    ->default('faltante_ordinario')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('purchase.id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('product_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('quantity')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('price')
                    ->money()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('type')
                    ->label('Tipo de Faltante')
                    ->sortable(),
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
            'index' => Pages\ListPurchaseItems::route('/'),
            'create' => Pages\CreatePurchaseItem::route('/create'),
            'edit' => Pages\EditPurchaseItem::route('/{record}/edit'),
        ];
    }
}
