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
    /*
    Route::get('/update', [WikiController::class, 'pull'])
        ->name('gitwiki.pull');
    */

    Route::where(['any' => '(?!images/).*'])->group(function () {
        Route::get('/{any}/edit', [WikiController::class, 'edit'])->name('wiki.edit');
        Route::get('/{any}', [WikiController::class, 'view'])->name('wiki.page');
        Route::post('/{any}/save', [WikiController::class, 'save'])->name('wiki.save');
    });
});
