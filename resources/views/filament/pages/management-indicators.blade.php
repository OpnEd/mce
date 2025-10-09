<x-filament-panels::page>

    <x-filament::section collapsible collapsed icon="phosphor-hand-coins" icon-color="success" icon-size="lg">
        
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
            @livewire('ficha-tecnica-indicador', ['indicador' => 'Recepción'])

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

    <x-filament::section collapsible collapsed icon="phosphor-hand-pointing" icon-color="success" icon-size="lg">

        <x-slot name="heading">
            Selección de productos
        </x-slot>

        <x-slot name="description">
            # de productos no dispensados por no contar con existencias por considerarse de baja rotación, alto costo o control especial.
        </x-slot>

        <x-filament::modal icon="phosphor-file-text" width="3xl" :close-button="false">

            <x-slot name="trigger">
                <x-filament::button>
                    Ficha técnica
                </x-filament::button>
            </x-slot>

            <x-slot name="heading">
                Indicador Selección - Ficha técnica
            </x-slot>

            {{-- Modal content --}}
            @livewire('ficha-tecnica-indicador', ['indicador' => 'Selección'])

        </x-filament::modal>

        {{-- Section content --}}
        <div class="flex flex-row justify-center gap-4 py-3">
            <div>
                @livewire(App\Filament\Resources\Quality\Records\Products\PurchaseResource\Widgets\MissingProductSelectionChart::class)
            </div>
        </div>

    </x-filament::section>

    <x-filament::section collapsible collapsed icon="phosphor-shopping-cart" icon-color="success" icon-size="lg">

        <x-slot name="heading">
            Adquisición de productos
        </x-slot>

        <x-slot name="description">
            # de productos de alta rotación no dispensados por no contar con existencias debido a fallas en el proceso de adquisición.
        </x-slot>

        <x-filament::modal icon="phosphor-file-text" width="3xl" :close-button="false">

            <x-slot name="trigger">
                <x-filament::button>
                    Ficha técnica
                </x-filament::button>
            </x-slot>

            <x-slot name="heading">
                Indicador Adquisición - Ficha técnica
            </x-slot>

            {{-- Modal content --}}
            @livewire('ficha-tecnica-indicador', ['indicador' => 'Adquisición'])

        </x-filament::modal>

        {{-- Section content --}}
        <div class="flex flex-row justify-center gap-4 py-3">
            <div>
                @livewire(App\Filament\Resources\Quality\Records\Products\PurchaseResource\Widgets\MissingProductAquisitionChart::class)
            </div>
            <div>
                @livewire(App\Filament\Resources\Quality\Records\Products\PurchaseResource\Widgets\MissingProductAquisitionProgressChart::class)
            </div>
        </div>

    </x-filament::section>

</x-filament-panels::page>
