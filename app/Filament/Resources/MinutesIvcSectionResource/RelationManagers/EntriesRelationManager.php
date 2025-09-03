<?php

namespace App\Filament\Resources\MinutesIvcSectionResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EntriesRelationManager extends RelationManager
{
    protected static string $relationship = 'entries';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('entry_id')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignorable: fn ($record) => $record)
                    ->disabled(),
                Forms\Components\Select::make('minutes_ivc_section_id')
                    ->relationship('minutesIvcSection', 'name')
                    ->required()
                    ->disabled(),
                Forms\Components\Toggle::make('apply')
                    ->required()
                    ->disabled(),
                Forms\Components\TextInput::make('entry_type')
                    ->required()
                    ->disabled(),
                Forms\Components\TextInput::make('criticality')
                    ->required()
                    ->disabled(),
                Forms\Components\Textarea::make('question')
                    ->required()
                    ->columnSpanFull()
                    ->disabled(),
                Forms\Components\Textarea::make('answer')
                    ->columnSpanFull(),
                Forms\Components\KeyValue::make('links')
                    ->disabled(),
                Forms\Components\Toggle::make('compliance')
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('entry_id')
                    ->searchable(),
                Tables\Columns\IconColumn::make('apply')
                    ->boolean(),
                Tables\Columns\TextColumn::make('criticality'),
                Tables\Columns\IconColumn::make('compliance')
                    ->boolean(),
                Tables\Columns\TextColumn::make('question')
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
}
