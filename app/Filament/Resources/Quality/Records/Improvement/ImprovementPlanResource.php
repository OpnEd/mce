<?php

namespace App\Filament\Resources\Quality\Records\Improvement;

use App\Filament\Resources\Quality\Records\Improvement\ImprovementPlanResource\Pages;
use App\Filament\Resources\Quality\Records\Improvement\ImprovementPlanResource\RelationManagers;
use App\Models\Quality\Records\Improvement\ImprovementPlan;
use App\Enums\ImprovementPlanStatus;
use App\Models\Process;
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

class ImprovementPlanResource extends Resource
{
    protected static ?string $model = ImprovementPlan::class;

    protected static ?int $navigationSort = 15;
    protected static ?string $navigationGroup = 'Plataforma Estratégica';
    protected static ?string $navigationLabel = 'Planes de mejora';
    protected static ?string $pluralModelLabel = 'Planes de mejora';
    protected static ?string $modelLabel = 'Plan de mejora';
    protected static ?string $recordTitleAttribute = 'title';
    protected static ?string $slug = 'plan-de-mejora';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informacion general')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label('Titulo del plan')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                        Forms\Components\DatePicker::make('ends_at')
                            ->label('Fecha limite')
                            ->required(),
                        Forms\Components\Select::make('status')
                            ->label('Estado')
                            ->options(ImprovementPlanStatus::options())
                            ->default(config('quality_improvement.default_status', ImprovementPlanStatus::Pending->value))
                            ->required(),
                    ])
                    ->columns(2),
                Section::make('Objetivo y descripcion')
                    ->schema([
                        Forms\Components\Textarea::make('objective')
                            ->label('Objetivo')
                            ->required()
                            ->rows(3)
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('descripcion')
                            ->label('Descripcion')
                            ->required()
                            ->rows(4)
                            ->columnSpanFull(),
                    ]),
                Section::make('Datos adicionales')
                    ->schema([
                        KeyValue::make('data')
                            ->label('Datos adicionales')
                            ->keyPlaceholder('Clave')
                            ->valuePlaceholder('Valor')
                            ->columnSpanFull(),
                    ])
                    ->collapsible(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Plan')
                    ->searchable()
                    ->sortable()
                    ->wrap(),
                Tables\Columns\TextColumn::make('ends_at')
                    ->label('Fecha limite')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Estado')
                    ->badge()
                    ->formatStateUsing(function ($state): string {
                        if ($state instanceof ImprovementPlanStatus) {
                            return $state->label();
                        }

                        return ImprovementPlanStatus::tryFrom((string) $state)?->label() ?? (string) $state;
                    })
                    ->color(function ($state): string {
                        if ($state instanceof ImprovementPlanStatus) {
                            return $state->color();
                        }

                        return ImprovementPlanStatus::tryFrom((string) $state)?->color() ?? 'gray';
                    })
                    ->searchable(),
                Tables\Columns\TextColumn::make('tasks_count')
                    ->label('Tareas')
                    ->counts('tasks')
                    ->badge(),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->label('Eliminado en')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
                Tables\Filters\SelectFilter::make('process_id')
                    ->label('Proceso')
                    ->options(function () {
                        $tenantId = \Filament\Facades\Filament::getTenant()?->id;
                        return Process::query()
                            ->when($tenantId, fn ($query) => $query->where('team_id', $tenantId))
                            ->orderBy('name')
                            ->pluck('name', 'id')
                            ->all();
                    })
                    ->query(function (Builder $query, array $data): Builder {
                        $value = $data['value'] ?? null;
                        if (! $value) {
                            return $query;
                        }

                        return $query->whereHas('checklistItemAnswer.checklistItem.checklist', function (Builder $sub) use ($value) {
                            $sub->where('process_id', $value);
                        });
                    }),
                Tables\Filters\SelectFilter::make('status')
                    ->label('Estado')
                    ->options(ImprovementPlanStatus::options()),
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\Action::make('descargar_pdf')
                        ->label('Descargar PDF')
                        ->icon('heroicon-m-arrow-down-tray')
                        ->color('success')
                        ->url(function (ImprovementPlan $record) {
                            $tenant = \Filament\Facades\Filament::getTenant();
                            return route('improvement.plan.pdf', [
                                'tenant' => $tenant?->id,
                                'plan' => $record,
                            ]);
                        })
                        ->openUrlInNewTab(),
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
            RelationManagers\TasksRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListImprovementPlans::route('/'),
            'create' => Pages\CreateImprovementPlan::route('/create'),
            'view' => Pages\ViewImprovementPlan::route('/{record}'),
            'edit' => Pages\EditImprovementPlan::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        ImprovementPlan::markOverdue();

        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
