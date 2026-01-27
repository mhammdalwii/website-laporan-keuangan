<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MidtransController;
use App\Http\Controllers\WithdrawalController;

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTE
|--------------------------------------------------------------------------
*/

// Redirect root
Route::get('/', function () {
    return Auth::check()
        ? redirect()->route('welcome')
        : redirect()->route('login');
});

/*
|--------------------------------------------------------------------------
| AUTHENTICATED ROUTE
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    // Dashboard transaksi
    Route::get('/welcome', [TransactionController::class, 'index'])
        ->name('welcome');

        Route::get('/laporan', [TransactionController::class, 'laporan'])
        ->name('laporan.index');

    // Input pengeluaran
    Route::get('/input_data', [TransactionController::class, 'create'])
        ->name('transactions.create');

    Route::post('/input_data', [TransactionController::class, 'store'])
        ->name('transactions.store');

    Route::delete('/transactions/{transaction}', [TransactionController::class, 'destroy'])
        ->name('transactions.destroy');

    // Withdraw
    Route::get('/withdraw', [WithdrawalController::class, 'create'])
        ->name('withdraw.create');

    Route::post('/withdraw', [WithdrawalController::class, 'store'])
        ->name('withdraw.store');

    // Dashboard utama
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    // Profile (Laravel Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| MIDTRANS CALLBACK (TIDAK PAKE AUTH)
|--------------------------------------------------------------------------
*/
Route::post('/midtrans/notification', [MidtransController::class, 'notification']);

/*
|--------------------------------------------------------------------------
| AUTH ROUTE (LOGIN, REGISTER, DLL)
|--------------------------------------------------------------------------
*/
require __DIR__.'/auth.php';
