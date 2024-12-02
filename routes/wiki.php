<?php

use App\Http\Controllers\Admin\WikiController;
use Illuminate\Support\Facades\Route;

Route::get('/', [WikiController::class, 'index'])->name('home');

// Wiki Routes
Route::prefix('wiki')->group(function () {
    Route::get('/images/{any}', [WikiController::class, 'image'])
        ->name('wiki.image')
        ->where('any', '.*');

    // Git Wiki Routes
    Route::get('/update', [WikiController::class, 'pull'])
        ->name('gitwiki.pull');

    Route::get('/{any}', [WikiController::class, 'view'])
        ->name('wiki.page')
        ->where('any', '(?!images/).*');
});