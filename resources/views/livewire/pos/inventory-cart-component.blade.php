<div>
    <x-filament::fieldset>
        <h3 class="text-lg font-bold mb-4">Carrito de Compras</h3>
        <hr>
        @if (empty($cart))
            <p>No hay artículos en el carrito.</p>
        @else
            <table class="w-full mb-4 border-separate border-spacing-0 text-center">
                <thead>
                    <tr class="border-b border-gray-300">
                        <th class="py-2 border-b border-gray-300">Producto</th>
                        <th class="py-2 border-b border-gray-300">Lote</th>
                        <th class="py-2 border-b border-gray-300">Cantidad a vender</th>
                        <th class="py-2 border-b border-gray-300">Precio</th>
                        <th class="py-2 border-b border-gray-300">Total</th>
                        <th class="py-2 border-b border-gray-300"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($cart as $inventoryId => $item)
                        <tr class="border-b border-gray-200">
                            <td class="py-2">{{ $item['product_name'] }}</td>
                            <td class="py-2">{{ $item['batch_code'] }}</td>
                            <td class="py-2">
                                <input type="number" min="1" max="{{ $item['quantity'] }}"
                                    wire:model.lazy="cart.{{ $inventoryId }}.sell_quantity"
                                    class="w-16 text-center border rounded">
                            </td>
                            <td class="py-2">{{ number_format($item['sale_price'], 2) }}</td>
                            <td class="py-2">
                                {{ number_format($item['sale_price'] * ($item['sell_quantity'] ?? $item['quantity']), 2) }}
                            </td>
                            <td class="py-2">
                                <x-filament::button wire:click="removeFromCart({{ $inventoryId }})" color="danger">
                                    Eliminar
                                </x-filament::button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <x-filament::button wire:click="clearCart" color="danger">
                Vaciar Carrito
            </x-filament::button>
            <x-filament::button wire:click="confirmCheckout" color="success">
                Finalizar Compra
            </x-filament::button>

    <!-- Modal para facturación electrónica -->
            <x-filament::modal id="facturacion-modal" wire:model="showFacturacionModal" alignment="center"
                icon="phosphor-receipt">
                <x-slot name="header">
                    ¿Desea personalizar facturación electrónica?
                </x-slot>
                <x-slot name="footer">
                    <x-filament::button wire:click="facturacionRespuesta(true)" color="info">
                        Sí
                    </x-filament::button>
                    <x-filament::button wire:click="facturacionRespuesta(false)" color="success">
                        No
                    </x-filament::button>
                </x-slot>
            </x-filament::modal>

            <!-- Modal para seleccionar o crear cliente -->
            <x-filament::modal id="cliente-modal" wire:model="showClienteModal" alignment="center">
                <x-slot name="header">
                    Seleccionar o crear cliente
                </x-slot>
                <div class="space-y-4">
                    <div>
                        <x-filament::input.wrapper>
                            <x-filament::input.select wire:model="selectedClienteId">

                                <option value="">Seleccione un cliente</option>
                                @foreach ($customers as $customer)
                                    <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                @endforeach

                            </x-filament::input.select>
                        </x-filament::input.wrapper>
                    </div>
                    <div class="text-center">o</div>
                    <div class="space-y-3">

                        <p>Nuevo cliente</p>

                        <x-filament::input.wrapper>
                            <x-filament::input type="text" placeholder="Nombre" wire:model="newCustomerName" />
                        </x-filament::input.wrapper>

                        <x-filament::input.wrapper>
                            <x-filament::input type="text" placeholder="No. Identidad" wire:model="newCustomerDocument" />
                        </x-filament::input.wrapper>

                        <x-filament::input.wrapper>
                            <x-filament::input type="text" placeholder="Dirección" wire:model="newCustomerAddress" />
                        </x-filament::input.wrapper>

                        <x-filament::input.wrapper>
                            <x-filament::input type="text" placeholder="E-mail" wire:model="newCustomerEmail" />
                        </x-filament::input.wrapper>

                        <x-filament::input.wrapper>
                            <x-filament::input type="text" placeholder="Teléfono" wire:model="newCustomerPhone" />
                        </x-filament::input.wrapper>
                        
                    </div>
                </div>
                <x-slot name="footer">
                    <x-filament::button wire:click="saveCustomerAndCheckout" color="primary">
                        Guardar y continuar
                    </x-filament::button>
                </x-slot>
            </x-filament::modal>

            <x-filament::modal id="cliente-validacion-modal">
            <x-slot name="header">
                <p>Debes seleccionar o crear un cliente antes de continuar.</p>           
            </x-slot>
            </x-filament::modal>
        @endif
    </x-filament::fieldset>

    <br>

    {{ $this->table }}

</div>
