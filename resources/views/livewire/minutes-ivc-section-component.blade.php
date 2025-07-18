<div class="space-y-2">
    <x-filament::section 
    collapsible
    collapsed
    icon="phosphor-magnifying-glass"
    icon-color="info">

        <x-slot name="heading">
            Acta de la Secretaría de Salud.
        </x-slot>

    <x-slot name="description">
        En esta sección encontrarás acceso con facilidad todo lo que debes presentar al inspector de la Secreatría de salud.
    </x-slot>
        @foreach ($sections as $section)
            @if (Route::has($section->slug))
                <a href="{{ route($section->slug, $tenant) }}"
                    class="block px-2 py-2 hover:bg-gray-100 rounded text-blue-600">
                    {{ $section->order }}. {{ $section->name }}
                </a>
            @else
                <div class="block px-2 py-2 text-gray-400 cursor-not-allowed">
                    {{ $section->order }}. {{ $section->name }}
                </div>
            @endif
        @endforeach
    </x-filament::section>
</div>
