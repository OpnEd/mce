<?php

namespace App\Filament\Resources\Quality\Records\Improvement;

use App\Filament\Resources\Quality\Records\Improvement\TaskResource\Pages;
use App\Filament\Resources\Quality\Records\Improvement\TaskResource\RelationManagers;
use App\Models\Quality\Records\Improvement\Task;
use App\Enums\TaskStatus;
use Filament\Forms;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Enums\ActionsPosition;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class TaskResource extends Resource
{
    protected static ?string $model = Task::class;

    protected static bool $shouldRegisterNavigation = false;
    protected static ?string $pluralModelLabel = 'Tareas';
    protected static ?string $modelLabel = 'Tarea';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Contexto')
                    ->schema([
                        Forms\Components\Select::make('improvement_plan_id')
                            ->label('Plan de mejora')
                            ->relationship('improvementPlan', 'title')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Forms\Components\Select::make('checklist_item_id')
                            ->label('Requerimiento')
                            ->relationship('checklistItem', 'description')
                            ->getOptionLabelFromRecordUsing(fn ($record): string => Str::limit($record->description, 60))
                            ->searchable()
                            ->preload()
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
                Section::make('Detalle de la tarea')
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

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('improvementPlan.title')
                    ->label('Plan de mejora')
                    ->searchable()
                    ->sortable()
                    ->wrap(),
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
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Actualizado en')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                ]),
            ], position: ActionsPosition::BeforeColumns)
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
            'index' => Pages\ListTasks::route('/'),
            'create' => Pages\CreateTask::route('/create'),
            'view' => Pages\ViewTask::route('/{record}'),
            'edit' => Pages\EditTask::route('/{record}/edit'),
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

