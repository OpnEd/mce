<?php

namespace App\Filament\Pages;

use App\Models\Event;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Actions\Contracts\HasActions;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Facades\Filament;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;

class Events extends Page implements HasTable, HasActions, HasForms
{
    use InteractsWithTable, InteractsWithForms, InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.events';

    public $events;

    public function createAction(): Action
    {
        return CreateAction::make('create')
            ->model(Event::class)
            ->createAnother(false)
            ->label(__('Create Event'))
            ->form(function (Form $form, array $arguments) {
                return $form
                    ->schema([
                        TextInput::make('title')
                            ->label(__('Event Title'))
                            ->required()
                            ->maxLength(255),
                        Select::make('schedule_id')
                            ->label(__('Schedule'))
                            ->relationship('schedule', 'name')
                            ->preload()
                            ->searchable()
                            ->placeholder(__('Select Schedule')),
                        Select::make('type')
                            ->label(__('Event Type'))
                            ->options([
                                'event' => __('Event'),
                                'task' => __('Task'),
                                'milestone' => __('Milestone'),
                            ])
                            ->default('event')
                            ->required()
                            ->placeholder(__('Select Event Type')),
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
                            }),
                        Textarea::make('description')
                            ->label(__('Description')),
                        DatePicker::make('start_date')
                            ->label(__('Start Date'))
                            ->required()
                            ->default(function () use ($arguments) {
                                if (!empty($arguments['startDate'])) {
                                    return Carbon::parse($arguments['startDate']);
                                } else {
                                    return Carbon::now();
                                }
                            }),
                        DatePicker::make('end_date')
                            ->label(__('End Date'))
                            ->required()
                            ->default(function () use ($arguments) {
                                if (!empty($arguments['endDate'])) {
                                    return Carbon::parse($arguments['endDate'])->subDay();
                                } else {
                                    return Carbon::now();
                                }
                            }),
                        TimePicker::make('start_time')
                            ->label(__('Start Time'))
                            ->disabled(fn(Get $get) => !$get('has_time'))
                            ->required(fn(Get $get) => $get('has_time'))
                            ->dehydrated()
                            ->seconds(false),
                        TimePicker::make('end_time')
                            ->label(__('End Time'))
                            ->disabled(fn(Get $get) => !$get('has_time'))
                            ->required(fn(Get $get) => $get('has_time'))
                            ->dehydrated()
                            ->seconds(false),
                    ]);
            })
            ->successNotification(function () {
                Notification::make()
                    ->title(__('Success'))
                    ->body(__('Event created successfully!'))
                    ->success()
                    ->send();
            })
            ->failureNotification(function () {
                Notification::make()
                    ->title(__('Error'))
                    ->body(__('Failed to create event. Please try again.'))
                    ->danger()
                    ->send();
            })
            ->after(function () {
                $this->dispatch('refresh-calendar')->self();
            });
    }

    public function editAction(): Action
    {
        return EditAction::make('edit')
            ->record(function (array $arguments) {
                return Event::query()->where('id', $arguments['id'])->first();
            })
            ->form(function (Form $form, array $arguments) {
                return $form
                    ->schema([
                        TextInput::make('title')
                            ->label(__('Event Title'))
                            ->required()
                            ->maxLength(255),
                        Select::make('schedule_id')
                            ->label(__('Schedule'))
                            ->relationship('schedule', 'name')
                            ->preload()
                            ->searchable()
                            ->placeholder(__('Select Schedule')),
                        Select::make('type')
                            ->label(__('Event Type'))
                            ->options([
                                'event' => __('Event'),
                                'task' => __('Task'),
                                'milestone' => __('Milestone'),
                            ])
                            ->default('event')
                            ->required()
                            ->placeholder(__('Select Event Type')),
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
                            }),
                        Textarea::make('description')
                            ->label(__('Description')),
                        DatePicker::make('start_date')
                            ->label(__('Start Date'))
                            ->required()
                            ->default(function () use ($arguments) {
                                if (!empty($arguments['startDate'])) {
                                    return Carbon::parse($arguments['startDate']);
                                } else {
                                    return Carbon::now();
                                }
                            }),
                        DatePicker::make('end_date')
                            ->label(__('End Date'))
                            ->required()
                            ->default(function () use ($arguments) {
                                if (!empty($arguments['endDate'])) {
                                    return Carbon::parse($arguments['endDate'])->subDay();
                                } else {
                                    return Carbon::now();
                                }
                            }),
                        TimePicker::make('start_time')
                            ->label(__('Start Time'))
                            ->disabled(fn(Get $get) => !$get('has_time'))
                            ->required(fn(Get $get) => $get('has_time'))
                            ->dehydrated()
                            ->seconds(false),
                        TimePicker::make('end_time')
                            ->label(__('End Time'))
                            ->disabled(fn(Get $get) => !$get('has_time'))
                            ->required(fn(Get $get) => $get('has_time'))
                            ->dehydrated()
                            ->seconds(false),
                    ]);
            })
            ->successNotification(function () {
                Notification::make()
                    ->title(__('Success'))
                    ->body(__('Event updated successfully!'))
                    ->success()
                    ->send();
            })
            ->failureNotification(function () {
                Notification::make()
                    ->title(__('Error'))
                    ->body(__('Failed to update event. Please try again.'))
                    ->danger()
                    ->send();
            })
            ->after(function () {
                $this->dispatch('refresh-calendar')->self();
            });
    }

    public function deleteAction(): Action
    {
        return DeleteAction::make('delete')
            ->record(function (array $arguments) {
                return Event::query()->where('id', $arguments['id'])->first();
            })
            ->after(function () {
                $this->dispatch('refresh-calendar')->self();
            });
    }

    public function viewAction(): Action
    {
        return ViewAction::make('view')
            ->record(function (array $arguments) {
                return Event::query()->where('id', $arguments['id'])->first();
            })
            ->infolist(function (Infolist $infolist) {
                return $infolist
                    ->schema([
                        Section::make()
                            ->columns(2)
                            ->schema([
                                TextEntry::make('title')
                                    ->label(__('Event Title'))
                                    ->columnSpanFull(),
                                TextEntry::make('type')
                                    ->label(__('Event Type')),
                                TextEntry::make('description')
                                    ->label(__('Description')),
                                TextEntry::make('role.name')
                                    ->label(__('Responsible')),
                                TextEntry::make('start_date')
                                    ->label(__('Start Date'))
                                    ->date('d/m/Y'),
                                TextEntry::make('end_date')
                                    ->label(__('End Date'))
                                    ->date('d/m/Y'),
                                IconEntry::make('has_time')
                                    ->boolean(),
                                TextEntry::make('start_time')
                                    ->label(__('Start Time'))
                                    ->dateTime('H:i'),
                                TextEntry::make('end_time')
                                    ->label(__('End Time'))
                                    ->dateTime('H:i'),
                                IconEntry::make('done')
                                    ->label(__('Done')),
                            ]),
                    ]);
            })
            ->modalHeading(__('Event Details'))
            ->extraModalFooterActions(function (array $arguments) {
                return [
                    Action::make('edit')
                        ->action(function () use ($arguments) {
                            $this->replaceMountedAction('edit', ['id' => $arguments['id']]);
                        }),
                    Action::make('delete')
                        ->color('danger')
                        ->action(function () use ($arguments) {
                            $this->replaceMountedAction('delete', ['id' => $arguments['id']]);
                        }),
                ];
            });
    }

    public function droppedEvent(): Action
    {
        return Action::make('droppedEvent')
            ->action(function (array $arguments) {

                $id = $arguments['id'];
                $event = Event::query()->where('id', $id)->first();

                // No permitir si el evento está marcado como hecho
                if ($event->done) {
                    Notification::make()
                        ->title(__('Error'))
                        ->body(__('Cannot update a completed event.'))
                        ->danger()
                        ->send();
                    $this->dispatch('refresh-calendar')->self();
                    return;
                }

                // Case 1. Check if time is present
                if (preg_match('#T#', $arguments['startDate']) || preg_match('#T#', $arguments['endDate'])) {
                    // If time is present, don't subtract a day from end date
                    $startDate = Carbon::parse($arguments['startDate'])->format('Y-m-d');
                    $endDate = Carbon::parse($arguments['endDate'])->format('Y-m-d');
                } else {
                    // Case 2. Check if time is not present
                    $startDate = Carbon::parse($arguments['startDate'])->format('Y-m-d');
                    $endDate = Carbon::parse($arguments['endDate'])->subDay()->format('Y-m-d');
                }

                // No permitir mover el evento a una fecha anterior a hoy
                if (Carbon::parse($startDate)->lt(Carbon::today())) {
                    Notification::make()
                        ->title(__('Error'))
                        ->body(__('Cannot move event to a past date.'))
                        ->danger()
                        ->send();
                    $this->dispatch('refresh-calendar')->self();
                    return;
                }

                $if_updated = $event->update([
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                ]);

                if ($if_updated) {
                    Notification::make()
                        ->title(__('Success'))
                        ->body(__('Event updated successfully!'))
                        ->success()
                        ->send();
                } else {
                    Notification::make()
                        ->title(__('Error'))
                        ->body(__('Failed to update event. Please try again.'))
                        ->danger()
                        ->send();
                }

                $this->dispatch('refresh-calendar')->self();
            });
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(Event::query())
            ->columns([
                TextColumn::make('title')
                    ->label(__('Event Title'))
                    ->searchable(),
                TextColumn::make('start_date')
                    ->label(__('Start Date'))
                    ->date('d/m/Y')
                    ->sortable(),
                TextColumn::make('end_date')
                    ->label(__('End Date'))
                    ->date('d/m/Y'),
                IconColumn::make('has_time')
                    ->boolean(),
                TextColumn::make('start_time')
                    ->label(__('Start Time'))
                    ->dateTime('H:i'),
                TextColumn::make('end_time')
                    ->label(__('End Time'))
                    ->dateTime('H:i'),
                ToggleColumn::make('done')
                    ->label(__('Done')),
            ])
            ->filters([
                // Define your filters here
            ])
            ->actions([
                // Define your actions here
            ])
            ->bulkActions([
                // Define your bulk actions here
            ]);
    }

    public function render(): View
    {
        $events = Event::query()
            ->where('team_id', Filament::getTenant()->id)
            ->get();

        $arr = [];

        foreach ($events as $event) {

            // case 1. If start & end is present (not null)
            if (!empty($event->start_time) && !empty($event->end_time)) {

                $start_date = Carbon::parse($event->start_date)->format('Y-m-d');
                $end_date = Carbon::parse($event->end_date)->format('Y-m-d');
                $start_time = Carbon::parse($event->start_time)->format('H:i');
                $end_time = Carbon::parse($event->end_time)->format('H:i');
                $start_date_time = $start_date . 'T' . $start_time;
                $end_date_time = $end_date . 'T' . $end_time;
            } else {

                // case 2. If start & end time is not present (null)
                $start_date = Carbon::parse($event->start_date)->format('Y-m-d');
                $end_date = Carbon::parse($event->end_date)->addDay()->format('Y-m-d');
                $start_date_time = $start_date;
                $end_date_time = $end_date;
            }

            $arr[] = [
                'id' => $event->id,
                'title' => $event->title,
                'start' => $start_date_time,
                'end' => $end_date_time,
                'type' => $event->type,
                'description' => $event->description,
                'done' => $event->done ? true : false,
            ];

            $this->events = json_encode($arr);
        }

        return parent::render();
    }
}
