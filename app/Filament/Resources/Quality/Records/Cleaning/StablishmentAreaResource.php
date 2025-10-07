<?php

namespace App\Filament\Resources\Quality\Records\Cleaning;

use App\Filament\Clusters\Cleaning;
use App\Filament\Resources\Quality\Records\Cleaning\StablishmentAreaResource\Pages;
use App\Filament\Resources\Quality\Records\Cleaning\StablishmentAreaResource\RelationManagers;
use App\Models\Quality\Records\Cleaning\StablishmentArea;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Section;

class StablishmentAreaResource extends Resource
{
    protected static ?string $model = StablishmentArea::class;

    protected static ?string $cluster = Cleaning::class;

    protected static ?string $slug = 'areas-del-establecimiento'; // Cambiado de 'productos-faltantes'
    protected static ?string $pluralModelLabel = 'Áreas del establecimiento';
    protected static ?string $modelLabel = 'Área de establecimiento';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label(__('fields.name'))
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Select::make('type')
                            ->label(__('fields.type'))
                            ->options([
                                'critica' => 'Crítica',
                                'semicritica' => 'Semicrítica',
                                'bajo_riesgo' => 'Bajo riesgo',
                            ])
                            ->default('semicritica')
                            ->required(),
                        Forms\Components\Textarea::make('description')
                            ->label(__('fields.description'))
                            ->columnSpanFull(),
                        Forms\Components\Select::make('frequency')
                            ->label(__('fields.periodicity'))
                            ->options([
                                'diaria' => 'Diaria',
                                'semanal' => 'Semanal',
                                'quincenal' => 'Quincenal',
                                'mensual' => 'Mensual',
                            ])
                            ->default('diaria')
                            ->required(),
                        Forms\Components\Toggle::make('active')
                            ->label(__('fields.active'))
                            ->inline(false)
                            ->required(),
                    ])->columns(2)
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
                Tables\Columns\TextColumn::make('frequency')
                    ->label(__('fields.periodicity'))
                    ->sortable(),
                Tables\Columns\IconColumn::make('active')
                    ->label(__('fields.active'))
                    ->boolean(),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->label(__('fields.deleted_at'))
                    ->dateTime('Y-m-d')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('fields.created_at'))
                    ->dateTime('Y-m-d')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('fields.updated_at'))
                    ->dateTime('Y-m-d')
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
            'index' => Pages\ListStablishmentAreas::route('/'),
            'create' => Pages\CreateStablishmentArea::route('/create'),
            'edit' => Pages\EditStablishmentArea::route('/{record}/edit'),
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
