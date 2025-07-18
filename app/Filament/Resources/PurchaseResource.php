<?php

namespace App\Filament\Resources;

use App\Filament\Clusters\POS;
use App\Filament\Resources\PurchaseResource\Pages;
use App\Filament\Resources\PurchaseResource\RelationManagers;
use App\Models\Purchase;
use Filament\Actions\ActionGroup;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup as ActionsActionGroup;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Section;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Redirect;

class PurchaseResource extends Resource
{
    protected static ?string $model = Purchase::class;

    protected static ?string $navigationIcon = 'phosphor-shopping-bag-open';

    protected static ?string $navigationGroup = 'POS';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', 'confirmed')->count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return static::getModel()::where('status', 'confirmed')->count() > 10 ? 'warning' : 'primary';
    }

    public static function getNavigationBadgeTooltip(): ?string
    {
        return 'Número de órdenes de compra confirmadas';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Order details')
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->options([
                                'confirmed' => 'Confirmed',
                                'delivered' => 'Delivered',
                            ])
                            ->default('pending')
                            ->required(),
                        Forms\Components\TextInput::make('total')
                            ->prefix('$')
                            ->readOnly()
                            ->disabled()
                            ->dehydrated(false) // para que no se guarde de nuevo en update
                            ->formatStateUsing(fn($state) => number_format($state, 0, ',', '.')),
                    ])
                    ->columns(2)
                    ->collapsed(),
                Section::make('Order meta-data')
                    ->schema([
                        Forms\Components\Textarea::make('observations'),
                        Forms\Components\KeyValue::make('data')
                    ])
                    ->columns(2)
                    ->collapsed(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('# Purchase')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\SelectColumn::make('status')
                    ->options([
                        'confirmed' => 'Confirmed',
                        'delivered' => 'Delivered',
                    ]),
                Tables\Columns\TextColumn::make('total')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                ActionsActionGroup::make([
                    Tables\Actions\ViewAction::make()
                        ->color('primary'),
                    Tables\Actions\DeleteAction::make()
                        ->visible(fn(Purchase $record): bool => $record->status === 'confirmed'),
                    /* Tables\Actions\Action::make('confirm')
                        ->label('Confirm')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->visible(
                            fn(Purchase $record): bool =>
                            Gate::allows('confirm', $record)
                                && $record->status === 'pending'
                                && $record->items()->count() > 0
                        )
                        ->requiresConfirmation()
                        ->modalHeading('Confirm Purchase')
                        ->modalDescription('Are you sure you want to confirm this purchase?')
                        ->action(function (Purchase $record) {
                            $record->status = 'confirmed';
                            $record->save();
                        }), */
                    Tables\Actions\Action::make('clone_to_reception')
                        ->label('Clonar a Recepción')
                        ->icon('phosphor-copy-simple')
                        ->color('secondary')
                        ->visible(fn(Purchase $record): bool => $record->status === 'delivered')
                        ->requiresConfirmation()
                        ->modalHeading('Clonar a Recepción de Producto')
                        ->modalDescription('¿Está seguro de que desea clonar esta orden de compra a una recepción de producto?')
                        ->action(function (Purchase $record) {
                            // Crear la recepción de producto
                            $reception = \App\Models\ProductReception::create([
                                'team_id' => $record->team_id,
                                'user_id' => Auth::id(),
                                'purchase_id' => $record->id,
                                'invoice_id' => null,
                                'status' => 'in_progress',
                                'reception_date' => now(),
                                'observations' => $record->observations,
                                'data' => $record->data,
                            ]);

                            // Clonar los items
                            if ($record->items) {
                                foreach ($record->items as $item) {
                                    \App\Models\ProductReceptionItem::create([
                                        'product_reception_id' => $reception->id,
                                        'product_id' => $item->product_id,
                                        'batch_id' => null,
                                        'quantity' => $item->quantity,
                                        'purchase_price' => $item->price,
                                        'total' => $item->total,
                                    ]);
                                }
                            }
                            // Redirigir a la página de recepción
                            Redirect::to(
                                \App\Filament\Resources\ProductReceptionResource::getUrl('edit', ['record' => $reception->id])
                            );
                        }),
                ])
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
            'index' => Pages\ListPurchases::route('/'),
            'create' => Pages\CreatePurchase::route('/create'),
            'view' => Pages\ViewPurchase::route('/{record}'),
            'edit' => Pages\EditPurchase::route('/{record}/edit'),
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
