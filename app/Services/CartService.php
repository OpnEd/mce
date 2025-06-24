<?php
namespace App\Services;

use App\Models\Inventory;
use Illuminate\Support\Facades\Session;

class CartService
{
    protected const KEY = 'cart';

    public function get(): array
    {
        return Session::get(self::KEY, []);
    }

    public function set(array $cart): array
    {
        Session::put(self::KEY, $cart);
        return $cart;
    }

    public function clear(): array
    {
        Session::forget(self::KEY);
        return [];
    }

    /**
     * Agrega un item al carrito usando su ID de inventario.
     */
    public function add($inventoryId): array
    {
        $inventory = Inventory::find($inventoryId);
        
        $cart = $this->get();

        // Si no existe el inventario, retornamos el carrito actual.
        // Esto evita errores si el ID no es válido o el inventario ha sido eliminado.
        /* if (!$inventory) {
            return $this->get();
        } */

        if (isset($cart[$inventoryId])) {

            // Si el producto ya está, incrementamos la cantidad de venta.
            $cart[$inventoryId]['sell_quantity']++;

        } else {

            $cart[$inventoryId] = [
                'inventory_id'  => $inventory->id,
                'batch_code'    => $inventory->batch->code,
                'product_name'  => $inventory->product->name,
                'sale_price'    => $inventory->product->peripheralPrice?->sale_price ?? 0,
                'quantity'      => $inventory->quantity,
                'sell_quantity' => 1,
            ];

        }

        return $this->set($cart);
    }

    /**
     * Quita un item del carrito.
     */
    public function remove(int $inventoryId): array
    {
        $cart = $this->get();        
        
        if (isset($cart[$inventoryId])) {

            unset($cart[$inventoryId]);

            Session::put('cart', $cart);
        }

        return $cart;

    }

    /**
     * Actualiza la cantidad de un item en el carrito.
     */
    public function updateCartItem($inventoryId, $sellQuantity): array
    {
        $cart = $this->get();

        if (!isset($cart[$inventoryId])) {
            return $cart;
        }

        $maxQty = intval($cart[$inventoryId]['quantity']);

        $qty = intval($sellQuantity);

        if ($qty < 1) {

            $qty = 1;

        } elseif ($qty > $maxQty) {

            $qty = $maxQty;

        }

        $cart = $this->get();

        $cart[$inventoryId]['sell_quantity'] = $qty;

        return $this->set($cart);
    }

}
