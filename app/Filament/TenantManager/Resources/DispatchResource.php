<?php

namespace App\Filament\TenantManager\Resources;

use App\Filament\TenantManager\Resources\DispatchResource\Pages;
use App\Filament\TenantManager\Resources\DispatchResource\RelationManagers;
use App\Models\Dispatch;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Section;

class DispatchResource extends Resource
{
    protected static ?string $model = Dispatch::class;

    protected static ?string $navigationGroup = 'Transactions';
    protected static ?string $navigationIcon = 'phosphor-check-fat';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Dispatch details')
                    ->schema([
                        Forms\Components\Select::make('purchase_id')
                            ->relationship('purchase', 'id')
                            ->required(),
                        Forms\Components\Select::make('team_id')
                            ->relationship('team', 'name')
                            ->required(),
                        Forms\Components\DateTimePicker::make('dispatched_at'),
                    ])
                    ->columns(3)
                    ->collapsed(),
                Section::make('Dispatch meta-data')
                    ->schema([
                        Forms\Components\KeyValue::make('data'),
                    ])
                    ->columnSpanFull()
                    ->collapsed(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('purchase.id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('team.name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Made by')
                    ->searchable(),
                Tables\Columns\TextColumn::make('dispatched_at')
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
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
            RelationManagers\ItemsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDispatches::route('/'),
            'create' => Pages\CreateDispatch::route('/create'),
            'view' => Pages\ViewDispatch::route('/{record}'),
            'edit' => Pages\EditDispatch::route('/{record}/edit'),
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
