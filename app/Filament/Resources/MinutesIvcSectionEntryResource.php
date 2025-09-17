<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MinutesIvcSectionEntryResource\Pages;
use App\Filament\Resources\MinutesIvcSectionEntryResource\RelationManagers;
use App\Models\MinutesIvcSectionEntry;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MinutesIvcSectionEntryResource extends Resource
{
    protected static ?string $model = MinutesIvcSectionEntry::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('minutes_ivc_section_id')
                    ->relationship('minutesIvcSection', 'name')
                    ->required(),
                Forms\Components\Toggle::make('apply')
                    ->required(),
                Forms\Components\TextInput::make('entry_type')
                    ->required(),
                Forms\Components\TextInput::make('criticality')
                    ->required(),
                Forms\Components\Textarea::make('question')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('answer')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('links'),
                Forms\Components\Toggle::make('compliance')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('minutesIvcSection.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\IconColumn::make('apply')
                    ->boolean(),
                Tables\Columns\TextColumn::make('entry_id')
                    ->searchable(),
                Tables\Columns\TextColumn::make('entry_type'),
                Tables\Columns\TextColumn::make('criticality'),
                Tables\Columns\IconColumn::make('compliance')
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
            'index' => Pages\ListMinutesIvcSectionEntries::route('/'),
            'create' => Pages\CreateMinutesIvcSectionEntry::route('/create'),
            'edit' => Pages\EditMinutesIvcSectionEntry::route('/{record}/edit'),
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
