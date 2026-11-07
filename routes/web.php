<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['web', 'auth', 'active'])->group(function () {
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])
        ->middleware('role:admin')
        ->name('admin.dashboard');

    Route::get('/kasir/dashboard', [DashboardController::class, 'index'])
        ->middleware('role:kasir')
        ->name('kasir.dashboard');

    Route::view('/pelanggan/dashboard', 'dashboard.pelanggan')
        ->middleware('role:pelanggan')
        ->name('pelanggan.dashboard');

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

    Route::get('/transaksi/penjualan', [PenjualanController::class, 'index'])
        ->middleware('role:admin|kasir')
        ->name('transactions.penjualan.index');
    Route::post('/transaksi/penjualan', [PenjualanController::class, 'checkout'])
        ->middleware('role:admin|kasir')
        ->name('transactions.penjualan.checkout');

    Route::get('/kasir/transaksi/penjualan', [PenjualanController::class, 'index'])
        ->middleware('role:kasir')
        ->name('kasir.penjualan');
    Route::post('/kasir/transaksi/penjualan', [PenjualanController::class, 'checkout'])
        ->middleware('role:kasir')
        ->name('kasir.penjualan.checkout');

    // Reports
    Route::get('/laporan/pembelian', function () {
        return view('reports.pembelian');
    })->middleware('role:admin')->name('reports.pembelian');

    Route::get('/laporan/penjualan', function () {
        return view('reports.penjualan');
    })->middleware('role:admin|kasir')->name('reports.penjualan');

    Route::middleware('role:admin')->group(function () {
        Route::get('/pengaturan', [SettingController::class, 'edit'])->name('settings.edit');
        Route::put('/pengaturan', [SettingController::class, 'update'])->name('settings.update');

        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    });
});
