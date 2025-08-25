<?php

use App\Http\Controllers\Modul\Contract\ContractController;
use App\Http\Controllers\Modul\Contract\MyContractController;
use App\Http\Controllers\Modul\CreditApplication\ApprovalController;
use App\Http\Controllers\Modul\CreditApplication\CreditApplicationController;
use App\Http\Controllers\Modul\Installment\InstallmentController;
use App\Http\Controllers\Modul\Payment\InstallmentPaymentController;
use Illuminate\Support\Facades\Route;

// Auth
Route::prefix('auth')->group(function () {
    Route::post('/register', [App\Http\Controllers\Auth\RegisterController::class, 'register']);
    Route::post('/login', [App\Http\Controllers\Auth\Authenticate::class, 'login']);
    Route::post('/logout', [App\Http\Controllers\Auth\Authenticate::class, 'logout'])->middleware('auth:sanctum', 'token.expired');
    Route::get('/user', [App\Http\Controllers\Auth\Authenticate::class, 'user'])->middleware('auth:sanctum', 'token.expired');
});

Route::middleware(['auth:sanctum'])->group(function () {

    // Data Vehicle
    Route::get('/vehicles', [App\Http\Controllers\Data\VehicleController::class, 'index']);

    // Admin
    Route::group(['prefix' => 'admin', 'middleware' => 'role:Admin'], function () {

        // Credit Application Approval
        Route::prefix('credit-applications')->group(function () {
            Route::prefix('approval')->group(function () {
                Route::get('/', [ApprovalController::class, 'index']);
                Route::get('/{id}', [ApprovalController::class, 'show']);
                Route::post('/{id}', [ApprovalController::class, 'store']);
            });
        });

        // Contract
        Route::prefix('contracts')->group(function () {
            Route::get('/', [ContractController::class, 'index']);
            Route::get('/', [ContractController::class, 'store']);
            Route::get('/{id}', [ContractController::class, 'show']);
        });
    });


    // Credit Application
    Route::prefix('credit-applications')->group(function () {
        Route::post('/', [CreditApplicationController::class, 'store']);
        Route::get('/me', [CreditApplicationController::class, 'myApplications']);
    });


    // Contract
    Route::prefix('contracts')->group(function () {
        Route::get('/', [MyContractController::class, 'index']);
        Route::get('/{id}', [MyContractController::class, 'show']);
    });

    // Installment
    Route::prefix('installments')->group(function () {
        Route::get('/', [InstallmentController::class, 'index']);
        Route::get('/{id}', [InstallmentController::class, 'show']);

        // Payment
        Route::prefix('payments')->group(function () {
            Route::get('/{installmentId}', [InstallmentPaymentController::class, 'show']);
            Route::post('/{installmentId}', [InstallmentPaymentController::class, 'store']);
        });
    });

});
