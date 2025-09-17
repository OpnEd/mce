<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MinutesIvcSectionResource\Pages;
use App\Filament\Resources\MinutesIvcSectionResource\RelationManagers;
use App\Models\MinutesIvcSection;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MinutesIvcSectionResource extends Resource
{
    protected static ?string $model = MinutesIvcSection::class;
    protected static ?string $navigationGroup = 'Settings';
    protected static ?string $navigationLabel = 'Acta de Inspección';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Detalles de Sección')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->disabled(),
                        Forms\Components\Textarea::make('description')
                            ->columnSpanFull()
                            ->disabled(),
                        Forms\Components\TextInput::make('slug')
                            ->required()
                            ->maxLength(255)
                            ->disabled(),
                        Forms\Components\TextInput::make('route')
                            ->maxLength(255)
                            ->disabled(),
                        Forms\Components\TextInput::make('order')
                            ->required()
                            ->numeric()
                            ->disabled(),
                        Forms\Components\TextInput::make('status')
                            ->required()
                            ->numeric()
                            ->default(1)
                            ->disabled(),  // ...
                    ])
                    ->collapsed()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('slug')
                    ->searchable(),
                Tables\Columns\TextColumn::make('route')
                    ->searchable(),
                Tables\Columns\TextColumn::make('order')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->numeric()
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
            RelationManagers\EntriesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMinutesIvcSections::route('/'),
            'create' => Pages\CreateMinutesIvcSection::route('/create'),
            'edit' => Pages\EditMinutesIvcSection::route('/{record}/edit'),
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
