<?php

namespace App\Livewire\Pos;

use Livewire\Component;
use App\Models\Inventory;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use App\Models\Sale;
use App\Models\SaleItem;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Redirect;

class InventoryCartComponent extends Component implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;

    public function table(Table $table): Table
    {
        return $table
            ->query(Inventory::query())
            ->heading('Productos en inventario')
            ->columns([
                TextColumn::make('bar_code')
                    ->label('Código de barras')
                    ->searchable(),
                TextColumn::make('product_name')
                    ->label('Nombre del producto')
                    ->searchable(),
                TextColumn::make('product.peripheralPrice.sale_price')
                    ->label('Precio de venta')
                    ->money(''),
                TextColumn::make('batch_code')
                    ->label('Lote')
                    ->searchable(),
                TextColumn::make('quantity')
                    ->label('Existencias'),
            ])
            ->filters([
                // ...
            ])
            ->actions([
                \Filament\Tables\Actions\Action::make('add')
                    ->label('Agregar')
                    ->icon('phosphor-plus')
                    ->action(fn($record) => $this->addToCart($record->id)),
            ])
            ->bulkActions([
                // ...
            ]);
    }
    public $cart = [];
    public $quantities = [];
    // Propiedades para los modales y cliente
    public $newCustomerAddress = '';
    public $newCustomerEmail = '';
    public $selectedClienteId = null;
    public $newCustomerName = '';
    public $newCustomerDocument = '';
    public $newCustomerPhone = '';
    public $emitirFactura = false;
    public $customers;
    public $inventories;

    protected $listeners = [
        'refreshCart' => 'loadCart',
    ];

    public function mount()
    {
        $this->loadCart();
        $this->customers = \App\Models\Customer::query()
            ->where('team_id', Filament::getTenant()->id)
            ->orderBy('name')
            ->get();
    }

    public function loadCart()
    {
        $this->cart = Session::get('cart', []);

        // Inicializar sell_quantity si faltara
        foreach ($this->cart as $id => &$item) {
            if (! isset($item['sell_quantity'])) {
                $item['sell_quantity'] = $item['quantity'];
            }
        }
    }

    public function updatedCart($value, $key)
    {
        // $key podría ser: "123.sell_quantity"
        [$inventoryId, $field] = explode('.', $key);

        if ($field !== 'sell_quantity' || ! isset($this->cart[$inventoryId])) {
            return;
        }

        // Solo permitir vender entre 1 y la cantidad total disponible
        $maxQty = intval($this->cart[$inventoryId]['quantity']);
        $sellQty = intval($this->cart[$inventoryId]['sell_quantity']);
        if ($sellQty < 1) {
            $sellQty = 1;
        } elseif ($sellQty > $maxQty) {
            $sellQty = $maxQty;
        }

        $this->cart[$inventoryId]['sell_quantity'] = $sellQty;

        // Persistimos el cambio en sesión
        $cart = Session::get('cart', []);
        if (isset($cart[$inventoryId])) {
            $cart[$inventoryId]['sell_quantity'] = $sellQty;
            Session::put('cart', $cart);
        }
    }

    public function addToCart($inventoryId)
    {
        $inventory = Inventory::find($inventoryId);

        $cart = Session::get('cart', []);

        if (isset($cart[$inventoryId])) {

            // Si el artículo ya está en el carrito, incrementar la cantidad de venta
            //$cart[$inventoryId]['quantity']++;
            $cart[$inventoryId]['sell_quantity']++;

            /* if (! isset($cart[$inventoryId]['sell_quantity_override'])) {
                $cart[$inventoryId]['sell_quantity'] = $cart[$inventoryId]['quantity'];
            } */
        } else {
            $cart[$inventoryId] = [
                'inventory_id'  => $inventory->id,
                'batch_code'    => $inventory->batch_code,
                'product_name'  => $inventory->product_name,
                'sale_price'    => $inventory->product->peripheralPrice?->sale_price ?? 0,
                'quantity'      => $inventory->quantity,
                'sell_quantity' => 1,
            ];
        }
        $this->cart = $cart;
        Session::put('cart', $this->cart);
        //$this->loadCart();
    }

    public function removeFromCart($inventoryId)
    {
        $cart = $this->cart;
        if (isset($cart[$inventoryId])) {
            unset($cart[$inventoryId]);
            Session::put('cart', $cart);
            $this->cart = $cart;
        }
    }

    public function clearCart()
    {
        Session::forget('cart');
        $this->loadCart();
    }

    public function confirmCheckout()
    {
        // Reforzar sincronía antes de procesar
        $this->loadCart();

        if (empty($this->cart)) {
            $this->dispatch('notify', ['type' => 'warning', 'message' => 'El carrito está vacío.']);
            return;
        }

        // Mostrar modal de facturación electrónica
        //$this->showFacturacionModal = true;
        $this->dispatch('open-modal', id: 'facturacion-modal');
    }

    public function facturacionRespuesta($respuesta)
    {
        $this->emitirFactura = (bool) $respuesta;
        //$this->showFacturacionModal = false;

        // Si se requiere factura, mostrar modal de cliente
        if ($this->emitirFactura) {
            //$this->showClienteModal = true;
            $this->dispatch('open-modal', id: 'cliente-modal');
        } else {
            // Cliente genérico
            $this->procesarVenta(88888888);
        }
    }

    public function saveCustomerAndCheckout()
    {
        // Validar selección o creación de cliente
        if ($this->selectedClienteId) {
            $clienteId = $this->selectedClienteId;
        } elseif ($this->newCustomerName && $this->newCustomerDocument) {
            // Crear cliente nuevo
            $cliente = \App\Models\Customer::create([
                'name'              => $this->newCustomerName,
                'identification'    => $this->newCustomerDocument,
                'address'           => $this->newCustomerAddress,
                'email'             => $this->newCustomerEmail,
                'phonenumber'       => $this->newCustomerPhone,
            ]);
            $clienteId = $cliente->id;
        } else {
            $this->dispatch('open-modal', id: 'cliente-validacion-modal');
            return;
        }
        $this->procesarVenta($clienteId);
    }

    protected function procesarVenta($clienteId)
    {
        $tenant = Filament::getTenant();
        $total = 0;
        $items = [];

        foreach ($this->cart as $item) {
            $qty = intval($item['sell_quantity']);
            $price = floatval($item['sale_price']);
            $total += $price * $qty;
            $items[] = compact('qty', 'price') + ['inventory_id' => $item['inventory_id']];
        }

        $sale = Sale::create([
            'team_id'     => $tenant->id,
            'customer_id' => $clienteId,
            'user_id'     => Auth::id(),
            'total'       => $total,
            'status'      => 'completed',
            'code'        => (new \App\Models\Sale())->generateCode(),
            'data'        => null,
        ]);

        foreach ($items as $i) {
            SaleItem::create([
                'sale_id'      => $sale->id,
                'inventory_id' => $i['inventory_id'],
                'quantity'     => $i['qty'],
                'sale_price'   => $i['price'],
                'total'        => $i['price'] * $i['qty'],
            ]);
        }

        $this->clearCart();

        // Redirigir al detalle de la venta
        Redirect::to(
            \App\Filament\Resources\SaleResource::getUrl('view', ['record' => $sale->id])
        );
    }


    public function render()
    {
        $inventories = Inventory::where('team_id', Filament::getTenant()->id)
            ->where('quantity', '>', 0)
            ->get();
        //$customers = \App\Models\Customer::where('team_id', Filament::getTenant()->id)->get();

        return view('livewire.pos.inventory-cart-component', [
            'inventories' => $inventories,
            'customers'   => $this->customers,
        ]);
    }
}
