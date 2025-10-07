<?php

namespace App\Filament\Resources\Quality\Records\Cleaning;

use App\Filament\Clusters\Cleaning;
use App\Filament\Resources\Quality\Records\Cleaning\CleaningImplementResource\Pages;
use App\Filament\Resources\Quality\Records\Cleaning\CleaningImplementResource\RelationManagers;
use App\Models\Quality\Records\Cleaning\CleaningImplement;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CleaningImplementResource extends Resource
{
    protected static ?string $model = CleaningImplement::class;

    protected static ?string $cluster = Cleaning::class;
    
    protected static ?string $slug = 'implementos-de-limpieza'; // Cambiado de 'productos-faltantes'
    protected static ?string $pluralModelLabel = 'Implementos de limpieza';
    protected static ?string $modelLabel = 'Implemento de limpieza';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label(__('fields.name'))
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('description')
                    ->label(__('fields.description'))
                    ->columnSpanFull(),
                Forms\Components\Select::make('type')
                    ->label(__('fields.type'))
                    ->options([
                        'desechable' => 'Desechable',
                        'reutilizable' => 'Reutilizable',
                    ])
                    ->default('reutilizable')
                    ->required(),
                Forms\Components\TextInput::make('areas_use')
                    ->label(__('fields.areas_use')),
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
                Tables\Columns\TextColumn::make('type')
                    ->label(__('fields.type'))
                    ->searchable(),
                Tables\Columns\IconColumn::make('active')
                    ->label(__('fields.active'))
                    ->boolean(),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
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
            'index' => Pages\ListCleaningImplements::route('/'),
            'create' => Pages\CreateCleaningImplement::route('/create'),
            'edit' => Pages\EditCleaningImplement::route('/{record}/edit'),
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
