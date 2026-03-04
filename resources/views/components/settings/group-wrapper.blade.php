@props(['title' => null])

<x-filament::section collapsible collapsed>
    <x-slot name="heading">{{ $title }}</x-slot>

    <div class="overflow-hidden rounded-lg border mt-6">
        <dl class="divide-y">
            {{ $slot }}
        </dl>
    </div>
</x-filament::section>