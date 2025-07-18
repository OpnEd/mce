<div class="p-4 space-y-4">
    <p><strong>Objetivo:</strong> {{ $fichaTecnica['objective'] }}</p>
    <p><strong>Descripción:</strong> {{ $fichaTecnica['description'] }}</p>
    <p><strong>Periodicidad:</strong> {{ $fichaTecnica['periodicity'] }}</p>
    <p><strong>Tipo:</strong> {{ $fichaTecnica['type'] }}</p>
    <p><strong>Fuente de información:</strong> {{ $fichaTecnica['information_source'] }}</p>
    <p><strong>Responsable:</strong> {{ $fichaTecnica['roleName'] }}</p>
    <p><strong>Objetivo de Calidad asociado:</strong> {{ $fichaTecnica['qualityGoal'] }}</p>
    <div class="flex flex-row justify-between align-middle">
        <div><p><strong>Fórmula:</strong></p></div>
        <div>
            <table class="table-auto border border-gray-600 rounded align-middle">
                <tbody>
                    @if ($fichaTecnica['type'] === 'Cardinal')
                        <tr class="border-b border-gray-600">
                            <td class="text-center px-4 py-2">{{ $fichaTecnica['numerator'] }}  </td>
                        </tr>
                    @else
                        <tr class="border-b border-gray-600">
                            <td class="text-center px-4 py-2">{{ $fichaTecnica['numerator'] }}  </td>
                        </tr>
                        <tr>
                            <td class="text-center px-4 py-2">{{ $fichaTecnica['denominator_description'] }}</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
        <div>
            <p><strong>Meta:</strong> {{ $fichaTecnica['goal'] }}
                @if ($fichaTecnica['type'] === 'Porcentual')
                    %
                @endif
            </p></div>
    </div>
</div>
