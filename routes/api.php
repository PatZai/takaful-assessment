<?php

use App\Http\Controllers\BankAccountController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/bank-accounts/{accountId}/deposit', [BankAccountController::class, 'deposit']);
    Route::post('/bank-accounts/{accountId}/withdraw', [BankAccountController::class, 'withdraw']);
    Route::get('/bank-accounts/{accountId}/balance', [BankAccountController::class, 'getBalance']);
    Route::post('/transfer', [BankAccountController::class, 'transfer']);
});
