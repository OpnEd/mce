<x-layouts.app :title="'Encuesta de Satisfaccion'">
    <section class="px-4 py-12 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-4xl">
            <div class="mb-8 text-center">
                <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-primary-100 text-primary-700">
                    <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6M7 4h10a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2Z" />
                    </svg>
                </div>
                <h1 class="text-3xl font-bold tracking-tight text-gray-900">
                    Ayudanos a mejorar
                </h1>
                <p class="mt-3 text-base text-gray-600">
                    Evalua con franqueza el servicio que te hemos prestado.
                </p>
            </div>

            @if ($errors->any())
                <div class="mb-6 rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                    <p class="font-semibold">No pudimos procesar la encuesta.</p>
                    <ul class="mt-2 list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form
                method="POST"
                action="{{ route('user.satisfactionaswer') }}"
                class="space-y-6 rounded-2xl bg-white p-6 shadow-lg ring-1 ring-gray-200 sm:p-8"
            >
                @csrf

                <div class="space-y-6">
                    <div>
                        <label class="mb-3 block text-sm font-semibold text-gray-900" for="pregunta1">
                            1. ¿Como te parece la presentacion visual de la drogueria?
                        </label>
                        <div class="flex flex-wrap gap-4">
                            @foreach (['Deficiente', 'Regular', 'Bueno', 'Excelente'] as $option)
                                <label class="inline-flex items-center gap-2 rounded-full border border-gray-200 px-4 py-2 text-sm text-gray-700">
                                    <input type="radio" name="pregunta1" value="{{ $option }}" class="text-primary-600" required>
                                    <span>{{ $option }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div>
                        <label class="mb-3 block text-sm font-semibold text-gray-900" for="pregunta2">
                            2. ¿Como te parece la calidad de los productos adquiridos?
                        </label>
                        <div class="flex flex-wrap gap-4">
                            @foreach (['Deficiente', 'Regular', 'Bueno', 'Excelente'] as $option)
                                <label class="inline-flex items-center gap-2 rounded-full border border-gray-200 px-4 py-2 text-sm text-gray-700">
                                    <input type="radio" name="pregunta2" value="{{ $option }}" class="text-primary-600" required>
                                    <span>{{ $option }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div>
                        <label class="mb-3 block text-sm font-semibold text-gray-900" for="pregunta3">
                            3. ¿Como evaluas la disponibilidad de los productos que solicito?
                        </label>
                        <div class="flex flex-wrap gap-4">
                            @foreach (['Deficiente', 'Regular', 'Bueno', 'Excelente'] as $option)
                                <label class="inline-flex items-center gap-2 rounded-full border border-gray-200 px-4 py-2 text-sm text-gray-700">
                                    <input type="radio" name="pregunta3" value="{{ $option }}" class="text-primary-600" required>
                                    <span>{{ $option }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div>
                        <label class="mb-3 block text-sm font-semibold text-gray-900" for="pregunta4">
                            4. ¿Como te parece la atencion de la persona que te colaboro?
                        </label>
                        <div class="flex flex-wrap gap-4">
                            @foreach (['Deficiente', 'Regular', 'Bueno', 'Excelente'] as $option)
                                <label class="inline-flex items-center gap-2 rounded-full border border-gray-200 px-4 py-2 text-sm text-gray-700">
                                    <input type="radio" name="pregunta4" value="{{ $option }}" class="text-primary-600" required>
                                    <span>{{ $option }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div>
                        <label class="mb-3 block text-sm font-semibold text-gray-900" for="pregunta5">
                            5. ¿Como te parece la informacion que la persona que lo atendio te brindo sobre los productos adquiridos?
                        </label>
                        <div class="flex flex-wrap gap-4">
                            @foreach (['Deficiente', 'Regular', 'Bueno', 'Excelente'] as $option)
                                <label class="inline-flex items-center gap-2 rounded-full border border-gray-200 px-4 py-2 text-sm text-gray-700">
                                    <input type="radio" name="pregunta5" value="{{ $option }}" class="text-primary-600" required>
                                    <span>{{ $option }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div>
                        <label class="mb-3 block text-sm font-semibold text-gray-900" for="pregunta6">
                            6. ¿Como te parece la favorabilidad de los precios de los productos?
                        </label>
                        <div class="flex flex-wrap gap-4">
                            @foreach (['Deficiente', 'Regular', 'Bueno', 'Excelente'] as $option)
                                <label class="inline-flex items-center gap-2 rounded-full border border-gray-200 px-4 py-2 text-sm text-gray-700">
                                    <input type="radio" name="pregunta6" value="{{ $option }}" class="text-primary-600" required>
                                    <span>{{ $option }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div>
                        <label class="mb-3 block text-sm font-semibold text-gray-900" for="pregunta7">
                            7. Si has utilizado el servicio de inyectologia, ¿como te parecio?
                        </label>
                        <div class="flex flex-wrap gap-4">
                            @foreach (['N.A.', 'Deficiente', 'Regular', 'Bueno', 'Excelente'] as $option)
                                <label class="inline-flex items-center gap-2 rounded-full border border-gray-200 px-4 py-2 text-sm text-gray-700">
                                    <input type="radio" name="pregunta7" value="{{ $option }}" class="text-primary-600" required>
                                    <span>{{ $option }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div>
                        <label class="mb-3 block text-sm font-semibold text-gray-900" for="pregunta8_identificacion">
                            Es valioso para nosotros contar con tu identificacion para que la autoridad sanitaria compruebe que esta respuesta proviene de un usuario. Es opcional.
                        </label>
                        <input
                            type="number"
                            id="pregunta8_identificacion"
                            name="pregunta8"
                            placeholder="Identificacion"
                            class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm text-gray-900 shadow-sm focus:border-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-200"
                        >
                    </div>

                    <div>
                        <label class="mb-3 block text-sm font-semibold text-gray-900" for="sugerencias">
                            Sugerencias
                        </label>
                        <textarea
                            id="sugerencias"
                            name="sugerencias"
                            rows="5"
                            placeholder="Cuéntanos como podemos mejorar"
                            class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm text-gray-900 shadow-sm focus:border-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-200"
                        ></textarea>
                    </div>
                </div>

                <div class="flex justify-center">
                    <button
                        type="submit"
                        class="inline-flex items-center rounded-full bg-primary-600 px-6 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-300"
                    >
                        Enviar respuestas
                    </button>
                </div>
            </form>

            <div id="successModal" class="hidden">
                <div class="rounded-lg bg-white p-6 shadow-lg">
                    <p class="text-center text-base font-medium text-gray-900">
                        Gracias por ayudarnos a mejorar.
                    </p>
                </div>
            </div>
        </div>
    </section>
</x-layouts.app>
