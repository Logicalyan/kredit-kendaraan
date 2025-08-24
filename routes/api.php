<?php

use App\Http\Controllers\Modul\Contract\ContractController;
use App\Http\Controllers\Modul\Contract\MyContractController;
use App\Http\Controllers\Modul\CreditApplication\ApprovalController;
use App\Http\Controllers\Modul\CreditApplication\CreditApplicationController;
use App\Http\Controllers\Modul\Payment\InstallmentPaymentController;
use Illuminate\Support\Facades\Route;


Route::prefix('auth')->group(function () {
    Route::post('/register', [App\Http\Controllers\Auth\RegisterController::class, 'register']);
    Route::post('/login', [App\Http\Controllers\Auth\Authenticate::class, 'login']);
    Route::post('/logout', [App\Http\Controllers\Auth\Authenticate::class, 'logout'])->middleware('auth:sanctum', 'token.expired');
    Route::get('/user', [App\Http\Controllers\Auth\Authenticate::class, 'user'])->middleware('auth:sanctum', 'token.expired');
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::group(['prefix' => 'admin', 'middleware' => 'role:Admin'], function () {
        Route::post('/credit-applications/{id}/approval', [ApprovalController::class, 'store']);
        Route::get('/credit-applications/{id}/approval', [ApprovalController::class, 'show']);

        // Contract
        Route::post('/contracts', [ContractController::class, 'store']);
        Route::get('/contracts/{id}', [ContractController::class, 'show']);
    });

    Route::get('/vehicles', [App\Http\Controllers\Data\VehicleController::class, 'index']);
    Route::post('/credit-applications', [CreditApplicationController::class, 'store']);
    Route::get('/credit-applications/me', [CreditApplicationController::class, 'myApplications']);
    Route::get('/my-contracts', [MyContractController::class, 'index']);
    Route::get('/my-contracts/{id}', [MyContractController::class, 'show']);
    Route::get('/installments/{installmentId}/payments', [InstallmentPaymentController::class, 'show']);
    Route::post('/installments/{installmentId}/payments', [InstallmentPaymentController::class, 'store']);
});
