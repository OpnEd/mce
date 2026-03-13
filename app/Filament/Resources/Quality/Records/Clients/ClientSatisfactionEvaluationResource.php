<?php

namespace App\Filament\Resources\Quality\Records\Clients;

use App\Filament\Resources\Quality\Records\Clients\ClientSatisfactionEvaluationResource\Pages;
use App\Models\Quality\Records\Clients\ClientSatisfactionEvaluation;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ClientSatisfactionEvaluationResource extends Resource
{
    protected static ?string $model = ClientSatisfactionEvaluation::class;

    protected static ?string $navigationGroup = 'Registros Diarios';
    protected static ?string $slug = 'satisfaccion-usuario';
    protected static ?string $pluralModelLabel = 'Satisfaccion del Usuario';
    protected static ?string $modelLabel = 'Satisfaccion del Usuario';

    public static function form(Form $form): Form
    {
        $scoreOptions = [
            1 => '1 - Muy insatisfecho',
            2 => '2 - Insatisfecho',
            3 => '3 - Neutral',
            4 => '4 - Satisfecho',
            5 => '5 - Muy satisfecho',
        ];

        $recommendationOptions = [
            0 => '0',
            1 => '1',
            2 => '2',
            3 => '3',
            4 => '4',
            5 => '5',
            6 => '6',
            7 => '7',
            8 => '8',
            9 => '9',
            10 => '10',
        ];

        return $form
            ->schema([
                Forms\Components\Section::make('Datos de la evaluacion')
                    ->schema([
                        Forms\Components\DateTimePicker::make('evaluated_at')
                            ->label('Fecha de evaluacion')
                            ->default(now())
                            ->required(),
                        Forms\Components\Select::make('channel')
                            ->label('Canal')
                            ->options(ClientSatisfactionEvaluation::getChannels())
                            ->required(),
                        Forms\Components\Select::make('service_area')
                            ->label('Servicio evaluado')
                            ->options(ClientSatisfactionEvaluation::getServiceAreas()),
                    ])
                    ->columns(3),

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
                                    ->hidden(fn (Get $get): bool => (bool) $get('is_anonymous')),
                                Forms\Components\TextInput::make('client_document')
                                    ->label('Documento')
                                    ->maxLength(100)
                                    ->hidden(fn (Get $get): bool => (bool) $get('is_anonymous')),
                                Forms\Components\TextInput::make('client_phone')
                                    ->label('Telefono')
                                    ->tel()
                                    ->maxLength(50)
                                    ->hidden(fn (Get $get): bool => (bool) $get('is_anonymous')),
                                Forms\Components\TextInput::make('client_email')
                                    ->label('Correo')
                                    ->email()
                                    ->maxLength(255)
                                    ->hidden(fn (Get $get): bool => (bool) $get('is_anonymous')),
                            ]),
                    ]),

                Forms\Components\Section::make('Calificaciones')
                    ->schema([
                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\Select::make('overall_score')
                                    ->label('Satisfaccion general')
                                    ->options($scoreOptions)
                                    ->required(),
                                Forms\Components\Select::make('attention_score')
                                    ->label('Atencion del personal')
                                    ->options($scoreOptions),
                                Forms\Components\Select::make('waiting_time_score')
                                    ->label('Tiempo de espera')
                                    ->options($scoreOptions),
                                Forms\Components\Select::make('availability_score')
                                    ->label('Disponibilidad de productos')
                                    ->options($scoreOptions),
                                Forms\Components\Select::make('information_clarity_score')
                                    ->label('Claridad de la informacion')
                                    ->options($scoreOptions),
                                Forms\Components\Select::make('cleanliness_score')
                                    ->label('Limpieza y orden')
                                    ->options($scoreOptions),
                                Forms\Components\Select::make('facility_score')
                                    ->label('Comodidad del establecimiento')
                                    ->options($scoreOptions),
                                Forms\Components\Select::make('recommendation_score')
                                    ->label('Recomendacion (0-10)')
                                    ->options($recommendationOptions),
                            ]),
                        Forms\Components\Toggle::make('would_recommend')
                            ->label('Recomendaria el servicio'),
                        Forms\Components\Toggle::make('would_return')
                            ->label('Volveria a usar el servicio'),
                        Forms\Components\Toggle::make('follow_up_required')
                            ->label('Requiere seguimiento')
                            ->default(false),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Comentarios')
                    ->schema([
                        Forms\Components\Textarea::make('comments')
                            ->label('Comentarios')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('evaluated_at')
                    ->label('Fecha')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('channel')
                    ->label('Canal')
                    ->formatStateUsing(fn (?string $state) => ClientSatisfactionEvaluation::getChannels()[$state] ?? $state)
                    ->sortable(),
                Tables\Columns\TextColumn::make('service_area')
                    ->label('Servicio')
                    ->formatStateUsing(fn (?string $state) => ClientSatisfactionEvaluation::getServiceAreas()[$state] ?? $state)
                    ->toggleable(),
                Tables\Columns\TextColumn::make('overall_score')
                    ->label('General')
                    ->sortable(),
                Tables\Columns\TextColumn::make('cleanliness_score')
                    ->label('Limpieza')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('facility_score')
                    ->label('Comodidad')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\IconColumn::make('would_recommend')
                    ->label('Recomienda')
                    ->boolean(),
                Tables\Columns\IconColumn::make('would_return')
                    ->label('Volveria')
                    ->boolean()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\IconColumn::make('follow_up_required')
                    ->label('Seguimiento')
                    ->boolean(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Registrado por')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('channel')
                    ->label('Canal')
                    ->options(ClientSatisfactionEvaluation::getChannels()),
                Tables\Filters\SelectFilter::make('service_area')
                    ->label('Servicio')
                    ->options(ClientSatisfactionEvaluation::getServiceAreas()),
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListClientSatisfactionEvaluations::route('/'),
            'create' => Pages\CreateClientSatisfactionEvaluation::route('/create'),
            'edit' => Pages\EditClientSatisfactionEvaluation::route('/{record}/edit'),
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
