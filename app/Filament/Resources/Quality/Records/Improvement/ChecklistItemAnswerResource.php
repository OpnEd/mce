<?php

namespace App\Filament\Resources\Quality\Records\Improvement;

use App\Filament\Resources\Quality\Records\Improvement\ChecklistItemAnswerResource\Pages;
use App\Filament\Resources\Quality\Records\Improvement\ChecklistItemAnswerResource\RelationManagers;
use App\Filament\Resources\Quality\Records\Improvement\ImprovementPlanResource;
use App\Enums\ImprovementPlanStatus;
use App\Models\Quality\Records\Improvement\ChecklistItemAnswer;
use App\Services\Quality\Records\Improvement\ImprovementPlanService;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Enums\ActionsPosition;
use Filament\Tables\Table;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ChecklistItemAnswerResource extends Resource
{
    protected static ?string $model = ChecklistItemAnswer::class;

    protected static bool $shouldRegisterNavigation = false;
    protected static ?string $pluralModelLabel = 'Respuestas de checklist';
    protected static ?string $modelLabel = 'Respuesta de item';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Contexto')
                    ->schema([
                        Forms\Components\Select::make('checklist_item_id')
                            ->label('Requerimiento')
                            ->relationship('checklistItem', 'description')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
                Section::make('Evaluacion')
                    ->schema([
                        Forms\Components\Toggle::make('apply')
                            ->label('Aplica')
                            ->default(true)
                            ->inline(false)
                            ->live()
                            ->afterStateUpdated(function (Set $set, bool $state): void {
                                if (! $state) {
                                    $set('meets', false);
                                }
                            })
                            ->required(),
                        Forms\Components\Toggle::make('meets')
                            ->label('Cumple')
                            ->default(true)
                            ->inline(false)
                            ->disabled(fn (Get $get): bool => ! $get('apply'))
                            ->dehydrated()
                            ->required(),
                    ])
                    ->columns(2),
                Section::make('Evidencias y observaciones')
                    ->schema([
                        FileUpload::make('evidence')
                            ->label('Evidencias')
                            ->multiple()
                            ->directory('quality/improvement/checklists')
                            ->disk('public')
                            ->downloadable()
                            ->openable()
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('observations')
                            ->label('Observaciones')
                            ->rows(4)
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('checklistItem.checklist.title')
                    ->label('Plan')
                    ->searchable()
                    ->sortable()
                    ->wrap(),
                Tables\Columns\TextColumn::make('checklistItem.description')
                    ->label('Requerimiento')
                    ->searchable()
                    ->limit(80)
                    ->wrap(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Auditor')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\IconColumn::make('meets')
                    ->label('Cumple')
                    ->boolean(),
                Tables\Columns\IconColumn::make('apply')
                    ->label('Aplica')
                    ->boolean(),
                Tables\Columns\TextColumn::make('team.name')
                    ->label('Equipo')
                    ->searchable()
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
                Tables\Columns\TextColumn::make('deleted_at')
                    ->label('Eliminado en')
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
                    Tables\Actions\Action::make('createImprovementPlan')
                        ->label('Crear plan de mejora')
                        ->icon('heroicon-o-clipboard-document-check')
                        ->color('danger')
                        ->visible(fn (ChecklistItemAnswer $record): bool => $record->apply && ! $record->meets && ! $record->improvementPlan)
                        ->action(function (ChecklistItemAnswer $record): void {
                            $plan = app(ImprovementPlanService::class)->createFromAnswer($record);

                            Notification::make()
                                ->title('Plan de mejora creado')
                                ->success()
                                ->send();

                            $this->redirect(ImprovementPlanResource::getUrl('edit', ['record' => $plan]));
                        }),
                    Tables\Actions\Action::make('closeImprovementPlan')
                        ->label('Cerrar plan')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->requiresConfirmation()
                        ->visible(function (ChecklistItemAnswer $record): bool {
                            if (! $record->improvementPlan) {
                                return false;
                            }

                            $status = $record->improvementPlan->status;
                            $statusValue = $status instanceof ImprovementPlanStatus ? $status->value : (string) $status;

                            return ! in_array($statusValue, [
                                ImprovementPlanStatus::Completed->value,
                                ImprovementPlanStatus::Canceled->value,
                            ], true);
                        })
                        ->action(function (ChecklistItemAnswer $record): void {
                            $plan = $record->improvementPlan;
                            if (! $plan) {
                                return;
                            }

                            $plan->status = ImprovementPlanStatus::Completed->value;
                            $plan->save();

                            Notification::make()
                                ->title('Plan cerrado')
                                ->success()
                                ->send();
                        }),
                    Tables\Actions\Action::make('cancelImprovementPlan')
                        ->label('Cancelar plan')
                        ->icon('heroicon-o-x-circle')
                        ->color('gray')
                        ->requiresConfirmation()
                        ->visible(function (ChecklistItemAnswer $record): bool {
                            if (! $record->improvementPlan) {
                                return false;
                            }

                            $status = $record->improvementPlan->status;
                            $statusValue = $status instanceof ImprovementPlanStatus ? $status->value : (string) $status;

                            return ! in_array($statusValue, [
                                ImprovementPlanStatus::Completed->value,
                                ImprovementPlanStatus::Canceled->value,
                            ], true);
                        })
                        ->action(function (ChecklistItemAnswer $record): void {
                            $plan = $record->improvementPlan;
                            if (! $plan) {
                                return;
                            }

                            $plan->status = ImprovementPlanStatus::Canceled->value;
                            $plan->save();

                            Notification::make()
                                ->title('Plan cancelado')
                                ->success()
                                ->send();
                        }),
                    Tables\Actions\Action::make('reopenImprovementPlan')
                        ->label('Reabrir plan')
                        ->icon('heroicon-o-arrow-path')
                        ->color('info')
                        ->requiresConfirmation()
                        ->visible(function (ChecklistItemAnswer $record): bool {
                            if (! $record->improvementPlan) {
                                return false;
                            }

                            $status = $record->improvementPlan->status;
                            $statusValue = $status instanceof ImprovementPlanStatus ? $status->value : (string) $status;

                            return in_array($statusValue, [
                                ImprovementPlanStatus::Completed->value,
                                ImprovementPlanStatus::Canceled->value,
                            ], true);
                        })
                        ->action(function (ChecklistItemAnswer $record): void {
                            $plan = $record->improvementPlan;
                            if (! $plan) {
                                return;
                            }

                            $plan->status = ImprovementPlanStatus::InProgressOnTime->value;
                            $plan->save();

                            Notification::make()
                                ->title('Plan reabierto')
                                ->success()
                                ->send();
                        }),
                    Tables\Actions\Action::make('viewImprovementPlan')
                        ->label('Ver plan de mejora')
                        ->icon('heroicon-o-clipboard-document-check')
                        ->color('primary')
                        ->visible(fn (ChecklistItemAnswer $record): bool => (bool) $record->improvementPlan)
                        ->url(fn (ChecklistItemAnswer $record): string => ImprovementPlanResource::getUrl('edit', ['record' => $record->improvementPlan]))
                        ->openUrlInNewTab(),
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
            'index' => Pages\ListChecklistItemAnswers::route('/'),
            'create' => Pages\CreateChecklistItemAnswer::route('/create'),
            'view' => Pages\ViewChecklistItemAnswer::route('/{record}'),
            'edit' => Pages\EditChecklistItemAnswer::route('/{record}/edit'),
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
