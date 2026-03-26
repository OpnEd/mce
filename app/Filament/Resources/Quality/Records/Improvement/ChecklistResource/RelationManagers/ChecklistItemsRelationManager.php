<?php

namespace App\Filament\Resources\Quality\Records\Improvement\ChecklistResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Enums\ActionsPosition;
use Filament\Tables\Table;

class ChecklistItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'checklistItems';

    protected static ?string $title = 'Items del plan';
    protected static ?string $label = 'Item';
    protected static ?string $pluralLabel = 'Items';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Hidden::make('team_id')
                    ->default(fn () => $this->ownerRecord?->team_id),
                Section::make('Detalle del item')
                    ->schema([
                        Forms\Components\Textarea::make('description')
                            ->label('Requerimiento')
                            ->required()
                            ->rows(3)
                            ->columnSpanFull(),
                        KeyValue::make('data')
                            ->label('Datos adicionales')
                            ->keyPlaceholder('Clave')
                            ->valuePlaceholder('Valor')
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('description')
                    ->label('Requerimiento')
                    ->searchable()
                    ->limit(100)
                    ->wrap(),
                Tables\Columns\TextColumn::make('answers_count')
                    ->label('Respuestas')
                    ->counts('answers')
                    ->badge(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Creado en')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Agregar item'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ], position: ActionsPosition::BeforeColumns)
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }
}
