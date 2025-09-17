<div class="fi-page-header">
    {{-- Renderiza las migas de pan si existen para la página actual --}}
    @if ($breadcrumbs = $this->getBreadcrumbs())
        <x-filament::breadcrumbs :breadcrumbs="$breadcrumbs" class="mb-2 hidden md:block" />
    @endif

    <div class="fi-page-header-header flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        {{-- Renderiza el título de la página actual --}}
        <h1 class="fi-header-heading text-2xl font-bold tracking-tight text-gray-950 dark:text-white">
            {{ $this->getTitle() }}
        </h1>

        {{-- Renderiza las acciones de la cabecera (ej. botón de crear) si existen --}}
        @if ($actions = $this->getHeaderActions())
            <div class="fi-page-header-actions flex shrink-0 items-center gap-3 flex-wrap justify-start">
                @foreach ($actions as $action)
                    {{ $action }}
                @endforeach
            </div>
        @endif
    </div>
</div>
