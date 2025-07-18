<div>
    @if (!request()->routeIs(['filament.pos.pages.sales']))
        <x-filament::button size="xs" color="warning" href="{{ route('filament.pos.pages.sales', $teamId) }}" tag="a">
            Registro de venta
        </x-filament::button>
    @endif
</div>
