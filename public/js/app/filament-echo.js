/**
 * Esperar a que Echo esté disponible en Filament
 */

console.log('🔧 Esperando que Echo esté disponible...');

let attempts = 0;
const checkEcho = setInterval(() => {
    attempts++;
    
    if (typeof window.Echo !== 'undefined' && window.Echo) {
        console.log('✅ Echo disponible');
        clearInterval(checkEcho);
        return;
    }
    
    if (attempts >= 30) {
        console.error('❌ Echo NO está disponible tras 30 intentos (3 segundos)');
        console.warn('⚠️ Reverb podría no estar corriendo');
        clearInterval(checkEcho);
    }
}, 100);
