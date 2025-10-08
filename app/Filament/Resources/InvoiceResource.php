<?php

namespace App\Filament\Resources;

use App\Filament\Clusters\POS;
use App\Filament\Resources\InvoiceResource\Pages;
use App\Filament\Resources\InvoiceResource\RelationManagers;
use App\Models\Invoice;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Section;

class InvoiceResource extends Resource
{
    protected static ?string $model = Invoice::class;

    protected static ?string $navigationGroup = 'POS';
    protected static ?string $pluralModelLabel = 'Facturas';
    protected static ?string $modelLabel = 'Factura';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Order details')
                    ->schema([
                        Forms\Components\Select::make('sale_id')
                            ->label(__('fields.sale_number'))
                            ->relationship('sale', 'id'),
                        Forms\Components\TextInput::make('code')
                            ->label(__('fields.invoice_code'))
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('amount')
                            ->label(__('fields.amount'))
                            ->required()
                            ->numeric(),
                        Forms\Components\Toggle::make('is_our')
                            ->label(__('fields.is_our'))
                            ->inline(false)
                            ->required(),
                        Forms\Components\DatePicker::make('issued_date')
                            ->label(__('fields.issued_date'))
                            ->default(now())
                            ->required(),
                        Forms\Components\KeyValue::make('data')
                            ->label(__('fields.extra_data'))
                            ->columnSpanFull(),
                    ])
                    ->columns(4),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('sale.id')
                    ->label(__('fields.sale_number'))
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('supplier.name')
                    ->label(__('fields.supplier'))
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('code')
                    ->label(__('fields.invoice_code'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('amount')
                    ->label(__('fields.amount'))
                    ->numeric()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_our')
                    ->label(__('fields.is_our'))
                    ->boolean(),
                Tables\Columns\TextColumn::make('issued_date')
                    ->label(__('fields.issued_date'))
                    ->date('d/m/Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->label(__('fields.deleted_at'))
                    ->dateTime('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('fields.created_at'))
                    ->dateTime('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('fields.updated_at'))
                    ->dateTime('d/m/Y')
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
            //RelationManagers\ReceptionRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInvoices::route('/'),
            'create' => Pages\CreateInvoice::route('/create'),
            'view' => Pages\ViewInvoice::route('/{record}'),
            'edit' => Pages\EditInvoice::route('/{record}/edit'),
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
