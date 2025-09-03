<x-filament::page>
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2">
            <x-filament::card>
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-xl font-bold">Misión</h2>
                        <p class="mt-2 text-sm text-gray-700">{{ $mission ?? 'No definida' }}</p>
                    </div>
                </div>
            </x-filament::card>
            <div class="mt-6"></div>
            <x-filament::card>
                <div class="flex items-center justify-between">
                    <div class="text-right">
                        <h2 class="text-xl font-bold">Visión</h2>
                        <p class="mt-2 text-sm text-gray-700">{{ $vision ?? 'No definida' }}</p>
                    </div>
                </div>
            </x-filament::card>
            <div class="mt-6"></div>
            <x-filament::card>
                <h3 class="text-lg font-semibold">Política de calidad</h3>
                <p class="mt-3 text-sm text-gray-700">{{ $quality_policy ?? 'No definida' }}</p>
            </x-filament::card>
            <div class="mt-6"></div>
            <x-filament::card>
                <h3 class="text-lg font-semibold">Valores</h3>
                @if(!empty($values))
                    <dl class="mt-3 space-y-3">
                        @foreach($values as $key => $val)
                            <div class="p-3 bg-white border rounded shadow-sm">
                                <dt class="text-sm font-medium text-gray-700">{{ $key }}</dt>
                                <dd class="text-sm text-gray-600">
                                    {{ is_array($val) ? ($val['label'] ?? json_encode($val)) : $val }}
                                </dd>
                            </div>
                        @endforeach
                    </dl>
                @else
                    <p class="text-sm text-gray-500">No hay valores definidos.</p>
                @endif
            </x-filament::card>
            <div class="mt-6"></div>
            <x-filament::card>
                <h3 class="text-lg font-semibold">Objetivos de Calidad</h3>
                @if(!empty($quality_objectives))
                    <dl class="mt-3 space-y-3">
                        @foreach($quality_objectives as $key => $obj)
                            <div class="p-3 bg-white border rounded shadow-sm">
                                <dt class="text-sm font-medium">{{ $key }}</dt>
                                <dd>
                                    <div class="font-medium text-gray-700">
                                        {{ is_array($obj) ? ($obj['title'] ?? json_encode($obj)) : $obj }}
                                    </div>
                                    @if(is_array($obj) && isset($obj['target']))
                                        <div class="text-xs text-gray-500">Meta: {{ $obj['target'] }}</div>
                                    @endif
                                </dd>
                            </div>
                        @endforeach
                    </dl>
                @else
                    <p class="text-sm text-gray-500">No hay objetivos definidos.</p>
                @endif
            </x-filament::card>
            <div class="mt-6"></div>
            <x-filament::card>
                <h3 class="text-lg font-semibold">Indicadores de Gestión</h3>
                @if(!empty($management_indicators))
                    <dl class="mt-3 space-y-3">
                        @foreach($management_indicators as $key => $ind)
                            <div class="p-3 border rounded bg-white shadow-sm">
                                <dt class="text-sm font-medium">{{ $key }}</dt>
                                <dd>
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <div class="text-sm font-medium">
                                                {{ is_array($ind) ? ($ind['name'] ?? json_encode($ind)) : $ind }}
                                            </div>
                                            @if(is_array($ind) && isset($ind['definition']))
                                                <div class="text-xs text-gray-500">{{ $ind['definition'] }}</div>
                                            @endif
                                        </div>
                                        @if(is_array($ind) && isset($ind['value']))
                                            <div class="text-sm font-semibold">{{ $ind['value'] }}</div>
                                        @endif
                                    </div>
                                </dd>
                            </div>
                        @endforeach
                    </dl>
                @else
                    <p class="text-sm text-gray-500">No hay indicadores definidos.</p>
                @endif
            </x-filament::card>
        </div>
    </div>
</x-filament::page>
