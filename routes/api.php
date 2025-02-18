<?php

use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function() {
    Route::get('/ping', function() {
        return response()->json('pong', 200);
    })->name('ping');
});
