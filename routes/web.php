<?php

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\UserProfileController;
use App\Http\Controllers\Admin\WikiController;
use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/', [LoginController::class, 'showLoginForm'])->name('home');
    Route::post('/login', [LoginController::class, 'login'])->name('login');
});

Route::middleware('auth')->group(function () {
    Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

    // Profile Routes
    Route::prefix('profile')->group(function () {
        Route::get('/', [UserProfileController::class, 'index'])->name('user.profile');
        Route::post('/', [UserProfileController::class, 'update'])->name('user.profile-update');
    });

    // User Management Routes
    Route::prefix('users')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('user.list');
        Route::post('/{id}', [UserController::class, 'update'])->name('user.update');
        Route::post('/', [UserController::class, 'add'])->name('user.add');
        Route::delete('/{id}', [UserController::class, 'delete'])->name('user.delete');
    });

    // Wiki Routes
    Route::prefix('wiki')->group(function () {
        Route::get('/', [WikiController::class, 'index'])->name('wiki');
        Route::get('/images/{any}', [WikiController::class, 'image'])
            ->name('wiki.image')
            ->where('any', '.*');
        Route::get('/{any}', [WikiController::class, 'view'])
            ->name('wiki.view')
            ->where('any', '(?!images/).*');
    });
});
