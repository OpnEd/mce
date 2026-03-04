<?php

use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ManufacturerController;
use App\Http\Controllers\Quality\DocumentController;
use App\Http\Controllers\Quality\WasteGenerationReportController;
use App\Livewire\LandingPage;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Whatsapp\WhatsAppWebhookController;
use App\Http\Controllers\ResiduoReportController;

Route::get('/generate-invoice-pdf/{id}', [InvoiceController::class, 'generatePdf'])->name('invoice.download');
Route::get('/invoice/{id}/print', [InvoiceController::class, 'print'])->name('invoice.print');
Route::post('/invoice/{id}/email', [InvoiceController::class, 'sendByEmail'])->name('invoice.email');
/**
 * Descargar, imprimir y enviar por correo electrónico facturas
 */
/* Route::middleware(['auth', 'team.context'])
    ->prefix('admin/{team}')
    ->group(function () { 
        Route::get('/generate-invoice-pdf/{id}', [InvoiceController::class, 'generatePdf'])->name('invoice.download');
        Route::get('/invoice/{id}/print', [InvoiceController::class, 'print'])->name('invoice.print');
        Route::post('/invoice/{id}/email', [InvoiceController::class, 'sendByEmail'])->name('invoice.email');
    }); */

/* Route::get('/', function () {
    return redirect()->route('filament.admin.auth.login');
}); */

/* Route::get('/debug-session', function () {
    echo '<pre>';
    print_r(session()->all());
    echo '</pre>';
}); */

/* Route::get('/clear-session', function () {
    session()->flush();
    return 'Sesión vaciada';
}); */

Route::get('/clear-artisan', function () {
    Artisan::call('optimize:clear');
    Artisan::call('config:clear');
    Artisan::call('cache:clear');
    Artisan::call('view:clear');
    Artisan::call('route:clear');
    return 'Comandos Artisan ejecutados: optimize:clear, config:clear, cache:clear';
});

/* Route::get('/optimize-cache', function () {
    Artisan::call('config:cache');
    Artisan::call('route:cache');
    Artisan::call('view:cache');
    Artisan::call('filament:cache-components');
    Artisan::call('optimize');

    return '¡Caché generada exitosamente!';
}); */


/* Route::get('/create-symlink', function () {
    Artisan::call('storage:link');
}); */

Route::middleware([
    'auth',
])->group(function () {
    //Route::get('/process/{id}', [ProcessController::class, 'generateCharacterization'])->name('generate.characterization');
    Route::get('admin/{tenant}/documents/{document:slug}.pdf', [DocumentController::class, 'documentDetails'])->name('document.details')->scopeBindings();
    /* Route::get('admin/{tenant}/documents/{document:slug}.pdf', function ($tenant, $document) {
         dd('Parámetros crudos recibidos:', $tenant, $document);
     })->name('document.details'); */
    //Route::get('/orders/{id}', [OrdenController::class, 'orderDetails'])->name('order.details');
    //Route::get('/environmental-records', EnvironmentalRecordComponent::class)->name('environmental.records');
    //Route::get('admin/{tenant}/informes/residuos/{report:numero_informe}.pdf', [WasteGenerationReportController::class, 'downloadLastYear'])->name('informe.residuos')->scopeBindings();
    
    // DEBUG: Ruta temporal para interceptar parámetros si tienes problemas de 404 (Descomentar para probar)
     /* Route::get('admin/{tenant}/informes/residuos/{report}.pdf', function ($tenant, $report) {
         dd('Parámetros crudos recibidos:', $tenant, $report);
     })->name('informe.residuos'); */

    Route::get('admin/{tenant}/informes/residuos/{report:numero_informe}.pdf', [WasteGenerationReportController::class, 'downloadLastYear'])->name('informe.residuos');

});

/* Route::get('/debug/lesson-template', function () {
    $cfg = config('lesson_template');

    // Dump legible
    return response()->json([
        'found' => $cfg !== null,
        'type' => gettype($cfg),
        'count' => is_array($cfg) ? count($cfg) : null,
        'sample' => is_array($cfg) ? array_slice($cfg, 0, 3) : $cfg,
    ]);
}); */

Route::get('/', LandingPage::class);

// Página que muestra "por favor verifica tu correo"
Route::get('/email/verify', function () {
    return view('auth.verify-email'); // crea esta vista o adapta
})->middleware('auth')->name('verification.notice');

// La ruta que marca el email como verificado (nombre requerido: verification.verify)
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill(); // marca email_verified_at y dispara Verified event

    // redirige donde quieras; para Filament panel usa la ruta del panel:
    return redirect()->intended('/admin'); // ajusta a tu prefijo Filament
})->middleware(['auth', 'signed'])->name('verification.verify');

// Reenviar link de verificación
Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();

    return back()->with('status', 'verification-link-sent');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

Route::get('/login', function () {
    return redirect('/admin/login');
})->name('login');

/* Route::get('/debug-mail-config', function () {
    // Solo si no es producción estricta
    if (config('app.env') === 'production' && !config('app.debug')) {
        return 'Debug no disponible';
    }
    
    return response()->json([
        'mail_smtp_config' => config('mail.mailers.smtp'),
        'mail_from_config' => config('mail.from'),
        'app_env' => config('app.env'),
        'config_cached' => file_exists(base_path('bootstrap/cache/config.php'))
    ], 200, [], JSON_PRETTY_PRINT);
}); */


//Route::match(['get', 'post'], '/webhook/whatsapp', WhatsAppWebhookController::class);
Route::get('webhook/whatsapp', [WhatsAppWebhookController::class, 'verifyWebhook']);
Route::post('webhook/whatsapp', [WhatsAppWebhookController::class, 'handleIncomingMessage']);