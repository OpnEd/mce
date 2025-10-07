<?php

namespace App\Filament\Resources\Quality\Records\Cleaning;

use App\Filament\Resources\Quality\Records\Cleaning\DesinfectantResource\Pages;
use App\Filament\Resources\Quality\Records\Cleaning\DesinfectantResource\RelationManagers;
use App\Models\Quality\Records\Cleaning\Desinfectant;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Clusters\Cleaning;

class DesinfectantResource extends Resource
{
    protected static ?string $model = Desinfectant::class;

    protected static ?string $cluster = Cleaning::class;
    //protected static ?string $heading = 'Órdenes de compra e Indicadores de Selección y Adquisición';
    protected static ?string $slug = 'desinfectantes'; // Cambiado de 'productos-faltantes'
    protected static ?string $pluralModelLabel = 'Desinfectantes';
    protected static ?string $modelLabel = 'Desinfectante';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label(__('fields.name'))
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('active_ingredient')
                    ->label(__('fields.active_ingredient'))
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('concentration')
                    ->label(__('fields.concentration'))
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('indications')
                    ->label(__('fields.indications'))
                    ->columnSpanFull(),
                Forms\Components\Select::make('level')
                    ->label(__('fields.level'))
                    ->options([
                        'alto' => 'Alto',
                        'medio' => 'Medio',
                        'bajo' => 'Bajo',
                    ])
                    ->default('medio')
                    ->required(),
                Forms\Components\TextInput::make('applicable_areas')
                    ->label(__('fields.applicable_areas')),
                Forms\Components\Toggle::make('active')
                    ->label(__('fields.active'))
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('fields.name'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('active_ingredient')
                    ->label(__('fields.active_ingredient'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('concentration')
                    ->label(__('fields.concentration'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('level')
                    ->label(__('fields.level'))
                    ->searchable(),
                Tables\Columns\IconColumn::make('active')
                    ->label(__('fields.active'))
                    ->boolean(),
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
            'index' => Pages\ListDesinfectants::route('/'),
            'create' => Pages\CreateDesinfectant::route('/create'),
            'edit' => Pages\EditDesinfectant::route('/{record}/edit'),
        ];
    }
}
