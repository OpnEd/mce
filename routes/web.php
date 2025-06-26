<?php

use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ManufacturerController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

/**
 * Descargar, imprimir y enviar por correo electrónico facturas
 */
/* Route::middleware(['auth', 'team.context'])
    ->prefix('admin/{team}')
    ->group(function () { */
        Route::get('/generate-invoice-pdf/{id}', [InvoiceController::class, 'generatePdf'])->name('invoice.download');
        Route::get('/invoice/{id}/print', [InvoiceController::class, 'print'])->name('invoice.print');
        Route::post('/invoice/{id}/email', [InvoiceController::class, 'sendByEmail'])->name('invoice.email');
/*     }); */

Route::get('/', [ManufacturerController::class, 'show'])->name('home');

Route::get('/debug-session', function () {
    echo '<pre>';
    print_r(session()->all());
    echo '</pre>';
});

Route::get('/clear-session', function () {
    session()->flush();
    return 'Sesión vaciada';
});

Route::get('/clear-artisan', function () {
    Artisan::call('optimize:clear');
    Artisan::call('config:clear');
    Artisan::call('cache:clear');
    Artisan::call('view:clear');
    Artisan::call('route:clear');
    return 'Comandos Artisan ejecutados: optimize:clear, config:clear, cache:clear';
});
