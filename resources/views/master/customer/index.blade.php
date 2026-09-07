@extends('layouts.app')

@section('title', 'Data Pelanggan | PharmaPOS')

@section('breadcrumb', 'Home / Customer')

@section('content')
<div class="space-y-6">
    <!-- Header: Title, Subtitle, Add Button -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-[#006680] tracking-tight">Data Pelanggan</h1>
            <p class="text-sm text-slate-500 mt-1">Kelola data pasien dan anggota loyalitas apotek.</p>
        </div>
        <div>
            <button type="button"
                    class="bg-[#006680] hover:bg-[#004d60] text-white px-4 py-2.5 rounded-lg text-sm font-medium flex items-center gap-2 shadow-sm transition-colors cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                </svg>
                <span>Tambah Pelanggan</span>
            </button>
        </div>
    </div>

    <!-- Control Bar: Search Input, Filter Button, Export Button -->
    <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3">
        <!-- Search Input -->
        <div class="relative w-full sm:w-96">
            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            <input type="text"
                   placeholder="Cari nama atau no. telepon..."
                   class="w-full pl-10 pr-4 py-2 bg-white border border-slate-300 rounded-lg text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#006680] focus:border-transparent transition-all shadow-sm">
        </div>

        <!-- Action Buttons: Filter & Export -->
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
                        <th class="py-3.5 px-6">Nama Pelanggan</th>
                        <th class="py-3.5 px-6">No. Telepon</th>
                        <th class="py-3.5 px-6">Alamat</th>
                        <th class="py-3.5 px-6 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    <!-- Row 1: Budi Santoso -->
                    <tr class="hover:bg-slate-50/70 transition-colors">
                        <td class="py-4 px-6">
                            <div class="flex items-center gap-3.5">
                                <div class="w-10 h-10 rounded-lg bg-[#006680] text-white font-bold text-xs flex items-center justify-center flex-shrink-0 shadow-sm">
                                    AS
                                </div>
                                <div>
                                    <div class="font-bold text-slate-900 text-sm">Budi Santoso</div>
                                    <div class="text-xs text-slate-400 font-normal">ID: CUST-001</div>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 px-6 text-slate-600">
                            0812-3456-7890
                        </td>
                        <td class="py-4 px-6 text-slate-600">
                            Jl. Merdeka No. 45, Jakarta Selatan
                        </td>
                        <td class="py-4 px-6 text-center">
                            <div class="inline-flex flex-col items-center justify-center gap-2">
                                <button type="button" class="text-slate-500 hover:text-slate-700 transition-colors cursor-pointer" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                    </svg>
                                </button>
                                <button type="button" class="text-slate-400 hover:text-rose-600 transition-colors cursor-pointer" title="Hapus">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>

                    <!-- Row 2: Siti Wijaya -->
                    <tr class="hover:bg-slate-50/70 transition-colors">
                        <td class="py-4 px-6">
                            <div class="flex items-center gap-3.5">
                                <div class="w-10 h-10 rounded-lg bg-slate-200 text-slate-700 font-bold text-xs flex items-center justify-center flex-shrink-0">
                                    SW
                                </div>
                                <div>
                                    <div class="font-bold text-slate-900 text-sm">Siti Wijaya</div>
                                    <div class="text-xs text-slate-400 font-normal">ID: CUST-002</div>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 px-6 text-slate-600">
                            0856-7890-1234
                        </td>
                        <td class="py-4 px-6 text-slate-600">
                            Komp. Mawar Blok B/12, Depok
                        </td>
                        <td class="py-4 px-6 text-center">
                            <div class="inline-flex flex-col items-center justify-center gap-2">
                                <button type="button" class="text-slate-500 hover:text-slate-700 transition-colors cursor-pointer" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                    </svg>
                                </button>
                                <button type="button" class="text-slate-400 hover:text-rose-600 transition-colors cursor-pointer" title="Hapus">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>

                    <!-- Row 3: Andi Rahman -->
                    <tr class="hover:bg-slate-50/70 transition-colors">
                        <td class="py-4 px-6">
                            <div class="flex items-center gap-3.5">
                                <div class="w-10 h-10 rounded-lg bg-[#006680] text-white font-bold text-xs flex items-center justify-center flex-shrink-0 shadow-sm">
                                    AR
                                </div>
                                <div>
                                    <div class="font-bold text-slate-900 text-sm">Andi Rahman</div>
                                    <div class="text-xs text-slate-400 font-normal">ID: CUST-003</div>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 px-6 text-slate-600">
                            0811-2233-4455
                        </td>
                        <td class="py-4 px-6 text-slate-600">
                            Jl. Sudirman Gg. 4, Tangerang
                        </td>
                        <td class="py-4 px-6 text-center">
                            <div class="inline-flex flex-col items-center justify-center gap-2">
                                <button type="button" class="text-slate-500 hover:text-slate-700 transition-colors cursor-pointer" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                    </svg>
                                </button>
                                <button type="button" class="text-slate-400 hover:text-rose-600 transition-colors cursor-pointer" title="Hapus">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>

                    <!-- Row 4: Dewi Ningsih -->
                    <tr class="hover:bg-slate-50/70 transition-colors">
                        <td class="py-4 px-6">
                            <div class="flex items-center gap-3.5">
                                <div class="w-10 h-10 rounded-lg bg-slate-200 text-slate-700 font-bold text-xs flex items-center justify-center flex-shrink-0">
                                    DN
                                </div>
                                <div>
                                    <div class="font-bold text-slate-900 text-sm">Dewi Ningsih</div>
                                    <div class="text-xs text-slate-400 font-normal">ID: CUST-004</div>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 px-6 text-slate-600">
                            0899-8877-6655
                        </td>
                        <td class="py-4 px-6 text-slate-600">
                            Perumahan Griya Indah 2, Bekasi
                        </td>
                        <td class="py-4 px-6 text-center">
                            <div class="inline-flex flex-col items-center justify-center gap-2">
                                <button type="button" class="text-slate-500 hover:text-slate-700 transition-colors cursor-pointer" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                    </svg>
                                </button>
                                <button type="button" class="text-slate-400 hover:text-rose-600 transition-colors cursor-pointer" title="Hapus">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>

                    <!-- Row 5: Rizal Kurniawan -->
                    <tr class="hover:bg-slate-50/70 transition-colors">
                        <td class="py-4 px-6">
                            <div class="flex items-center gap-3.5">
                                <div class="w-10 h-10 rounded-lg bg-[#006680] text-white font-bold text-xs flex items-center justify-center flex-shrink-0 shadow-sm">
                                    RK
                                </div>
                                <div>
                                    <div class="font-bold text-slate-900 text-sm">Rizal Kurniawan</div>
                                    <div class="text-xs text-slate-400 font-normal">ID: CUST-005</div>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 px-6 text-slate-600">
                            0813-5544-3322
                        </td>
                        <td class="py-4 px-6 text-slate-600">
                            Apartemen Green Park Lt. 8, Jakarta Pusat
                        </td>
                        <td class="py-4 px-6 text-center">
                            <div class="inline-flex flex-col items-center justify-center gap-2">
                                <button type="button" class="text-slate-500 hover:text-slate-700 transition-colors cursor-pointer" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                    </svg>
                                </button>
                                <button type="button" class="text-slate-400 hover:text-rose-600 transition-colors cursor-pointer" title="Hapus">
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
                Showing 1-5 of 1,248 customers
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
                <span class="px-1 text-slate-400">...</span>
                <button type="button" class="px-2.5 py-1 rounded border border-slate-200 text-slate-700 hover:bg-slate-50 transition-colors cursor-pointer">
                    250
                </button>
                <button type="button" class="px-2.5 py-1 rounded border border-slate-200 text-slate-700 hover:bg-slate-50 transition-colors cursor-pointer">
                    &gt;
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
