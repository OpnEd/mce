<div class="space-y-2">
    <x-filament::section>

        <x-slot name="heading">
            Índice
        </x-slot>
        @foreach ($sections as $section)
            @if (Route::has($section->slug))
                <a href="{{ route($section->slug, $tenant) }}"
                    class="block px-2 py-1 hover:bg-gray-100 rounded text-blue-600">
                    {{ $section->order }}. {{ $section->name }}
                </a>
            @else
                <div class="block px-2 py-1 text-gray-400 cursor-not-allowed">
                    {{ $section->order }}. {{ $section->name }}
                </div>
            @endif
        @endforeach
    </x-filament::section>
</div>
