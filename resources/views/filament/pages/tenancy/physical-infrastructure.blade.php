<x-filament-panels::page>

    @php
        $entries = collect($sectionEntries ?? []);
        $group3 = $entries->filter(fn ($entry) => str_starts_with((string) ($entry['entry_id'] ?? ''), '3.'))->values();

        $sectionDescription = minutesIvcSectionDescriptionFromConfig(
            ['Infraestructura Física', 'infraestructura-fisica', 3],
            'Sin descripción normativa disponible.'
        );
    @endphp

    <x-filament::section>
        <x-slot name="heading">3. Infraestructura Física</x-slot>
        <x-slot name="description">{{ $sectionDescription }}</x-slot>
    </x-filament::section>

    <x-settings.group-wrapper title="3. Infraestructura Física">
        @forelse ($group3 as $entry)

            <x-settings.field-row
                :label="$entry['question']"
                :value="$entry['answer']"
                :type="$entry['entry_type'] ?? 'text'"
                :key="$entry['entry_id']"
                :attributes-data="[
                    'links' => $entry['resolved_links'] ?? [],
                    'compliance' => $entry['compliance'] ?? null,
                    'criticality' => $entry['criticality'] ?? null,
                ]"
            />
        @empty
            <div class="px-4 py-6 text-sm text-gray-500">Sin registros en esta sección.</div>
        @endforelse
    </x-settings.group-wrapper>
</x-filament-panels::page>
