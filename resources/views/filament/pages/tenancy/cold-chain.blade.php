<x-filament-panels::page>
    <x-settings.group-wrapper title="Cadena de Frío — detalles">
        @foreach ($sectionEntries as $entry)
            <x-settings.field-row
                :label="$entry['question']"
                :value="$entry['answer']"
                :type="$entry['entry_type'] ?? 'text'"
                :key="$entry['entry_id']"
                :attributes-data="[
                    'links' => $entry['resolved_links'],
                    'compliance' => $entry['compliance'] ?? null,
                    'criticality' => $entry['criticality'] ?? null,
                ]"
            />
        @endforeach
    </x-settings.group-wrapper>
</x-filament-panels::page>
