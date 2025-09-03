@props(['title' => null])

<x-filament::section>
    <x-slot name="heading">{{ $title }}</x-slot>

    <div class="overflow-hidden rounded-lg border">
        <dl class="divide-y">
            {{ $slot }}
        </dl>
    </div>
</x-filament::section>