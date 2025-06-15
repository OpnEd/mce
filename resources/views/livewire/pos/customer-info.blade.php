<x-filament::fieldset>
@if($record)

    <table class="w-full">
        <tr>
            <td class="font-semibold pr-2">Nombre:</td>
            <td>{{ $record->customer->name ?? 'No registrado' }}</td>
        </tr>
        <tr>
            <td class="font-semibold pr-2">Dirección:</td>
            <td>{{ $record->customer->address ?? 'No registrado' }}</td>
        </tr>
        <tr>
            <td class="font-semibold pr-2">Email:</td>
            <td>{{ $record->customer->email ?? 'No registrado' }}</td>
        </tr>
        <tr>
            <td class="font-semibold pr-2">Teléfono:</td>
            <td>{{ $record->customer->phonenumber ?? 'No registrado' }}</td>
        </tr>
    </table>
    
@else
    <div class="p-4 bg-gray-50 rounded shadow text-gray-500">
        No se encontró información del cliente.
    </div>
@endif
</x-filament::fieldset>
