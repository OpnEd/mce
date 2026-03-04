<x-filament-panels::page>

    @php
        $entries = collect($sectionEntries ?? []);

        $group4 = $entries->filter(fn ($entry) => str_starts_with((string) ($entry['entry_id'] ?? ''), '4.'))->values();

        $sectionDescription = minutesIvcSectionDescriptionFromConfig(
            ['Saneamiento de edificaciones', 'saneamiento-edificiones', 'filament.admin.pages.saneamiento-de-edificaciones', 4],
            'Sin descripción normativa disponible.'
        );
    @endphp

    <x-filament::section>
        <x-slot name="heading">4. Saneamiento de edificaciones</x-slot>
        <x-slot name="description">{{ $sectionDescription }}</x-slot>
    </x-filament::section>

    <x-settings.group-wrapper title="4. Saneamiento de edificaciones">
        @forelse ($group4 as $entry)
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
