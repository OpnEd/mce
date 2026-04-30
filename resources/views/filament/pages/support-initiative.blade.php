<x-filament-panels::page>
    <div class="space-y-6">
        <div class="grid grid-cols-1 gap-6 xl:grid-cols-3">
            <div class="xl:col-span-2">
                <div class="rounded-lg border-l-4 border-primary-500 bg-white p-6 shadow dark:bg-gray-800">
                    <div class="flex items-start gap-4">
                        <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-primary-50 text-primary-600 dark:bg-primary-500/10 dark:text-primary-300">
                            <x-filament::icon icon="heroicon-o-heart" class="h-6 w-6" />
                        </div>

                        <div class="space-y-3">
                            <div class="inline-flex items-center rounded-full bg-primary-50 px-3 py-1 text-xs font-medium text-primary-700 dark:bg-primary-500/10 dark:text-primary-200">
                                Uso gratuito, apoyo voluntario
                            </div>

                            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                                La app puede seguir siendo gratuita porque algunos usuarios deciden sostenerla.
                            </h2>

                            <p class="text-sm leading-6 text-gray-700 dark:text-gray-300">
                                Droguería Digital puede usarse gratis. Si te está ayudando a organizar procesos, ahorrar tiempo o trabajar con más tranquilidad, puedes apoyarla con una compra voluntaria.
                            </p>

                            <p class="text-sm leading-6 text-gray-600 dark:text-gray-400">
                                Ese apoyo nos permite mantener la plataforma, mejorar funciones, brindar acompañamiento y seguir llegando a más droguerías.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div>
                <div class="rounded-lg bg-white p-6 shadow dark:bg-gray-800">
                    <h3 class="text-base font-semibold text-gray-900 dark:text-white">
                        Queremos que esto sea claro
                    </h3>

                    <p class="mt-3 text-sm leading-6 text-gray-600 dark:text-gray-300">
                        Tu apoyo no desbloquea la app. Ayuda a sostener mejoras, soporte y continuidad para más droguerías.
                    </p>

                    <div class="mt-5 flex flex-col gap-3">
                        <x-filament::button tag="a" href="{{ $whatsAppUrl }}" target="_blank" color="success" icon="heroicon-o-chat-bubble-left-right">
                            Apoyar por WhatsApp
                        </x-filament::button>

                        <x-filament::button tag="a" href="{{ $websiteUrl }}" target="_blank" color="gray" icon="heroicon-o-arrow-top-right-on-square">
                            Ver opciones en la web
                        </x-filament::button>
                    </div>
                </div>
            </div>
        </div>

        <x-filament::section>
            <x-slot name="heading">Seguir usándola gratis sigue siendo posible</x-slot>
            <x-slot name="description">Queremos que la herramienta siga siendo accesible para más droguerías.</x-slot>

            <div class="space-y-4 text-sm leading-6 text-gray-700 dark:text-gray-300">
                <p>
                    No necesitas pagar para entrar ni para seguir usando las funciones base.
                </p>

                <p>
                    Si decides comprarnos algo, no es una obligación: es una forma de sostener el proyecto y ayudarnos a seguir construyéndolo.
                </p>
            </div>
        </x-filament::section>

        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
            @foreach ($impactItems as $item)
                <section class="rounded-lg bg-white p-5 shadow dark:bg-gray-800">
                    <div class="flex h-11 w-11 items-center justify-center rounded-lg bg-gray-100 text-primary-600 dark:bg-gray-700 dark:text-primary-300">
                        <x-filament::icon :icon="$item['icon']" class="h-6 w-6" />
                    </div>

                    <h3 class="mt-4 text-base font-semibold text-gray-900 dark:text-white">
                        {{ $item['title'] }}
                    </h3>

                    <p class="mt-2 text-sm leading-6 text-gray-600 dark:text-gray-400">
                        {{ $item['description'] }}
                    </p>
                </section>
            @endforeach
        </div>

        <x-filament::section>
            <x-slot name="heading">Formas de apoyar</x-slot>
            <x-slot name="description">Escoge la modalidad que mejor encaje con tu momento actual.</x-slot>

            <div class="grid gap-4 xl:grid-cols-2">
                @foreach ($supportOptions as $option)
                    <section class="flex h-full flex-col rounded-lg bg-white p-6 shadow dark:bg-gray-800">
                        <div class="space-y-3">
                            <div class="inline-flex w-fit items-center rounded-full bg-gray-100 px-3 py-1 text-xs font-medium text-gray-700 dark:bg-gray-700 dark:text-gray-200">
                                Forma de apoyo
                            </div>

                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                {{ $option['title'] }}
                            </h3>

                            <p class="text-sm leading-6 text-gray-700 dark:text-gray-300">
                                {{ $option['description'] }}
                            </p>

                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                {{ $option['audience'] }}
                            </p>
                        </div>

                        <div class="mt-6">
                            <x-filament::button tag="a" href="{{ $option['url'] }}" target="_blank" color="primary" icon="heroicon-o-chat-bubble-left-right">
                                {{ $option['cta'] }}
                            </x-filament::button>
                        </div>
                    </section>
                @endforeach
            </div>
        </x-filament::section>

        <x-filament::section>
            <x-slot name="heading">Queremos que esto sea claro</x-slot>

            <div class="grid gap-3 md:grid-cols-2">
                @foreach ($trustPoints as $point)
                    <div class="flex items-start gap-3 rounded-lg bg-white px-4 py-3 text-sm text-gray-700 shadow dark:bg-gray-800 dark:text-gray-300">
                        <x-filament::icon icon="heroicon-o-check-circle" class="mt-0.5 h-5 w-5 shrink-0 text-emerald-600 dark:text-emerald-400" />
                        <span>{{ $point }}</span>
                    </div>
                @endforeach
            </div>
        </x-filament::section>

        <section class="rounded-lg border-l-4 border-emerald-500 bg-white p-6 shadow dark:bg-gray-800">
            <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
                <div class="max-w-3xl space-y-2">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                        Si esta app ya te está ayudando, tu apoyo puede hacer que siga ayudando a más droguerías.
                    </h3>

                    <p class="text-sm leading-6 text-gray-700 dark:text-gray-300">
                        Cada compra ayuda a que este proyecto siga vivo, útil y disponible para más personas.
                    </p>
                </div>

                <div class="flex flex-col gap-3 sm:flex-row">
                    <x-filament::button tag="a" href="{{ $whatsAppUrl }}" target="_blank" color="success" icon="heroicon-o-chat-bubble-left-right">
                        Apoyar por WhatsApp
                    </x-filament::button>

                    <x-filament::button tag="a" href="{{ $websiteUrl }}" target="_blank" color="gray" icon="heroicon-o-arrow-top-right-on-square">
                        Ver opciones en drogueriadigital.net.co
                    </x-filament::button>
                </div>
            </div>
        </section>
    </div>
</x-filament-panels::page>
