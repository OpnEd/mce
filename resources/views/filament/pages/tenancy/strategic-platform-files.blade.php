<x-filament::page>
    <div class="space-y-6">
        <x-filament::card>
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-bold">Mapa de Procesos</h2>
                    <p class="mt-1 text-sm text-gray-500">Archivos asociados al mapa de procesos.</p>
                </div>
            </div>

            @if (empty($processMapFiles))
                <p class="mt-4 text-sm text-gray-500">No hay archivos cargados.</p>
            @else
                <div class="mt-4 space-y-3">
                    @foreach ($processMapFiles as $file)
                        <div class="rounded border p-4 bg-white shadow-sm">
                            <div class="font-medium">
                                @if (!empty($file['url']))
                                    <a href="{{ $file['url'] }}" class="text-primary-600 underline" target="_blank" rel="noopener">
                                        {{ $file['name'] }}
                                    </a>
                                @else
                                    {{ $file['name'] }}
                                @endif
                                @if (!empty($file['is_default']))
                                    <span class="ml-2 inline-flex items-center rounded-full bg-gray-100 px-2 py-0.5 text-xs text-gray-700">
                                        Archivo predeterminado
                                    </span>
                                @endif
                            </div>

                            <div class="mt-2 text-sm text-gray-600">
                                Descripción:
                                <span class="text-gray-900">
                                    {{ $file['description'] ?: 'Sin descripción.' }}
                                </span>
                            </div>

                            @if (!empty($file['url']) && ($file['is_pdf'] || $file['is_image']))
                                <div class="mt-3">
                                    @if ($file['is_pdf'])
                                        <iframe
                                            src="{{ $file['url'] }}"
                                            class="h-80 w-full rounded border bg-white"
                                            loading="lazy"
                                        ></iframe>
                                    @else
                                        <img
                                            src="{{ $file['url'] }}"
                                            alt="{{ $file['name'] }}"
                                            class="w-full h-auto max-h-96 rounded border bg-white object-contain"
                                            loading="lazy"
                                        >
                                    @endif
                                </div>
                            @endif

                            <div class="mt-2 text-sm text-gray-600">
                                Metadatos:
                                @if (!empty($file['meta_lines']))
                                    <ul class="mt-1 list-disc list-inside text-gray-900">
                                        @foreach ($file['meta_lines'] as $line)
                                            <li>{{ $line }}</li>
                                        @endforeach
                                    </ul>
                                @else
                                    <span class="text-gray-900">Sin metadatos.</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </x-filament::card>

        <x-filament::card>
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-bold">Organigrama</h2>
                    <p class="mt-1 text-sm text-gray-500">Archivos asociados al organigrama.</p>
                </div>
            </div>

            @if (empty($orgChartFiles))
                <p class="mt-4 text-sm text-gray-500">No hay archivos cargados.</p>
            @else
                <div class="mt-4 space-y-3">
                    @foreach ($orgChartFiles as $file)
                        <div class="rounded border p-4 bg-white shadow-sm">
                            <div class="font-medium">
                                @if (!empty($file['url']))
                                    <a href="{{ $file['url'] }}" class="text-primary-600 underline" target="_blank" rel="noopener">
                                        {{ $file['name'] }}
                                    </a>
                                @else
                                    {{ $file['name'] }}
                                @endif
                                @if (!empty($file['is_default']))
                                    <span class="ml-2 inline-flex items-center rounded-full bg-gray-100 px-2 py-0.5 text-xs text-gray-700">
                                        Archivo predeterminado
                                    </span>
                                @endif
                            </div>

                            <div class="mt-2 text-sm text-gray-600">
                                Descripción:
                                <span class="text-gray-900">
                                    {{ $file['description'] ?: 'Sin descripción.' }}
                                </span>
                            </div>

                            @if (!empty($file['url']) && ($file['is_pdf'] || $file['is_image']))
                                <div class="mt-3">
                                    @if ($file['is_pdf'])
                                        <iframe
                                            src="{{ $file['url'] }}"
                                            class="h-80 w-full rounded border bg-white"
                                            loading="lazy"
                                        ></iframe>
                                    @else
                                        <img
                                            src="{{ $file['url'] }}"
                                            alt="{{ $file['name'] }}"
                                            class="w-full h-auto max-h-96 rounded border bg-white object-contain"
                                            loading="lazy"
                                        >
                                    @endif
                                </div>
                            @endif

                            <div class="mt-2 text-sm text-gray-600">
                                Metadatos:
                                @if (!empty($file['meta_lines']))
                                    <ul class="mt-1 list-disc list-inside text-gray-900">
                                        @foreach ($file['meta_lines'] as $line)
                                            <li>{{ $line }}</li>
                                        @endforeach
                                    </ul>
                                @else
                                    <span class="text-gray-900">Sin metadatos.</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </x-filament::card>
    </div>
</x-filament::page>
