<x-filament-panels::page>
    {{ $this->form }}
    <div class="flex items-center justify-end space-x-2">
        <x-filament::button  color="info" wire:click="populateSelected">
            Poblar seleccionado
        </x-filament::button>
    </div>
</x-filament-panels::page>
