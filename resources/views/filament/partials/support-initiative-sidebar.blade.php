@php
    $tenant = \Filament\Facades\Filament::getTenant();
@endphp

@if (auth()->check() && $tenant)
    @php
        $supportUrl = \App\Filament\Pages\SupportInitiative::getUrl(panel: 'admin', tenant: $tenant);
        $isActive = request()->routeIs(\App\Filament\Pages\SupportInitiative::getRouteName('admin'));
    @endphp

    <div class="mx-3 my-4 rounded-lg bg-white p-4 shadow dark:bg-gray-800">
        <div class="flex items-start gap-3">
            <div class="mt-0.5 flex h-10 w-10 items-center justify-center rounded-lg bg-gray-100 text-primary-700 dark:bg-gray-700 dark:text-primary-300">
                <x-filament::icon icon="heroicon-o-heart" class="h-5 w-5" />
            </div>

            <div class="min-w-0 flex-1">
                <h3 class="text-sm font-semibold text-gray-950 dark:text-white">
                    Esta app sigue viva gracias al apoyo de usuarios
                </h3>

                <p class="mt-1 text-xs leading-5 text-gray-600 dark:text-gray-400">
                    Si te está ayudando, puedes apoyar su continuidad con una compra voluntaria.
                </p>
            </div>
        </div>

        <div class="mt-4">
            <x-filament::button
                tag="a"
                :href="$supportUrl"
                wire:navigate
                :color="$isActive ? 'gray' : 'primary'"
                size="sm"
                class="w-full justify-center"
            >
                {{ $isActive ? 'Ya estás aquí' : 'Apoyar la iniciativa' }}
            </x-filament::button>
        </div>

        <p class="mt-3 text-[11px] leading-4 text-gray-500 dark:text-gray-400">
            Uso gratuito, apoyo voluntario.
        </p>
    </div>
@endif
