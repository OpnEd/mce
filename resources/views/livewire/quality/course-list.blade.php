<div>
    <h2 class="text-2xl font-bold mb-4">Cursos disponibles</h2>

    @if ($courses->isEmpty())
        <p class="text-gray-600">No hay cursos activos en este momento.</p>
    @else
        <ul class="space-y-3">
            @foreach ($courses as $course)
                <li
                    class="p-4 bg-white rounded shadow-sm flex justify-between items-center gap-4 hover:bg-gray-50 transition-colors">
                    {{-- Imagen del curso al extremo izquierdo --}}
                    <div class="w-16 h-16 bg-gray-200 rounded mr-6 flex items-center justify-center text-gray-400">
                        <img src="{{ $course->image_url }}" alt="Imagen del curso" class="w-16 h-16 object-cover rounded">
                    </div>
                    <div class="flex-1 space-y-2">
                        <h3 class="text-lg font-semibold">{{ $course->title }}</h3>
                        @if ($course->objective)
                            <p class="text-xs text-gray-400 mb-1"><span class="font-semibold">Objetivo:</span>
                                {{ Str::limit($course->objective, 80) }}</p>
                        @endif
                        @if ($course->description)
                            <p class="text-sm text-gray-500 mb-1">{{ Str::limit($course->description, 80) }}</p>
                        @endif
                        @if ($course->instructor)
                            <p class="text-xs text-gray-500"><span class="font-semibold">Instructor:</span>
                                {{ $course->instructor->name }}</p>
                        @endif
                    </div>
                    <x-filament::modal id="enrollUser" icon="phosphor-student">
                        <x-slot name="heading">
                            Inscripción al curso <strong>{{ $course->title }}</strong>
                        </x-slot>

                        ¿Estás seguro de que deseas inscribirte en este curso?
                        <x-slot name="footerActions">
                            <x-filament::button size="xs" color="success" wire:click="confirmEnrollment({{ $course->id }})">
                                Confirmar
                            </x-filament::button>
                        </x-slot>

                        <x-slot name="trigger">
                            <x-filament::button>
                                Inscribirme
                            </x-filament::button>
                        </x-slot>

                    </x-filament::modal>
                </li>
            @endforeach
        </ul>
    @endif
</div>
