<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SpillCleanupResource\Pages;
use App\Filament\Resources\SpillCleanupResource\RelationManagers;
use App\Models\SpillCleanup;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Contracts\View\View;

class SpillCleanupResource extends Resource
{
    protected static ?string $model = SpillCleanup::class;

    protected static ?string $navigationGroup = 'Registros Diarios';
    protected static ?string $navigationLabel = 'Limpieza de Derrames';
    protected static ?string $recordTitleAttribute = 'sustancia';
    protected static ?string $slug = 'limpieza-derrame';

    public function getHeader(): ?View
    {
        return view('filament.components.custom-header');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\Section::make('Detalles del Derrame')
                    ->schema([
                        Forms\Components\DatePicker::make('fecha')
                            ->required()
                            ->default(now()),
                        Forms\Components\TimePicker::make('hora')
                            ->default(now()),
                        Forms\Components\TextInput::make('ubicacion')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('sustancia')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('tipo')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('cantidad')
                            ->numeric(),
                        Forms\Components\TextInput::make('unidad')
                            ->maxLength(255),
                        Forms\Components\Textarea::make('medidas_seguridad')
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('personal_expuesto')
                            ->maxLength(255),
                        Forms\Components\Textarea::make('acciones')
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('observaciones')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('fecha')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('hora'),
                Tables\Columns\TextColumn::make('ubicacion')
                    ->searchable(),
                Tables\Columns\TextColumn::make('sustancia')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tipo')
                    ->searchable(),
                Tables\Columns\TextColumn::make('cantidad')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('unidad')
                    ->searchable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Registrado por')
                    ->sortable(),
                Tables\Columns\TextColumn::make('personal_expuesto')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListSpillCleanups::route('/'),
            'create' => Pages\CreateSpillCleanup::route('/create'),
            'edit' => Pages\EditSpillCleanup::route('/{record}/edit'),
        ];
    }
}
