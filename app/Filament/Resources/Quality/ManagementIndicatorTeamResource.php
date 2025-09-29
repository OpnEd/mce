<?php

namespace App\Filament\Resources\Quality;

use App\Filament\Resources\Quality\ManagementIndicatorTeamResource\Pages;
use App\Filament\Resources\Quality\ManagementIndicatorTeamResource\RelationManagers;
use App\Models\Quality\ManagementIndicatorTeam;
use Dom\Text;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ManagementIndicatorTeamResource extends Resource
{
    protected static ?string $model = ManagementIndicatorTeam::class;

    protected static ?int $navigationSort = 68;
    protected static ?string $navigationGroup = 'Settings';
    protected static ?string $pluralModelLabel = 'Indicadores de Gestión';
    protected static ?string $modelLabel = 'Indicador de Gestión';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('management_indicator_id')
                    ->label(__('fields.management_indicator'))
                    ->required()
                    ->relationship('managementIndicator', 'name'),
                Select::make('role_id')
                    ->label('Responsable')
                    ->required()
                    ->relationship('role', 'name'),
                TextInput::make('periodicity')
                    ->label(__('fields.periodicity'))
                    ->required(),
                TextInput::make('indicator_goal')
                    ->label(__('fields.indicator_goal'))
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('managementIndicator.name')
                    ->label(__('fields.management_indicator'))
                    ->searchable(),
                TextColumn::make('role.name')
                    ->label('Responsable')
                    ->searchable(),
                TextColumn::make('periodicity')
                    ->label(__('fields.periodicity'))
                    ->sortable(),
                TextColumn::make('indicator_goal')
                    ->label(__('fields.indicator_goal'))
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label(__('fields.created_at'))
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label(__('fields.updated_at'))
                    ->dateTime('d/m/Y H:i')
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
            'index' => Pages\ListManagementIndicatorTeams::route('/'),
            'create' => Pages\CreateManagementIndicatorTeam::route('/create'),
            'edit' => Pages\EditManagementIndicatorTeam::route('/{record}/edit'),
        ];
    }
}
