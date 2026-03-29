<div class="space-y-6">
    <x-filament::section>
        <x-slot name="heading">
            {{ $course->title }}
        </x-slot>

        <x-slot name="description">
            {{ $course->objective }}
        </x-slot>

        <div class="prose dark:prose-invert max-w-full">
            {!! str($course->description)->markdown() !!}
        </div>
    </x-filament::section>

    @if ($record)
        <div class="space-y-4">
            <h2 class="text-xl font-bold tracking-tight text-gray-950 dark:text-white">
                Modulos del curso
            </h2>

            @forelse ($course->modules as $module)
                <x-filament::section collapsible>
                    <x-slot name="heading" class="flex items-center gap-x-2">
                        <x-filament::icon icon="heroicon-o-bookmark-square" class="h-6 w-6 text-gray-500" />
                        <span>{{ $module->title }}</span>
                        <x-filament::badge>
                            {{ trans_choice(':count leccion|:count lecciones', $module->lessons->count()) }}
                        </x-filament::badge>
                    </x-slot>

                    <ul class="space-y-4">
                        @foreach ($module->lessons as $lesson)
                            <li class="flex items-center justify-between gap-x-3">
                                @if ($lesson->active)
                                    <x-filament::link
                                        :href="\App\Filament\Resources\Quality\Training\EnrollmentResource::getUrl('lesson', [
                                            'record' => $record,
                                            'lesson' => $lesson,
                                        ])"
                                        icon="heroicon-o-document-text"
                                    >
                                        {{ $lesson->title }}
                                    </x-filament::link>
                                @else
                                    <span class="flex items-center gap-x-1.5 text-sm text-gray-500 dark:text-gray-400">
                                        <x-filament::icon icon="heroicon-o-lock-closed" class="h-5 w-5" />
                                        <span>{{ $lesson->title }}</span>
                                    </span>
                                @endif

                                @php
                                    $status = $lessonStatuses[$lesson->id] ?? ['text' => 'No cursada', 'color' => 'gray'];
                                @endphp

                                <x-filament::badge :color="$status['color']" class="flex-shrink-0">
                                    {{ $status['text'] }}
                                </x-filament::badge>
                            </li>
                        @endforeach
                    </ul>
                </x-filament::section>
            @empty
                <x-filament::section icon="heroicon-o-x-circle">
                    <x-slot name="heading">
                        Sin modulos aun
                    </x-slot>
                </x-filament::section>
            @endforelse
        </div>
    @endif
</div>
