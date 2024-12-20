<?php

use App\Http\Controllers\Admin\GitController;
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

        Route::prefix('git')->group(function () {
            Route::get('/', [GitController::class, 'index'])->name('git.index');
            Route::get('/update', [GitController::class, 'pull'])->name('git.pull');
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
