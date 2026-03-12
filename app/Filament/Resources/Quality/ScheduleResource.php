<?php

namespace App\Filament\Resources\Quality;

use App\Filament\Resources\Quality\ScheduleResource\Pages;
use App\Filament\Resources\Quality\ScheduleResource\RelationManagers;
use App\Models\Schedule;
use Filament\Forms;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Enums\ActionsPosition;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ScheduleResource extends Resource
{
    protected static ?string $model = Schedule::class;

    protected static ?string $navigationGroup = 'Plataforma Estratégica';
    protected static ?string $pluralModelLabel = 'Cronogramas';
    protected static ?string $modelLabel = 'Cronograma';
    protected static ?string $slug = 'cronogramas';
    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Fieldset::make('')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nombre')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('objective')
                            ->label('Objetivo')
                            ->required(),
                        Forms\Components\Textarea::make('description')
                            ->label('Descripción')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                    ]),
                Fieldset::make('Fechas')
                    ->schema([
                        Forms\Components\DatePicker::make('starts_at')
                            ->label('Fecha de inicio')
                            ->required(),
                        Forms\Components\DatePicker::make('ends_at')
                            ->label('Fecha de finalización')
                            ->required(),
                    ]),
                Fieldset::make('')
                    ->schema([
                        Forms\Components\ColorPicker::make('color')
                            ->default('#000000')
                            ->required(),
                        Forms\Components\TextInput::make('icon')
                            ->label('Icono'),
                    ]),
                Fieldset::make('Estado del cronograma')
                    ->schema([
                        Forms\Components\Checkbox::make('is_cancelled')
                            ->label('Cancelado')
                            ->default(false),
                        Forms\Components\Checkbox::make('is_rescheduled')
                            ->label('Reprogramado')
                            ->default(false),
                        Forms\Components\Checkbox::make('is_completed')
                            ->label('Completado')
                            ->default(false),
                        Forms\Components\Checkbox::make('is_in_progress')
                            ->label('En progreso')
                            ->default(true),
                    ]),
            ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nombre')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('starts_at')
                    ->label('Fecha de inicio')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                Tables\Columns\TextColumn::make('ends_at')
                    ->label('Fecha de finalización')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                Tables\Columns\ColorColumn::make('color'),
                Tables\Columns\CheckboxColumn::make('is_cancelled')
                    ->label('Cancelado')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\CheckboxColumn::make('is_rescheduled')
                    ->label('Reprogramado')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\CheckboxColumn::make('is_completed')
                    ->label('Completado')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\CheckboxColumn::make('is_in_progress')
                    ->label('En progreso')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])->filters([
                //
            ])->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ])
            ], position: ActionsPosition::BeforeColumns)
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\EventsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSchedules::route('/'),
            'create' => Pages\CreateSchedule::route('/create'),
            'edit' => Pages\EditSchedule::route('/{record}/edit'),
        ];
    }
}
