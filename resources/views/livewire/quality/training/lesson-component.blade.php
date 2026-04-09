<div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    @if (!empty($breadcrumbs))
        <nav class="border-b border-gray-200 bg-white px-4 py-3 dark:border-white/10 dark:bg-gray-800 sm:px-6 lg:px-8">
            <ol class="flex items-center space-x-2">
                @foreach ($breadcrumbs as $index => $crumb)
                    <li class="flex items-center">
                        @if ($index > 0)
                            <span class="mx-2 text-gray-400 dark:text-gray-600">/</span>
                        @endif
                        @if (isset($crumb['url']))
                            <a href="{{ $crumb['url'] }}" class="text-sm font-medium text-primary-600 hover:underline dark:text-primary-400">
                                {{ $crumb['label'] }}
                            </a>
                        @else
                            <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $crumb['label'] }}</span>
                        @endif
                    </li>
                @endforeach
            </ol>
        </nav>
    @endif

    <div class="relative overflow-hidden bg-gradient-to-r from-primary-500 to-primary-700 px-4 py-12 dark:from-primary-600 dark:to-primary-800 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-7xl">
            <div class="flex items-center justify-between">
                <div>
                    <div class="mb-3 flex items-center gap-2">
                        <span class="inline-flex items-center rounded-full bg-white/20 px-3 py-1 text-sm font-medium text-white">
                            Modulo {{ $record->module?->order ?? '-' }}
                        </span>
                        <span class="inline-flex items-center rounded-full bg-white/20 px-3 py-1 text-sm font-medium text-white">
                            Leccion {{ $currentLessonPosition }}/{{ $totalLessons }}
                        </span>
                    </div>
                    <h1 class="text-3xl font-bold tracking-tight text-white sm:text-4xl">{{ $record->title }}</h1>
                    <p class="mt-2 text-primary-100">{{ $record->module?->title ?? 'Sin modulo' }}</p>
                </div>
                <div class="hidden items-center gap-3 md:flex">
                    @if (!empty($lessonStatus))
                        <x-filament::badge :color="$lessonStatus['color']" class="text-base">
                            {{ $lessonStatus['text'] }}
                        </x-filament::badge>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="grid gap-8 lg:grid-cols-3">
            <div class="lg:col-span-2">
                <x-filament::section class="mb-6">
                    <x-slot name="heading">
                        <h2 class="text-lg font-semibold">Contenido</h2>
                    </x-slot>

                    @if ($record->iframe)
                        <div class="relative h-0 overflow-hidden rounded-lg shadow-lg" style="padding-bottom: 56.25%;">
                            <div class="absolute left-0 top-0 h-full w-full">
                                {!! $record->iframe !!}
                            </div>
                        </div>
                    @else
                        <div class="flex flex-col items-center justify-center rounded-lg bg-gray-50 px-6 py-16 text-center dark:bg-gray-800/50">
                            <x-filament::icon
                                icon="heroicon-o-video-camera"
                                class="h-16 w-16 text-gray-300 dark:text-gray-600"
                            />
                            <p class="mt-4 text-base font-medium text-gray-600 dark:text-gray-400">
                                No hay video disponible para esta leccion
                            </p>
                        </div>
                    @endif
                </x-filament::section>

                <x-filament::section class="mb-6">
                    <div class="grid grid-cols-2 gap-4 md:grid-cols-4">
                        <div>
                            <dt class="text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-400">Duracion</dt>
                            <dd class="mt-2 text-xl font-bold text-gray-900 dark:text-white">{{ $record->duration }}m</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-400">Modulo</dt>
                            <dd class="mt-2 text-xl font-bold text-gray-900 dark:text-white">{{ $record->module?->order ?? '-' }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-400">Posicion</dt>
                            <dd class="mt-2 text-xl font-bold text-gray-900 dark:text-white">{{ $record->order }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-400">Modalidad</dt>
                            <dd class="mt-2 text-sm font-semibold text-gray-900 dark:text-white">
                                @if ($record->isConsumptionOnly())
                                    <span class="inline-block rounded-full bg-blue-100 px-2 py-1 text-xs text-blue-800 dark:bg-blue-900/30 dark:text-blue-200">Consumo</span>
                                @else
                                    <span class="inline-block rounded-full bg-purple-100 px-2 py-1 text-xs text-purple-800 dark:bg-purple-900/30 dark:text-purple-200">Evaluacion</span>
                                @endif
                            </dd>
                        </div>
                    </div>
                </x-filament::section>

                @if ($record->objective)
                    <x-filament::section class="mb-6">
                        <x-slot name="heading">
                            <h3 class="text-base font-semibold">Objetivo</h3>
                        </x-slot>
                        <div class="prose max-w-none dark:prose-invert">
                            {!! nl2br(e($record->objective)) !!}
                        </div>
                    </x-filament::section>
                @endif

                @if ($record->description)
                    <x-filament::section class="mb-6">
                        <x-slot name="heading">
                            <h3 class="text-base font-semibold">Descripcion</h3>
                        </x-slot>
                        <div class="prose max-w-none dark:prose-invert">
                            {!! nl2br(e($record->description)) !!}
                        </div>
                    </x-filament::section>
                @endif

                @if ($record->content)
                    <x-filament::section class="mb-6">
                        <x-slot name="heading">
                            <h3 class="text-base font-semibold">Material de Estudio</h3>
                        </x-slot>
                        <div class="prose max-w-none dark:prose-invert">
                            @if (is_array($record->content))
                                @if (isset($record->content['text']))
                                    {!! $record->content['text'] !!}
                                @else
                                    @foreach ($record->content as $block)
                                        {!! $block['html'] ?? '' !!}
                                    @endforeach
                                @endif
                            @else
                                {!! $record->content !!}
                            @endif
                        </div>
                    </x-filament::section>
                @endif
            </div>

            <div class="space-y-6">
                <x-filament::section>
                    <x-slot name="heading">
                        <h3 class="text-base font-semibold">Estado de Consumo</h3>
                    </x-slot>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-600 dark:text-gray-300">Leccion consumida</span>
                            @if ($lessonConsumed)
                                <x-filament::icon icon="heroicon-o-check-circle" class="h-5 w-5 text-green-600 dark:text-green-400" />
                            @else
                                <x-filament::icon icon="heroicon-o-x-circle" class="h-5 w-5 text-gray-400 dark:text-gray-500" />
                            @endif
                        </div>
                        <button
                            @if (!$lessonConsumed) wire:click="markLessonConsumed" @endif
                            @class([
                                'w-full rounded-lg px-4 py-2 text-sm font-medium transition duration-150',
                                'bg-green-600 text-white hover:bg-green-700 dark:bg-green-700 dark:hover:bg-green-600' => !$lessonConsumed,
                                'bg-gray-200 text-gray-600 cursor-not-allowed dark:bg-gray-700 dark:text-gray-400' => $lessonConsumed,
                            ])
                        >
                            {{ $lessonConsumed ? 'Consumida' : 'Marcar como consumida' }}
                        </button>
                    </div>
                </x-filament::section>

                @if ($assessment)
                    <x-filament::section>
                        <x-slot name="heading">
                            <h3 class="text-base font-semibold">Evaluacion</h3>
                        </x-slot>
                        <div class="space-y-4">
                            <div class="rounded-lg bg-blue-50 p-4 dark:bg-blue-900/30">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-medium text-blue-900 dark:text-blue-200">
                                        @if ($assessment->max_attempts)
                                            {{ $remainingAttempts }} de {{ $assessment->max_attempts }} intentos
                                        @else
                                            Intentos ilimitados
                                        @endif
                                    </span>
                                    <x-filament::icon icon="heroicon-o-information-circle" class="h-5 w-5 text-blue-600 dark:text-blue-400" />
                                </div>
                            </div>

                            @if ($assessment->duration_minutes)
                                <div class="rounded-lg bg-amber-50 p-3 dark:bg-amber-900/30">
                                    <p class="text-xs font-medium text-amber-900 dark:text-amber-200">
                                        Tiempo limite: {{ $assessment->duration_minutes }} minutos
                                    </p>
                                </div>
                            @endif

                            @if ($latestAttempt)
                                <div class="space-y-2 border-t border-gray-200 pt-4 dark:border-white/10">
                                    <p class="text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-400">Ultimo intento</p>
                                    <div class="rounded-lg bg-gray-50 p-3 dark:bg-gray-800/50">
                                        <div class="flex items-center justify-between">
                                            <span class="text-sm text-gray-700 dark:text-gray-300">Puntuacion</span>
                                            <span class="text-lg font-bold text-primary-600 dark:text-primary-400">{{ number_format($latestAttempt->score ?? 0, 1) }}</span>
                                        </div>
                                        @if ($latestAttempt->isPassed())
                                            <span class="mt-2 inline-block rounded-full bg-green-100 px-2 py-1 text-xs font-semibold text-green-800 dark:bg-green-900/30 dark:text-green-200">Aprobada</span>
                                        @else
                                            <span class="mt-2 inline-block rounded-full bg-red-100 px-2 py-1 text-xs font-semibold text-red-800 dark:bg-red-900/30 dark:text-red-200">No aprobada</span>
                                        @endif
                                    </div>
                                </div>
                            @endif

                            <button
                                wire:click="toggleAssessmentForm"
                                @class([
                                    'w-full rounded-lg px-4 py-3 text-sm font-semibold text-white transition duration-150',
                                    'bg-primary-600 hover:bg-primary-700 dark:bg-primary-700 dark:hover:bg-primary-600' => $assessmentCanStart,
                                    'bg-gray-400 cursor-not-allowed dark:bg-gray-600' => !$assessmentCanStart,
                                ])
                                @disabled(!$assessmentCanStart)
                            >
                                @if ($showAssessment)
                                    Cerrar Evaluacion
                                @else
                                    {{ $assessmentCanStart ? 'Comenzar Evaluacion' : 'Evaluacion no disponible' }}
                                @endif
                            </button>

                            @if (!$assessmentCanStart && $assessmentStartError)
                                <p class="text-xs text-gray-600 dark:text-gray-400">
                                    {{ $assessmentStartError }}
                                </p>
                            @endif
                        </div>
                    </x-filament::section>

                    @if ($showAssessment && $assessmentCanStart)
                        <x-filament::section>
                            @livewire('quality.training.assessment-component', ['assessment' => $assessment, 'enrollment' => $enrollment], key('assessment-' . $assessment->id))
                        </x-filament::section>
                    @endif
                @else
                    <x-filament::section>
                        <div class="rounded-lg bg-green-50 p-4 text-center dark:bg-green-900/30">
                            <x-filament::icon icon="heroicon-o-check-circle" class="mx-auto h-8 w-8 text-green-600 dark:text-green-400" />
                            <p class="mt-2 text-sm font-medium text-green-900 dark:text-green-200">
                                No hay evaluacion para esta leccion
                            </p>
                        </div>
                    </x-filament::section>
                @endif

                <x-filament::section>
                    <x-slot name="heading">
                        <h3 class="text-base font-semibold">Progreso del Curso</h3>
                    </x-slot>
                    <div class="space-y-2">
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-600 dark:text-gray-300">Avance general</span>
                            <span class="font-semibold text-gray-900 dark:text-white">{{ $enrollment->progress ?? 0 }}%</span>
                        </div>
                        <div class="h-2 overflow-hidden rounded-full bg-gray-200 dark:bg-gray-700">
                            <div
                                class="h-full bg-gradient-to-r from-primary-500 to-primary-600 dark:from-primary-600 dark:to-primary-700"
                                style="width: {{ $enrollment->progress ?? 0 }}%"
                            ></div>
                        </div>
                    </div>
                </x-filament::section>
            </div>
        </div>

        <div class="mt-12 border-t border-gray-200 pt-8 dark:border-white/10">
            <div class="flex items-center justify-between">
                <x-filament::button
                    wire:click="previous"
                    icon="heroicon-o-arrow-left"
                    color="gray"
                    size="lg"
                    :disabled="!$hasPreviousLesson"
                >
                    Leccion Anterior
                </x-filament::button>

                <div class="text-center">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">
                        Leccion <span class="font-bold text-gray-900 dark:text-white">{{ $currentLessonPosition }}</span> de <span class="font-bold text-gray-900 dark:text-white">{{ $totalLessons }}</span>
                    </p>
                </div>

                <x-filament::button
                    wire:click="next"
                    icon-position="after"
                    icon="heroicon-o-arrow-right"
                    size="lg"
                    :disabled="!$hasNextLesson"
                >
                    Leccion Siguiente
                </x-filament::button>
            </div>
        </div>
    </div>
</div>
