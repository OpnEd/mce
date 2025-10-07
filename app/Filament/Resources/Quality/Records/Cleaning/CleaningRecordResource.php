<?php

namespace App\Filament\Resources\Quality\Records\Cleaning;

use App\Filament\Resources\Quality\Records\Cleaning\CleaningRecordResource\Pages;
use App\Filament\Resources\Quality\Records\Cleaning\CleaningRecordResource\RelationManagers;
use App\Models\Quality\Records\Cleaning\CleaningImplement;
use App\Models\Quality\Records\Cleaning\CleaningRecord;
use App\Models\Quality\Records\Cleaning\Desinfectant;
use App\Models\Quality\Records\Cleaning\StablishmentArea;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Support\Enums\FontWeight;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\HtmlString;


class CleaningRecordResource extends Resource
{
    protected static ?string $model = CleaningRecord::class;

    protected static ?string $navigationGroup = 'Registros Diarios';
    protected static ?string $slug = 'limpieza-y-sanitizacion';
    protected static ?string $pluralModelLabel = 'Limpieza - Sanitización';
    protected static ?string $modelLabel = 'Limpieza - Sanitización';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Información General')
                    ->schema([
                        // Alerta para registros existentes
                        Forms\Components\Placeholder::make('existing_records_alert')
                            ->label('')
                            ->content(function () {
                                $today = now()->toDateString();
                                $existingRecords = \App\Models\Quality\Records\Cleaning\CleaningRecord::whereDate('created_at', $today)->count();

                                if ($existingRecords > 0) {
                                    return new HtmlString(
                                        view('components.existing-records-alert', [
                                            'count' => $existingRecords,
                                            'date' => now()->format('d/m/Y')
                                        ])->render()
                                    );
                                }

                                return null;
                            })
                            ->visible(function () {
                                $today = now()->toDateString();
                                return \App\Models\Quality\Records\Cleaning\CleaningRecord::whereDate('created_at', $today)->exists();
                            }),

                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\Select::make('shift')
                                    ->label('Turno')
                                    ->options(\App\Models\Quality\Records\Cleaning\CleaningRecord::getShifts())
                                    ->default('dia_completo')
                                    ->required()
                                    ->live()
                                    ->afterStateUpdated(function ($state, $set) {
                                        // Sugerir horarios según el turno
                                        match ($state) {
                                            'mañana' => [
                                                $set('start_time', '06:00:00'),
                                                $set('end_time', '08:00:00')
                                            ],
                                            'tarde' => [
                                                $set('start_time', '14:00:00'),
                                                $set('end_time', '16:00:00')
                                            ],
                                            default => [
                                                $set('start_time', now()->format('H:i:s')),
                                                $set('end_time', null)
                                            ]
                                        };
                                    })
                                    ->helperText('Selecciona el turno correspondiente. Esto ayudará a organizar múltiples registros del mismo día.'),

                                Forms\Components\TimePicker::make('start_time')
                                    ->label(__('fields.start_time'))
                                    ->default(now())
                                    ->required(),

                                Forms\Components\TimePicker::make('end_time')
                                    ->label(__('fields.end_time')),
                            ]),


                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('reviewed_by')
                                    ->label(__('fields.reviewed_by'))
                                    ->maxLength(255),

                                Forms\Components\TextInput::make('shift_notes')
                                    ->label('Notas del Turno')
                                    ->placeholder('Ej: Limpieza especial por derrame, turno de refuerzo')
                                    ->maxLength(255),
                            ]),
                    ]),

                Forms\Components\Section::make('Detalle de Áreas Limpiadas y Sanitizadas')
                    ->schema([
                        Forms\Components\Repeater::make('cleaned_areas')
                            ->label(__('fields.cleaned_areas'))
                            ->schema([
                                Forms\Components\Select::make('area_id')
                                    ->label('Área')
                                    ->options(StablishmentArea::where('active', true)->pluck('name', 'id'))
                                    ->required()
                                    ->searchable(),

                                Forms\Components\Grid::make(3)
                                    ->schema([
                                        Forms\Components\Checkbox::make('floor')
                                            ->label(__('fields.floor'))
                                            ->default(true),

                                        Forms\Components\Checkbox::make('walls')
                                            ->label(__('fields.walls'))
                                            ->default(false),

                                        Forms\Components\Checkbox::make('ceiling')
                                            ->label(__('fields.ceiling'))
                                            ->default(false),
                                    ]),

                                Forms\Components\Grid::make(3)
                                    ->schema([
                                        Forms\Components\Checkbox::make('cleaned')
                                            ->label(__('fields.cleaned'))
                                            ->default(true),

                                        Forms\Components\Checkbox::make('desinfected')
                                            ->label(__('fields.desinfected'))
                                            ->default(true),

                                        Forms\Components\Checkbox::make('sanitized')
                                            ->label(__('fields.sanitized'))
                                            ->default(false),
                                    ]),

                                Forms\Components\Grid::make(3)
                                    ->schema([
                                        Forms\Components\Select::make('desinfectant_id')
                                            ->label(__('fields.desinfectant'))
                                            ->options(Desinfectant::where('active', true)->pluck('name', 'id'))
                                            ->required()
                                            ->searchable(),

                                        Forms\Components\TextInput::make('amount_used')
                                            ->label(__('fields.amount'))
                                            ->placeholder('Ej: 100ml, 2 sobres'),

                                        Forms\Components\TextInput::make('concentration')
                                            ->label(__('fields.concentration'))
                                            ->placeholder('Ej: 1:50, 70%'),
                                    ]),

                                Forms\Components\Select::make('implements_used')
                                    ->label(__('fields.implements_used'))
                                    ->options(CleaningImplement::where('active', true)->pluck('name', 'id'))
                                    ->multiple()
                                    ->searchable()
                                    ->columns(3),
                                Forms\Components\Textarea::make('area_observations')
                                    ->label('Observaciones del área')
                                    ->columnSpanFull()
                                    ->rows(2),
                                Forms\Components\Toggle::make('search_evidence_pests')
                                    ->label(__('fields.search_evidence_pests'))
                                    ->live()
                                    ->default(false),
                                Forms\Components\Select::make('search_evidence_pests_observations')
                                    ->label(__('De la búsqueda de plagas, se encontró:'))
                                    ->options([
                                        'Sí hay plagas' => 'Evidencia de presencia de plagas. Se inicia plan de acción.',
                                        'No hay plagas' => 'No hay evidencia de presencia de plagas.',
                                    ])
                                    ->hidden(fn(Get $get): bool => !$get('search_evidence_pests')),
                                Forms\Components\Select::make('status')
                                    ->label(__('fields.status'))
                                    ->default('completada')
                                    ->options([
                                        'en_proceso' => 'En Proceso',
                                        'completada' => 'Completada',
                                    ])
                                    ->required(),
                            ])
                            ->collapsible()
                            ->defaultItems(1)
                            ->addActionLabel('Agregar Área'),
                    ]),


                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\Textarea::make('observations')
                            ->label(__('fields.observations'))
                            ->helperText('Observaciones generales del proceso de limpieza y sanitización.')
                            ->columnSpanFull()
                            ->rows(3),
                    ]),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Fecha de Registro')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Realizado por')
                    ->searchable()
                    ->sortable()
                    ->weight(FontWeight::Bold),
                Tables\Columns\TextColumn::make('start_time')
                    ->label(__('fields.start_time'))
                    ->time('H:i')
                    ->sortable(),
                Tables\Columns\TextColumn::make('end_time')
                    ->label(__('fields.end_time'))
                    ->time('H:i')
                    ->sortable(),
                Tables\Columns\TextColumn::make('reviewed_by')
                    ->label(__('fields.reviewed_by'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('deleted_at')
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
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListCleaningRecords::route('/'),
            'create' => Pages\CreateCleaningRecord::route('/create'),
            'table' => Pages\CleaningTableView::route('/vista-detallada'),
            'edit' => Pages\EditCleaningRecord::route('/{record}/edit'),
            'view' => Pages\ViewCleaningRecord::route('/{record}'),
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
