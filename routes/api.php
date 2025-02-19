<?php

use App\Http\Middleware\JwtMiddleware;

use App\Http\Controllers\UserController;

use Illuminate\Support\Facades\Route;

/**
 * /v1 routes group
 */
Route::prefix('v1')->group(function() {
    Route::middleware(['client'])->group(function() {
        Route::prefix('')->name('users.')->group(function() {
            Route::controller(UserController::class)->group(function() {
                Route::post('/login', 'login')->name('login');
                Route::post('/create', 'create')->name('create');
                Route::delete('/delete', 'delete')->name('delete');
                Route::patch('/restore', 'restore')->name('restore');
            });
        });
    });
    Route::middleware([JwtMiddleware::class])->group(function() {
        Route::prefix('')->name('users.')->group(function() {
            Route::controller(UserController::class)->group(function() {
                Route::get('/me', 'me')->name('me');
                Route::put('/update', 'update')->name('update');
                Route::patch('/update-password', 'updatePassword')->name('update.password');
            });
        });
    });
});
