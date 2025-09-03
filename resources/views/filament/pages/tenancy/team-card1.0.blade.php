<x-filament-panels::page>
    <x-filament::section>
        <x-slot name="heading">
            Cédula del Establecimiento
        </x-slot>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($groups['Cédula del Establecimiento'] ?? [] as $s)
                <x-filament::card class="h-full">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <h3 class="text-sm font-medium">{{ $s['label'] ?? $s['key'] }}</h3>
                            @if (!empty($s['attributes']['description']))
                                <p class="text-xs text-gray-500 mt-1">
                                    {{ $s['attributes']['description'] }}
                                </p>
                            @endif
                        </div>

                        <div class="text-right">
                            <x-filament::badge size="sm">{{ $s['type'] ?? 'text' }}</x-filament::badge>
                        </div>
                    </div>

                    <div class="mt-4">
                        @php
                            $type = $s['type'] ?? 'text';
                            $value = $s['value'] ?? null;
                        @endphp

                        @switch($type)
                            @case('file')
                                @if ($value)
                                    <a href="{{ \Illuminate\Support\Facades\Storage::url($value) }}" target="_blank"
                                        class="inline-block text-sm underline">
                                        Ver archivo
                                    </a>
                                @else
                                    <p class="text-sm text-gray-500">Sin archivo.</p>
                                @endif
                            @break

                            @case('textarea')
                                @if ($value)
                                    <pre class="whitespace-pre-wrap text-sm">{{ $value }}</pre>
                                @elseif(!empty($s['data']))
                                    <pre class="whitespace-pre-wrap text-sm">
{{ json_encode($s['data'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}
                                </pre>
                                @else
                                    <p class="text-sm text-gray-500">Sin valor establecido.</p>
                                @endif
                            @break

                            @case('select')
                                @if ($value)
                                    <x-filament::badge size="sm">{{ $value }}</x-filament::badge>
                                @else
                                    <p class="text-sm text-gray-500">No seleccionado.</p>
                                @endif
                            @break

                            @case('boolean')
                                @if ($value === '1' || $value === 1 || $value === true || $value === 'true')
                                    <x-filament::badge color="success">Activado</x-filament::badge>
                                @else
                                    <x-filament::badge color="danger">Desactivado</x-filament::badge>
                                @endif
                            @break

                            @default
                                @if (!is_null($value) && $value !== '')
                                    <p class="text-sm">{{ $value }}</p>
                                @elseif(!empty($s['data']))
                                    <pre class="whitespace-pre-wrap text-sm">{{ json_encode($s['data'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                @else
                                    <p class="text-sm text-gray-500">Sin valor.</p>
                                @endif
                        @endswitch
                    </div>

                    {{-- Metadatos / ayuda --}}
                    @if (!empty($s['attributes']))
                        <div class="mt-4 text-xs text-gray-500">
                            @foreach ($s['attributes'] as $k => $v)
                                <div><strong>{{ $k }}:</strong> {{ is_scalar($v) ? $v : json_encode($v) }}
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <div class="mt-4 text-right text-xs text-gray-400">
                        {{-- muestro id para trazabilidad; opcional --}}
                        ID: {{ $s['tenant_setting_id'] ?? '-' }}
                    </div>
                </x-filament::card>
            @endforeach
        </div>
    </x-filament::section>

</x-filament-panels::page>
