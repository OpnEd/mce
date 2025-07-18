<x-filament-panels::page>

    <x-filament::section collapsible collapsed icon="phosphor-hand-coins" icon-color="info" icon-size="lg">

        <x-slot name="heading">
            Recepción técnica
        </x-slot>

        <x-slot name="description">
            Ordenes de compra Vs. Recepción técnica
        </x-slot>

        <x-filament::modal icon="phosphor-file-text" width="3xl" :close-button="false">

            <x-slot name="trigger">
                <x-filament::button>
                    Ficha técnica
                </x-filament::button>
            </x-slot>

            <x-slot name="heading">
                Indicador Recepción Técnica - Ficha técnica
            </x-slot>

            {{-- Modal content --}}
            @livewire('ficha-tecnica-indicador', ['indicador' => 'Recepción técnica'])

        </x-filament::modal>

        {{-- Section content --}}
        <div class="flex flex-row justify-center gap-4 py-3">
            <div>
                @livewire(App\Filament\Resources\ProductReceptionResource\Widgets\ProductReceptionProgressChart::class)
            </div>
            <div>
                @livewire(App\Filament\Resources\ProductReceptionResource\Widgets\ProductReceptionMonthlyChart::class)
            </div>
        </div>

    </x-filament::section>

</x-filament-panels::page>
