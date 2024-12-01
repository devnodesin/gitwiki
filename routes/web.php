<?php

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\UserProfileController;
use App\Http\Controllers\Admin\WikiController;
use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'login'])->name('login');
    Route::post('/login', [LoginController::class, 'loginPost'])->name('login.post');
});

Route::middleware('auth')->group(function () {
    Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
    
    Route::get('/', [WikiController::class, 'index'])->name('home');

    // Wiki Routes
    Route::prefix('wiki')->group(function () {
        Route::get('/images/{any}', [WikiController::class, 'image'])
            ->name('wiki.image')
            ->where('any', '.*');
        Route::get('/{any}', [WikiController::class, 'view'])
            ->name('wiki.page')
            ->where('any', '(?!images/).*');
    });

    // Profile Routes
    Route::get('/profile', [UserProfileController::class, 'profile'])->name('profile');
    Route::post('/profile', [UserProfileController::class, 'profileUpdate'])->name('profile.update');

    // User Management Routes
    Route::prefix('users')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('user.list');
        Route::post('/{id}', [UserController::class, 'update'])->name('user.update');
        Route::post('/', [UserController::class, 'add'])->name('user.add');
        Route::delete('/{id}', [UserController::class, 'delete'])->name('user.delete');
    });
});

// Fallback route for handling 404s
Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});
