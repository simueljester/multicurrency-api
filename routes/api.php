<?php

use App\Http\Controllers\API\CompanyController;
use App\Http\Controllers\API\ExchangeRateController;
use App\Http\Controllers\API\InvoiceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::prefix('v1')->middleware('api', 'throttle:60,1')->group(function () {

    Route::prefix('companies')->group(function () {
        Route::get('/', [CompanyController::class, 'index']);
        Route::post('/store', [CompanyController::class, 'store']);
        Route::get('/show/{company_id}', [CompanyController::class, 'show']);
        Route::put('/update/{company_id}', [CompanyController::class, 'update']);

    });

    Route::prefix('rates')->group(function () {
        Route::get('/', [ExchangeRateController::class, 'index']);
        Route::post('/store', [ExchangeRateController::class, 'store']);
        Route::put('/update/{exchange_rate_id}', [ExchangeRateController::class, 'update']);
    });

    Route::prefix('invoices')->group(function () {
        Route::get('/', [InvoiceController::class, 'index']);
        Route::post('/store', [InvoiceController::class, 'store']);
        Route::get('/show/{invoice_id}', [InvoiceController::class, 'show']);
    });
});
