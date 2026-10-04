<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ItemController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['web', 'auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->middleware('role:admin|kasir')
        ->name('dashboard');

    // Master Data
    Route::get('/master/supplier', function () {
        return view('master.supplier.index');
    })->middleware('role:admin')->name('master.supplier.index');

    Route::get('/master/customer', function () {
        return view('master.customer.index');
    })->middleware('role:admin|kasir')->name('master.customer.index');

    Route::get('/barang', [ItemController::class, 'index'])
        ->middleware('role:admin|kasir')
        ->name('barang.index');

    // Transaksi
    Route::get('/transaksi/pembelian', function () {
        return view('transactions.pembelian.index');
    })->middleware('role:admin')->name('transactions.pembelian.index');

    Route::get('/transaksi/penjualan', function () {
        return view('transactions.penjualan.index');
    })->middleware('role:admin|kasir')->name('transactions.penjualan.index');

    // Reports
    Route::get('/laporan/pembelian', function () {
        return view('reports.pembelian');
    })->middleware('role:admin')->name('reports.pembelian');

    Route::get('/laporan/penjualan', function () {
        return view('reports.penjualan');
    })->middleware('role:admin|kasir')->name('reports.penjualan');
});
