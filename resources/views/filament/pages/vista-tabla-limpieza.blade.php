<!-- resources/views/filament/pages/vista-tabla-limpieza.blade.php -->
<x-filament-panels::page>
    <div class="space-y-6">
        <!-- Header con fecha seleccionada -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex justify-between items-center mb-4">
                <div>
                    <h2 class="text-xl font-semibold text-gray-900">
                        Registro de Limpieza y Sanitización
                    </h2>
                    <p class="text-sm text-gray-600">
                        Fecha: {{ \Carbon\Carbon::parse($this->fecha_seleccionada)->format('d/m/Y') }}
                    </p>
                </div>
            </div>

            <!-- Tabla principal estilo papel -->
            <div class="overflow-x-auto">
                <table class="min-w-full border-collapse border border-gray-300">
                    <!-- Header principal -->
                    <thead class="bg-gray-50">
                        <tr>
                            <th rowspan="2" class="border border-gray-300 px-3 py-2 text-left font-semibold">
                                ÁREAS
                            </th>
                            <th colspan="3" class="border border-gray-300 px-3 py-2 text-center font-semibold bg-blue-50">
                                ACTIVIDADES
                            </th>
                            <th colspan="{{ $sustancias->count() }}" class="border border-gray-300 px-3 py-2 text-center font-semibold bg-green-50">
                                SUSTANCIAS UTILIZADAS
                            </th>
                            <th rowspan="2" class="border border-gray-300 px-3 py-2 text-center font-semibold">
                                OBSERVACIONES
                            </th>
                        </tr>
                        <tr>
                            <!-- Subheader actividades -->
                            <th class="border border-gray-300 px-2 py-1 text-xs font-medium bg-blue-50">L</th>
                            <th class="border border-gray-300 px-2 py-1 text-xs font-medium bg-blue-50">D</th>
                            <th class="border border-gray-300 px-2 py-1 text-xs font-medium bg-blue-50">S</th>
                            
                            <!-- Subheader sustancias -->
                            @foreach($sustancias as $sustancia)
                                <th class="border border-gray-300 px-2 py-1 text-xs font-medium bg-green-50 writing-mode-vertical" 
                                    title="{{ $sustancia->nombre }} ({{ $sustancia->concentracion }})">
                                    {{ $sustancia->principio_activo }}
                                </th>
                            @endforeach
                        </tr>
                    </thead>

                    <!-- Body de la tabla -->
                    <tbody>
                        @foreach($areas as $area)
                            <tr class="hover:bg-gray-50">
                                <!-- Nombre del área -->
                                <td class="border border-gray-300 px-3 py-2 font-medium">
                                    <div>
                                        <span class="text-sm font-semibold">{{ $area->nombre }}</span>
                                        @if($area->codigo)
                                            <span class="text-xs text-gray-500">({{ $area->codigo }})</span>
                                        @endif
                                    </div>
                                    <div class="text-xs text-gray-400">
                                        {{ $area->tipo_label }} - {{ $area->frecuencia_label }}
                                    </div>
                                </td>

                                <!-- Actividades (L, D, S) -->
                                <td class="border border-gray-300 px-2 py-2 text-center">
                                    @if($this->estaLimpiada($area->id, 'limpiado'))
                                        <span class="text-green-600 font-bold text-lg">✓</span>
                                    @else
                                        <span class="text-gray-300">-</span>
                                    @endif
                                </td>
                                <td class="border border-gray-300 px-2 py-2 text-center">
                                    @if($this->estaLimpiada($area->id, 'desinfectado'))
                                        <span class="text-green-600 font-bold text-lg">✓</span>
                                    @else
                                        <span class="text-gray-300">-</span>
                                    @endif
                                </td>
                                <td class="border border-gray-300 px-2 py-2 text-center">
                                    @if($this->estaLimpiada($area->id, 'sanitizado'))
                                        <span class="text-green-600 font-bold text-lg">✓</span>
                                    @else
                                        <span class="text-gray-300">-</span>
                                    @endif
                                </td>

                                <!-- Sustancias -->
                                @foreach($sustancias as $sustancia)
                                    <td class="border border-gray-300 px-2 py-2 text-center">
                                        @if($this->sustanciaUsada($sustancia->id))
                                            <span class="text-blue-600 font-bold text-lg">X</span>
                                        @else
                                            <span class="text-gray-300">-</span>
                                        @endif
                                    </td>
                                @endforeach

                                <!-- Observaciones para esta área -->
                                <td class="border border-gray-300 px-2 py-2 text-xs">
                                    @php
                                        $observaciones = collect();
                                        foreach($this->registros as $registro) {
                                            foreach(($registro->areas_limpiadas ?? []) as $areaReg) {
                                                if($areaReg['area_id'] == $area->id && !empty($areaReg['observaciones_area'])) {
                                                    $observaciones->push($areaReg['observaciones_area']);
                                                }
                                            }
                                        }
                                    @endphp
                                    {{ $observaciones->implode('; ') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Resumen y responsables -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Responsables -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold mb-4">Responsables</h3>
                @if($registros->isNotEmpty())
                    <div class="space-y-2">
                        @foreach($registros->unique('responsable') as $registro)
                            <div class="flex justify-between text-sm">
                                <span class="font-medium">{{ $registro->responsable }}</span>
                                <span class="text-gray-500">
                                    {{ $registro->hora_inicio ? \Carbon\Carbon::parse($registro->hora_inicio)->format('H:i') : '' }}
                                    -
                                    {{ $registro->hora_fin ? \Carbon\Carbon::parse($registro->hora_fin)->format('H:i') : '' }}
                                </span>
                            </div>
                        @endforeach
                        
                        @if($registros->whereNotNull('supervisor')->isNotEmpty())
                            <hr class="my-2">
                            <div class="text-xs text-gray-600">
                                <strong>Supervisores:</strong><br>
                                {{ $registros->whereNotNull('supervisor')->pluck('supervisor')->unique()->implode(', ') }}
                            </div>
                        @endif
                    </div>
                @else
                    <p class="text-gray-500 text-sm">No hay registros para esta fecha</p>
                @endif
            </div>

            <!-- Leyenda -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold mb-4">Leyenda</h3>
                <div class="space-y-2 text-sm">
                    <div class="flex items-center space-x-2">
                        <span class="text-green-600 font-bold text-lg">✓</span>
                        <span>Actividad realizada</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="text-blue-600 font-bold text-lg">X</span>
                        <span>Sustancia utilizada</span>
                    </div>
                    <div class="text-xs text-gray-600 mt-4">
                        <strong>L:</strong> Limpiado &nbsp;&nbsp;
                        <strong>D:</strong> Desinfectado &nbsp;&nbsp;
                        <strong>S:</strong> Sanitizado
                    </div>
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