<?php

namespace App\Filament\Resources;

use App\Filament\Clusters\POS;
use App\Filament\Resources\BatchResource\Pages;
use App\Filament\Resources\BatchResource\RelationManagers;
use App\Models\Batch;
use App\Models\Product;
use App\Models\SanitaryRegistry;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BatchResource extends Resource
{
    protected static ?string $model = Batch::class;

    //protected static ?string $cluster = POS::class;

    protected static ?string $navigationGroup = 'POS';
    protected static ?string $pluralModelLabel = 'Lotes';
    protected static ?string $modelLabel = 'Lote';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('manufacturer_id')
                    ->label(__('fields.manufacturer'))
                    ->relationship(name: 'manufacturer', titleAttribute: 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\Select::make('sanitary_registry')
                    ->label(__('fields.sanitary_registry'))
                    ->options(SanitaryRegistry::all()->pluck('code', 'code'))
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\TextInput::make('code')
                    ->label(__('fields.batch'))
                    ->required()
                    ->maxLength(255),
                Forms\Components\DatePicker::make('manufacturing_date')
                    ->label(__('fields.manufacturing_date'))
                    ->required(),
                Forms\Components\DatePicker::make('expiration_date')
                    ->label(__('fields.expiration_date'))
                    ->required(),
                Forms\Components\KeyValue::make('data')
                    ->label(__('fields.extra_data'))
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('sanitary_registry')
                    ->label(__('fields.sanitary_registry'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('code')
                    ->label(__('fields.batch'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('manufacturing_date')
                    ->label(__('fields.manufacturing_date'))
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('expiration_date')
                    ->label(__('fields.expiration_date'))
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->label(__('fields.deleted_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('fields.created_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('fields.updated_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBatches::route('/'),
            'create' => Pages\CreateBatch::route('/create'),
            'edit' => Pages\EditBatch::route('/{record}/edit'),
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
