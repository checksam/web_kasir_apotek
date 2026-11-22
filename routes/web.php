<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\PembelianController;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

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
    Route::middleware('role:admin')->group(function () {
        Route::get('/master/supplier', [SupplierController::class, 'index'])->name('master.supplier.index');
        Route::get('/master/supplier/create', [SupplierController::class, 'create'])->name('suppliers.create');
        Route::post('/master/supplier', [SupplierController::class, 'store'])->name('suppliers.store');
        Route::get('/master/supplier/{supplier}/edit', [SupplierController::class, 'edit'])->name('suppliers.edit');
        Route::put('/master/supplier/{supplier}', [SupplierController::class, 'update'])->name('suppliers.update');
        Route::delete('/master/supplier/{supplier}', [SupplierController::class, 'destroy'])->name('suppliers.destroy');
    });

    Route::get('/master/customer', function () {
        return view('master.customer.index');
    })->middleware('role:admin|kasir')->name('master.customer.index');

    Route::get('/barang', [ItemController::class, 'index'])
        ->middleware('role:admin|kasir')
        ->name('barang.index');
    Route::middleware('role:admin')->group(function () {
        Route::get('/barang/create', [ItemController::class, 'create'])->name('items.create');
        Route::post('/barang', [ItemController::class, 'store'])->name('items.store');
        Route::get('/barang/{item}/edit', [ItemController::class, 'edit'])->name('items.edit');
        Route::put('/barang/{item}', [ItemController::class, 'update'])->name('items.update');
        Route::delete('/barang/{item}', [ItemController::class, 'destroy'])->name('items.destroy');
    });

    // Transaksi
    Route::middleware('role:admin')->group(function () {
        Route::get('/transaksi/pembelian', [PembelianController::class, 'index'])->name('transactions.pembelian.index');
        Route::get('/transaksi/pembelian/create', [PembelianController::class, 'create'])->name('purchases.create');
        Route::get('/transaksi/pembelian/{purchase}', [PembelianController::class, 'show'])->name('purchases.show');
        Route::post('/transaksi/pembelian', [PembelianController::class, 'store'])->name('purchases.store');
    });

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
