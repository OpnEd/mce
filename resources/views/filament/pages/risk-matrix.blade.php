<x-filament-panels::page>
    <div class="space-y-6">
        <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-200">
            <form method="GET" class="flex flex-col gap-4 md:flex-row md:items-end">
                <div class="flex-1">
                    <label class="text-sm font-medium text-gray-700">Proceso</label>
                    <select name="process_id" class="mt-1 w-full rounded-lg border-gray-300">
                        <option value="">Todos los procesos</option>
                        @foreach ($processes as $process)
                            <option value="{{ $process->id }}" @selected($processId == $process->id)>
                                {{ $process->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <button type="submit" class="rounded-lg bg-primary-600 px-4 py-2 text-sm font-semibold text-white">
                        Filtrar
                    </button>
                </div>
            </form>
        </div>

        <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-200">
            <div class="px-6 py-4 text-sm text-gray-500">
                Total de riesgos: <span class="font-semibold text-gray-900">{{ $risks->count() }}</span>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50 text-gray-600">
                        <tr>
                            <th class="px-4 py-3 text-left font-semibold">Proceso</th>
                            <th class="px-4 py-3 text-left font-semibold">Actividad</th>
                            <th class="px-4 py-3 text-left font-semibold">Riesgo</th>
                            <th class="px-4 py-3 text-left font-semibold">Controles</th>
                            <th class="px-4 py-3 text-left font-semibold">P x I</th>
                            <th class="px-4 py-3 text-left font-semibold">Nivel</th>
                            <th class="px-4 py-3 text-left font-semibold">Residual</th>
                            <th class="px-4 py-3 text-left font-semibold">Estado</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($risks as $risk)
                            <tr>
                                <td class="px-4 py-3 text-gray-800">{{ $risk->process?->name ?? '-' }}</td>
                                <td class="px-4 py-3 text-gray-600">{{ $risk->activity ?? '-' }}</td>
                                <td class="px-4 py-3 font-medium text-gray-900">{{ $risk->title }}</td>
                                <td class="px-4 py-3 text-gray-600">{{ $risk->existing_controls ?? '-' }}</td>
                                <td class="px-4 py-3 text-gray-700">{{ $risk->risk_score ?? '-' }}</td>
                                <td class="px-4 py-3">
                                    @php
                                        $badgeClasses = [
                                            'success' => 'bg-green-50 text-green-700',
                                            'warning' => 'bg-yellow-50 text-yellow-700',
                                            'danger' => 'bg-red-50 text-red-700',
                                            'primary' => 'bg-blue-50 text-blue-700',
                                            'info' => 'bg-sky-50 text-sky-700',
                                            'gray' => 'bg-gray-50 text-gray-700',
                                        ];
                                        $badgeClass = $badgeClasses[$risk->riskLevelColor($risk->risk_level)] ?? $badgeClasses['gray'];
                                    @endphp
                                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $badgeClass }}">
                                        {{ $risk->riskLevelLabel($risk->risk_level) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-gray-700">
                                    {{ $risk->residual_score ? $risk->residual_score . ' (' . $risk->riskLevelLabel($risk->residual_level) . ')' : '-' }}
                                </td>
                                <td class="px-4 py-3 text-gray-700">{{ ucfirst(str_replace('_', ' ', $risk->status)) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-4 py-6 text-center text-gray-500">
                                    No hay riesgos registrados para el filtro seleccionado.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-filament-panels::page>
