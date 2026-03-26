<?php

namespace App\Filament\Resources\Quality\Records\Improvement\ImprovementPlanResource\RelationManagers;

use App\Enums\TaskStatus;
use Filament\Forms;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Enums\ActionsPosition;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class TasksRelationManager extends RelationManager
{
    protected static string $relationship = 'tasks';

    protected static ?string $title = 'Tareas';
    protected static ?string $label = 'Tarea';
    protected static ?string $pluralLabel = 'Tareas';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Hidden::make('team_id')
                    ->default(fn () => $this->ownerRecord?->team_id),
                Section::make('Asignacion')
                    ->schema([
                        Forms\Components\Select::make('checklist_item_id')
                            ->label('Requerimiento')
                            ->relationship('checklistItem', 'description')
                            ->getOptionLabelFromRecordUsing(fn ($record): string => Str::limit($record->description, 60))
                            ->searchable()
                            ->preload()
                            ->default(fn () => $this->ownerRecord?->checklistItemAnswer?->checklist_item_id)
                            ->required()
                            ->columnSpanFull(),
                        Forms\Components\Select::make('user_id')
                            ->label('Responsable')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                    ])
                    ->columns(2),
                Section::make('Detalle')
                    ->schema([
                        Forms\Components\Textarea::make('description')
                            ->label('Descripcion')
                            ->required()
                            ->rows(4)
                            ->columnSpanFull(),
                        KeyValue::make('causal_analysis')
                            ->label('Analisis causal')
                            ->keyPlaceholder('Causa')
                            ->valuePlaceholder('Detalle')
                            ->columnSpanFull(),
                    ]),
                Section::make('Seguimiento')
                    ->schema([
                        Forms\Components\DatePicker::make('ends_at')
                            ->label('Fecha limite'),
                        Forms\Components\Select::make('status')
                            ->label('Estado')
                            ->options(collect(TaskStatus::cases())->mapWithKeys(fn (TaskStatus $status) => [
                                $status->value => $status->label(),
                            ])->all())
                            ->default(TaskStatus::InProgress->value)
                            ->required(),
                    ])
                    ->columns(2),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('checklistItem.description')
                    ->label('Requerimiento')
                    ->searchable()
                    ->limit(60)
                    ->wrap(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Responsable')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('ends_at')
                    ->label('Fecha limite')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Estado')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => TaskStatus::tryFrom($state)?->label() ?? $state)
                    ->color(fn (string $state): string => match ($state) {
                        TaskStatus::InProgress->value => 'warning',
                        TaskStatus::Completed->value => 'success',
                        default => 'gray',
                    }),
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
                    ->label('Agregar tarea'),
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
