<?php

namespace App\Filament\Resources\Quality\Records\Products\PurchaseResource\RelationManagers;

use App\Filament\Resources\Quality\Records\Products\PurchaseResource;
use App\Helpers\PermissionVerificationHelper;
use App\Models\CentralProductPrice;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Quality\Records\Products\MissingProduct;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class ItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'items';
    protected static ?string $title = 'Products';
    //protected static ?string $model = Purchase::class;


    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('product_id')
                    ->label(__('fields.product'))
                    ->searchable()
                    ->relationship('product', 'name')
                    ->getSearchResultsUsing(       // callback personalizado
                        fn(string $search) => Product::withoutGlobalScopes()
                            ->where('name', 'like', "%{$search}%")
                            ->pluck('name', 'id')
                            ->toArray()
                    )
                    ->afterStateUpdated(function (?string $state, Set $set, Get $get) {
                        // Obtener la cantidad actual o 1 si es nulo
                        $quantity = $get('quantity') ?? 1;
                        // Buscar el precio actualizado del producto seleccionado
                        $price = CentralProductPrice::find($state)?->price ?? 0;
                        $set('price', $price);
                        $set('total', $quantity * $price);
                    })
                    ->live()
                    ->required(),

                Forms\Components\TextInput::make('quantity')
                    ->label(__('Quantity'))
                    ->required()
                    ->numeric()
                    ->default(1)
                    ->afterStateUpdated(function (?string $state, Set $set, Get $get) {
                        // Calcular y persistir price y total aunque no haya inputs
                        $price = CentralProductPrice::find($get('product_id'))?->price ?? 0;
                        $set('price', $price);
                        $set('total', $state * $price);
                    })
                    ->live(),

                Forms\Components\Select::make('type')
                    ->label('Tipo de Faltante')
                    ->helperText(str('**Faltante Ordinario**: Producto seleccionado que se debe comprar (puede tener existencias). **Faltante Efectivo**: Producto seleccionado, solicitado por usuario y sin existencias. **Faltante Baja Rotacion**: Producto no seleccionado, solicitado por usuario y sin existencias.')->inlineMarkdown()->toHtmlString())
                    ->options([
                        'faltante_ordinario' => 'Faltante Ordinario',
                        'faltante_efectivo' => 'Faltante Efectivo',
                        'faltante_baja_rotacion' => 'Faltante Baja Rotacion',
                    ]) // ->default('faltante_ordinario')
                    ->default('faltante_ordinario')
                    ->required()
                    ->columnSpanFull(),

                Forms\Components\Hidden::make('price')
                    ->default(0),
                Forms\Components\Hidden::make('total')
                    ->default(0),

                // --- Campo para forzar team_id igual al del Purchase dueño ---
                Forms\Components\Hidden::make('team_id')
                    ->default(function () {
                        // Si hay owner record, tomar su team_id; si no, tomar team del usuario
                        $owner = $this->getOwnerRecord();
                        if ($owner && isset($owner->team_id)) {
                            return $owner->team_id;
                        }
                        return Auth::user()?->team_id ?? null;
                    })
                    ->dehydrated(true)
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('product_id')
            ->columns([
                Tables\Columns\TextColumn::make('product.name')
                    ->label(__('Product'))
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('quantity')
                    ->label(__('Quantity'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('type')
                    ->label('Tipo de Faltante')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('price')
                    ->label(__('Price'))
                    ->money('cop', true)
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\Action::make('addMissingProducts')
                    ->label('Agregar faltantes')
                    ->icon('phosphor-plus-circle')
                    ->color('primary')
                    ->form([
                        Forms\Components\Select::make('missing_product_ids')
                            ->label('Faltantes disponibles')
                            ->multiple()
                            ->searchable()
                            ->preload(false)
                            ->getSearchResultsUsing(function (string $search) {
                                $owner = $this->getOwnerRecord();

                                return MissingProduct::query()
                                    ->where('team_id', $owner->team_id)
                                    ->open()
                                    ->whereHas('product', function ($query) use ($search) {
                                        $query->where('name', 'like', "%{$search}%");
                                    })
                                    ->with('product')
                                    ->orderByDesc('created_at')
                                    ->limit(50)
                                    ->get()
                                    ->mapWithKeys(function (MissingProduct $missingProduct) {
                                        return [$missingProduct->id => $this->formatMissingProductOption($missingProduct)];
                                    })
                                    ->toArray();
                            })
                            ->getOptionLabelsUsing(function (array $values) {
                                return MissingProduct::query()
                                    ->whereIn('id', $values)
                                    ->with('product')
                                    ->get()
                                    ->mapWithKeys(function (MissingProduct $missingProduct) {
                                        return [$missingProduct->id => $this->formatMissingProductOption($missingProduct)];
                                    })
                                    ->toArray();
                            })
                            ->required(),
                    ])
                    ->action(function (array $data) {
                        $purchase = $this->ownerRecord;

                        $missingProducts = MissingProduct::query()
                            ->whereIn('id', $data['missing_product_ids'])
                            ->whereNull('purchase_item_id')
                            ->with('product')
                            ->get();

                        if ($missingProducts->isEmpty()) {
                            Notification::make()
                                ->title('No hay faltantes disponibles')
                                ->warning()
                                ->send();
                            return;
                        }

                        DB::transaction(function () use ($purchase, $missingProducts) {
                            $grouped = $missingProducts->groupBy('product_id');

                            foreach ($grouped as $productId => $items) {
                                $quantity = $items->count();
                                $price = CentralProductPrice::find($productId)?->price ?? 0;

                                $existingItem = $purchase->items()->where('product_id', $productId)->first();

                                if ($existingItem) {
                                    $existingItem->quantity += $quantity;
                                    if ($existingItem->price <= 0) {
                                        $existingItem->price = $price;
                                    }
                                    $existingItem->total = $existingItem->quantity * $existingItem->price;
                                    $existingItem->save();
                                    $purchaseItem = $existingItem;
                                } else {
                                    $purchaseItem = $purchase->items()->create([
                                        'product_id' => $productId,
                                        'quantity' => $quantity,
                                        'price' => $price,
                                        'total' => $quantity * $price,
                                        'enlisted' => false,
                                        'team_id' => $purchase->team_id,
                                        'type' => $this->resolveTypeFromMissingProduct($items->first()),
                                    ]);
                                }

                                MissingProduct::whereIn('id', $items->pluck('id'))
                                    ->update(['purchase_item_id' => $purchaseItem->id]);
                            }

                            $purchase->updatePurchaseTotal();
                        });

                        Notification::make()
                            ->title('Faltantes agregados a la orden')
                            ->success()
                            ->send();
                    }),
                CreateAction::make()
                    ->label('Agregar producto')
                    ->icon('phosphor-plus')
                    ->visible(fn(): bool => Gate::allows('confirm', $this->ownerRecord))
                    ->before(function (array $data, $action) {
                        $purchase = $this->ownerRecord;
                        $exists = $purchase->items()->where('product_id', $data['product_id'])->exists();
                        // si ya existe el producto en la orden, no permitir agregarlo de nuevo
                        if ($exists) {
                            \Filament\Notifications\Notification::make()
                                ->title('Este producto ya fue agregado')
                                ->body('No puedes agregar el mismo producto dos veces a la orden, pero sí puedes cambiar la cantidad en el registro.')
                                ->danger()
                                ->send();

                            // Cancelar la acción correctamente
                            $action->cancel();
                        }
                    })
                    // FORZAR team_id desde el Purchase dueño antes de crear
                    ->mutateFormDataUsing(function (array $data) {
                        $owner = $this->getOwnerRecord();
                        if ($owner && isset($owner->team_id)) {
                            $data['team_id'] = $owner->team_id;
                        } else {
                            $data['team_id'] = Auth::user()?->team_id ?? null;
                        }
                        return $data;
                    })
                    ->after(function ($record) {
                        DB::transaction(function ($livewire) use ($record) {
                            $record->purchase->updatePurchaseTotal();
                        });
                    }),
                Tables\Actions\Action::make('confirmPurchase')
                    ->label('Confirmar Compra')
                    ->color('success')
                    ->icon('heroicon-o-check')
                    ->visible(
                        fn(): bool =>
                        Gate::allows('confirm', $this->ownerRecord)
                            &&
                            $this->ownerRecord->items()->count() > 0
                    )
                    ->requiresConfirmation()
                    ->action(function () {
                        $purchase = $this->ownerRecord;
                        try {
                            DB::transaction(function () use ($purchase) {
                                $purchase->update([
                                    'status' => 'confirmed',
                                    'confirmed_at' => now(),
                                ]);
                            });

                            \Filament\Notifications\Notification::make()
                                ->title('Order confirmed')
                                ->color('success')
                                ->send();

                            // Redireccionar a la página 'index'
                            //return redirect()->route('filament.admin.resources.purchases.index');


                            // Redirigir al formulario de edición de este Purchase
                            Redirect::to(
                                PurchaseResource::getUrl('index')
                            );
                        } catch (\Exception $e) {

                            \Filament\Notifications\Notification::make()
                                ->title('Error al confirmar')
                                ->body($e->getMessage())
                                ->danger()
                                ->send();

                            throw $e;
                        }
                    }),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make()
                        ->after(function ($record) {
                            DB::transaction(function ($livewire) use ($record) {
                                $record->purchase->updatePurchaseTotal();
                            });
                        }),
                    Tables\Actions\DeleteAction::make()
                        ->after(function ($record) {
                            DB::transaction(function ($livewire) use ($record) {
                                $record->purchase->updatePurchaseTotal();
                            });
                        }),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    /* protected function afterCreate(): void
    {
        $this->recalculatePurchaseTotal();
    }

    protected function afterSave(): void
    {
        $this->recalculatePurchaseTotal();
    }

    protected function afterDelete(): void
    {
        $this->recalculatePurchaseTotal();
    }

    protected function recalculatePurchaseTotal(): void
    {
        $purchase = $this->getOwnerRecord(); // modelo Purchase
        $total = $purchase->items()->sum('total');

        $purchase->update(['total' => $total]);
    } */

    protected function formatMissingProductOption(MissingProduct $missingProduct): string
    {
        $productName = $missingProduct->product?->name ?? 'Producto';
        $class = $missingProduct->missing_class ?? '-';
        $stockStatus = MissingProduct::getStockStatuses()[$missingProduct->stock_status] ?? $missingProduct->stock_status;
        $requested = $missingProduct->requested_by_user ? 'Solicitado' : 'No solicitado';
        $date = $missingProduct->created_at?->format('Y-m-d') ?? '';

        return "{$productName} | Clase {$class} | {$stockStatus} | {$requested} | {$date}";
    }

    protected function resolveTypeFromMissingProduct(MissingProduct $missingProduct): string
    {
        if (
            $missingProduct->is_selected
            && $missingProduct->requested_by_user
            && $missingProduct->stock_status === MissingProduct::STOCK_STATUS_OUT_OF_STOCK
        ) {
            return 'faltante_efectivo';
        }

        if (
            !$missingProduct->is_selected
            && $missingProduct->requested_by_user
            && $missingProduct->stock_status === MissingProduct::STOCK_STATUS_OUT_OF_STOCK
        ) {
            return 'faltante_baja_rotacion';
        }

        return 'faltante_ordinario';
    }
}
