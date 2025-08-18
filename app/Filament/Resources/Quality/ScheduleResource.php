<?php

namespace App\Filament\Resources\Quality;

use App\Filament\Resources\Quality\ScheduleResource\Pages;
use App\Filament\Resources\Quality\ScheduleResource\RelationManagers;
use App\Models\Schedule;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ScheduleResource extends Resource
{
    protected static ?string $model = Schedule::class;

    protected static ?string $navigationGroup = 'Universidad';
    protected static ?string $navigationLabel = 'Cronogramas';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('description'),
                Forms\Components\TextInput::make('objective'),
                Forms\Components\DatePicker::make('starts_at'),
                Forms\Components\DatePicker::make('ends_at'),
                Forms\Components\ColorPicker::make('color')
                    ->default('#000000')
                    ->required(),
                Forms\Components\TextInput::make('icon'),
                Forms\Components\Checkbox::make('is_cancelled')
                    ->label('')
                    ->default(false),
                Forms\Components\Checkbox::make('is_rescheduled')
                    ->label('')
                    ->default(false),
                Forms\Components\Checkbox::make('is_completed')
                    ->label('')
                    ->default(false),
                Forms\Components\Checkbox::make('is_in_progress')
                    ->label('')
                    ->default(true),
            ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('starts_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('ends_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\ColorColumn::make('color'),
                Tables\Columns\CheckboxColumn::make('is_cancelled'),
                Tables\Columns\CheckboxColumn::make('is_rescheduled'),
                Tables\Columns\CheckboxColumn::make('is_completed'),
                Tables\Columns\CheckboxColumn::make('is_in_progress'),
            ])->filters([
                //
            ])->actions([
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListSchedules::route('/'),
            'create' => Pages\CreateSchedule::route('/create'),
            'edit' => Pages\EditSchedule::route('/{record}/edit'),
        ];
    }
}
