<div class="min-h-screen">
    @if ($showThankYou)
        <!-- Thank You Page -->
        <div class="min-h-screen bg-gradient-to-br from-green-50 to-emerald-100 flex items-center justify-center px-4">
            <div class="max-w-2xl mx-auto text-center">
                <div class="bg-white rounded-3xl shadow-2xl p-8 lg:p-12">
                    <!-- Success Icon -->
                    <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-12 h-12 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>

                    <h1 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4">
                        ¡Perfecto, {{ $name }}! 🎉
                    </h1>

                    <div class="space-y-4 text-lg text-gray-600 mb-8">
                        <p>Tu solicitud para <strong>{{ $drogueria_name }}</strong> ha sido recibida.</p>

                        <div class="bg-blue-50 rounded-xl p-6 text-left">
                            <h3 class="font-semibold text-blue-900 mb-3">📞 ¿Qué sigue ahora?</h3>
                            <ul class="space-y-2 text-blue-800">
                                <li class="flex items-start space-x-2">
                                    <span class="text-blue-600 font-bold">1.</span>
                                    <span>Te llamaremos en las próximas <strong>2 horas</strong></span>
                                </li>
                                <li class="flex items-start space-x-2">
                                    <span class="text-blue-600 font-bold">2.</span>
                                    <span>Haremos una demo personalizada de <strong>15 minutos</strong></span>
                                </li>
                                <li class="flex items-start space-x-2">
                                    <span class="text-blue-600 font-bold">3.</span>
                                    <span>Te mostraremos cómo resolver: <strong>{{ $mayor_problema }}</strong></span>
                                </li>
                            </ul>
                        </div>

                        <div class="bg-yellow-50 rounded-xl p-6">
                            <h3 class="font-semibold text-yellow-900 mb-2">⏰ Mientras esperas...</h3>
                            <p class="text-yellow-800">Revisa este video de 3 minutos donde te mostramos exactamente
                                cómo funciona:</p>
                            <a href="#"
                                class="inline-block mt-3 bg-yellow-500 text-white px-6 py-2 rounded-lg font-medium hover:bg-yellow-600 transition-colors">
                                Ver Video Demo
                            </a>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <a href="https://wa.me/573143095251?text=Hola! Acabo de solicitar la demo para {{ $drogueria_name }}"
                            target="_blank"
                            class="bg-green-500 text-white px-8 py-3 rounded-xl font-semibold hover:bg-green-600 transition-colors flex items-center justify-center space-x-2">
                            <i class="fab fa-whatsapp"></i>
                            <span>Escríbenos por WhatsApp</span>
                        </a>

                        <button wire:click="resetForm"
                            class="border-2 border-gray-300 text-gray-700 px-8 py-3 rounded-xl font-semibold hover:bg-gray-50 transition-colors">
                            Enviar Otra Solicitud
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @else
        <!-- Navigation -->
        <nav class="bg-white shadow-sm sticky top-0 z-50" x-data="{ mobileOpen: false }">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <div class="flex items-center space-x-3">
                        <div class="w-14 h-14">
                            <img src="{{ asset('storage/landing-page-images/logo.png') }}" alt="PQM Logo" class="h-full w-full object-contain">
                        </div>
                        <div>
                            <h1 class="text-xl font-bold text-gray-900">PQM</h1>
                            <p class="text-xs text-gray-500">Pharmaceutical Quality Management</p>
                        </div>
                    </div>

                    <div class="hidden md:flex items-center space-x-6">
                        <!-- Login discreto para usuarios existentes -->
                        <a href="{{ route('filament.admin.auth.login') }}"
                            class="text-sm text-gray-600 hover:text-gray-900 transition-colors flex items-center space-x-1">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            <span>Acceso Clientes</span>
                        </a>

                        <!-- Separador visual -->
                        <div class="h-4 w-px bg-gray-300"></div>

                        <!-- WhatsApp -->
                        <span class="text-sm text-gray-600">📞 WhatsApp: <strong>314 309 5251</strong></span>

                        <!-- CTA Principal -->
                        <a href="#demo"
                            class="bg-primary-600 text-white px-6 py-2 rounded-lg font-medium hover:bg-primary-700 transition-colors">
                            Demo Gratis
                        </a>
                    </div>

                    <button @click="mobileOpen = !mobileOpen" class="md:hidden p-2">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>

                <!-- Mobile Menu -->
                <div x-show="mobileOpen" class="md:hidden border-t bg-gray-50 px-4 py-4 space-y-3">
                    <!-- Login en móvil -->
                    <a href="{{ route('filament.admin.auth.login') }}"
                        class="flex items-center space-x-2 text-gray-600 hover:text-gray-900 py-2">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <span>Acceso Clientes</span>
                    </a>

                    <!-- CTA principal en móvil -->
                    <a href="#demo"
                        class="block w-full text-center bg-primary-600 text-white py-3 rounded-lg font-medium">
                        Solicitar Demo Gratis
                    </a>
                </div>
            </div>
        </nav>


        <!-- Hero Section -->
        <x-landing-hero />

        <!-- Problems Section -->
        <x-landing-problems />

        <!-- Solution Section -->
        <x-landing-solution />

        <!-- Benefits Section -->
        <x-landing-benefits />

        <!-- Testimonial Section -->
        <x-landing-testimonial />

        <!-- Pricing Section -->
        <x-landing-pricing />

        <!-- Lead Form Section -->
        <x-landing-lead-form :currentStep="$currentStep" />

        <!-- Final CTA -->
        <x-landing-final-cta />

        <!-- Footer -->
        <footer class="bg-gray-900 text-white py-12">
            <div class="max-w-4xl mx-auto px-4">
                <!-- Main Footer Content -->
                <div class="text-center mb-8">
                    <h3 class="text-2xl font-bold mb-4">¿Listo para transformar tu droguería?</h3>
                    <p class="text-gray-300 mb-6">Únete a más de 150 droguerías que ya evitan multas y ahorran tiempo
                    </p>
                    <a href="#demo"
                        class="inline-block bg-primary-600 text-white px-8 py-4 rounded-xl font-semibold hover:bg-primary-700 transition-colors">
                        Solicitar Demo Ahora
                    </a>
                </div>

                <!-- Secondary Links -->
                <div
                    class="flex flex-col sm:flex-row justify-center items-center space-y-4 sm:space-y-0 sm:space-x-8 mb-8 text-sm">
                    <a href="{{ route('filament.admin.auth.login') }}"
                        class="text-gray-400 hover:text-white transition-colors flex items-center space-x-1">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <span>Acceso Clientes</span>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white transition-colors">Términos de
                        Servicio</a>
                    <a href="#" class="text-gray-400 hover:text-white transition-colors">Política de
                        Privacidad</a>
                    <a href="https://wa.me/573143095251?text=Hola!" target="_blank"
                        class="text-gray-400 hover:text-white transition-colors">Soporte</a>
                </div>

                <div class="border-t border-gray-700 pt-8 text-sm text-gray-400 text-center">
                    <p>&copy; 2025 DrogueriasSaaS. Todos los derechos reservados.</p>
                    <p class="mt-2">Software especializado para droguerías colombianas</p>
                </div>
            </div>
        </footer>
    @endif
</div>
