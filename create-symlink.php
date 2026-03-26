<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// --------------------------------------------------------------------------
// 1. Verificar modo mantenimiento (del frontend)
// --------------------------------------------------------------------------
if (file_exists($maintenance = __DIR__.'/../drogueria/storage/framework/maintenance.php')) {
    require $maintenance;
}

// --------------------------------------------------------------------------
// 2. Registrar autoload del FRONTEND (proyecto A)
// --------------------------------------------------------------------------
require __DIR__.'/../drogueria/vendor/autoload.php';

// --------------------------------------------------------------------------
// 3. Crear SYMLINK desde el FRONTEND a la carpeta PUBLIC del BACKEND
//
// FRONTEND:  /public_html/storage
// BACKEND:   /admin.drogueriadigital.net.co/storage/app/public
// --------------------------------------------------------------------------

/* 
$linkTarget = realpath(__DIR__.'/../admin.drogueriadigital.net.co/storage/app/public'); // Obtener la ruta absoluta para mayor seguridad
$link = __DIR__.'/storage';

try {
    if (!file_exists($link) && $linkTarget && is_dir($linkTarget)) {
        if (symlink($linkTarget, $link)) {
            echo "Symlink creado correctamente: $link -> $linkTarget";
        } else {
            echo "Error al crear el symlink.";
        }
    } else {
        echo "El enlace simbólico ya existe o el directorio de destino no está disponible.";
    }
} catch (Exception $e) {
    echo "Excepción al crear el symlink: " . $e->getMessage();
} 
*/

$backendStoragePublic = realpath(__DIR__ . '/../drogueria/storage/app/public');
$frontendStorageLink  = __DIR__ . '/storage';

try {

    if (!$backendStoragePublic || !is_dir($backendStoragePublic)) {
        echo "ERROR: La ruta destino NO existe o no es un directorio: {$backendStoragePublic}";
        exit;
    }

    if (!file_exists($frontendStorageLink)) {

        if (symlink($backendStoragePublic, $frontendStorageLink)) {
            echo "Symlink creado correctamente: {$frontendStorageLink} → {$backendStoragePublic}";
        } else {
            echo "ERROR: No se pudo crear el symlink.";
        }

    } else {
        echo "El symlink ya existe o el path 'storage' ya está presente en el frontend.";
    }

} catch (\Exception $e) {
    echo "Excepción al crear el symlink: " . $e->getMessage();
}

// --------------------------------------------------------------------------
// 4. Inicializar LARAVEL (frontend)
// --------------------------------------------------------------------------
(require_once __DIR__.'/../drogueria/bootstrap/app.php')
    ->handleRequest(Request::capture());
