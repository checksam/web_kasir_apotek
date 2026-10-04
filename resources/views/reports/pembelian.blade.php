@extends('layouts.app')

@section('title', 'Laporan Pembelian — PharmaPOS')

@push('styles')
<style>
    /* ── Bar Chart Animations ── */
    @keyframes growBar {
        from { transform: scaleY(0); }
        to   { transform: scaleY(1); }
    }
    .bar-animate {
        transform-origin: bottom;
        animation: growBar 0.7s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
    }
    .bar-delay-1 { animation-delay: 0.05s; }
    .bar-delay-2 { animation-delay: 0.15s; }
    .bar-delay-3 { animation-delay: 0.25s; }
    .bar-delay-4 { animation-delay: 0.35s; }

    /* ── Progress Bar Animations ── */
    @keyframes growWidth {
        from { width: 0; }
    }
    .progress-animate {
        animation: growWidth 1s ease-out forwards;
        animation-delay: 0.3s;
        width: 0;
    }

    /* ── Metric Card subtle hover lift ── */
    .metric-card {
        transition: box-shadow 0.2s ease, transform 0.2s ease;
    }
    .metric-card:hover {
        box-shadow: 0 8px 30px -6px rgba(0,122,135,0.15);
        transform: translateY(-2px);
    }

    /* ── Table row hover ── */
    .tr-hover:hover {
        background-color: #f0fdfd;
    }

    /* ── Badge pulse for Menunggu ── */
    @keyframes pulse-dot {
        0%, 100% { opacity: 1; }
        50%       { opacity: 0.4; }
    }
    .dot-pulse {
        animation: pulse-dot 1.5s ease-in-out infinite;
    }
</style>
@endpush

@section('content')

{{-- ══════════════════════════════════════════════════════════
     HEADER ROW — Title + Date Filter
═══════════════════════════════════════════════════════════ --}}
<div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

    {{-- Page Title --}}
    <div>
        <h1 class="text-2xl font-bold text-slate-800 leading-tight tracking-tight">
            Laporan Pembelian
        </h1>
        <p class="mt-1 text-sm text-slate-500">
            Tinjauan komprehensif pengeluaran inventory.
        </p>
    </div>

    {{-- Date Filter --}}
    <form method="GET" action="{{ request()->url() }}"
          class="flex flex-col gap-2 sm:flex-row sm:items-center">

        <div class="flex items-center gap-2">
            {{-- Start Date --}}
            <div class="relative">
                <input
                    id="filter_start"
                    type="date"
                    name="start_date"
                    value="{{ request('start_date', '2023-10-01') }}"
                    class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700
                           shadow-sm transition focus:border-teal-500 focus:outline-none focus:ring-2
                           focus:ring-teal-500/30 hover:border-slate-300"
                >
            </div>

            <span class="flex-shrink-0 text-sm font-medium text-slate-400">hingga</span>

            {{-- End Date --}}
            <div class="relative">
                <input
                    id="filter_end"
                    type="date"
                    name="end_date"
                    value="{{ request('end_date', '2023-10-31') }}"
                    class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700
                           shadow-sm transition focus:border-teal-500 focus:outline-none focus:ring-2
                           focus:ring-teal-500/30 hover:border-slate-300"
                >
            </div>
        </div>

        {{-- Filter Button --}}
        <button
            type="submit"
            id="btn-filter"
            class="inline-flex items-center justify-center gap-2 rounded-lg bg-[#007A87] px-4 py-2
                   text-sm font-semibold text-white shadow-sm transition
                   hover:bg-[#005f6b] active:scale-95 focus:outline-none focus:ring-2
                   focus:ring-[#007A87]/40"
        >
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z"/>
            </svg>
            Filter
        </button>
    </form>
</div>

{{-- ══════════════════════════════════════════════════════════
     METRIC CARDS — 4 columns
═══════════════════════════════════════════════════════════ --}}
<div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">

    {{-- Card 1 – Total Pengeluaran --}}
    <div class="metric-card rounded-xl border border-slate-100 bg-white p-5 shadow-sm">
        <p class="mb-2 text-xs font-semibold uppercase tracking-wider text-slate-400">
            Total Pengeluaran
        </p>
        <p class="text-2xl font-extrabold text-[#007A87]">
            Rp&nbsp;45.230.000
        </p>
        <div class="mt-3 flex items-center gap-1.5">
            {{-- Up arrow + percentage --}}
            <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-2 py-0.5 text-xs font-semibold text-emerald-600">
                <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                </svg>
                +12%
            </span>
            <span class="text-xs text-slate-400">dari bulan lalu</span>
        </div>
    </div>

    {{-- Card 2 – Total Item Dibeli --}}
    <div class="metric-card rounded-xl border border-slate-100 bg-white p-5 shadow-sm">
        <p class="mb-2 text-xs font-semibold uppercase tracking-wider text-slate-400">
            Total Item Dibeli
        </p>
        <p class="text-2xl font-extrabold text-slate-800">
            1.245 <span class="text-lg font-semibold text-slate-500">Unit</span>
        </p>
        <div class="mt-3 flex items-center gap-1.5">
            {{-- Down arrow + percentage --}}
            <span class="inline-flex items-center gap-1 rounded-full bg-rose-50 px-2 py-0.5 text-xs font-semibold text-rose-600">
                <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"/>
                </svg>
                -3%
            </span>
            <span class="text-xs text-slate-400">dari bulan lalu</span>
        </div>
    </div>

    {{-- Card 3 – Pesanan Aktif --}}
    <div class="metric-card rounded-xl border border-slate-100 bg-white p-5 shadow-sm">
        <p class="mb-2 text-xs font-semibold uppercase tracking-wider text-slate-400">
            Pesanan Aktif
        </p>
        <p class="text-2xl font-extrabold text-slate-800">
            8 <span class="text-lg font-semibold text-slate-500">Pesanan</span>
        </p>
        <div class="mt-3 flex items-start gap-2">
            <span class="mt-0.5 inline-flex flex-shrink-0 items-center rounded-md bg-amber-100 px-2 py-0.5 text-xs font-bold text-amber-700">
                Rp 18.200.000
            </span>
            <span class="text-xs leading-snug text-slate-400">
                Menunggu pengiriman &amp; tempo
            </span>
        </div>
    </div>

    {{-- Card 4 – Vendor/Supplier Aktif --}}
    <div class="metric-card rounded-xl border border-slate-100 bg-white p-5 shadow-sm">
        <p class="mb-2 text-xs font-semibold uppercase tracking-wider text-slate-400">
            Vendor / Supplier Aktif
        </p>
        <p class="text-2xl font-extrabold text-slate-800">
            12 <span class="text-lg font-semibold text-slate-500">PBF Rekanan</span>
        </p>
        <div class="mt-3 flex items-center gap-2">
            <div class="flex h-5 w-5 flex-shrink-0 items-center justify-center rounded-full bg-[#007A87]/10">
                <svg class="h-3 w-3 text-[#007A87]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04
                             A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622
                             0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
            </div>
            <span class="text-xs font-medium text-slate-500">PBF Resmi Terverifikasi BPOM</span>
        </div>
    </div>

</div>

{{-- ══════════════════════════════════════════════════════════
     MID SECTION — Weekly Chart + Supplier List
═══════════════════════════════════════════════════════════ --}}
<div class="mb-6 grid grid-cols-1 gap-6 lg:grid-cols-3">

    {{-- ── Weekly Purchase Trend Chart (col-span-2) ── --}}
    <div class="rounded-xl border border-slate-100 bg-white p-5 shadow-sm lg:col-span-2">
        {{-- Widget Header --}}
        <div class="mb-6 flex flex-wrap items-start justify-between gap-3">
            <div>
                <h2 class="text-base font-bold text-slate-800">Tren Pembelian Mingguan</h2>
                <p class="mt-0.5 text-xs text-slate-400">
                    Fluktuasi nilai restok inventory obat periode Oktober 2023
                </p>
            </div>
            <span class="inline-flex items-center gap-1.5 rounded-full bg-[#007A87]/10 px-3 py-1
                         text-xs font-semibold text-[#007A87]">
                <span class="h-2 w-2 rounded-full bg-[#007A87]"></span>
                4 Pekan Aktif
            </span>
        </div>

        {{-- Bar Chart --}}
        <div class="flex items-end justify-around gap-3 sm:gap-6" style="height: 160px;">

            {{-- Minggu 1 --}}
            <div class="flex flex-1 flex-col items-center gap-1.5">
                <span class="text-[11px] font-medium text-slate-500">Rp 9.8Jt</span>
                <div class="relative w-full overflow-hidden rounded-t-md" style="height: 100px;">
                    <div class="bar-animate bar-delay-1 absolute bottom-0 left-0 right-0 h-[68%]
                                rounded-t-md bg-slate-200"></div>
                </div>
                <span class="text-xs font-medium text-slate-400">Mgg 1</span>
            </div>

            {{-- Minggu 2 --}}
            <div class="flex flex-1 flex-col items-center gap-1.5">
                <span class="text-[11px] font-medium text-slate-500">Rp 14.5Jt</span>
                <div class="relative w-full overflow-hidden rounded-t-md" style="height: 100px;">
                    <div class="bar-animate bar-delay-2 absolute bottom-0 left-0 right-0 h-full
                                rounded-t-md bg-[#4db6c2]"></div>
                </div>
                <span class="text-xs font-medium text-slate-400">Mgg 2</span>
            </div>

            {{-- Minggu 3 --}}
            <div class="flex flex-1 flex-col items-center gap-1.5">
                <span class="text-[11px] font-medium text-slate-500">Rp 8.1Jt</span>
                <div class="relative w-full overflow-hidden rounded-t-md" style="height: 100px;">
                    <div class="bar-animate bar-delay-3 absolute bottom-0 left-0 right-0 h-[56%]
                                rounded-t-md bg-slate-200"></div>
                </div>
                <span class="text-xs font-medium text-slate-400">Mgg 3</span>
            </div>

            {{-- Minggu 4 (Active / Highlighted) --}}
            <div class="flex flex-1 flex-col items-center gap-1.5">
                <span class="text-[11px] font-semibold text-[#007A87]">Rp 12.8Jt</span>
                <div class="relative w-full overflow-hidden rounded-t-md" style="height: 100px;">
                    <div class="bar-animate bar-delay-4 absolute bottom-0 left-0 right-0 h-[88%]
                                rounded-t-md bg-[#007A87]"></div>
                </div>
                <span class="text-xs font-bold text-[#007A87]">Mgg 4</span>
            </div>

        </div>

        {{-- Widget Footer --}}
        <div class="mt-5 flex flex-wrap items-center justify-between gap-2 border-t border-slate-100 pt-4">
            <p class="text-xs text-slate-500">
                Rata-rata pengeluaran per minggu:
                <span class="font-semibold text-slate-700">Rp 11.307.500</span>
            </p>
            <p class="text-xs text-slate-500">
                Total kuantitas:
                <span class="font-semibold text-slate-700">1.245 Box/Botol</span>
            </p>
        </div>
    </div>

    {{-- ── Supplier Terbesar (col-span-1) ── --}}
    <div class="rounded-xl border border-slate-100 bg-white p-5 shadow-sm lg:col-span-1">
        {{-- Widget Header --}}
        <div class="mb-5 flex items-start justify-between">
            <div>
                <h2 class="text-base font-bold text-slate-800">Supplier Terbesar</h2>
                <p class="mt-0.5 text-xs text-slate-400">
                    Alokasi pengeluaran ke distributor PBF
                </p>
            </div>
            <button class="flex h-7 w-7 items-center justify-center rounded-lg border border-slate-200
                           text-slate-400 transition hover:bg-slate-50 hover:text-slate-600">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M4 6h16M4 10h16M4 14h16M4 18h7"/>
                </svg>
            </button>
        </div>

        {{-- Supplier List --}}
        <div class="space-y-4">

            {{-- PT Kalbe Farma Tbk (35%) --}}
            <div>
                <div class="mb-1.5 flex items-center justify-between">
                    <span class="text-sm font-medium text-slate-700">PT Kalbe Farma Tbk</span>
                    <div class="text-right">
                        <span class="text-sm font-bold text-[#007A87]">Rp 15.800.000</span>
                        <span class="ml-1 text-xs font-semibold text-[#007A87]">(35%)</span>
                    </div>
                </div>
                <div class="h-2 w-full overflow-hidden rounded-full bg-slate-100">
                    <div class="progress-animate h-full rounded-full bg-[#007A87]" style="width:35%"></div>
                </div>
            </div>

            {{-- PT Dexa Medica (28%) --}}
            <div>
                <div class="mb-1.5 flex items-center justify-between">
                    <span class="text-sm font-medium text-slate-700">PT Dexa Medica</span>
                    <div class="text-right">
                        <span class="text-sm font-bold text-slate-700">Rp 12.500.000</span>
                        <span class="ml-1 text-xs font-semibold text-slate-400">(28%)</span>
                    </div>
                </div>
                <div class="h-2 w-full overflow-hidden rounded-full bg-slate-100">
                    <div class="progress-animate h-full rounded-full bg-[#4db6c2]" style="width:28%"></div>
                </div>
            </div>

            {{-- PT Kimia Farma Trading (18%) --}}
            <div>
                <div class="mb-1.5 flex items-center justify-between">
                    <span class="text-sm font-medium text-slate-700">PT Kimia Farma Trading</span>
                    <div class="text-right">
                        <span class="text-sm font-bold text-slate-700">Rp 8.200.000</span>
                        <span class="ml-1 text-xs font-semibold text-slate-400">(18%)</span>
                    </div>
                </div>
                <div class="h-2 w-full overflow-hidden rounded-full bg-slate-100">
                    <div class="progress-animate h-full rounded-full bg-slate-400" style="width:18%"></div>
                </div>
            </div>

            {{-- PT Bina San Prima (19%) --}}
            <div>
                <div class="mb-1.5 flex items-center justify-between">
                    <span class="text-sm font-medium text-slate-700">PT Bina San Prima</span>
                    <div class="text-right">
                        <span class="text-sm font-bold text-slate-700">Rp 8.730.000</span>
                        <span class="ml-1 text-xs font-semibold text-slate-400">(19%)</span>
                    </div>
                </div>
                <div class="h-2 w-full overflow-hidden rounded-full bg-slate-100">
                    <div class="progress-animate h-full rounded-full bg-slate-300" style="width:19%"></div>
                </div>
            </div>

        </div>

        {{-- Footer Link --}}
        <div class="mt-5 border-t border-slate-100 pt-4">
            <a href="#"
               class="inline-flex items-center gap-1 text-xs font-semibold text-[#007A87]
                      transition hover:text-[#005f6b] hover:underline">
                Kelola Rekanan Supplier
                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>
    </div>

</div>

{{-- ══════════════════════════════════════════════════════════
     TRANSACTION TABLE SECTION
═══════════════════════════════════════════════════════════ --}}
<div class="rounded-xl border border-slate-100 bg-white shadow-sm">

    {{-- Table Header --}}
    <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-100 px-5 py-4">
        <div class="flex items-center gap-3">
            <h2 class="text-base font-bold text-slate-800">Rincian Transaksi Pembelian</h2>
            <span class="rounded-full bg-[#007A87]/10 px-2.5 py-0.5 text-xs font-bold text-[#007A87]">
                45 Transaksi
            </span>
        </div>

        {{-- Action Buttons --}}
        <div class="flex items-center gap-2">
            {{-- Kolom Button --}}
            <button id="btn-kolom"
                    class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-white
                           px-3 py-1.5 text-xs font-semibold text-slate-600 shadow-sm transition
                           hover:bg-slate-50 hover:border-slate-300 active:scale-95 focus:outline-none
                           focus:ring-2 focus:ring-slate-300">
                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M3 10h18M3 6h18M3 14h18M3 18h18"/>
                </svg>
                Kolom
            </button>

            {{-- Ekspor CSV Button --}}
            <button id="btn-ekspor"
                    class="inline-flex items-center gap-1.5 rounded-lg bg-[#007A87] px-3 py-1.5
                           text-xs font-semibold text-white shadow-sm transition
                           hover:bg-[#005f6b] active:scale-95 focus:outline-none
                           focus:ring-2 focus:ring-[#007A87]/40">
                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                </svg>
                Ekspor CSV
            </button>
        </div>
    </div>

    {{-- Scrollable Table --}}
    <div class="overflow-x-auto w-full">
        <table class="w-full min-w-[720px] text-sm">
            <thead>
                <tr class="border-b border-slate-100 bg-slate-50/70">
                    <th class="whitespace-nowrap px-5 py-3 text-left text-xs font-semibold uppercase
                               tracking-wider text-slate-400">
                        ID PO / Faktur
                    </th>
                    <th class="whitespace-nowrap px-4 py-3 text-left text-xs font-semibold uppercase
                               tracking-wider text-slate-400">
                        Tanggal
                    </th>
                    <th class="whitespace-nowrap px-4 py-3 text-left text-xs font-semibold uppercase
                               tracking-wider text-slate-400">
                        Supplier / PBF
                    </th>
                    <th class="whitespace-nowrap px-4 py-3 text-left text-xs font-semibold uppercase
                               tracking-wider text-slate-400">
                        Item Terbanyak
                    </th>
                    <th class="whitespace-nowrap px-4 py-3 text-right text-xs font-semibold uppercase
                               tracking-wider text-slate-400">
                        Total (Rp)
                    </th>
                    <th class="whitespace-nowrap px-4 py-3 text-center text-xs font-semibold uppercase
                               tracking-wider text-slate-400">
                        Status
                    </th>
                    <th class="whitespace-nowrap px-4 py-3 text-center text-xs font-semibold uppercase
                               tracking-wider text-slate-400">
                        Aksi
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">

                {{-- ── Row 1 – PO-2310-045 ── --}}
                <tr class="tr-hover transition-colors duration-150">
                    <td class="px-5 py-4">
                        <p class="font-semibold text-[#007A87] hover:underline cursor-pointer">
                            PO-2310-045
                        </p>
                        <p class="mt-0.5 text-[11px] text-slate-400">FAK-DXM-9921</p>
                    </td>
                    <td class="whitespace-nowrap px-4 py-4 text-slate-600">28 Okt 2023</td>
                    <td class="px-4 py-4 text-slate-700 font-medium">PT Dexa Medica</td>
                    <td class="px-4 py-4 text-slate-500 max-w-[200px] truncate">
                        Amoxicillin 500mg, Paracetamol
                    </td>
                    <td class="whitespace-nowrap px-4 py-4 text-right font-bold text-slate-800">
                        12.500.000
                    </td>
                    <td class="px-4 py-4 text-center">
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50
                                     px-2.5 py-1 text-xs font-semibold text-emerald-700">
                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                            Selesai
                        </span>
                    </td>
                    <td class="px-4 py-4 text-center">
                        <button title="Lihat Detail"
                                class="inline-flex h-8 w-8 items-center justify-center rounded-lg
                                       text-[#007A87] transition hover:bg-[#007A87]/10 active:scale-95">
                            <svg class="h-4.5 w-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7
                                         -1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </button>
                    </td>
                </tr>

                {{-- ── Row 2 – PO-2310-044 ── --}}
                <tr class="tr-hover transition-colors duration-150">
                    <td class="px-5 py-4">
                        <p class="font-semibold text-[#007A87] hover:underline cursor-pointer">
                            PO-2310-044
                        </p>
                        <p class="mt-0.5 text-[11px] text-slate-400">FAK-KFT-1042</p>
                    </td>
                    <td class="whitespace-nowrap px-4 py-4 text-slate-600">25 Okt 2023</td>
                    <td class="px-4 py-4 text-slate-700 font-medium">PT Kimia Farma Trading</td>
                    <td class="px-4 py-4 text-slate-500 max-w-[200px] truncate">
                        Ibuprofen 400mg, Vitamin C
                    </td>
                    <td class="whitespace-nowrap px-4 py-4 text-right font-bold text-slate-800">
                        8.200.000
                    </td>
                    <td class="px-4 py-4 text-center">
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50
                                     px-2.5 py-1 text-xs font-semibold text-emerald-700">
                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                            Selesai
                        </span>
                    </td>
                    <td class="px-4 py-4 text-center">
                        <button title="Lihat Detail"
                                class="inline-flex h-8 w-8 items-center justify-center rounded-lg
                                       text-[#007A87] transition hover:bg-[#007A87]/10 active:scale-95">
                            <svg class="h-4.5 w-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7
                                         -1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </button>
                    </td>
                </tr>

                {{-- ── Row 3 – PO-2310-043 ── --}}
                <tr class="tr-hover transition-colors duration-150">
                    <td class="px-5 py-4">
                        <p class="font-semibold text-[#007A87] hover:underline cursor-pointer">
                            PO-2310-043
                        </p>
                        <p class="mt-0.5 text-[11px] text-slate-400">FAK-KLB-6830</p>
                    </td>
                    <td class="whitespace-nowrap px-4 py-4 text-slate-600">22 Okt 2023</td>
                    <td class="px-4 py-4 text-slate-700 font-medium">PT Kalbe Farma</td>
                    <td class="px-4 py-4 text-slate-500 max-w-[200px] truncate">
                        Promag, Mixagrip Flu
                    </td>
                    <td class="whitespace-nowrap px-4 py-4 text-right font-bold text-slate-800">
                        15.800.000
                    </td>
                    <td class="px-4 py-4 text-center">
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-amber-50
                                     px-2.5 py-1 text-xs font-semibold text-amber-700">
                            <span class="h-1.5 w-1.5 rounded-full bg-amber-500"></span>
                            Proses Kirim
                        </span>
                    </td>
                    <td class="px-4 py-4 text-center">
                        <button title="Lihat Detail"
                                class="inline-flex h-8 w-8 items-center justify-center rounded-lg
                                       text-[#007A87] transition hover:bg-[#007A87]/10 active:scale-95">
                            <svg class="h-4.5 w-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7
                                         -1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </button>
                    </td>
                </tr>

                {{-- ── Row 4 – PO-2310-042 ── --}}
                <tr class="tr-hover transition-colors duration-150">
                    <td class="px-5 py-4">
                        <p class="font-semibold text-[#007A87] hover:underline cursor-pointer">
                            PO-2310-042
                        </p>
                        <p class="mt-0.5 text-[11px] text-slate-400">FAK-BSP-5112</p>
                    </td>
                    <td class="whitespace-nowrap px-4 py-4 text-slate-600">18 Okt 2023</td>
                    <td class="px-4 py-4 text-slate-700 font-medium">PT Bina San Prima</td>
                    <td class="px-4 py-4 text-slate-500 max-w-[200px] truncate">
                        Omeprazole 20mg, Cetirizin
                    </td>
                    <td class="whitespace-nowrap px-4 py-4 text-right font-bold text-slate-800">
                        8.730.000
                    </td>
                    <td class="px-4 py-4 text-center">
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-rose-50
                                     px-2.5 py-1 text-xs font-semibold text-rose-700">
                            <span class="dot-pulse h-1.5 w-1.5 rounded-full bg-rose-500"></span>
                            Menunggu Pembayaran
                        </span>
                    </td>
                    <td class="px-4 py-4 text-center">
                        <button title="Lihat Detail"
                                class="inline-flex h-8 w-8 items-center justify-center rounded-lg
                                       text-[#007A87] transition hover:bg-[#007A87]/10 active:scale-95">
                            <svg class="h-4.5 w-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7
                                         -1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </button>
                    </td>
                </tr>

            </tbody>
        </table>
    </div>

    {{-- ── Pagination Footer ── --}}
    <div class="flex flex-col items-start justify-between gap-3 border-t border-slate-100
                px-5 py-3.5 sm:flex-row sm:items-center">

        {{-- Info Text --}}
        <p class="text-xs text-slate-500">
            Menampilkan 1–4 dari
            <span class="font-semibold text-slate-700">45 transaksi</span>
            &bull;
            Halaman 1 dari 12
        </p>

        {{-- Page Buttons --}}
        <nav class="flex items-center gap-1" aria-label="Paginasi">

            {{-- Prev --}}
            <button disabled
                    class="inline-flex h-8 w-8 items-center justify-center rounded-lg border
                           border-slate-200 bg-white text-slate-400 cursor-not-allowed opacity-50
                           transition">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </button>

            {{-- Page 1 (active) --}}
            <button aria-current="page"
                    class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-[#007A87]
                           text-xs font-bold text-white shadow-sm">
                1
            </button>

            {{-- Page 2 --}}
            <button class="inline-flex h-8 w-8 items-center justify-center rounded-lg border
                           border-slate-200 bg-white text-xs font-medium text-slate-600 transition
                           hover:bg-slate-50 hover:border-slate-300">
                2
            </button>

            {{-- Page 3 --}}
            <button class="inline-flex h-8 w-8 items-center justify-center rounded-lg border
                           border-slate-200 bg-white text-xs font-medium text-slate-600 transition
                           hover:bg-slate-50 hover:border-slate-300">
                3
            </button>

            {{-- Ellipsis --}}
            <span class="inline-flex h-8 w-8 items-center justify-center text-xs text-slate-400">
                &hellip;
            </span>

            {{-- Last Page --}}
            <button class="inline-flex h-8 w-8 items-center justify-center rounded-lg border
                           border-slate-200 bg-white text-xs font-medium text-slate-600 transition
                           hover:bg-slate-50 hover:border-slate-300">
                12
            </button>

            {{-- Next --}}
            <button class="inline-flex h-8 w-8 items-center justify-center rounded-lg border
                           border-slate-200 bg-white text-slate-600 transition
                           hover:bg-slate-50 hover:border-slate-300 active:scale-95">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </button>

        </nav>
    </div>

</div>
{{-- ── End of page content ── --}}

@endsection

@push('scripts')
<script>
    /**
     * Ekspor CSV button — simple client-side stub.
     * Replace with a real route/controller action as needed.
     */
    document.getElementById('btn-ekspor')?.addEventListener('click', function () {
        // Example: redirect to a controller that streams CSV
        // Export endpoint can be wired here when the report export route is available.
        alert('Ekspor CSV akan diimplementasikan via controller.');
    });

    /**
     * Kolom toggle button — stub for future column visibility control.
     */
    document.getElementById('btn-kolom')?.addEventListener('click', function () {
        alert('Toggle kolom akan diimplementasikan.');
    });
</script>
@endpush
