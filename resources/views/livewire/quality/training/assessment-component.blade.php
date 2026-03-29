<div class="space-y-6">
    <!-- Loading State -->
    @if (!$currentAttempt)
        <div class="flex items-center justify-center py-12">
            <div class="animate-spin">
                <x-filament::icon icon="heroicon-o-arrow-path" class="h-8 w-8 text-primary-600 dark:text-primary-400" />
            </div>
        </div>
    @else
        <!-- Timer Header (if duration limit exists) -->
        @if ($assessment->duration_minutes)
            <div class="rounded-lg bg-amber-50 p-4 dark:bg-amber-900/30">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <x-filament::icon icon="heroicon-o-clock" class="h-5 w-5 text-amber-600 dark:text-amber-400" />
                        <div>
                            <p class="text-sm font-semibold text-amber-900 dark:text-amber-200">
                                Tiempo límite
                            </p>
                            <p class="text-xs text-amber-700 dark:text-amber-300">
                                {{ $assessment->duration_minutes }} minutos para completar esta evaluación
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if (!$showResults)
            <!-- Questions Form -->
            <form wire:submit="submitAssessment" class="space-y-8">
                @foreach ($questions as $index => $question)
                    <div class="rounded-lg border border-gray-200 p-6 dark:border-white/10">
                        <!-- Question Header -->
                        <div class="mb-4">
                            <div class="flex items-start justify-between gap-4">
                                <div class="flex-1">
                                    <div class="flex items-center gap-2 mb-2">
                                        <span class="inline-flex items-center rounded-full bg-primary-100 px-2.5 py-0.5 text-xs font-semibold text-primary-800 dark:bg-primary-900/30 dark:text-primary-200">
                                            Pregunta {{ $index + 1 }} de {{ $questionsCount }}
                                        </span>
                                        @if ($question->required)
                                            <span class="text-xs font-semibold text-red-600 dark:text-red-400">*</span>
                                        @endif
                                    </div>
                                    <h3 class="text-base font-semibold text-gray-900 dark:text-white">
                                        {{ $question->question_text }}
                                    </h3>
                                </div>
                            </div>
                        </div>

                        <!-- Question Description -->
                        @if ($question->description)
                            <p class="mb-4 text-sm text-gray-600 dark:text-gray-400">
                                {{ $question->description }}
                            </p>
                        @endif

                        <!-- Answer Input by Question Type -->
                        <div class="space-y-3">
                            @if ($question->type === 'multiple_choice')
                                <!-- Multiple Choice Options -->
                                @if ($question->options)
                                    @php
                                        $options = is_array($question->options)
                                            ? $question->options
                                            : json_decode($question->options, true);
                                    @endphp
                                    <div class="space-y-2">
                                        @foreach ($options as $optionValue => $optionLabel)
                                            <label class="flex items-start gap-3 rounded-lg border border-gray-200 p-3 cursor-pointer hover:bg-gray-50 dark:border-white/10 dark:hover:bg-gray-800/50"
                                                   wire:key="option-{{ $question->id }}-{{ $optionValue }}"
                                            >
                                                <input
                                                    type="radio"
                                                    name="question_{{ $question->id }}"
                                                    value="{{ $optionValue }}"
                                                    wire:model="userAnswers.{{ $question->id }}"
                                                    class="mt-1 h-4 w-4 text-primary-600 border-gray-300 dark:border-white/20 dark:bg-gray-700 dark:checked:bg-primary-600"
                                                    @required($question->required)\n                                                />\n                                                <span class="text-sm text-gray-700 dark:text-gray-300\">{{ $optionLabel }}</span>\n                                            </label>\n                                        @endforeach\n                                    </div>\n                                @endif\n\n                            @elseif ($question->type === 'true_false')\n                                <!-- True/False Options -->\n                                <div class=\"space-y-2\">\n                                    <label class=\"flex items-center gap-3 rounded-lg border border-gray-200 p-3 cursor-pointer hover:bg-gray-50 dark:border-white/10 dark:hover:bg-gray-800/50\">\n                                        <input\n                                            type=\"radio\"\n                                            name=\"question_{{ $question->id }}\"\n                                            value=\"true\"\n                                            wire:model=\"userAnswers.{{ $question->id }}\"\n                                            class=\"h-4 w-4 text-primary-600 border-gray-300 dark:border-white/20 dark:bg-gray-700 dark:checked:bg-primary-600\"\n                                            @required($question->required)\n                                        />\n                                        <span class=\"text-sm text-gray-700 dark:text-gray-300\">Verdadero</span>\n                                    </label>\n                                    <label class=\"flex items-center gap-3 rounded-lg border border-gray-200 p-3 cursor-pointer hover:bg-gray-50 dark:border-white/10 dark:hover:bg-gray-800/50\">\n                                        <input\n                                            type=\"radio\"\n                                            name=\"question_{{ $question->id }}\"\n                                            value=\"false\"\n                                            wire:model=\"userAnswers.{{ $question->id }}\"\n                                            class=\"h-4 w-4 text-primary-600 border-gray-300 dark:border-white/20 dark:bg-gray-700 dark:checked:bg-primary-600\"\n                                            @required($question->required)\n                                        />\n                                        <span class=\"text-sm text-gray-700 dark:text-gray-300\">Falso</span>\n                                    </label>\n                                </div>\n\n                            @elseif ($question->type === 'short_answer')\n                                <!-- Short Answer Text Input -->\n                                <input\n                                    type=\"text\"\n                                    wire:model=\"userAnswers.{{ $question->id }}\"\n                                    placeholder=\"Tu respuesta...\"\n                                    class=\"w-full rounded-lg border border-gray-300 bg-white px-4 py-2 text-gray-900 placeholder-gray-500 focus:border-primary-500 focus:outline-none focus:ring-1 focus:ring-primary-500 dark:border-white/20 dark:bg-gray-800 dark:text-white dark:placeholder-gray-400 dark:focus:border-primary-400\"\n                                    @required($question->required)\n                                />\n\n                            @elseif ($question->type === 'essay')\n                                <!-- Essay Text Area -->\n                                <textarea\n                                    wire:model=\"userAnswers.{{ $question->id }}\"\n                                    placeholder=\"Escribe tu respuesta aquí...\"\n                                    rows=\"4\"\n                                    class=\"w-full rounded-lg border border-gray-300 bg-white px-4 py-2 text-gray-900 placeholder-gray-500 focus:border-primary-500 focus:outline-none focus:ring-1 focus:ring-primary-500 dark:border-white/20 dark:bg-gray-800 dark:text-white dark:placeholder-gray-400 dark:focus:border-primary-400\"\n                                    @required($question->required)\n                                ></textarea>\n                            @endif\n                        </div>\n                    </div>\n                @endforeach\n\n                <!-- Form Actions -->\n                <div class=\"flex items-center justify-between border-t border-gray-200 pt-6 dark:border-white/10\">\n                    <x-filament::button\n                        type=\"button\"\n                        color=\"gray\"\n                        wire:click=\"cancelAttempt\"\n                        :disabled=\"$isSubmitting\"\n                    >\n                        Cancelar\n                    </x-filament::button>\n\n                    <div class=\"flex items-center gap-3\">\n                        <span class=\"text-sm text-gray-600 dark:text-gray-400\">\n                            {{ count(array_filter($userAnswers)) }} de {{ $questionsCount }} respondidas\n                        </span>\n                        <x-filament::button\n                            type=\"submit\"\n                            :disabled=\"$isSubmitting\"\n                            wire:loading.attr=\"disabled\"\n                        >\n                            <x-filament::icon\n                                icon=\"heroicon-o-arrow-path\"\n                                class=\"h-4 w-4 animate-spin\"\n                                style=\"display: {{ $isSubmitting ? 'inline' : 'none' }}\"\n                            />\n                            {{ $isSubmitting ? 'Procesando...' : 'Enviar Evaluación' }}\n                        </x-filament::button>\n                    </div>\n                </div>\n            </form>\n        @else\n            <!-- Results Display -->\n            <div class=\"space-y-6\">\n                <!-- Score Card -->\n                <div class=\"relative overflow-hidden rounded-lg bg-gradient-to-br from-primary-500 to-primary-700 p-8 text-white dark:from-primary-600 dark:to-primary-800\">\n                    <div class=\"relative z-10 text-center\">\n                        <p class=\"text-sm font-semibold uppercase tracking-wider opacity-90\">Tu puntuación</p>\n                        <p class=\"mt-2 text-6xl font-bold\">{{ number_format($results['score'], 1) }}%</p>\n                        <p class=\"mt-4 text-base font-semibold\">\n                            @if ($results['passed'])\n                                ✓ Aprobado\n                            @else\n                                ✗ No aprobado\n                            @endif\n                        </p>\n                    </div>\n                    <div class=\"absolute right-0 top-0 h-full w-1/2 opacity-10\">\n                        <x-filament::icon icon=\"heroicon-o-check-circle\" class=\"h-full w-full\" />\n                    </div>\n                </div>\n\n                <!-- Results Statistics -->\n                <div class=\"grid grid-cols-3 gap-4\">\n                    <div class=\"rounded-lg border border-gray-200 p-4 dark:border-white/10\">\n                        <p class=\"text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-400\">Correctas</p>\n                        <p class=\"mt-2 text-2xl font-bold text-gray-900 dark:text-white\">{{ $results['correct_answers'] }}/{{ $results['total_questions'] }}</p>\n                    </div>\n                    <div class=\"rounded-lg border border-gray-200 p-4 dark:border-white/10\">\n                        <p class=\"text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-400\">Duración</p>\n                        <p class=\"mt-2 text-2xl font-bold text-gray-900 dark:text-white\">{{ $results['duration'] }}m</p>\n                    </div>\n                    <div class=\"rounded-lg border border-gray-200 p-4 dark:border-white/10\">\n                        <p class=\"text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-400\">Total</p>\n                        <p class=\"mt-2 text-2xl font-bold text-gray-900 dark:text-white\">{{ $results['total_questions'] }}</p>\n                    </div>\n                </div>\n\n                <!-- Feedback -->\n                @if ($results['feedback'])\n                    <div class=\"rounded-lg bg-blue-50 p-4 dark:bg-blue-900/30\">\n                        <p class=\"text-sm text-blue-900 dark:text-blue-200\">\n                            {{ $results['feedback'] }}\n                        </p>\n                    </div>\n                @endif\n\n                <!-- Action Buttons -->\n                <div class=\"flex items-center justify-between border-t border-gray-200 pt-6 dark:border-white/10\">\n                    <x-filament::button\n                        color=\"gray\"\n                        wire:click=\"$refresh\"\n                    >\n                        Cerrar\n                    </x-filament::button>\n\n                    @if ($assessment->max_attempts === null || $currentAttempt->attempt_number < $assessment->max_attempts)\n                        <x-filament::button\n                            color=\"primary\"\n                            wire:click=\"startAttempt\"\n                        >\n                            Intentar de Nuevo\n                        </x-filament::button>\n                    @endif\n                </div>\n            </div>\n        @endif\n    @endif\n</div>\n