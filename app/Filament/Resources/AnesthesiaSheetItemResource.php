<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AnesthesiaSheetItemResource\Pages;
use App\Filament\Resources\AnesthesiaSheetItemResource\RelationManagers;
use App\Models\AnesthesiaSheetItem;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AnesthesiaSheetItemResource extends Resource
{
    protected static ?string $model = AnesthesiaSheetItem::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $tenantOwnershipRelationshipName = 'anesthesiaSheet';
    protected static ?string $slug = 'parangaricutirimicuarelamemte-anesthesia-sheet-items';

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('anesthesia_sheet_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('phase')
                    ->required()
                    ->maxLength(15),
                Forms\Components\TextInput::make('inventory_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('dose_per_kg')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('dose_measure')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('dose_measure_unit')
                    ->required()
                    ->maxLength(15),
                Forms\Components\TextInput::make('administration_route')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('anesthesia_sheet_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('phase')
                    ->searchable(),
                Tables\Columns\TextColumn::make('inventory_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('dose_per_kg')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('dose_measure')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('dose_measure_unit')
                    ->searchable(),
                Tables\Columns\TextColumn::make('administration_route'),
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
            'index' => Pages\ListAnesthesiaSheetItems::route('/'),
            'create' => Pages\CreateAnesthesiaSheetItem::route('/create'),
            'edit' => Pages\EditAnesthesiaSheetItem::route('/{record}/edit'),
        ];
    }
}
