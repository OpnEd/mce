<div>

    @foreach ($entries as $entry)
        @if ($entry->entry_type === 'informativo')
            <div class="my-4">
                <x-filament::section collapsible collapsed>

                    <x-slot name="heading">
                        {{ $entry->entry_id }}
                    </x-slot>

                    <x-slot name="description">
                        <p>{{ $entry->question }}
                    </x-slot>
                    <p>{{ $entry->answer }}
                </x-filament::section>
            </div>
        @else
            <div class="my-4">
                <x-filament::section collapsible collapsed>
                    <x-slot name="heading">
                        {{ $entry->entry_id }}
                    </x-slot>

                    <x-slot name="description">
                        <p>{{ $entry->question }}
                    </x-slot>
                    <p>{{ $entry->answer }}

                        @if ($entry->links)
                            <x-filament::dropdown width="2xl">
                                <x-slot name="trigger">
                                    <x-filament::button size="xs" color="success">
                                        Evidencia
                                    </x-filament::button>
                                </x-slot>
                                <x-filament::dropdown.list>
                                    @foreach ($entry->links as $link)
                                        @php
                                            $routeName = $link['value'] ?? null;
                                        @endphp
                                        @if ($routeName && Route::has($routeName))
                                            <x-filament::dropdown.list.item
                                                tag="a"
                                                href="{{ route($routeName, $teamId) }}"
                                                target="_blank"
                                                rel="noopener noreferrer"
                                                icon="phosphor-arrow-square-out">
                                                
                                                <span>{{ $link['key'] ?? 'Ver enlace' }}</span>
                                            </x-filament::dropdown.list.item>
                                        @endif
                                    @endforeach
                                </x-filament::dropdown.list>
                            </x-filament::dropdown>
                        @endif
                </x-filament::section>
            </div>
        @endif
    @endforeach

</div>
