@extends('layouts.app')

@section('title', 'Dashboard | PharmaPOS')

@section('breadcrumb', 'Home / Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Page Title Header -->
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Dashboard</h1>
    </div>

    <!-- 4 Stat Cards Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <!-- Card 1: Pengguna (Cyan Accent) -->
        <div class="bg-white rounded-lg border border-slate-200 border-t-4 border-t-[#006680] shadow-sm overflow-hidden flex flex-col justify-between relative group">
            <div class="p-5 pb-4 relative">
                <!-- Watermark User Icon -->
                <div class="absolute top-3 right-3 text-sky-100 pointer-events-none">
                    <svg class="w-16 h-16 stroke-[1.2]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
                <div class="relative z-10">
                    <div class="text-4xl font-bold text-slate-800 tracking-tight">3</div>
                    <div class="text-sm font-medium text-slate-500 mt-1">Pengguna</div>
                </div>
            </div>
            <a href="#" class="bg-slate-50 hover:bg-slate-100 border-t border-slate-100 px-4 py-2 text-xs font-medium text-slate-600 flex items-center justify-center gap-1.5 transition-colors">
                <span>More info</span>
                <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 9l3 3m0 0l-3 3m3-3H8m13 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </a>
        </div>

        <!-- Card 2: Supplier (Emerald Accent) -->
        <div class="bg-white rounded-lg border border-slate-200 border-t-4 border-t-[#10b981] shadow-sm overflow-hidden flex flex-col justify-between relative group">
            <div class="p-5 pb-4 relative">
                <!-- Watermark Truck Icon -->
                <div class="absolute top-3 right-3 text-emerald-100 pointer-events-none">
                    <svg class="w-16 h-16 stroke-[1.2]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0" />
                    </svg>
                </div>
                <div class="relative z-10">
                    <div class="text-4xl font-bold text-slate-800 tracking-tight">2</div>
                    <div class="text-sm font-medium text-slate-500 mt-1">Supplier</div>
                </div>
            </div>
            <a href="{{ route('master.supplier.index') }}" class="bg-slate-50 hover:bg-slate-100 border-t border-slate-100 px-4 py-2 text-xs font-medium text-slate-600 flex items-center justify-center gap-1.5 transition-colors">
                <span>More info</span>
                <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 9l3 3m0 0l-3 3m3-3H8m13 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </a>
        </div>

        <!-- Card 3: Pelanggan (Amber Accent) -->
        <div class="bg-white rounded-lg border border-slate-200 border-t-4 border-t-[#f59e0b] shadow-sm overflow-hidden flex flex-col justify-between relative group">
            <div class="p-5 pb-4 relative">
                <!-- Watermark Users Icon -->
                <div class="absolute top-3 right-3 text-amber-100 pointer-events-none">
                    <svg class="w-16 h-16 stroke-[1.2]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <div class="relative z-10">
                    <div class="text-4xl font-bold text-slate-800 tracking-tight">2</div>
                    <div class="text-sm font-medium text-slate-500 mt-1">Pelanggan</div>
                </div>
            </div>
            <a href="{{ route('master.customer.index') }}" class="bg-slate-50 hover:bg-slate-100 border-t border-slate-100 px-4 py-2 text-xs font-medium text-slate-600 flex items-center justify-center gap-1.5 transition-colors">
                <span>More info</span>
                <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 9l3 3m0 0l-3 3m3-3H8m13 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </a>
        </div>

        <!-- Card 4: Item Barang (Rose Accent) -->
        <div class="bg-white rounded-lg border border-slate-200 border-t-4 border-t-[#f43f5e] shadow-sm overflow-hidden flex flex-col justify-between relative group">
            <div class="p-5 pb-4 relative">
                <!-- Watermark Cart Icon -->
                <div class="absolute top-3 right-3 text-rose-100 pointer-events-none">
                    <svg class="w-16 h-16 stroke-[1.2]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <div class="relative z-10">
                    <div class="text-4xl font-bold text-slate-800 tracking-tight">4</div>
                    <div class="text-sm font-medium text-slate-500 mt-1">Item Barang</div>
                </div>
            </div>
            <a href="#" class="bg-slate-50 hover:bg-slate-100 border-t border-slate-100 px-4 py-2 text-xs font-medium text-slate-600 flex items-center justify-center gap-1.5 transition-colors">
                <span>More info</span>
                <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 9l3 3m0 0l-3 3m3-3H8m13 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </a>
        </div>
    </div>

    <!-- Section Bawah: 2 Kolom Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-stretch">
        <!-- Left Card: Info Stok Barang -->
        <div class="bg-white rounded-lg border border-slate-200 border-t-4 border-t-[#f43f5e] shadow-sm overflow-hidden flex flex-col">
            <div class="px-5 py-4 border-b border-slate-100">
                <h2 class="font-bold text-base text-slate-800">Info Stok Barang</h2>
            </div>
            <div class="divide-y divide-slate-100 flex-1 flex flex-col justify-center">
                <!-- Item 1 -->
                <div class="px-5 py-3.5 flex items-center justify-between">
                    <span class="text-sm font-medium text-slate-700">Amoxicillin 250mg</span>
                    <span class="inline-flex items-center gap-1 bg-rose-50 text-rose-600 border border-rose-200 px-3 py-1 rounded-full text-xs font-medium">
                        <svg class="w-3.5 h-3.5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <span>Stok Kurang</span>
                    </span>
                </div>

                <!-- Item 2 -->
                <div class="px-5 py-3.5 flex items-center justify-between">
                    <span class="text-sm font-medium text-slate-700">Paracetamol 500mg</span>
                    <span class="inline-flex items-center gap-1 bg-rose-50 text-rose-600 border border-rose-200 px-3 py-1 rounded-full text-xs font-medium">
                        <svg class="w-3.5 h-3.5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <span>Stok Kurang</span>
                    </span>
                </div>
            </div>
        </div>

        <!-- Right Card: Omzet Penjualan -->
        <div class="bg-white rounded-lg border border-slate-200 border-t-4 border-t-[#006680] shadow-sm overflow-hidden flex flex-col">
            <div class="px-5 py-4 border-b border-slate-100">
                <h2 class="font-bold text-base text-slate-800">Omzet Penjualan</h2>
            </div>
            <div class="flex-1 flex flex-col items-center justify-center p-8 text-center min-h-[140px]">
                <div class="text-xs font-semibold uppercase tracking-wider text-slate-500 mb-2">
                    TOTAL HARI INI
                </div>
                <div class="text-4xl sm:text-5xl font-bold text-[#006680] tracking-tight">
                    {{ $dailyIncome ?? 'Rp 400.000' }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
