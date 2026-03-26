<?php

namespace App\Filament\Resources\Quality\Records\Clients;

use App\Filament\Resources\Quality\Records\Clients\ClientPqrsRecordResource\Pages;
use App\Models\Quality\Records\Clients\ClientPqrsRecord;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Enums\ActionsPosition;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ClientPqrsRecordResource extends Resource
{
    protected static ?string $model = ClientPqrsRecord::class;

    protected static ?string $navigationGroup = 'Registros Diarios';
    protected static ?string $slug = 'pqrs';
    protected static ?string $pluralModelLabel = 'PQRS';
    protected static ?string $modelLabel = 'PQRS';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Registro PQRS')
                    ->schema([
                        Forms\Components\DateTimePicker::make('received_at')
                            ->label('Fecha de recepcion')
                            ->default(now())
                            ->required()
                            ->live()
                            ->afterStateUpdated(function ($state, callable $set, Get $get) {
                                $days = $get('response_time_limit_days');
                                if ($state && $days !== null && $days !== '') {
                                    $set('response_due_at', \Carbon\Carbon::parse($state)->addDays((int) $days));
                                }
                            }),
                        Forms\Components\Select::make('channel')
                            ->label('Canal')
                            ->options(ClientPqrsRecord::getChannels())
                            ->required(),
                        Forms\Components\Select::make('type')
                            ->label('Tipo')
                            ->options(ClientPqrsRecord::getTypes())
                            ->required()
                            ->live()
                            ->afterStateUpdated(function ($state, callable $set, Get $get) {
                                $currentDays = $get('response_time_limit_days');
                                $defaultDays = ClientPqrsRecord::getDefaultResponseDaysByType($state);

                                if ($currentDays === null || $currentDays === '') {
                                    $set('response_time_limit_days', $defaultDays);
                                }

                                $receivedAt = $get('received_at');
                                if ($receivedAt && $defaultDays !== null) {
                                    $set('response_due_at', \Carbon\Carbon::parse($receivedAt)->addDays((int) ($currentDays ?: $defaultDays)));
                                }
                            }),
                        Forms\Components\Select::make('priority')
                            ->label('Prioridad')
                            ->options(ClientPqrsRecord::getPriorities())
                            ->default('media')
                            ->required(),
                        Forms\Components\Select::make('status')
                            ->label('Estado')
                            ->options(ClientPqrsRecord::getStatuses())
                            ->default('recibido')
                            ->required(),
                        Forms\Components\TextInput::make('response_time_limit_days')
                            ->label('Tiempo maximo de respuesta (dias)')
                            ->numeric()
                            ->minValue(0)
                            ->live()
                            ->afterStateUpdated(function ($state, callable $set, Get $get) {
                                $receivedAt = $get('received_at');
                                if ($receivedAt && $state !== null && $state !== '') {
                                    $set('response_due_at', \Carbon\Carbon::parse($receivedAt)->addDays((int) $state));
                                }
                            })
                            ->helperText('Define el limite interno para responder la PQRS.'),
                        Forms\Components\DateTimePicker::make('response_due_at')
                            ->label('Fecha limite de respuesta')
                            ->helperText('Se calcula automaticamente si hay fecha y dias definidos.')
                            ->rule('after_or_equal:received_at'),
                        Forms\Components\TextInput::make('tracking_code')
                            ->label('Codigo de seguimiento')
                            ->maxLength(100),
                        Forms\Components\TextInput::make('subject')
                            ->label('Asunto')
                            ->required()
                            ->maxLength(255),
                    ])
                    ->columns(3),

                Forms\Components\Section::make('Descripcion')
                    ->schema([
                        Forms\Components\Textarea::make('description')
                            ->label('Descripcion')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('Datos del usuario')
                    ->schema([
                        Forms\Components\Toggle::make('is_anonymous')
                            ->label('Registro anonimo')
                            ->live()
                            ->default(false),
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('client_name')
                                    ->label('Nombre')
                                    ->maxLength(255)
                                    ->hidden(fn(Get $get): bool => (bool) $get('is_anonymous')),
                                Forms\Components\TextInput::make('client_document')
                                    ->label('Documento')
                                    ->maxLength(100)
                                    ->hidden(fn(Get $get): bool => (bool) $get('is_anonymous')),
                                Forms\Components\TextInput::make('client_phone')
                                    ->label('Telefono')
                                    ->tel()
                                    ->maxLength(50)
                                    ->hidden(fn(Get $get): bool => (bool) $get('is_anonymous')),
                                Forms\Components\TextInput::make('client_email')
                                    ->label('Correo')
                                    ->email()
                                    ->maxLength(255)
                                    ->hidden(fn(Get $get): bool => (bool) $get('is_anonymous')),
                            ]),
                    ]),

                Forms\Components\Section::make('Atencion y cierre')
                    ->schema([
                        Forms\Components\TextInput::make('responsible_area')
                            ->label('Area responsable')
                            ->maxLength(255),
                        Forms\Components\Textarea::make('response')
                            ->label('Respuesta al usuario')
                            ->rows(3)
                            ->columnSpanFull(),
                        Forms\Components\DateTimePicker::make('responded_at')
                            ->label('Fecha de respuesta')
                            ->rule('after_or_equal:received_at'),
                        Forms\Components\Textarea::make('corrective_action')
                            ->label('Accion correctiva o preventiva')
                            ->rows(3)
                            ->columnSpanFull(),
                        Forms\Components\DateTimePicker::make('closed_at')
                            ->label('Fecha de cierre')
                            ->rule('after_or_equal:received_at'),
                        Forms\Components\Toggle::make('requires_follow_up')
                            ->label('Requiere seguimiento')
                            ->default(false),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('received_at')
                    ->label('Recepcion')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('type')
                    ->label('Tipo')
                    ->badge()
                    ->formatStateUsing(fn(?string $state) => ClientPqrsRecord::getTypes()[$state] ?? $state),
                Tables\Columns\TextColumn::make('status')
                    ->label('Estado')
                    ->badge()
                    ->formatStateUsing(fn(?string $state) => ClientPqrsRecord::getStatuses()[$state] ?? $state)
                    ->color(fn(?string $state): string => match ($state) {
                        'recibido' => 'gray',
                        'en_analisis' => 'warning',
                        'respondido' => 'info',
                        'cerrado' => 'success',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('priority')
                    ->label('Prioridad')
                    ->badge()
                    ->formatStateUsing(fn(?string $state) => ClientPqrsRecord::getPriorities()[$state] ?? $state)
                    ->color(fn(?string $state): string => match ($state) {
                        'baja' => 'gray',
                        'media' => 'warning',
                        'alta' => 'danger',
                        'critica' => 'danger',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('subject')
                    ->label('Asunto')
                    ->searchable()
                    ->wrap(),
                Tables\Columns\TextColumn::make('response_time_limit_days')
                    ->label('Limite (dias)')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('response_due_at')
                    ->label('Fecha limite')
                    ->dateTime()
                    ->color(fn(ClientPqrsRecord $record) => $record->is_overdue ? 'danger' : null)
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\IconColumn::make('is_overdue')
                    ->label('Vencida')
                    ->boolean()
                    ->trueColor('danger')
                    ->falseColor('gray')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('channel')
                    ->label('Canal')
                    ->formatStateUsing(fn(?string $state) => ClientPqrsRecord::getChannels()[$state] ?? $state)
                    ->toggleable(),
                Tables\Columns\TextColumn::make('tracking_code')
                    ->label('Codigo')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('client_name')
                    ->label('Usuario')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('responded_at')
                    ->label('Respondido')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('closed_at')
                    ->label('Cierre')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->label('Tipo')
                    ->options(ClientPqrsRecord::getTypes()),
                Tables\Filters\SelectFilter::make('status')
                    ->label('Estado')
                    ->options(ClientPqrsRecord::getStatuses()),
                Tables\Filters\SelectFilter::make('priority')
                    ->label('Prioridad')
                    ->options(ClientPqrsRecord::getPriorities()),
                Tables\Filters\SelectFilter::make('channel')
                    ->label('Canal')
                    ->options(ClientPqrsRecord::getChannels()),
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListClientPqrsRecords::route('/'),
            'create' => Pages\CreateClientPqrsRecord::route('/create'),
            'edit' => Pages\EditClientPqrsRecord::route('/{record}/edit'),
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
