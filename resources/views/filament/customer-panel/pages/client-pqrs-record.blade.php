<x-filament-panels::page>
    @if (! $this->team)
        <x-filament::section>
            <div class="text-sm text-gray-600">
                No se encontro el equipo asociado a esta PQRS. Verifica el enlace o solicita un nuevo QR.
            </div>
        </x-filament::section>
    @else
        <form wire:submit="store" class="space-y-6">
            <div class="text-sm text-gray-500">
                {{ $this->team->name }}
            </div>

            {{ $this->form }}
        </form>
    @endif
</x-filament-panels::page>
