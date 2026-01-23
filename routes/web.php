<?php
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\MidtransController;

use App\Http\Controllers\TransactionController;
use App\Http\Controllers\WithdrawalController;

Route::get('/', [TransactionController::class, 'index'])->name('welcome');

Route::get('/laporan', function () {
    return view('laporan');
});

Route::get('/', function () {
    // Jika user sudah Login, langsung ke halaman Input Pengeluaran
    if (Auth::check()) {
        return redirect()->route('transactions.welcome');
    }
    // Jika belum, lempar ke Login
    return redirect()->route('login');
});

Route::get('/input_data', [TransactionController::class, 'create'])->name('transactions.create');
Route::post('/input_data', [TransactionController::class, 'store'])->name('transactions.store');
Route::delete('/transactions/{transaction}', [TransactionController::class, 'destroy'])->name('transactions.destroy');

Route::get('/withdraw', [WithdrawalController::class, 'create'])->name('withdraw.create');
Route::post('/withdraw', [WithdrawalController::class, 'store'])->name('withdraw.store');

Route::post('/midtrans/notification', [MidtransController::class, 'notification']);

// Grup Rute untuk semua halaman yang memerlukan login
Route::middleware('auth')->group(function () {
    // Rute dasbor sekarang menunjuk ke DashboardController dan dilindungi oleh middleware
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    // Rute profil bawaan dari Laravel Breeze
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/', function () {
    return redirect()->route('login');
});
});

require __DIR__.'/auth.php';
