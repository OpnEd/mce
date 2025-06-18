<?php

use App\Http\Controllers\ManufacturerController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

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
    return 'Comandos Artisan ejecutados: optimize:clear, config:clear, cache:clear';
});