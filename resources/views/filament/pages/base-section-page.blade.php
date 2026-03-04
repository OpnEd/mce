<x-filament-panels::page>
    <div class="space-y-6">
        <x-filament::section>

            <x-slot name="description">
                Datos generales de la droguería.
            </x-slot>

            <dl class="grid grid-cols-1 gap-4 md:grid-cols-2 2xl:grid-cols-3">
                {{-- Identificación --}}

                <div class="rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">ID establecimiento (SDS)</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $team->establishment_id ?? '—' }}</dd>
                </div>

                <div class="rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Número de Inscripción (Negocios saludables, negocios rentables)</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $team->registration_number ?? '—' }}</dd>
                </div>

                <div class="rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Razón social</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $team->team_name ?? '—' }}</dd>
                </div>

                <div class="rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Nombre del establecimiento</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $team->name ?? '—' }}</dd>
                </div>

                <div class="rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">NIT</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $team->identification ?? '—' }}</dd>
                </div>

                <div class="rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Sede</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $team->location_1 ?? '—' }}</dd>
                </div>

                {{-- Ubicación --}}
                <div class="rounded-xl border border-gray-200 bg-white p-4 md:col-span-2 2xl:col-span-3 dark:border-gray-800 dark:bg-gray-900">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Dirección</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $team->address ?? '—' }}</dd>
                </div>

                <div class="rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Ubicado dentro de (location_2)</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $team->location_2 ?? '—' }}</dd>
                </div>

                <div class="rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Localidad</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $team->town ?? '—' }}</dd>
                </div>

                <div class="rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">UPZ</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $team->upz ?? '—' }}</dd>
                </div>

                <div class="rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Barrio</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $team->neighborhood ?? '—' }}</dd>
                </div>

                <div class="rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Teléfono principal</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $team->phonenumber ?? '—' }}</dd>
                </div>

                <div class="rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Teléfono 1 (adicional)</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $team->phone_number_1 ?? '—' }}</dd>
                </div>

                <div class="rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Teléfono 2 (adicional)</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $team->phone_number_2 ?? '—' }}</dd>
                </div>

                {{-- Contacto --}}
                <div class="rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">E-mail</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                        @if(!empty($team->email))
                            <a class="underline decoration-dotted" href="mailto:{{ $team->email }}">{{ $team->email }}</a>
                        @else
                            —
                        @endif
                    </dd>
                </div>
            </dl>
        </x-filament::section>

        <x-filament::section>
            <x-slot name="heading">
                Propietario, Representante legal y horarios
            </x-slot>

            <dl class="grid grid-cols-1 gap-4 md:grid-cols-2 2xl:grid-cols-3">

                <div class="rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Propietario</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $owner_name ?? '—' }}</dd>
                </div>

                <div class="rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Tipo de documento</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $ownerCardType ?? '—' }}</dd>
                </div>

                <div class="rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Número de documento</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $ownerCardNumber ?? '—' }}</dd>
                </div>

                <hr>

                <div class="rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Representante legal</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $team->legal_representative_name ?? '—' }}</dd>
                </div>

                <div class="rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Tipo de documento</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $team->legal_representative_doc_type ?? '—' }}</dd>
                </div>

                <div class="rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Número de documento</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $team->legal_representative_doc_num ?? '—' }}</dd>
                </div>

                <div class="rounded-xl border border-gray-200 bg-white p-4 md:col-span-2 2xl:col-span-3 dark:border-gray-800 dark:bg-gray-900">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Horario</dt>
                    <dd class="mt-1 whitespace-pre-line text-sm text-gray-900 dark:text-gray-100">
                        {{ $team->operating_hours ?? '—' }}
                    </dd>
                </div>
            </dl>
        </x-filament::section>
    </div>
</x-filament-panels::page>
