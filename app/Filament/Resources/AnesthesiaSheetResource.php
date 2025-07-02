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
                    ->required()
                    ->searchable()
                    ->disabled(fn ($get) => !$get('customer_id'))
                    ->live(),

                Forms\Components\Select::make('surgeon_id')
                    ->relationship('surgeon', 'name')
                    ->required(),

                    ]),
                
                Section::make('Anamnesis')
                    ->description('Asegúrate de registrar como mínimo horas de ayuno:, dieta reciente:, tratamientos y medicación actual:, enfermedades actuales:, concurrentes: y anteriores:, otras cirugías o anestesias. Ejemplo: [Key = "Horas de ayuno" : Value = "8"]')
                    ->schema([
                        Forms\Components\KeyValue::make('anamnesis')
                            ->label('')
                            ->keyPlaceholder('Horas de ayuno'),
                    ]),

                Section::make('Anesthesia Notes')
                    ->description('Asegúrate de registrar información relativa a distintos insumos empleados como tubos endotraqueales, oxígeno y otros. No olvides el registro ASA(I, II, III, IV, V, E). Ejemplo: [Key = "tubo endotraqueal" : Value = "calibre 8"]')
                    ->schema([
                        Forms\Components\KeyValue::make('anesthesia_notes')
                            ->label('')
                            ->keyPlaceholder('Tubo endotraqueal No.:'),
                    ]),
                Forms\Components\DateTimePicker::make('anesthesia_start_time'),
                Forms\Components\DateTimePicker::make('anesthesia_end_time'),
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
                Tables\Actions\EditAction::make(),
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
}
