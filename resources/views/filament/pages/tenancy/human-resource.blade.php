<x-filament-panels::page>

    @php
        $entries = collect($sectionEntries ?? []);

        $group21 = $entries->filter(fn ($entry) => str_starts_with((string) ($entry['entry_id'] ?? ''), '2.1.'))->values();
        $group211 = $entries->filter(fn ($entry) => str_starts_with((string) ($entry['entry_id'] ?? ''), '2.1.1.'))->values();
        $group212 = $entries->filter(fn ($entry) => str_starts_with((string) ($entry['entry_id'] ?? ''), '2.1.2.'))->values();
        $group213 = $entries
            ->filter(fn ($entry) => str_starts_with((string) ($entry['entry_id'] ?? ''), '2.1.3.'))
            ->filter(fn ($entry) => filled((string) ($entry['answer'] ?? '')))
            ->values();
        $group22 = $entries->filter(fn ($entry) => str_starts_with((string) ($entry['entry_id'] ?? ''), '2.2.'))->values();

        $sectionDescription = minutesIvcSectionDescriptionFromConfig(
            ['Talento Humano', 'Recurso Humano', 'talento-humano', 'recurso-humano', 2],
            'Sin descripción normativa disponible.'
        );
    @endphp

    <x-filament::section>
        <x-slot name="heading">2.1 Información Talento Humano</x-slot>
        <x-slot name="description">{{ $sectionDescription }}</x-slot>

    </x-filament::section>

    <x-settings.group-wrapper title="2.1.1 Identificación del Director Técnico">
        @forelse ($group211 as $entry)
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
            <div class="px-4 py-6 text-sm text-gray-500">Sin registros en esta subsección.</div>
        @endforelse
    </x-settings.group-wrapper>

    <x-settings.group-wrapper title="2.1.2 Identificación del Delegado Responsable">
        @forelse ($group212 as $entry)
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
            <div class="px-4 py-6 text-sm text-gray-500">Sin registros en esta subsección.</div>
        @endforelse
    </x-settings.group-wrapper>

    <x-settings.group-wrapper title="2.1.3 Identificación del personal dispensador">
        @forelse ($group213 as $entry)
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
            <div class="px-4 py-6 text-sm text-gray-500">Sin registros en esta subsección.</div>
        @endforelse
    </x-settings.group-wrapper>

    <x-settings.group-wrapper title="2.2 Principios y generalidades">
        @forelse ($group22 as $entry)
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
