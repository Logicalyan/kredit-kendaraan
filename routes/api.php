<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::prefix('auth')->group(function () {
    Route::post('/register', [App\Http\Controllers\Auth\RegisterController::class, 'register']);
    Route::post('/login', [App\Http\Controllers\Auth\Authenticate::class, 'login']);
    Route::post('/logout', [App\Http\Controllers\Auth\Authenticate::class, 'logout'])->middleware('auth:sanctum', 'token.expired');
    Route::get('/user', [App\Http\Controllers\Auth\Authenticate::class, 'user'])->middleware('auth:sanctum', 'token.expired');
});

Route::middleware(['auth:sanctum'])->group(function () {

});
