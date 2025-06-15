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

class InventoryCartComponent extends Component implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;
    
    public function table(Table $table): Table
    {
        return $table
            ->query(Inventory::query())
            ->columns([
                TextColumn::make('name'),
            ])
            ->filters([
                // ...
            ])
            ->actions([
                // ...
            ])
            ->bulkActions([
                // ...
            ]);
    }
    public $cart = [];

    protected $listeners = [
        'refreshCart' => 'loadCart',
    ];

    public function mount()
    {
        $this->loadCart();
    }

    public function loadCart()
    {
        $this->cart = Session::get('cart', []);
    }

    public function addToCart($inventoryId)
    {
        $inventory = Inventory::find($inventoryId);

        $cart = Session::get('cart', []);
        
        if (isset($cart[$inventoryId])) {
            $cart[$inventoryId]['quantity']++;
        } else {
            $cart[$inventoryId] = [
                'inventory_id' => $inventory->id,
                'product_name' => $inventory->product_name,
                'sale_price'   => $inventory->sale_price,
                'quantity'     => 1,
            ];
        }
        Session::put('cart', $cart);
        $this->loadCart();
        $this->dispatchBrowserEvent('notify', ['type' => 'success', 'message' => 'Artículo agregado al carrito.']);
    }

    public function removeFromCart($inventoryId)
    {
        $cart = Session::get('cart', []);
        if (isset($cart[$inventoryId])) {
            unset($cart[$inventoryId]);
            Session::put('cart', $cart);
            $this->loadCart();
            $this->dispatchBrowserEvent('notify', ['type' => 'success', 'message' => 'Artículo removido del carrito.']);
        }
    }

    public function clearCart()
    {
        Session::forget('cart');
        $this->loadCart();
    }

    public function checkout()
    {
        if (empty($this->cart)) {
            $this->dispatchBrowserEvent('notify', ['type' => 'warning', 'message' => 'El carrito está vacío.']);
            return;
        }
        $tenant = Filament::getTenant();
        $sale = Sale::create([
            'team_id'     => $tenant->id,
            'customer_id' => null,
            'user_id'     => Auth::id(),
            'total'       => collect($this->cart)->sum(fn($i) => $i['sale_price'] * $i['quantity']),
            'data'        => null,
        ]);
        foreach ($this->cart as $item) {
            SaleItem::create([
                'sale_id'      => $sale->id,
                'inventory_id' => $item['inventory_id'],
                'quantity'     => $item['quantity'],
                'sale_price'   => $item['sale_price'],
                'total'        => $item['sale_price'] * $item['quantity'],
            ]);
        }
        $this->clearCart();
        return redirect()->to(route('filament.resources.sales.edit', ['record' => $sale->id]));
    }

    public function render()
    {
        $inventories = Inventory::where('team_id', Filament::getTenant()->id)
            ->where('quantity', '>', 0)
            ->get();

        return view('livewire.pos.inventory-cart-component', [
            'inventories' => $inventories,
        ]);
    }
}
