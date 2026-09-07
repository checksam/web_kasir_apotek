@extends('layouts.app')

@section('title', 'Data Supplier | PharmaPOS')

@section('breadcrumb', 'Home / Supplier')

@section('content')
<div class="space-y-6">
    <!-- Header: Title, Subtitle, Add Button -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-[#006680] tracking-tight">Data Supplier</h1>
            <p class="text-sm text-slate-500 mt-1">Kelola informasi vendor dan pemasok farmasi</p>
        </div>
        <div>
            <button type="button"
                    class="bg-[#006680] hover:bg-[#004d60] text-white px-4 py-2.5 rounded-lg text-sm font-medium flex items-center gap-2 shadow-sm transition-colors cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                </svg>
                <span>Tambah Supplier</span>
            </button>
        </div>
    </div>

    <!-- Control Bar: Search Input & Filter Button -->
    <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3">
        <!-- Search Input -->
        <div class="relative w-full sm:w-96">
            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            <input type="text"
                   placeholder="Cari nama supplier atau kontak..."
                   class="w-full pl-10 pr-4 py-2 bg-white border border-slate-300 rounded-lg text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#006680] focus:border-transparent transition-all shadow-sm">
        </div>

        <!-- Filter Button -->
        <div class="flex items-center gap-2">
            <button type="button"
                    class="bg-white border border-slate-300 text-slate-700 hover:bg-slate-50 px-4 py-2 rounded-lg text-sm font-medium flex items-center gap-2 shadow-sm transition-colors cursor-pointer">
                <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                </svg>
                <span>Filter</span>
            </button>
        </div>
    </div>

    <!-- Table Container -->
    <div class="bg-white rounded-lg border border-slate-200 overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-200 bg-slate-50/50 text-xs font-bold text-slate-800 tracking-wider">
                        <th class="py-3.5 px-6">Nama Supplier</th>
                        <th class="py-3.5 px-6">Kontak Person</th>
                        <th class="py-3.5 px-6">Alamat</th>
                        <th class="py-3.5 px-6 text-center">Status</th>
                        <th class="py-3.5 px-6 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    <!-- Row 1 -->
                    <tr class="hover:bg-slate-50/70 transition-colors">
                        <td class="py-4 px-6 font-semibold text-[#006680]">
                            <a href="#" class="hover:underline">PT. Global Medika Utama</a>
                        </td>
                        <td class="py-4 px-6">
                            <div class="font-medium text-slate-800">Budi Santoso</div>
                            <div class="text-xs text-slate-500 mt-0.5">0812-3456-7890</div>
                        </td>
                        <td class="py-4 px-6 text-slate-600">
                            Jl. Jend. Sudirman No. 45, Jakarta Selatan
                        </td>
                        <td class="py-4 px-6 text-center">
                            <span class="inline-flex items-center px-3 py-0.5 rounded-full text-xs font-medium bg-sky-100 text-sky-700">
                                Aktif
                            </span>
                        </td>
                        <td class="py-4 px-6">
                            <div class="flex items-center justify-center gap-3">
                                <button type="button" class="text-slate-500 hover:text-slate-700 transition-colors cursor-pointer" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                    </svg>
                                </button>
                                <button type="button" class="text-rose-600 hover:text-rose-800 transition-colors cursor-pointer" title="Hapus">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>

                    <!-- Row 2 -->
                    <tr class="hover:bg-slate-50/70 transition-colors">
                        <td class="py-4 px-6 font-semibold text-[#006680]">
                            <a href="#" class="hover:underline">CV. Farmasi Sejahtera</a>
                        </td>
                        <td class="py-4 px-6">
                            <div class="font-medium text-slate-800">Anita Wijaya</div>
                            <div class="text-xs text-slate-500 mt-0.5">0856-7890-1234</div>
                        </td>
                        <td class="py-4 px-6 text-slate-600">
                            Komp. Ruko Harapan Indah Blok A1, Bekasi
                        </td>
                        <td class="py-4 px-6 text-center">
                            <span class="inline-flex items-center px-3 py-0.5 rounded-full text-xs font-medium bg-sky-100 text-sky-700">
                                Aktif
                            </span>
                        </td>
                        <td class="py-4 px-6">
                            <div class="flex items-center justify-center gap-3">
                                <button type="button" class="text-slate-500 hover:text-slate-700 transition-colors cursor-pointer" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                    </svg>
                                </button>
                                <button type="button" class="text-rose-600 hover:text-rose-800 transition-colors cursor-pointer" title="Hapus">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>

                    <!-- Row 3 -->
                    <tr class="hover:bg-slate-50/70 transition-colors">
                        <td class="py-4 px-6 font-semibold text-[#006680]">
                            <a href="#" class="hover:underline">Distributor Obat Nusantara</a>
                        </td>
                        <td class="py-4 px-6">
                            <div class="font-medium text-slate-800">Hendra Gunawan</div>
                            <div class="text-xs text-slate-500 mt-0.5">0811-2233-4455</div>
                        </td>
                        <td class="py-4 px-6 text-slate-600">
                            Jl. Gatot Subroto Kav. 12, Bandung
                        </td>
                        <td class="py-4 px-6 text-center">
                            <span class="inline-flex items-center px-3 py-0.5 rounded-full text-xs font-medium bg-slate-200 text-slate-600">
                                Nonaktif
                            </span>
                        </td>
                        <td class="py-4 px-6">
                            <div class="flex items-center justify-center gap-3">
                                <button type="button" class="text-slate-500 hover:text-slate-700 transition-colors cursor-pointer" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                    </svg>
                                </button>
                                <button type="button" class="text-rose-600 hover:text-rose-800 transition-colors cursor-pointer" title="Hapus">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Table Footer / Pagination -->
        <div class="px-6 py-3.5 border-t border-slate-200 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-slate-500">
            <div>
                Menampilkan 1 hingga 3 dari 12 entri
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
