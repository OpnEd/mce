<section id="demo" class="py-20 bg-gradient-to-br from-primary-50 to-blue-100">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl lg:text-5xl font-bold text-gray-900 mb-6">
                Solicita tu Demo <span class="text-primary-600">Personalizada</span>
            </h2>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                En 7 días te mostramos exactamente cómo PQM resuelve 
                los problemas específicos de TU droguería. Sin compromisos.
            </p>
        </div>
        
        <div class="bg-white rounded-3xl shadow-2xl p-8 lg:p-12">
            @if($currentStep == 1)
                <!-- Step 1: Basic Info -->
                <div class="space-y-8">
                    <div class="text-center">
                        <div class="inline-flex items-center space-x-2 bg-primary-100 text-primary-700 px-4 py-2 rounded-full text-sm font-medium mb-4">
                            <span class="w-6 h-6 bg-primary-600 text-white rounded-full flex items-center justify-center text-xs">1</span>
                            <span>Información Básica</span>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900">Cuéntanos sobre tu droguería</h3>
                    </div>
                    
                    <form wire:submit.prevent="nextStep" class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Tu nombre completo *
                                </label>
                                <input wire:model.defer="name" 
                                       type="text" 
                                       placeholder="Ej: María García"
                                       class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-colors">
                                @error('name') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                            </div>
                            
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Teléfono (para llamarte) *
                                </label>
                                <input wire:model.defer="phone" 
                                       type="tel" 
                                       placeholder="Ej: 300 123 4567"
                                       class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-colors">
                                @error('phone') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Nombre de tu droguería *
                            </label>
                            <input wire:model.defer="drogueria_name" 
                                   type="text" 
                                   placeholder="Ej: Droguería San José"
                                   class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-colors">
                            @error('drogueria_name') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Email (opcional)
                            </label>
                            <input wire:model.defer="email" 
                                   type="email" 
                                   placeholder="tu@email.com (para enviarte información)"
                                   class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-colors">
                            @error('email') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>
                        
                        <button type="submit" 
                                class="w-full bg-primary-600 text-white py-4 rounded-xl font-bold text-lg hover:bg-primary-700 transition-colors flex items-center justify-center space-x-2">
                            <span wire:loading.remove wire:target="nextStep">Continuar</span>
                            <span wire:loading wire:target="nextStep" class="flex items-center">
                                <svg class="animate-spin -ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Procesando...
                            </span>
                            <svg wire:loading.remove wire:target="nextStep" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                            </svg>
                        </button>
                    </form>
                </div>
                
            @elseif($currentStep == 2)
                <!-- Step 2: Business Details -->
                <div class="space-y-8">
                    <div class="text-center">
                        <div class="inline-flex items-center space-x-2 bg-primary-100 text-primary-700 px-4 py-2 rounded-full text-sm font-medium mb-4">
                            <span class="w-6 h-6 bg-primary-600 text-white rounded-full flex items-center justify-center text-xs">2</span>
                            <span>Detalles del Negocio</span>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900">Para personalizar tu demo</h3>
                        <p class="text-gray-600 mt-2">Así te mostramos exactamente lo que necesitas</p>
                    </div>
                    
                    <form wire:submit.prevent="submitLead" class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-3">
                                    ¿Cuántos empleados tienes? *
                                </label>
                                <div class="space-y-3">
                                    <label class="flex items-center p-3 border-2 rounded-xl cursor-pointer hover:bg-gray-50 transition-colors" 
                                           :class="$wire.empleados === '1' ? 'border-primary-500 bg-primary-50' : 'border-gray-200'">
                                        <input wire:model="empleados" type="radio" value="1" class="sr-only">
                                        <div class="w-5 h-5 border-2 rounded-full mr-3 flex items-center justify-center" 
                                             :class="$wire.empleados === '1' ? 'border-primary-500' : 'border-gray-300'">
                                            <div class="w-2 h-2 bg-primary-500 rounded-full" 
                                                 x-show="$wire.empleados === '1'"></div>
                                        </div>
                                        <div>
                                            <div class="font-medium">Solo yo</div>
                                            <div class="text-sm text-gray-500">Droguería individual</div>
                                        </div>
                                    </label>
                                    
                                    <label class="flex items-center p-3 border-2 rounded-xl cursor-pointer hover:bg-gray-50 transition-colors" 
                                           :class="$wire.empleados === '2' ? 'border-primary-500 bg-primary-50' : 'border-gray-200'">
                                        <input wire:model="empleados" type="radio" value="2" class="sr-only">
                                        <div class="w-5 h-5 border-2 rounded-full mr-3 flex items-center justify-center" 
                                             :class="$wire.empleados === '2' ? 'border-primary-500' : 'border-gray-300'">
                                            <div class="w-2 h-2 bg-primary-500 rounded-full" 
                                                 x-show="$wire.empleados === '2'"></div>
                                        </div>
                                        <div>
                                            <div class="font-medium">2 empleados</div>
                                            <div class="text-sm text-gray-500">Yo + 1 auxiliar</div>
                                        </div>
                                    </label>
                                    
                                    <label class="flex items-center p-3 border-2 rounded-xl cursor-pointer hover:bg-gray-50 transition-colors" 
                                           :class="$wire.empleados === '3' ? 'border-primary-500 bg-primary-50' : 'border-gray-200'">
                                        <input wire:model="empleados" type="radio" value="3" class="sr-only">
                                        <div class="w-5 h-5 border-2 rounded-full mr-3 flex items-center justify-center" 
                                             :class="$wire.empleados === '3' ? 'border-primary-500' : 'border-gray-300'">
                                            <div class="w-2 h-2 bg-primary-500 rounded-full" 
                                                 x-show="$wire.empleados === '3'"></div>
                                        </div>
                                        <div>
                                            <div class="font-medium">3 empleados</div>
                                            <div class="text-sm text-gray-500">Pequeño equipo</div>
                                        </div>
                                    </label>
                                    
                                    <label class="flex items-center p-3 border-2 rounded-xl cursor-pointer hover:bg-gray-50 transition-colors" 
                                           :class="$wire.empleados === '4+' ? 'border-primary-500 bg-primary-50' : 'border-gray-200'">
                                        <input wire:model="empleados" type="radio" value="4+" class="sr-only">
                                        <div class="w-5 h-5 border-2 rounded-full mr-3 flex items-center justify-center" 
                                             :class="$wire.empleados === '4+' ? 'border-primary-500' : 'border-gray-300'">
                                            <div class="w-2 h-2 bg-primary-500 rounded-full" 
                                                 x-show="$wire.empleados === '4+'"></div>
                                        </div>
                                        <div>
                                            <div class="font-medium">4+ empleados</div>
                                            <div class="text-sm text-gray-500">Droguería mediana</div>
                                        </div>
                                    </label>
                                </div>
                                @error('empleados') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                            </div>
                            
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-3">
                                    ¿Cuál es tu mayor problema? *
                                </label>
                                <div class="space-y-3">
                                    <label class="flex items-center p-3 border-2 rounded-xl cursor-pointer hover:bg-gray-50 transition-colors" 
                                           :class="$wire.mayor_problema === 'inventario_vencido' ? 'border-red-500 bg-red-50' : 'border-gray-200'">
                                        <input wire:model="mayor_problema" type="radio" value="inventario_vencido" class="sr-only">
                                        <div class="w-5 h-5 border-2 rounded-full mr-3 flex items-center justify-center" 
                                             :class="$wire.mayor_problema === 'inventario_vencido' ? 'border-red-500' : 'border-gray-300'">
                                            <div class="w-2 h-2 bg-red-500 rounded-full" 
                                                 x-show="$wire.mayor_problema === 'inventario_vencido'"></div>
                                        </div>
                                        <div>
                                            <div class="font-medium">Productos vencidos</div>
                                            <div class="text-sm text-gray-500">Pierdo dinero constantemente</div>
                                        </div>
                                    </label>
                                    
                                    <label class="flex items-center p-3 border-2 rounded-xl cursor-pointer hover:bg-gray-50 transition-colors" 
                                           :class="$wire.mayor_problema === 'conciliaciones' ? 'border-orange-500 bg-orange-50' : 'border-gray-200'">
                                        <input wire:model="mayor_problema" type="radio" value="conciliaciones" class="sr-only">
                                        <div class="w-5 h-5 border-2 rounded-full mr-3 flex items-center justify-center" 
                                             :class="$wire.mayor_problema === 'conciliaciones' ? 'border-orange-500' : 'border-gray-300'">
                                            <div class="w-2 h-2 bg-orange-500 rounded-full" 
                                                 x-show="$wire.mayor_problema === 'conciliaciones'"></div>
                                        </div>
                                        <div>
                                            <div class="font-medium">Conciliaciones manuales</div>
                                            <div class="text-sm text-gray-500">Las cuentas no cuadran</div>
                                        </div>
                                    </label>
                                    
                                    <label class="flex items-center p-3 border-2 rounded-xl cursor-pointer hover:bg-gray-50 transition-colors" 
                                           :class="$wire.mayor_problema === 'inspecciones' ? 'border-purple-500 bg-purple-50' : 'border-gray-200'">
                                        <input wire:model="mayor_problema" type="radio" value="inspecciones" class="sr-only">
                                        <div class="w-5 h-5 border-2 rounded-full mr-3 flex items-center justify-center" 
                                             :class="$wire.mayor_problema === 'inspecciones' ? 'border-purple-500' : 'border-gray-300'">
                                            <div class="w-2 h-2 bg-purple-500 rounded-full" 
                                                 x-show="$wire.mayor_problema === 'inspecciones'"></div>
                                        </div>
                                        <div>
                                            <div class="font-medium">Inspecciones INVIMA</div>
                                            <div class="text-sm text-gray-500">Me da miedo no cumplir</div>
                                        </div>
                                    </label>
                                    
                                    <label class="flex items-center p-3 border-2 rounded-xl cursor-pointer hover:bg-gray-50 transition-colors" 
                                           :class="$wire.mayor_problema === 'todo' ? 'border-blue-500 bg-blue-50' : 'border-gray-200'">
                                        <input wire:model="mayor_problema" type="radio" value="todo" class="sr-only">
                                        <div class="w-5 h-5 border-2 rounded-full mr-3 flex items-center justify-center" 
                                             :class="$wire.mayor_problema === 'todo' ? 'border-blue-500' : 'border-gray-300'">
                                            <div class="w-2 h-2 bg-blue-500 rounded-full" 
                                                 x-show="$wire.mayor_problema === 'todo'"></div>
                                        </div>
                                        <div>
                                            <div class="font-medium">Todos los anteriores</div>
                                            <div class="text-sm text-gray-500">Necesito ayuda integral</div>
                                        </div>
                                    </label>
                                </div>
                                @error('mayor_problema') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>
                        
                        <!-- Checkboxes de confirmación -->
                        <div class="space-y-4 pt-6 border-t border-gray-200">
                            <label class="flex items-start space-x-3 cursor-pointer">
                                <input wire:model="acepta_llamada" 
                                       type="checkbox" 
                                       class="mt-1 w-4 h-4 text-primary-600 border-gray-300 rounded focus:ring-primary-500">
                                <span class="text-sm text-gray-700">
                                    <strong>Sí, autorizo que me contacten</strong> por teléfono/WhatsApp para agendar la demo personalizada. 
                                    <span class="text-gray-500">(Solo te llamaremos una vez para la demo, no spam)</span>
                                </span>
                            </label>
                            @error('acepta_llamada') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
                            
                            <label class="flex items-start space-x-3 cursor-pointer">
                                <input wire:model="acepta_terminos" 
                                       type="checkbox" 
                                       class="mt-1 w-4 h-4 text-primary-600 border-gray-300 rounded focus:ring-primary-500">
                                <span class="text-sm text-gray-700">
                                    Acepto los <a href="#" class="text-primary-600 hover:underline">términos de servicio</a> 
                                    y <a href="#" class="text-primary-600 hover:underline">política de privacidad</a>
                                </span>
                            </label>
                            @error('acepta_terminos') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
                        </div>
                        
                        <div class="flex gap-4">
                            <button type="button" 
                                    wire:click="prevStep"
                                    class="flex-1 border-2 border-gray-300 text-gray-700 py-4 rounded-xl font-bold hover:bg-gray-50 transition-colors">
                                ← Volver
                            </button>
                            
                            <button type="submit" 
                                    class="flex-2 bg-primary-600 text-white py-4 px-8 rounded-xl font-bold text-lg hover:bg-primary-700 transition-colors flex items-center justify-center space-x-2">
                                <span wire:loading.remove wire:target="submitLead">🚀 Solicitar Demo Ahora</span>
                                <span wire:loading wire:target="submitLead" class="flex items-center">
                                    <svg class="animate-spin -ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Enviando solicitud...
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            @endif
        </div>
        
        <!-- Trust indicators -->
        <div class="mt-12 text-center">
            <div class="inline-flex items-center space-x-6 text-sm text-gray-500">
                <div class="flex items-center space-x-2">
                    <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                    </svg>
                    <span>100% Seguro</span>
                </div>
                
                <div class="flex items-center space-x-2">
                    <svg class="w-4 h-4 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Demo en 2 horas</span>
                </div>
                
                <div class="flex items-center space-x-2">
                    <svg class="w-4 h-4 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Sin compromisos</span>
                </div>
            </div>
        </div>
    </div>
</section>
