<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <x-validation-errors class="mb-4" />
        <div class="flex justify-center" style="text-align: center">
            <h1>!Ayúdanos a mejorar, evalúa con franqueza el servicio que te hemos prestado!</h1>
        </div>
        <form method="POST" action="{{ route('user.satisfactionaswer') }}"
            class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">

            @csrf

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="pregunta1">
                    1. ¿Cómo te parece la presentación visual de la droguería?
                </label>
                <div class="flex flex-row">
                    <div class="mr-4">
                        <input type="radio" id="pregunta1_deficiente" name="pregunta1" value="Deficiente"
                            class="mr-2" required>
                        <label for="pregunta1_deficiente">Deficiente</label>
                    </div>
                    <div class="mr-4">
                        <input type="radio" id="pregunta1_regular" name="pregunta1" value="Regular" class="mr-2"
                            required>
                        <label for="pregunta1_regular">Regular</label>
                    </div>
                    <div class="mr-4">
                        <input type="radio" id="pregunta1_bueno" name="pregunta1" value="Bueno" class="mr-2"
                            required>
                        <label for="pregunta1_bueno">Bueno</label>
                    </div>
                    <div class="mr-4">
                        <input type="radio" id="pregunta1_excelente" name="pregunta1" value="Excelente" class="mr-2"
                            required>
                        <label for="pregunta1_excelente">Excelente</label>
                    </div>
                </div>
                <label class="block text-gray-700 text-sm font-bold mb-2 mt-4" for="pregunta2">
                    2. ¿Cómo te parece la calidad de los productos adquiridos?
                </label>
                <div class="flex flex-row">
                    <div class="mr-4">
                        <input type="radio" id="pregunta2_deficiente" name="pregunta2" value="Deficiente"
                            class="mr-2" required>
                        <label for="pregunta2_deficiente">Deficiente</label>
                    </div>
                    <div class="mr-4">
                        <input type="radio" id="pregunta2_regular" name="pregunta2" value="Regular" class="mr-2"
                            required>
                        <label for="pregunta2_regular">Regular</label>
                    </div>
                    <div class="mr-4">
                        <input type="radio" id="pregunta2_bueno" name="pregunta2" value="Bueno" class="mr-2"
                            required>
                        <label for="pregunta2_bueno">Bueno</label>
                    </div>
                    <div class="mr-4">
                        <input type="radio" id="pregunta2_excelente" name="pregunta2" value="Excelente" class="mr-2"
                            required>
                        <label for="pregunta2_excelente">Excelente</label>
                    </div>
                </div>
                <label class="block text-gray-700 text-sm font-bold mb-2 mt-4" for="pregunta3">
                    3. ¿Cómo evalúas la disponibilidad de los productos que solicitó?
                </label>
                <div class="flex flex-row">
                    <div class="mr-4">
                        <input type="radio" id="pregunta3_deficiente" name="pregunta3" value="Deficiente"
                            class="mr-2" required>
                        <label for="pregunta3_deficiente">Deficiente</label>
                    </div>
                    <div class="mr-4">
                        <input type="radio" id="pregunta3_regular" name="pregunta3" value="Regular" class="mr-2"
                            required>
                        <label for="pregunta3_regular">Regular</label>
                    </div>
                    <div class="mr-4">
                        <input type="radio" id="pregunta3_bueno" name="pregunta3" value="Bueno" class="mr-2"
                            required>
                        <label for="pregunta3_bueno">Bueno</label>
                    </div>
                    <div class="mr-4">
                        <input type="radio" id="pregunta3_excelente" name="pregunta3" value="Excelente" class="mr-2"
                            required>
                        <label for="pregunta3_excelente">Excelente</label>
                    </div>
                </div>
                <label class="block text-gray-700 text-sm font-bold mb-2 mt-4" for="pregunta4">
                    4. ¿Cómo te parece la atención de la persona que le colaboró?
                </label>
                <div class="flex flex-row">
                    <div class="mr-4">
                        <input type="radio" id="pregunta4_deficiente" name="pregunta4" value="Deficiente"
                            class="mr-2" required>
                        <label for="pregunta4_deficiente">Deficiente</label>
                    </div>
                    <div class="mr-4">
                        <input type="radio" id="pregunta4_regular" name="pregunta4" value="Regular"
                            class="mr-2" required>
                        <label for="pregunta4_regular">Regular</label>
                    </div>
                    <div class="mr-4">
                        <input type="radio" id="pregunta4_bueno" name="pregunta4" value="Bueno" class="mr-2"
                            required>
                        <label for="pregunta4_bueno">Bueno</label>
                    </div>
                    <div class="mr-4">
                        <input type="radio" id="pregunta4_excelente" name="pregunta4" value="Excelente"
                            class="mr-2" required>
                        <label for="pregunta4_excelente">Excelente</label>
                    </div>
                </div>
                <label class="block text-gray-700 text-sm font-bold mb-2 mt-4" for="pregunta5">
                    5. ¿Cómo te parece la información que la persona que lo atendió le brindó sobre los productos
                    adquiridos?
                </label>
                <div class="flex flex-row">
                    <div class="mr-4">
                        <input type="radio" id="pregunta5_deficiente" name="pregunta5" value="Deficiente"
                            class="mr-2" required>
                        <label for="pregunta5_deficiente">Deficiente</label>
                    </div>
                    <div class="mr-4">
                        <input type="radio" id="pregunta5_regular" name="pregunta5" value="Regular"
                            class="mr-2" required>
                        <label for="pregunta5_regular">Regular</label>
                    </div>
                    <div class="mr-4">
                        <input type="radio" id="pregunta5_bueno" name="pregunta5" value="Bueno" class="mr-2"
                            required>
                        <label for="pregunta5_bueno">Bueno</label>
                    </div>
                    <div class="mr-4">
                        <input type="radio" id="pregunta5_excelente" name="pregunta5" value="Excelente"
                            class="mr-2" required>
                        <label for="pregunta5_excelente">Excelente</label>
                    </div>
                </div>
                <label class="block text-gray-700 text-sm font-bold mb-2 mt-4" for="pregunta6">
                    6. ¿Cómo te parece la La fovarabilidad de los precios de los productos?
                </label>
                <div class="flex flex-row">
                    <div class="mr-4">
                        <input type="radio" id="pregunta6_deficiente" name="pregunta6" value="Deficiente"
                            class="mr-2" required>
                        <label for="pregunta6_deficiente">Deficiente</label>
                    </div>
                    <div class="mr-4">
                        <input type="radio" id="pregunta6_regular" name="pregunta6" value="Regular"
                            class="mr-2" required>
                        <label for="pregunta6_regular">Regular</label>
                    </div>
                    <div class="mr-4">
                        <input type="radio" id="pregunta6_bueno" name="pregunta6" value="Bueno" class="mr-2"
                            required>
                        <label for="pregunta6_bueno">Bueno</label>
                    </div>
                    <div class="mr-4">
                        <input type="radio" id="pregunta6_excelente" name="pregunta6" value="Excelente"
                            class="mr-2" required>
                        <label for="pregunta6_excelente">Excelente</label>
                    </div>
                </div>
                <label class="block text-gray-700 text-sm font-bold mb-2 mt-4" for="pregunta7">
                    7. Si has utilizado el servicio de inyectología ¿Cómo te pareció?
                </label>
                <div class="flex flex-row">
                    <div class="mr-4">
                        <input type="radio" id="pregunta7_na" name="pregunta7" value="N.A."
                            class="mr-2" required>
                        <label for="pregunta7_na">N.A.</label>
                    </div>
                    <div class="mr-4">
                        <input type="radio" id="pregunta7_deficiente" name="pregunta7" value="Deficiente"
                            class="mr-2" required>
                        <label for="pregunta7_deficiente">Deficiente</label>
                    </div>
                    <div class="mr-4">
                        <input type="radio" id="pregunta7_regular" name="pregunta7" value="Regular"
                            class="mr-2" required>
                        <label for="pregunta7_regular">Regular</label>
                    </div>
                    <div class="mr-4">
                        <input type="radio" id="pregunta7_bueno" name="pregunta7" value="Bueno" class="mr-2"
                            required>
                        <label for="pregunta7_bueno">Bueno</label>
                    </div>
                    <div class="mr-4">
                        <input type="radio" id="pregunta7_excelente" name="pregunta7" value="Excelente"
                            class="mr-2" required>
                        <label for="pregunta7_excelente">Excelente</label>
                    </div>
                </div>
                <label class="block text-gray-700 text-sm font-bold mb-2 mt-4" for="pregunta8">
                    Es valioso para nosotros poder contar con tu identificación, ya que así la
                    autoridad sanitaria comprueba que esta respuesta proviene de un usuario (Opcional).
                </label>
                <div class="flex flex-row">
                    <div class="mr-4">
                        <input type="number" id="pregunta8_identificacion" name="pregunta8" value="Identificacion" placeholder="Identificación"
                            class="rounded-md p-2 border border-gray-300 focus:outline-none focus:border-blue-500 mr-2 text-gray-300 my-3">
                    </div>
                </div>
                <div class="flex flex-row">
                    <div class="mr-4">
                        <textarea id="sugerencias" name="sugerencias" placeholder="Sugerencias"
                            class="rounded-md p-2 border border-gray-300 focus:outline-none focus:border-blue-500 mr-2 text-gray-300" rows="5" cols="40"></textarea>
                    </div>
                </div>
                <div class="flex justify-center">
                    <button type="submit"
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline mt-4">
                        Enviar Respuestas
                    </button>
                </div>

        </form>

        <div id="successModal" class="modal">
            <div class="modal-content">
                <p>¡Gracias por ayudarnos a mejorar!</p>
            </div>
        </div>

    </x-authentication-card>

</x-guest-layout>
