<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\MidtransController;

Route::get('/products', [DashboardController::class, 'getProducts']);

Route::post('/notification', [MidtransController::class, 'notification']);
