@props([
    'label' => null,
    'value' => null,
    'type' => 'text',
    'help' => null,
    'key' => null,
    'attributesData' => [],
])

@php
    use Illuminate\Support\Facades\Storage;
    $type = $type ?? 'text';
    $value = $value ?? null;
    $attributesData = is_array($attributesData)
        ? $attributesData
        : (is_string($attributesData)
            ? json_decode($attributesData, true)
            : []);
    $criticalityColors = [
        'Crítico' => 'danger',
        'Mayor' => 'warning',
        'menor' => 'info',
    ];
    $criticality = $attributesData['criticality'] ?? null;
    $color = $criticalityColors[$criticality] ?? 'secondary';
@endphp

<div class="px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 border-b last:border-b-0">
    <dt class="text-sm font-medium text-gray-600 flex items-center gap-4">
        <p>{{ $key . ' - ' . $label }}</p>
        @if (!empty($criticality))
            <x-filament::badge size="xs" color="{{ $color }}">
                {{ $criticality }}
            </x-filament::badge>
        @endif
    </dt>

    <dd class="mt-1 ml-3 text-sm text-gray-900 sm:col-span-2 sm:mt-0 flex flex-col gap-3">
        {{-- Valor principal según tipo --}}
        @if ($type === 'file')
            @if ($value)
                <a href="{{ Storage::url($value) }}" target="_blank" class="underline text-sm">Ver documento</a>
            @else
                <span class="text-gray-500">Sin archivo</span>
            @endif
        @elseif ($type === 'boolean')
            @if ($value === '1' || $value === 1 || $value === true || $value === 'true')
                <x-filament::badge color="success">Sí</x-filament::badge>
            @else
                <x-filament::badge color="danger">No</x-filament::badge>
            @endif
        @else
            <span class="text-sm">{{ $value !== '' && $value !== null ? $value : '-' }}</span>
        @endif

        {{-- Compliance --}}
        {{-- @if (isset($attributesData['compliance']))
            <span class="text-xs">
                Cumplimiento:
                @if ($attributesData['compliance'])
                    <x-filament::badge color="success">Sí</x-filament::badge>
                @else
                    <x-filament::badge color="danger">No</x-filament::badge>
                @endif
            </span>
        @endif --}}

        <div class="minutes-entry">
            {{-- Render principal según tipo --}}


            {{-- Separador visual --}}
            <div class="mt-2"></div>

            {{-- Links resueltos (si los hay) --}}
            @if (isset($attributesData['links']) && is_array($attributesData['links']))
                <div class="space-y-1 text-sm">
                    @foreach ($attributesData['links'] as $l)
                        @if ($l['type'] === 'route')
                            <a href="{{ $l['url'] }}" target="_blank" class="underline">
                                {{ $l['label'] }}
                            </a>
                        @elseif ($l['type'] === 'folder')
                            <p class="text-sm">{{ $l['text'] }}</p>
                        @else
                            {{-- por si aparecen otros tipos inesperados --}}
                            <span class="text-gray-500">Link no disponible</span>
                        @endif
                    @endforeach
                </div>
            @endif
        </div>


        {{-- Ayuda --}}
        @if (!empty($attributesData['help'] ?? $help))
            <p class="text-xs text-gray-500 mt-1">{{ $attributesData['help'] ?? $help }}</p>
        @endif
    </dd>
</div>
