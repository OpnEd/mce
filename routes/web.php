<?php

use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ManufacturerController;
use App\Http\Controllers\Quality\DocumentController;
use App\Livewire\LandingPage;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

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
    //Route::get('/orders/{id}', [OrdenController::class, 'orderDetails'])->name('order.details');
    //Route::get('/environmental-records', EnvironmentalRecordComponent::class)->name('environmental.records');

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
