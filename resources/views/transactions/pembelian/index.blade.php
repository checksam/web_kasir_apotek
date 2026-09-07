@extends('layouts.app')

@section('title', 'Transaksi Pembelian | PharmaPOS')

@section('breadcrumb', 'Home / Transaksi Pembelian')

@section('content')
<div class="space-y-6">
    <!-- Header: Title, Subtitle, Add Button -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Transaksi Pembelian</h1>
            <p class="text-sm text-slate-500 mt-1">Kelola riwayat pembelian obat dan alat kesehatan dari supplier.</p>
        </div>
        <div>
            <button type="button"
                    class="bg-[#006680] hover:bg-[#004d60] text-white px-4 py-2.5 rounded-lg text-xs font-semibold uppercase tracking-wider flex items-center gap-2 shadow-sm transition-colors cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                </svg>
                <span>INPUT PEMBELIAN BARU</span>
            </button>
        </div>
    </div>

    <!-- Summary Cards Grid (3 Kolom Top) -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        <!-- Card 1: Total Pembelian (Bulan Ini) -->
        <div class="bg-white rounded-lg border border-slate-200 p-5 relative overflow-hidden shadow-sm flex flex-col justify-between">
            <!-- Watermark Truck Icon -->
            <div class="absolute right-2 top-2 text-teal-100/60 pointer-events-none">
                <svg class="w-20 h-20 stroke-[1.2]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0" />
                </svg>
            </div>
            <div class="relative z-10">
                <div class="text-xs font-semibold text-slate-500 uppercase tracking-wider">
                    TOTAL PEMBELIAN (BULAN INI)
                </div>
                <div class="text-2xl sm:text-3xl font-bold text-slate-900 tracking-tight mt-1.5">
                    Rp 124.500.000
                </div>
                <div class="flex items-center gap-1 text-teal-600 text-xs font-medium mt-2.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                    </svg>
                    <span>+12.5% dari bulan lalu</span>
                </div>
            </div>
        </div>

        <!-- Card 2: Menunggu Pembayaran -->
        <div class="bg-white rounded-lg border border-slate-200 p-5 relative overflow-hidden shadow-sm flex flex-col justify-between">
            <!-- Watermark Receipt Icon -->
            <div class="absolute right-2 top-2 text-rose-100/60 pointer-events-none">
                <svg class="w-20 h-20 stroke-[1.2]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </div>
            <div class="relative z-10">
                <div class="text-xs font-semibold text-slate-500 uppercase tracking-wider">
                    MENUNGGU PEMBAYARAN
                </div>
                <div class="text-2xl sm:text-3xl font-bold text-slate-900 tracking-tight mt-1.5">
                    5 Faktur
                </div>
                <div class="text-rose-600 text-xs font-medium mt-2.5">
                    Total: Rp 18.200.000
                </div>
            </div>
        </div>

        <!-- Card 3: Barang Diterima -->
        <div class="bg-white rounded-lg border border-slate-200 p-5 relative overflow-hidden shadow-sm flex flex-col justify-between">
            <!-- Watermark Package Icon -->
            <div class="absolute right-2 top-2 text-emerald-100/60 pointer-events-none">
                <svg class="w-20 h-20 stroke-[1.2]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                </svg>
            </div>
            <div class="relative z-10">
                <div class="text-xs font-semibold text-slate-500 uppercase tracking-wider">
                    BARANG DITERIMA
                </div>
                <div class="text-2xl sm:text-3xl font-bold text-slate-900 tracking-tight mt-1.5">
                    142 Item
                </div>
                <div class="text-slate-500 text-xs mt-2.5">
                    Dalam 30 hari terakhir
                </div>
            </div>
        </div>
    </div>

    <!-- Control Bar: Search Input & Action Buttons -->
    <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3">
        <!-- Search Input -->
        <div class="relative w-full sm:w-96">
            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            <input type="text"
                   placeholder="Cari No. Faktur atau Supplier..."
                   class="w-full pl-10 pr-4 py-2 bg-white border border-slate-300 rounded-lg text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#006680] focus:border-transparent transition-all shadow-sm">
        </div>

        <!-- Filter & Export Buttons -->
        <div class="flex items-center gap-2.5">
            <button type="button"
                    class="bg-white border border-slate-300 text-slate-700 hover:bg-slate-50 px-3.5 py-2 rounded-lg text-sm font-medium flex items-center gap-2 shadow-sm transition-colors cursor-pointer">
                <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                </svg>
                <span>Filter</span>
            </button>
            <button type="button"
                    class="bg-white border border-slate-300 text-slate-700 hover:bg-slate-50 px-3.5 py-2 rounded-lg text-sm font-medium flex items-center gap-2 shadow-sm transition-colors cursor-pointer">
                <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                </svg>
                <span>Export</span>
            </button>
        </div>
    </div>

    <!-- Table Container -->
    <div class="bg-white rounded-lg border border-slate-200 overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-200 bg-slate-50/50 text-xs font-bold text-slate-800 tracking-wider">
                        <th class="py-3.5 px-6">NO. FAKTUR</th>
                        <th class="py-3.5 px-6">TANGGAL</th>
                        <th class="py-3.5 px-6">SUPPLIER</th>
                        <th class="py-3.5 px-6">TOTAL BIAYA</th>
                        <th class="py-3.5 px-6 text-center">STATUS</th>
                        <th class="py-3.5 px-6 text-center">AKSI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    <!-- Row 1 -->
                    <tr class="hover:bg-slate-50/70 transition-colors">
                        <td class="py-4 px-6 font-semibold text-[#006680]">
                            <a href="#" class="hover:underline">INV-202310-001</a>
                        </td>
                        <td class="py-4 px-6 text-slate-600">
                            24 Okt 2023
                        </td>
                        <td class="py-4 px-6 text-slate-800 font-medium">
                            PT. Bina San Prima
                        </td>
                        <td class="py-4 px-6 font-bold text-slate-900">
                            Rp 15.400.000
                        </td>
                        <td class="py-4 px-6 text-center">
                            <span class="inline-flex items-center px-3 py-0.5 rounded-full text-xs font-medium bg-emerald-50 text-emerald-600 border border-emerald-200">
                                Selesai
                            </span>
                        </td>
                        <td class="py-4 px-6 text-center">
                            <button type="button" class="text-slate-400 hover:text-slate-600 transition-colors cursor-pointer" title="Lihat Detail">
                                <svg class="w-5 h-5 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>
                        </td>
                    </tr>

                    <!-- Row 2 -->
                    <tr class="hover:bg-slate-50/70 transition-colors">
                        <td class="py-4 px-6 font-semibold text-[#006680]">
                            <a href="#" class="hover:underline">INV-202310-002</a>
                        </td>
                        <td class="py-4 px-6 text-slate-600">
                            23 Okt 2023
                        </td>
                        <td class="py-4 px-6 text-slate-800 font-medium">
                            PBF Kimia Farma
                        </td>
                        <td class="py-4 px-6 font-bold text-slate-900">
                            Rp 8.250.000
                        </td>
                        <td class="py-4 px-6 text-center">
                            <span class="inline-flex items-center px-3 py-0.5 rounded-full text-xs font-medium bg-amber-50 text-amber-600 border border-amber-200">
                                Menunggu Pembayaran
                            </span>
                        </td>
                        <td class="py-4 px-6 text-center">
                            <button type="button" class="text-slate-400 hover:text-slate-600 transition-colors cursor-pointer" title="Lihat Detail">
                                <svg class="w-5 h-5 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>
                        </td>
                    </tr>

                    <!-- Row 3 -->
                    <tr class="hover:bg-slate-50/70 transition-colors">
                        <td class="py-4 px-6 font-semibold text-[#006680]">
                            <a href="#" class="hover:underline">INV-202310-003</a>
                        </td>
                        <td class="py-4 px-6 text-slate-600">
                            21 Okt 2023
                        </td>
                        <td class="py-4 px-6 text-slate-800 font-medium">
                            PT. Enseval Putera
                        </td>
                        <td class="py-4 px-6 font-bold text-slate-900">
                            Rp 24.100.000
                        </td>
                        <td class="py-4 px-6 text-center">
                            <span class="inline-flex items-center px-3 py-0.5 rounded-full text-xs font-medium bg-emerald-50 text-emerald-600 border border-emerald-200">
                                Selesai
                            </span>
                        </td>
                        <td class="py-4 px-6 text-center">
                            <button type="button" class="text-slate-400 hover:text-slate-600 transition-colors cursor-pointer" title="Lihat Detail">
                                <svg class="w-5 h-5 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>
                        </td>
                    </tr>

                    <!-- Row 4 -->
                    <tr class="hover:bg-slate-50/70 transition-colors">
                        <td class="py-4 px-6 font-semibold text-[#006680]">
                            <a href="#" class="hover:underline">INV-202310-004</a>
                        </td>
                        <td class="py-4 px-6 text-slate-600">
                            20 Okt 2023
                        </td>
                        <td class="py-4 px-6 text-slate-800 font-medium">
                            PT. Parit Padang Global
                        </td>
                        <td class="py-4 px-6 font-bold text-slate-900">
                            Rp 5.750.000
                        </td>
                        <td class="py-4 px-6 text-center">
                            <span class="inline-flex items-center px-3 py-0.5 rounded-full text-xs font-medium bg-rose-50 text-rose-600 border border-rose-200">
                                Dibatalkan
                            </span>
                        </td>
                        <td class="py-4 px-6 text-center">
                            <button type="button" class="text-slate-400 hover:text-slate-600 transition-colors cursor-pointer" title="Lihat Detail">
                                <svg class="w-5 h-5 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>
                        </td>
                    </tr>

                    <!-- Row 5 -->
                    <tr class="hover:bg-slate-50/70 transition-colors">
                        <td class="py-4 px-6 font-semibold text-[#006680]">
                            <a href="#" class="hover:underline">INV-202310-005</a>
                        </td>
                        <td class="py-4 px-6 text-slate-600">
                            18 Okt 2023
                        </td>
                        <td class="py-4 px-6 text-slate-800 font-medium">
                            PT. Antarmitra Sembada
                        </td>
                        <td class="py-4 px-6 font-bold text-slate-900">
                            Rp 11.300.000
                        </td>
                        <td class="py-4 px-6 text-center">
                            <span class="inline-flex items-center px-3 py-0.5 rounded-full text-xs font-medium bg-emerald-50 text-emerald-600 border border-emerald-200">
                                Selesai
                            </span>
                        </td>
                        <td class="py-4 px-6 text-center">
                            <button type="button" class="text-slate-400 hover:text-slate-600 transition-colors cursor-pointer" title="Lihat Detail">
                                <svg class="w-5 h-5 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Table Footer / Pagination -->
        <div class="px-6 py-3.5 border-t border-slate-200 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-slate-500">
            <div>
                Menampilkan 1-5 dari 42 transaksi
            </div>
            <div class="flex items-center gap-1.5">
                <button type="button" class="px-2.5 py-1 rounded border border-slate-200 text-slate-400 hover:bg-slate-50 transition-colors cursor-pointer" disabled>
                    &lt;
                </button>
                <button type="button" class="px-3 py-1 rounded bg-[#006680] text-white font-medium shadow-sm cursor-pointer">
                    1
                </button>
                <button type="button" class="px-3 py-1 rounded border border-slate-200 text-slate-700 hover:bg-slate-50 transition-colors cursor-pointer">
                    2
                </button>
                <button type="button" class="px-3 py-1 rounded border border-slate-200 text-slate-700 hover:bg-slate-50 transition-colors cursor-pointer">
                    3
                </button>
                <button type="button" class="px-2.5 py-1 rounded border border-slate-200 text-slate-700 hover:bg-slate-50 transition-colors cursor-pointer">
                    &gt;
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
