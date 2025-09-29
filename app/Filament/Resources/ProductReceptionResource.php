<?php

namespace App\Filament\Resources;

use App\Filament\Clusters\POS;
use App\Filament\Resources\ProductReceptionResource\Pages;
use App\Filament\Resources\ProductReceptionResource\RelationManagers;
use App\Models\ProductReception;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductReceptionResource extends Resource
{
    protected static ?string $model = ProductReception::class;

    protected static ?string $navigationGroup = 'POS'; // Agrupación en el menú de navegación
    protected static ?string $pluralModelLabel = 'Recepciones';
    protected static ?string $modelLabel = 'Recepción Técnica';
    protected static ?string $slug = 'recepciones-tecnicas';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('fields.reception_details'))
                    ->schema([
                        Forms\Components\Select::make('invoice_id')
                            ->label(__('fields.invoice'))
                            ->relationship('invoice', 'code')
                            ->createOptionForm([   // form para crear nueva Invoice “in-line”
                                Forms\Components\TextInput::make('code')
                                    ->label(__('fields.code'))
                                    ->required()
                                    ->unique(ignoreRecord: true),

                                Forms\Components\TextInput::make('amount')
                                    ->label(__('fields.amount'))
                                    ->numeric()
                                    ->required(),

                                Forms\Components\Hidden::make('is_our')
                                    ->label(__('fields.is_our'))
                                    ->default(false),

                                Forms\Components\DatePicker::make('issued_date')
                                    ->label(__('fields.issued_date'))
                                    ->required(),

                                // Si necesitas vincular Supplier o Sale, puedes usar:
                                Forms\Components\Select::make('supplier_id')
                                    ->label(__('fields.supplier'))
                                    ->relationship('supplier', 'name')
                                    ->searchable()
                                    ->nullable(),

                                // Additional data:
                                Forms\Components\KeyValue::make('data')
                                    ->label(__('fields.extra_data')),
                            ])
                            ->required(),
                        Forms\Components\Select::make('status')
                            ->label(__('fields.status'))
                            ->options([
                                'in_progress' => __('fields.in_progress'),
                                'done' => __('fields.done'),
                            ])
                            ->required(),
                        Forms\Components\DateTimePicker::make('reception_date')
                            ->label(__('fields.reception_date')),
                        Forms\Components\Textarea::make('observations')
                            ->label(__('fields.observations'))
                            ->columnSpanFull(),
                        Forms\Components\KeyValue::make('data')
                            ->label(__('fields.extra_data'))
                            ->columnSpanFull(),
                    ])
                    ->columns(2)
                    ->collapsible()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label(__('fields.created_by'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('purchase.id')
                    ->label(__('fields.purchase_id'))
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('supplier.name')
                    ->label(__('fields.supplier'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('invoice.code')
                    ->label(__('fields.invoice_id'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label(__('fields.status')),
                Tables\Columns\TextColumn::make('reception_date')
                    ->label(__('fields.reception_date'))
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
            RelationManagers\ItemsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProductReceptions::route('/'),
            'create' => Pages\CreateProductReception::route('/create'),
            'edit' => Pages\EditProductReception::route('/{record}/edit'),
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
