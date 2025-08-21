<div x-data="{ showLogin: false }" x-cloak>
    <!-- Trigger Button -->
    <button @click="showLogin = true" 
            class="text-sm text-gray-600 hover:text-gray-900 transition-colors flex items-center space-x-1">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
        </svg>
        <span>Acceso Clientes</span>
    </button>

    <!-- Modal Overlay -->
    <div x-show="showLogin" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4"
         @click.self="showLogin = false">
        
        <!-- Modal Content -->
        <div x-show="showLogin"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform scale-95"
             x-transition:enter-end="opacity-100 transform scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 transform scale-100"
             x-transition:leave-end="opacity-0 transform scale-95"
             class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-8">
            
            <!-- Header -->
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-bold text-gray-900">Acceso Clientes</h3>
                <button @click="showLogin = false" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            
            <!-- Login Options -->
            <div class="space-y-4">
                <!-- Acceso Principal -->
                <a href="{{ route('login') }}" 
                   class="w-full bg-primary-600 text-white py-3 px-4 rounded-xl font-semibold hover:bg-primary-700 transition-colors flex items-center justify-center space-x-2">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Ingresar al Sistema</span>
                </a>
                
                <!-- Soporte -->
                <a href="https://wa.me/573001234567?text=Hola!%20Necesito%20ayuda%20con%20mi%20cuenta" 
                   target="_blank"
                   class="w-full border-2 border-green-500 text-green-600 py-3 px-4 rounded-xl font-semibold hover:bg-green-50 transition-colors flex items-center justify-center space-x-2">
                    <i class="fab fa-whatsapp"></i>
                    <span>Soporte Técnico</span>
                </a>
                
                <!-- Link para problemas -->
                <div class="text-center">
                    <a href="#" class="text-sm text-gray-500 hover:text-gray-700">
                        ¿Olvidaste tu contraseña?
                    </a>
                </div>
            </div>
            
            <!-- Info adicional -->
            <div class="mt-6 p-4 bg-blue-50 rounded-xl">
                <p class="text-sm text-blue-800">
                    <strong>¿Nuevo cliente?</strong> Cierra esta ventana y solicita tu demo gratuita más abajo.
                </p>
            </div>
        </div>
    </div>
</div>
