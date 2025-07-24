<?php

namespace App\Filament\Pos\Resources;

use App\Filament\Pos\Resources\PurchaseResource\Pages;
use App\Filament\Pos\Resources\PurchaseResource\RelationManagers;
use App\Models\Purchase;
use App\Models\Supplier;
use Filament\Actions\ActionGroup;
use Filament\Facades\Filament;
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
        $teamId = Filament::getTenant()->id;
        return static::getModel()::where('team_id', $teamId)->where('status', 'confirmed')->count();
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
                                'in_progress' => 'In Progress',
                                'confirmed' => 'Confirmed',
                                'delivered' => 'Delivered',
                            ])
                            ->default('in_progress')
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
                Tables\Columns\TextColumn::make('code')
                    ->label(__('Code'))
                    ->sortable()
                    ->searchable(),
                Tables\Columns\SelectColumn::make('status')
                    ->options([
                        'in_progress' => 'In Progress',
                        'confirmed' => 'Confirmed',
                        'delivered' => 'Delivered',
                        'received' => 'Received',
                    ]),
                Tables\Columns\TextColumn::make('total')
                    ->prefix('$ ')
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
                        ->visible(fn(Purchase $record): bool => $record->status === 'in_progress'),
                    Tables\Actions\EditAction::make()
                        ->visible(fn(Purchase $record): bool => $record->status === 'in_progress'),
                    Tables\Actions\Action::make('clone_to_reception')
                        ->label('Clonar a Recepción')
                        ->icon('phosphor-copy-simple')
                        ->color('info')
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

                            // Cambiar el status del registro Purchase a 'received'
                            $record->status = 'received';
                            $record->save();
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
