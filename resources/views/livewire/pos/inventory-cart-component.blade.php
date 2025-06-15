<div>
<x-filament-field
    <h2 class="text-lg font-bold mb-4">Carrito de Compras</h2>
    @if(empty($cart))
        <p>No hay artículos en el carrito.</p>
    @else
        <table class="w-full mb-4">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Cant.</th>
                    <th>Precio</th>
                    <th>Total</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($cart as $item)
                    <tr>
                        <td>{{ $item['product_name'] }}</td>
                        <td>{{ $item['quantity'] }}</td>
                        <td>{{ number_format($item['sale_price'],2) }}</td>
                        <td>{{ number_format($item['sale_price'] * $item['quantity'],2) }}</td>
                        <td>
                            <button wire:click="removeFromCart({{ $item['inventory_id'] }})" class="text-red-500">Eliminar</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <button wire:click="clearCart" class="px-3 py-1 bg-gray-500 text-white rounded mr-2">Vaciar carrito</button>
        <button wire:click="checkout" class="px-3 py-1 bg-green-600 text-white rounded">Crear Venta</button>
    @endif
</div>
