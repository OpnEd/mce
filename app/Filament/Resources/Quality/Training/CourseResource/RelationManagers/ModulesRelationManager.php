<?php

namespace App\Filament\Resources\Quality\Training\CourseResource\RelationManagers;

use App\Models\Quality\Training\Module;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class ModulesRelationManager extends RelationManager
{
    protected static string $relationship = 'modules';

    protected static ?string $recordTitleAttribute = 'title';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->label('Título del Módulo')
                    ->required()
                    ->maxLength(255),

                Forms\Components\Textarea::make('description')
                    ->label('Descripción')
                    ->rows(3)
                    ->maxLength(1000),

                Forms\Components\TextInput::make('order')
                    ->label('Orden')
                    ->numeric()
                    ->required()
                    ->minValue(1),

                Forms\Components\Toggle::make('active')
                    ->label('Activo')
                    ->default(true),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                Tables\Columns\TextColumn::make('order')
                    ->label('Orden')
                    ->sortable(),

                Tables\Columns\TextColumn::make('title')
                    ->label('Título')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('description')
                    ->label('Descripción')
                    ->limit(50)
                    ->tooltip(fn (Module $record) => $record->description),

                Tables\Columns\IconColumn::make('active')
                    ->label('Activo')
                    ->boolean(),

                Tables\Columns\TextColumn::make('lessons_count')
                    ->counts('lessons')
                    ->label('Lecciones'),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('active')
                    ->label('Activo'),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('order');
    }
}
