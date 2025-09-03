<x-filament-panels::page>
    <x-settings.group-wrapper title="Recurso Humano — detalles">
        @foreach ($groups['Recursos Humanos'] ?? [] as $s)
            <x-settings.field-row
                :label="$s['label'] ?? $s['key']"
                :value="$s['value'] ?? null"
                :type="$s['type'] ?? 'text'"
                :key="$s['key']"
                :attributes-data="$s['attributes'] ?? []"
            />
        @endforeach
    </x-settings.group-wrapper>
</x-filament-panels::page>