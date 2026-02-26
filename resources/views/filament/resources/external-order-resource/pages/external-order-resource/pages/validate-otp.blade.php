<x-filament-panels::page>
    <x-filament-widgets::widget>
        <x-filament::section>
            <div class="flex flex-col items-center justify-center space-y-6 py-8">
                
                <div class="text-center">
                    <h2 class="text-2xl font-bold text-gray-900">Validar Código de Entrega</h2>
                    <p class="mt-2 text-sm text-gray-600">
                        Orden: <span class="font-semibold">{{ $this->orderNumber }}</span>
                    </p>
                    <p class="text-sm text-gray-600">
                        Cliente: <span class="font-semibold">{{ $this->customerName }}</span>
                    </p>
                </div>

                <form wire:submit="validateCode" class="w-full max-w-sm space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Código de 4 dígitos
                        </label>
                        <input
                            type="text"
                            wire:model="otpInput"
                            maxlength="4"
                            inputmode="numeric"
                            placeholder="0000"
                            class="w-full text-center text-3xl tracking-widest font-bold border-2 border-gray-300 rounded-lg p-4 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition"
                            autofocus
                        />
                    </div>

                    <button
                        type="submit"
                        class="w-full bg-green-500 hover:bg-green-600 text-white font-bold py-3 px-4 rounded-lg transition"
                    >
                        Validar Código
                    </button>
                </form>

                <div class="text-center text-sm text-gray-500">
                    <p>El código fue enviado al cliente por WhatsApp.</p>
                </div>

            </div>
        </x-filament::section>
    </x-filament-widgets::widget>

</x-filament-panels::page>
