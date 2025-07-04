<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AnesthesiaSheetResource\Pages;
use App\Filament\Resources\AnesthesiaSheetResource\RelationManagers;
use App\Models\AnesthesiaSheet;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AnesthesiaSheetResource extends Resource
{
    protected static ?string $model = AnesthesiaSheet::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Anesthesia Sheet Header')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('recipe_number')
                            ->label(__('Recipe number'))
                            ->visible(fn() => self::userHasDirectorTecnicoRole()),
                        Forms\Components\Select::make('customer_id')
                            ->relationship('customer', 'identification')
                            ->required()
                            ->live(),

                        Forms\Components\Select::make('pet_id')
                            ->label('Pet')
                            ->relationship(
                                name: 'pet',
                                titleAttribute: 'name',
                                modifyQueryUsing: function ($query, $get) {
                                    $customerId = $get('customer_id');
                                    if ($customerId) {
                                        $query->where('customer_id', $customerId);
                                    } else {
                                        $query->whereRaw('0 = 1');
                                    }
                                }
                            )
                            ->preload()
                            ->required()
                            ->searchable()
                            ->disabled(fn($get) => !$get('customer_id'))
                            ->live(),

                        Forms\Components\Select::make('surgeon_id')
                            ->relationship('surgeon', 'name')
                            ->required(),

                        Forms\Components\DateTimePicker::make('anesthesia_start_time')
                            ->nullable(),
                    ]),

                Section::make('Anamnesis')
                    ->description('Asegúrate de registrar como mínimo horas de ayuno:, dieta reciente:, tratamientos y medicación actual:, enfermedades actuales:, concurrentes: y anteriores:, otras cirugías o anestesias. Ejemplo: [Key = "Horas de ayuno" : Value = "8"]')
                    ->schema([
                        Forms\Components\KeyValue::make('anamnesis')
                            ->label('')
                            ->keyPlaceholder('Horas de ayuno'),
                    ])
                    ->collapsed(),

                Section::make('Anesthesia Notes')
                    ->description('Asegúrate de registrar información relativa a distintos insumos empleados como tubos endotraqueales, oxígeno y otros. No olvides el registro ASA(I, II, III, IV, V, E). Ejemplo: [Key = "tubo endotraqueal" : Value = "calibre 8"]')
                    ->schema([
                        Forms\Components\KeyValue::make('anesthesia_notes')
                            ->label('')
                            ->keyPlaceholder('Tubo endotraqueal No.:'),
                    ])
                    ->collapsed(),
                Forms\Components\DateTimePicker::make('anesthesia_end_time')
                    ->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('team.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('customer.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('surgeon.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('anesthesia_start_time')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('anesthesia_end_time')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('pet.name')
                    ->numeric()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    //Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make()
                        ->visible(
                            fn($record) => ($record->status === 'opened') &&
                                (self::userHasDirectorTecnicoRole() || $record->user_id === auth()->id())
                        ),
                ]),
            ])
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
            RelationManagers\AnesthesiaItemsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAnesthesiaSheets::route('/'),
            'create' => Pages\CreateAnesthesiaSheet::route('/create'),
            'edit' => Pages\EditAnesthesiaSheet::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    // Función para verificar si el usuario tiene el rol 'Director Técnico' en el tenant actual
    public static function userHasDirectorTecnicoRole(): bool
    {
        $user = auth()->user();
        $team = \Filament\Facades\Filament::getTenant();

        if (!$team || !$user) {
            return false;
        }

        $teamId = $team->id;

        $role = $user->roles()
            ->where('model_has_roles.team_id', $teamId)
            ->where(function ($query) use ($teamId) {
                $query->whereNull('roles.team_id')
                    ->orWhere('roles.team_id', $teamId);
            })
            ->where('roles.name', 'Director')
            ->first();

        return (bool) $role;
    }
}
