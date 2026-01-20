/**
 * Escuchar notificaciones en tiempo real
 */

console.log('🔧 Configurando listeners de broadcast...');

document.addEventListener('DOMContentLoaded', () => {
    
    // Verificar que Echo está disponible
    if (typeof window.Echo === 'undefined' || !window.Echo) {
        console.error('❌ Echo no disponible - Reverb podría no estar corriendo');
        return;
    }

    console.log('✅ Echo disponible');

    // Obtener el team ID
    const teamId = document.documentElement.getAttribute('data-team-id');

    if (!teamId) {
        console.warn('⚠️ Team ID no encontrado');
        return;
    }

    console.log(`📡 Escuchando: team.${teamId}.notifications`);

    // Escuchar notificaciones
    window.Echo.private(`team.${teamId}.notifications`)
        .listen('OrderNotification', (data) => {
            console.log('✅ Notificación recibida:', data);
        })
        .error((error) => {
            console.error('❌ Error en canal:', error);
        });
});
