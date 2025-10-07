<!-- resources/views/filament/pages/cleaning-table-view-with-shifts.blade.php -->
<x-filament-panels::page>
    <div class="space-y-6">
        <!-- Header con estadísticas generales -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex justify-between items-center mb-4">
                <div>
                    <h2 class="text-xl font-semibold text-gray-900">
                        📋 Registro de Limpieza y Sanitización por Turnos
                    </h2>
                    <p class="text-sm text-gray-600">
                        Fecha: {{ \Carbon\Carbon::parse($this->fecha_seleccionada)->format('d/m/Y') }}
                        • {{ $this->total_registros }} registro(s) encontrado(s)
                    </p>
                </div>
            </div>

            <!-- Estadísticas por turno -->
            @if ($this->total_registros > 0)
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                    @php $stats = $this->getShiftStats(); @endphp
                    @foreach ($stats as $shift => $stat)
                        <div
                            class="bg-gradient-to-r from-{{ match ($shift) {
                                'mañana' => 'yellow-50 to-yellow-100 border-yellow-200',
                                'tarde' => 'blue-50 to-blue-100 border-blue-200',
                            
                                default => 'green-50 to-green-100 border-green-200',
                            } }} border rounded-lg p-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">
                                        {{ match ($shift) {
                                            'mañana' => '🌅 Mañana',
                                            'tarde' => '☀️ Tarde',
                                        
                                            default => '🕐 Día Completo',
                                        } }}
                                    </p>
                                    <p class="text-xs text-gray-600">
                                        {{ $stat['registros'] }} registro(s)
                                    </p>
                                </div>
                                <div class="text-right">
                                    <p class="text-lg font-bold text-gray-900">
                                        {{ $stat['porcentaje'] }}%
                                    </p>
                                    <p class="text-xs text-gray-600">
                                        {{ $stat['areas_completadas'] }}/{{ $stat['total_areas'] }} áreas
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        @if ($this->total_registros == 0)
            <!-- Estado vacío -->
            <div class="bg-white rounded-lg shadow p-12 text-center">
                <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                        </path>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Sin registros para esta fecha</h3>
                <p class="text-gray-500 mb-4">No se encontraron registros de limpieza para el
                    {{ \Carbon\Carbon::parse($this->fecha_seleccionada)->format('d/m/Y') }}</p>
                <a href="{{ \App\Filament\Resources\Quality\Records\Cleaning\CleaningRecordResource::getUrl('create') }}"
                    class="inline-flex items-center px-4 py-2 bg-primary-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-primary-700">
                    ➕ Crear Primer Registro
                </a>
            </div>
        @else
            <!-- Registros por turno -->
            @foreach ($this->registros_agrupados as $shift => $registros)
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <!-- Header del turno -->
                    <div
                        class="bg-gradient-to-r {{ match ($shift) {
                            'mañana' => 'from-yellow-400 to-yellow-500',
                            'tarde' => 'from-blue-400 to-blue-500',
                        
                            default => 'from-green-400 to-green-500',
                        } }} px-6 py-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <span class="text-2xl">
                                    {{ match ($shift) {
                                        'mañana' => '🌅',
                                        'tarde' => '☀️',
                                    
                                        default => '🕐',
                                    } }}
                                </span>
                                <div>
                                    <h3 class="text-lg font-semibold text-white">
                                        {{ \App\Models\Quality\Records\Cleaning\CleaningRecord::getShifts()[$shift] ?? $shift }}
                                    </h3>
                                    <p class="text-sm text-white/80">
                                        {{ $registros->count() }} registro(s) •
                                        {{ $registros->sum(fn($r) => count($r->cleaned_areas ?? [])) }} áreas
                                        procesadas
                                    </p>
                                </div>
                            </div>
                            <div class="text-right text-white">
                                <p class="text-sm">
                                    {{ $registros->first()?->start_time ? \Carbon\Carbon::parse($registros->first()->start_time)->format('H:i') : '' }}
                                    -
                                    {{ $registros->last()?->end_time ? \Carbon\Carbon::parse($registros->last()->end_time)->format('H:i') : '' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Detalles de responsables del turno -->
                    <div class="bg-gray-50 px-6 py-3 border-b">
                        <div class="flex items-center justify-between text-sm">
                            <div>
                                <span class="font-medium">👥 Responsables:</span>
                                {{ $registros->pluck('user.name')->filter()->unique()->implode(', ') ?: 'Sin asignar' }}
                            </div>
                            @if ($registros->whereNotNull('reviewed_by')->isNotEmpty())
                                <div>
                                    <span class="font-medium">✅ Supervisores:</span>
                                    {{ $registros->whereNotNull('reviewed_by')->pluck('reviewed_by')->unique()->implode(', ') }}
                                </div>
                            @endif
                        </div>

                        @if ($registros->whereNotNull('shift_notes')->isNotEmpty())
                            <div class="mt-2 text-sm text-gray-600">
                                <span class="font-medium">📝 Notas del turno:</span>
                                {{ $registros->whereNotNull('shift_notes')->pluck('shift_notes')->implode(' | ') }}
                            </div>
                        @endif
                    </div>

                    <!-- Tabla específica del turno -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full border-collapse">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th rowspan="2"
                                        class="border border-gray-300 px-3 py-2 text-left font-semibold text-xs">
                                        ÁREAS
                                    </th>
                                    <th colspan="3"
                                        class="border border-gray-300 px-2 py-1 text-center font-semibold bg-blue-50 text-xs">
                                        SUPERFICIES
                                    </th>
                                    <th colspan="3"
                                        class="border border-gray-300 px-2 py-1 text-center font-semibold bg-green-50 text-xs">
                                        ACTIVIDADES
                                    </th>
                                    <th colspan="{{ $desinfectants->count() }}"
                                        class="border border-gray-300 px-2 py-1 text-center font-semibold bg-yellow-50 text-xs">
                                        DESINFECTANTES
                                    </th>
                                    <!-- Cantidad utilizada -->
                                    <th rowspan="2"
                                        class="border border-gray-300 px-2 py-1 text-center font-semibold text-xs">
                                        CANTIDAD
                                    </th>
                                    <!-- Concentración utilizada -->
                                    <th rowspan="2"
                                        class="border border-gray-300 px-2 py-1 text-center font-semibold text-xs">
                                        CONCENTRACIÓN
                                    </th>
                                    <th rowspan="2"
                                        class="border border-gray-300 px-2 py-1 text-center font-semibold text-xs">
                                        PLAGAS
                                    </th>
                                    <th rowspan="2"
                                        class="border border-gray-300 px-2 py-1 text-center font-semibold text-xs">
                                        OBSERVACIONES
                                    </th>
                                </tr>
                                <tr>
                                    <th class="border border-gray-300 px-1 py-1 text-xs font-medium bg-blue-50">P</th>
                                    <th class="border border-gray-300 px-1 py-1 text-xs font-medium bg-blue-50">M</th>
                                    <th class="border border-gray-300 px-1 py-1 text-xs font-medium bg-blue-50">T</th>

                                    <th class="border border-gray-300 px-1 py-1 text-xs font-medium bg-green-50">L</th>
                                    <th class="border border-gray-300 px-1 py-1 text-xs font-medium bg-green-50">D</th>
                                    <th class="border border-gray-300 px-1 py-1 text-xs font-medium bg-green-50">S</th>

                                    @foreach ($desinfectants as $desinfectant)
                                        <th class="border border-gray-300 px-1 py-1 text-xs font-medium bg-yellow-50 writing-mode-vertical"
                                            title="{{ $desinfectant->name }}">
                                            {{ \Str::limit($desinfectant->active_ingredient, 6) }}
                                        </th>
                                    @endforeach
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($areas as $area)
                                    <tr class="hover:bg-gray-50">
                                        <td class="border border-gray-300 px-2 py-2 font-medium text-xs">
                                            <div>
                                                <span class="font-semibold">{{ $area->name }}</span>
                                                @if ($area->code)
                                                    <span class="text-gray-500">({{ $area->code }})</span>
                                                @endif
                                            </div>
                                        </td>

                                        <!-- Superficies -->
                                        <td class="border border-gray-300 px-1 py-1 text-center">
                                            @if ($this->estaLimpiada($registros, $area->id, 'floor'))
                                                <span class="text-green-600 font-bold">✓</span>
                                            @else
                                                <span class="text-gray-300">-</span>
                                            @endif
                                        </td>
                                        <td class="border border-gray-300 px-1 py-1 text-center">
                                            @if ($this->estaLimpiada($registros, $area->id, 'walls'))
                                                <span class="text-green-600 font-bold">✓</span>
                                            @else
                                                <span class="text-gray-300">-</span>
                                            @endif
                                        </td>
                                        <td class="border border-gray-300 px-1 py-1 text-center">
                                            @if ($this->estaLimpiada($registros, $area->id, 'ceiling'))
                                                <span class="text-green-600 font-bold">✓</span>
                                            @else
                                                <span class="text-gray-300">-</span>
                                            @endif
                                        </td>

                                        <!-- Actividades -->
                                        <td class="border border-gray-300 px-1 py-1 text-center">
                                            @if ($this->estaLimpiada($registros, $area->id, 'cleaned'))
                                                <span class="text-green-600 font-bold">✓</span>
                                            @else
                                                <span class="text-gray-300">-</span>
                                            @endif
                                        </td>
                                        <td class="border border-gray-300 px-1 py-1 text-center">
                                            @if ($this->estaLimpiada($registros, $area->id, 'desinfected'))
                                                <span class="text-green-600 font-bold">✓</span>
                                            @else
                                                <span class="text-gray-300">-</span>
                                            @endif
                                        </td>
                                        <td class="border border-gray-300 px-1 py-1 text-center">
                                            @if ($this->estaLimpiada($registros, $area->id, 'sanitized'))
                                                <span class="text-green-600 font-bold">✓</span>
                                            @else
                                                <span class="text-gray-300">-</span>
                                            @endif
                                        </td>
                                        <!-- Desinfectantes -->
                                        @foreach ($desinfectants as $desinfectant)
                                            <td class="border border-gray-300 px-2 py-2 text-center">
                                                @if ($this->desinfectanteUsadoEnArea($registros, $desinfectant->id, $area->id))
                                                    <span class="text-blue-600 font-bold text-lg">X</span>
                                                @else
                                                    <span class="text-gray-300">-</span>
                                                @endif
                                            </td>
                                        @endforeach
                                        <!-- Cantidad utilizada en esta área específica -->
                                        <td class="border border-gray-300 px-1 py-1 text-center text-xs">
                                            @php
                                                $cantidad = $this->getCantidadUsadaEnArea($registros, $area->id);
                                            @endphp
                                            {{ $cantidad ?: '-' }}
                                        </td>
                                        <!-- Concentración utilizada en esta área específica -->
                                        <td class="border border-gray-300 px-1 py-1 text-center text-xs">
                                            @php
                                                $concentracion = $this->getConcentracionUsadaEnArea(
                                                    $registros,
                                                    $area->id,
                                                );
                                            @endphp
                                            {{ $concentracion ?: '-' }}
                                        </td>
                                        <!-- Plagas -->
                                        <td class="border border-gray-300 px-1 py-1 text-center text-xs">
                                            @php $plagasResult = $this->tienePlagas($registros, $area->id); @endphp
                                            @if ($plagasResult)
                                                @if (str_contains($plagasResult, 'Sí hay'))
                                                    <span class="text-red-600 text-lg">⚠️</span>
                                                @else
                                                    <span class="text-green-600 text-lg">✅</span>
                                                @endif
                                            @else
                                                <span class="text-gray-300">-</span>
                                            @endif
                                        </td>

                                        <!-- Observaciones -->
                                        <td class="border border-gray-300 px-2 py-1 text-xs">
                                            {{ $this->tieneObservaciones($registros, $area->id) }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endforeach
        @endif

        <!-- Leyenda global -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-4">📖 Leyenda</h3>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 text-sm">
                <div>
                    <div class="font-medium mb-2 text-blue-600">Superficies:</div>
                    <div><strong>P:</strong> Piso</div>
                    <div><strong>M:</strong> Muros/Paredes</div>
                    <div><strong>T:</strong> Techo</div>
                </div>
                <div>
                    <div class="font-medium mb-2 text-green-600">Actividades:</div>
                    <div><strong>L:</strong> Limpiado</div>
                    <div><strong>D:</strong> Desinfectado</div>
                    <div><strong>S:</strong> Sanitizado</div>
                </div>
                <div>
                    <div class="font-medium mb-2 text-purple-600">Símbolos:</div>
                    <div><span class="text-green-600 font-bold">✓</span> Actividad realizada</div>
                    <div><span class="text-blue-600 font-bold">X</span> Desinfectante usado</div>
                    <div><span class="text-red-600">⚠️</span> Plagas encontradas</div>
                    <div><span class="text-green-600">✅</span> Sin plagas</div>
                </div>
                <div>
                    <div class="font-medium mb-2 text-gray-600">Estado:</div>
                    <div><span class="text-gray-300">-</span> No realizado/aplicable</div>
                    <div>🌅 Turno Mañana</div>
                    <div>☀️ Turno Tarde</div>
                    <div>🌙 Turno Noche</div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .writing-mode-vertical {
            writing-mode: vertical-lr;
            text-orientation: mixed;
            white-space: nowrap;
            min-width: 20px;
            max-width: 30px;
        }
    </style>
</x-filament-panels::page>
