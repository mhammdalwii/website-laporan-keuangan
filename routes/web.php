<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MidtransController;
use App\Http\Controllers\PaymentController;

use App\Http\Controllers\TransactionController;
use App\Http\Controllers\WithdrawalController;

Route::get('/', [TransactionController::class, 'index'])->name('welcome');

Route::get('/laporan', function () {
    return view('laporan');
});



Route::get('/payment/{id}', [PaymentController::class, 'index'])->name('payment');
Route::get('/input_data', [TransactionController::class, 'create'])->name('transactions.create');
Route::post('/input_data', [TransactionController::class, 'store'])->name('transactions.store');
Route::get('/withdraw', [WithdrawalController::class, 'create'])->name('withdraw.create');
Route::post('/withdraw', [WithdrawalController::class, 'store'])->name('withdraw.store');
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
