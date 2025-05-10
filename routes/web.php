<?php

use App\Http\Controllers\ManufacturerController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ManufacturerController::class, 'show'])->name('home');
