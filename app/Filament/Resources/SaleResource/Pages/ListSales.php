<?php

namespace App\Filament\Resources\SaleResource\Pages;

use App\Filament\Resources\SaleResource;
use App\Models\Inventory;
use App\Models\Sale;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Redirect;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Auth;
use Filament\Forms;
use App\Models\Product;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Illuminate\Support\Facades\Gate;

class ListSales extends ListRecords
{
    protected static string $resource = SaleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('quickSale')
                ->label('Iniciar venta!')
                ->icon('phosphor-shopping-bag')
                ->modalHeading('Nueva Venta')
                ->form([
                    Forms\Components\Select::make('inventory_id')
                        ->label('Producto')
                        ->options(function () {
                            return \App\Models\Inventory::whereHas('product', fn($q) => $q->inInventory())
                                ->pluck('bar_code', 'id');
                        })
                        ->searchable()
                        ->afterStateUpdated(function (?string $state, Set $set, Get $get) {
                            // Obtener el inventory seleccionado
                            $inventory = $get('inventory_id') ? \App\Models\Inventory::find($get('inventory_id')) : null;

                            // Calcular el sale_price usando la relación correcta
                            $price = $inventory?->product?->peripheralPrice?->sale_price ?? 0;

                            $set('sale_price', $price);
                            $set('total', ($get('quantity') ?? 0) * $price);

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
                    Forms\Components\Hidden::make('total')
                        ->default(0),
                ])
                ->action(function (array $data, Action $action) {
                    // Validar que exista el producto
                    if (empty($data['inventory_id'])) {
                        \Filament\Notifications\Notification::make()
                            ->title('Debes seleccionar un producto')
                            ->color('danger')
                            ->send();
                        return;
                    }

                    // Crear la venta
                    $sale = Sale::create([
                        'team_id'       => Filament::getTenant()->id,
                        'customer_id'   => 1,
                        'user_id'       => Auth::user()->id,
                        'total'         => $data['total'] ?? 0,
                        'status'        => 'in-progress',
                        'code'          => (new Sale())->generateCode(),
                        'data'          => [],
                    ]);

                    // Adjuntar el producto como item de la venta
                    $sale->items()->create([
                        'inventory_id'   => $data['inventory_id'],
                        'product_name'   => $data['product_name'],
                        'sale_price'     => $data['sale_price'],
                        'quantity'       => $data['quantity'],
                        'total'          => $data['total'],
                    ]);

                    // Redirigir al formulario de edición de este Sale (donde ItemsRelationManager mostrará el item)
                    return Redirect::to(
                        SaleResource::getUrl('edit', ['record' => $sale->id])
                    );
                })
                ->requiresConfirmation()
                ->visible(fn(): bool => Gate::allows('create', Sale::class)),
        ];
    }
}
