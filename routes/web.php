<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BankAccountController;

Route::get('/', function () {
    return view('welcome');
});

Route::post('/bank-accounts/{accountId}/deposit', [BankAccountController::class, 'deposit']);
Route::post('/bank-accounts/{accountId}/withdraw', [BankAccountController::class, 'withdraw']);
Route::get('/bank-accounts/{accountId}/balance', [BankAccountController::class, 'getBalance']);
Route::post('/transfer', [BankAccountController::class, 'transfer']);
