<div>
    @if (!request()->routeIs(['filament.admin.pages.dashboard']))
        <x-filament::button size="xs" color="success" href="{{ route('filament.pos.pages.sales', $teamId) }}" tag="a">
            Secretaría de Salud
        </x-filament::button>
    @endif
</div>