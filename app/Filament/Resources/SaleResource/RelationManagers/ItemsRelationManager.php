<?php

namespace App\Filament\Resources\SaleResource\RelationManagers;

use App\Filament\Resources\SaleResource;
use App\Models\Inventory;
use App\Models\PeripheralProductPrice;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class ItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'items';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('inventory_id')
                    ->relationship(
                        'inventory', // relación belongsTo en el modelo
                        'bar_code', // accede al nombre del producto a través de inventory->product->code
                        fn($query) => $query->whereHas('product', fn($q) => $q->inInventory())
                    )
                    ->searchable()
                    ->afterStateUpdated(function (?string $state, Set $set, Get $get) {
                        // Obtener el inventory seleccionado
                        $inventory = $get('inventory_id') ? \App\Models\Inventory::find($get('inventory_id')) : null;

                        // Calcular el sale_price usando la relación correcta
                        $price = $inventory?->product?->peripheralPrice?->sale_price ?? 0;

                        $set('sale_price', $price);
                        $set('total', ($state ?? 0) * $price);

                        // Establecer el nombre del producto directamente desde inventories
                        $productName = $inventory?->product_name ?? '';
                        $set('product_name', $productName);
                    })
                    ->required()
                    ->live()
                    ->helperText(
                        fn(Get $get) => 'Stock disponible: '
                            . (
                                ($inventoryId = $get('inventory_id'))
                                    ? Inventory::where('product_id', Inventory::find($inventoryId)?->product_id)
                                        ->sum('quantity')
                                    : 0
                            ) . ' unidades...'
                    ),
                Forms\Components\TextInput::make('product_name')
                    ->readOnly(),
                Forms\Components\TextInput::make('sale_price')
                    ->readOnly(),
                Forms\Components\TextInput::make('quantity')
                    ->required()
                    ->numeric()
                    ->default(1)
                    ->afterStateUpdated(function (?string $state, Set $set, Get $get) {
                        // Calcular y persistir total
                        $salePrice = $get('sale_price') ?? 0;
                        $set('total', $state * $salePrice);
                    }),
                    /*  */
                Forms\Components\Hidden::make('total')
                    ->default(0),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('inventory_id')
            ->columns([
                Tables\Columns\TextColumn::make('inventory.product.name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('quantity')
                    ->sortable()
                    ->numeric(),
                Tables\Columns\TextColumn::make('sale_price')
                    ->sortable()
                    ->numeric()
                    ->prefix('$'),
                Tables\Columns\TextColumn::make('total')
                    ->sortable()
                    ->numeric()
                    ->prefix('$'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Add product')
                    ->icon('phosphor-plus')
                    ->visible(fn(): bool => Gate::allows('create', $this->ownerRecord)),
                Tables\Actions\Action::make('confirmSale')
                    ->label('Confirmar Venta')
                    ->color('success')
                    ->icon('heroicon-o-check')
                    ->visible(
                        fn(): bool =>
                        Gate::allows('confirm', $this->ownerRecord)
                            &&
                            $this->ownerRecord->items()->count() > 0
                    )
                    ->requiresConfirmation()
                    ->action(function (): void {
                        $sale = $this->ownerRecord;

                        try {
                            DB::transaction(function () use ($sale) {
                                // Actualizar el estado de la venta
                                $sale->update([
                                    'status' => 'completed',
                                    'updated_at' => now(),
                                ]);

                                // Clonar Sale a Invoice
                                $invoice = new \App\Models\Invoice();
                                $invoice->fill([
                                    'team_id' => Filament::getTenant()->id,
                                    'sale_id' => $sale->id, // Relación con Venta que incluye la relación con el cliente
                                    'code' => (new \App\Models\Invoice())->generateCode($sale),
                                    'amount' => $sale->items->sum('total'),
                                    'is_our' => 1, // el modelo Invoice almacena todas las facturas, incluidas las que vienen de terceros
                                    'supplier_id' => null, // Asignar null si no es una factura de proveedor
                                    'issued_date' => now(),
                                    'data' => $sale->data,
                                ]);
                                $invoice->save();

                                // Clonar SaleItems a InvoiceItems
                                foreach ($sale->items as $item) {
                                    $invoiceItem = new \App\Models\InvoiceItem();
                                    $invoiceItem->fill([
                                        'invoice_id' => $invoice->id,
                                        'sale_item_id' => $item->id ?? null,
                                        'batch_id' => $item->inventory->batch_id,
                                        'due_date' => $item->inventory->batch->expiration_date,
                                        'quantity' => $item->quantity,
                                        'price' => $item->sale_price,
                                        'total' => $item->total,
                                        // Agrega aquí otros campos necesarios
                                    ]);
                                    $invoiceItem->save();
                                }

                                // Notificación de éxito
                                \Filament\Notifications\Notification::make()
                                    ->title('Venta confirmada y factura generada')
                                    ->color('success')
                                    ->send();

                                // Redirigir al detalle de la factura
                                Redirect::to(
                                    \App\Filament\Resources\InvoiceResource::getUrl('view', ['record' => $invoice->id])
                                );
                            });
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
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
