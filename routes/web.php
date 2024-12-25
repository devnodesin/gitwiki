<?php

use App\Http\Controllers\Admin\GitController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\UserProfileController;
use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;

//Wiki Routes
if (config('wiki.auth_enable')) {
    Route::middleware('auth')->group(base_path('routes/wiki.php'));
} else {
    Route::middleware('web')->group(base_path('routes/wiki.php'));
}

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'login'])->name('login');
    Route::post('/login', [LoginController::class, 'loginPost'])->name('login.post');
});

Route::middleware('auth')->group(function () {
    Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

    // Profile Routes
    Route::get('/profile', [UserProfileController::class, 'profile'])->name('profile');
    Route::post('/profile', [UserProfileController::class, 'update'])->name('profile.update');

    Route::middleware('admin')->group(function () {
        Route::prefix('users')->group(function () {
            Route::get('/', [UserController::class, 'index'])->name('user.list');
            Route::post('/{id}', [UserController::class, 'update'])->name('user.update');
            Route::post('/', [UserController::class, 'add'])->name('user.add');
            Route::delete('/{id}', [UserController::class, 'delete'])->name('user.delete');
        });

        Route::get('/settings', [SettingsController::class, 'index'])->name('settings');
        Route::post('/settings', [SettingsController::class, 'update'])->name('settings.update');

        Route::prefix('git')->group(function () {
            Route::get('/', [GitController::class, 'index'])->name('git.index');
            Route::get('/init', [GitController::class, 'init'])->name('git.init');
            Route::post('/clone', [GitController::class, 'clone'])->name('git.clone');
            Route::get('/pull', [GitController::class, 'pull'])->name('git.pull');
            Route::get('/push', [GitController::class, 'push'])->name('git.push');
            Route::get('/commit', [GitController::class, 'commit'])->name('git.commit');
            Route::get('/status', [GitController::class, 'status'])->name('git.status');
            Route::get('/reset', [GitController::class, 'reset'])->name('git.reset');
            Route::get('/reset/{hash}', [GitController::class, 'reset'])->name('git.reset.hash');
        });
    });
});

// Fallback route for handling 404s
Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});

Route::fallback(function () {
    return response()->view('errors.404', [], 403);
});
