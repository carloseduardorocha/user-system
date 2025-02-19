<?php

use Illuminate\Support\Facades\Route;

/**
 * /v1 routes group
 */
Route::prefix('v1')->group(function() {
    Route::middleware(['client'])->group(function() {
        Route::get('/test', function() {
            return response()->json('pong', 200);
        })->name('ping');
    });
});
