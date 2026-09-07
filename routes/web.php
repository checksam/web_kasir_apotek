<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('web')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Master Data
    Route::get('/master/supplier', function () {
        return view('master.supplier.index');
    })->name('master.supplier.index');

    Route::get('/master/customer', function () {
        return view('master.customer.index');
    })->name('master.customer.index');

    // Transaksi
    Route::get('/transaksi/pembelian', function () {
        return view('transactions.pembelian.index');
    })->name('transactions.pembelian.index');
});
