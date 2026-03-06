<?php

namespace App\Filament\Resources\Quality\ScheduleResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;
use Filament\Facades\Filament;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Tables\Enums\ActionsPosition;

class EventsRelationManager extends RelationManager
{
    protected static string $relationship = 'events';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Fieldset::make('')
                    ->schema([
                        TextInput::make('title')
                            ->label(__('Nombre del evento'))
                            ->required()
                            ->maxLength(255),

                        Select::make('type')
                            ->label(__('Tipo de evento / fecha'))
                            ->options([
                                'event' => __('Evento'),
                                'task' => __('Tarea'),
                                'milestone' => __('Hito'),
                                'training_session' => __('Sesion de entrenamiento')
                            ])
                            ->default('event')
                            ->required()
                            ->placeholder(__('Selecciona el tipo de fecha')),
                    ]),

                Fieldset::make('')
                    ->schema([
                        Select::make('role_id')
                            ->label(__('Role'))
                            ->relationship(
                                name: 'role',
                                titleAttribute: 'name',
                                modifyQueryUsing: function (Builder $query) {
                                    $tenant = Filament::getTenant();

                                    // Verificar existencia del tenant
                                    if (!$tenant) {
                                        throw new \Exception('Team no definido en este contexto');
                                    }

                                    $query->whereNotNull('team_id')
                                        ->where('team_id', $tenant->id);
                                }
                            )
                            ->preload()
                            ->searchable()
                            ->required()
                            ->placeholder(__('Select Role')),

                        Toggle::make('has_time')
                            ->label(__('Has Time?'))
                            ->default(false)
                            ->live()
                            ->afterStateUpdated(function (Set $set, $state) {
                                if (!$state) {
                                    $set('start_time', null);
                                    $set('end_time', null);
                                }
                            })
                            ->inline(false),
                    ]),
                Fieldset::make('')
                    ->schema([
                        Textarea::make('description')
                            ->label(__('Description'))
                            ->columnSpanFull()
                            ->required(),

                        DatePicker::make('start_date')
                            ->label(__('Start Date'))
                            ->required(),

                        TimePicker::make('start_time')
                            ->label(__('Start Time'))
                            ->disabled(fn(Get $get) => !$get('has_time'))
                            ->required(fn(Get $get) => $get('has_time'))
                            ->dehydrated()
                            ->seconds(false),

                        DatePicker::make('end_date')
                            ->label(__('End Date'))
                            ->required(),

                        TimePicker::make('end_time')
                            ->label(__('End Time'))
                            ->disabled(fn(Get $get) => !$get('has_time'))
                            ->required(fn(Get $get) => $get('has_time'))
                            ->dehydrated()
                            ->seconds(false),
                    ]),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->heading('Fechas o eventos de este cronograma')
            ->columns([
                TextColumn::make('title')
                    ->label(__('Nombre del evento'))
                    ->searchable(),
                TextColumn::make('start_date')
                    ->label(__('Fecha de inicio'))
                    ->date('d/m/Y')
                    ->sortable(),
                TextColumn::make('end_date')
                    ->label(__('Fecha de finalización'))
                    ->date('d/m/Y'),
                IconColumn::make('has_time')
                    ->label(__('Tiene hora fijada'))
                    ->boolean(),
                TextColumn::make('start_time')
                    ->label(__('Start Time'))
                    ->dateTime('H:i'),
                TextColumn::make('end_time')
                    ->label(__('End Time'))
                    ->dateTime('H:i'),
                ToggleColumn::make('done')
                    ->label(__('Realizado')),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label(__('Registrar evento'))
                    ->icon('phosphor-plus'),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ]),
            ], position: ActionsPosition::BeforeColumns)
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
