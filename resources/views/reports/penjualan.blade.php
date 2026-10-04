@extends('layouts.app')

@section('title', 'Laporan Penjualan — PharmaPOS')

@push('styles')
<style>
    /* ── Metric Card hover lift ── */
    .metric-card {
        transition: box-shadow 0.2s ease, transform 0.2s ease;
    }
    .metric-card:hover {
        box-shadow: 0 8px 30px -6px rgba(0,122,135,0.15);
        transform: translateY(-2px);
    }

    /* ── Table row hover ── */
    .tr-hover:hover { background-color: #f0fdfd; }

    /* ── Fade-in rows ── */
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(6px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    .fade-row { animation: fadeInUp 0.35s ease-out both; }
    .fd-1  { animation-delay: .04s; }
    .fd-2  { animation-delay: .08s; }
    .fd-3  { animation-delay: .12s; }
    .fd-4  { animation-delay: .16s; }
    .fd-5  { animation-delay: .20s; }
    .fd-6  { animation-delay: .24s; }

    /* ── Chart bar animations ── */
    @keyframes growBar {
        from { transform: scaleY(0); }
        to   { transform: scaleY(1); }
    }
    .bar-chart-col { transform-origin: bottom; }
    .bar-grow {
        animation: growBar 0.6s cubic-bezier(0.34,1.4,0.64,1) both;
    }

    /* ── Trend line SVG draw animation ── */
    .line-draw {
        stroke-dasharray: 900;
        stroke-dashoffset: 900;
        animation: drawLine 1.6s ease-out forwards;
        animation-delay: 0.2s;
    }
    @keyframes drawLine {
        to { stroke-dashoffset: 0; }
    }
    .area-fade {
        opacity: 0;
        animation: areaFadeIn 0.8s ease-out forwards;
        animation-delay: 0.8s;
    }
    @keyframes areaFadeIn {
        to { opacity: 1; }
    }

    /* ── Highlight dot on active date ── */
    .chart-dot-active {
        animation: popDot 0.4s cubic-bezier(0.34,1.6,0.64,1) both;
        animation-delay: 1.6s;
    }
    @keyframes popDot {
        from { r: 0; }
        to   { r: 4; }
    }

    /* ── Quick filter active ── */
    .qf-active {
        background-color: #007A87;
        color: #fff;
        border-color: #007A87;
    }
</style>
@endpush

@section('content')

{{-- ══════════════════════════════════════════════════════════
     HEADER ROW — Title + Filter Controls
═══════════════════════════════════════════════════════════ --}}
<div class="mb-6 flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">

    {{-- Page Title --}}
    <div class="min-w-0">
        <h1 class="text-2xl font-bold leading-tight tracking-tight text-slate-800">
            Laporan Penjualan
        </h1>
        <p class="mt-1 max-w-md text-sm text-slate-500">
            Ringkasan performa finansial, tren omzet, dan riwayat transaksi penjualan apotek.
        </p>
    </div>

    {{-- Controls --}}
    <div class="flex flex-col gap-2 sm:flex-row sm:flex-wrap sm:items-center">

        {{-- Date Range --}}
        <div class="flex items-center gap-2">
            <div class="relative">
                <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-slate-400">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0
                                 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </span>
                <input type="text" id="filter-range" readonly
                       value="01/10/2023 - 31/10/2023"
                       class="cursor-pointer rounded-lg border border-slate-200 bg-white
                              py-2 pl-9 pr-3 text-sm text-slate-700 shadow-sm transition
                              focus:border-teal-500 focus:outline-none focus:ring-2
                              focus:ring-teal-500/30 hover:border-slate-300 w-52">
            </div>
        </div>

        {{-- Quick Filter --}}
        <button id="btn-bulan-ini"
                class="qf-active inline-flex items-center gap-1.5 rounded-lg border px-3 py-2
                       text-sm font-semibold transition hover:opacity-90 active:scale-95
                       focus:outline-none focus:ring-2 focus:ring-[#007A87]/30">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            Bulan Ini (Okt 2023)
        </button>

        {{-- Ekspor CSV / PDF --}}
        <button id="btn-ekspor"
                class="inline-flex items-center justify-center gap-2 rounded-lg bg-[#007A87]
                       px-4 py-2 text-sm font-semibold text-white shadow-sm transition
                       hover:bg-[#005f6b] active:scale-95 focus:outline-none
                       focus:ring-2 focus:ring-[#007A87]/40">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
            </svg>
            Ekspor CSV / PDF
        </button>

    </div>
</div>

{{-- ══════════════════════════════════════════════════════════
     METRIC CARDS — 3 columns
═══════════════════════════════════════════════════════════ --}}
<div class="mb-6 grid grid-cols-1 gap-4 md:grid-cols-3">

    {{-- Card 1 – Total Pendapatan --}}
    <div class="metric-card relative overflow-hidden rounded-xl border border-slate-100
                bg-white p-5 shadow-sm">
        <div class="mb-3 flex items-start justify-between">
            <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                Total Pendapatan
            </p>
            <div class="flex h-7 w-7 flex-shrink-0 items-center justify-center rounded-lg bg-[#007A87]/10">
                <svg class="h-4 w-4 text-[#007A87]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2
                             m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1
                             c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
        <p class="text-2xl font-extrabold text-[#007A87]">Rp&nbsp;58.420.000</p>
        <div class="mt-2 flex items-center gap-1.5">
            <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-2 py-0.5
                         text-xs font-semibold text-emerald-600">
                <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                          d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                </svg>
                +14.2%
            </span>
            <span class="text-xs text-slate-400">dari bulan lalu</span>
        </div>
    </div>

    {{-- Card 2 – Total Transaksi --}}
    <div class="metric-card relative overflow-hidden rounded-xl border border-slate-100
                bg-white p-5 shadow-sm">
        <div class="mb-3 flex items-start justify-between">
            <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                Total Transaksi
            </p>
            <div class="flex h-7 w-7 flex-shrink-0 items-center justify-center rounded-lg bg-indigo-50">
                <svg class="h-4 w-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2
                             M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
        </div>
        <p class="text-3xl font-extrabold text-slate-800">
            428 <span class="text-xl font-bold text-slate-500">Transaksi</span>
        </p>
        <div class="mt-2 flex items-center gap-1.5">
            <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-2 py-0.5
                         text-xs font-semibold text-emerald-600">
                <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                          d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                </svg>
                +8%
            </span>
            <span class="text-xs text-slate-400">dari bulan lalu</span>
        </div>
    </div>

    {{-- Card 3 – Rata-rata Penjualan Harian --}}
    <div class="metric-card relative overflow-hidden rounded-xl border border-slate-100
                bg-white p-5 shadow-sm">
        <div class="mb-3 flex items-start justify-between">
            <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                Rata-rata Penjualan Harian
            </p>
            <div class="flex h-7 w-7 flex-shrink-0 items-center justify-center rounded-lg bg-amber-50">
                <svg class="h-4 w-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                </svg>
            </div>
        </div>
        <p class="text-2xl font-extrabold text-slate-800">Rp&nbsp;1.884.500</p>
        <div class="mt-2 flex items-center gap-1.5">
            <span class="h-3.5 w-0.5 rounded bg-slate-300"></span>
            <span class="text-xs text-slate-400">stabil dibanding bulan lalu</span>
        </div>
    </div>

</div>

{{-- ══════════════════════════════════════════════════════════
     MID SECTION — Daily Trend Chart + Top Products
═══════════════════════════════════════════════════════════ --}}
<div class="mb-6 grid grid-cols-1 gap-6 lg:grid-cols-4">

    {{-- ── Tren Penjualan Harian (col-span-3) ── --}}
    <div class="rounded-xl border border-slate-100 bg-white p-5 shadow-sm lg:col-span-3">

        {{-- Widget Header --}}
        <div class="mb-4 flex flex-wrap items-start justify-between gap-3">
            <div>
                <h2 class="text-base font-bold text-slate-800">Tren Penjualan Harian</h2>
                <p class="mt-0.5 text-xs text-slate-400">
                    Rata-rata Rp&nbsp;1.884.500 / hari &bull; Total 31 Hari
                </p>
            </div>
            <div class="flex items-center gap-4">
                <span class="flex items-center gap-1.5 text-xs font-medium text-slate-500">
                    <span class="h-3 w-3 rounded-full bg-[#007A87]"></span> Penjualan
                </span>
                <span class="flex items-center gap-1.5 text-xs font-medium text-slate-500">
                    <span class="h-3 w-3 rounded-full bg-sky-200"></span> Target
                </span>
            </div>
        </div>

        {{-- SVG Line Chart --}}
        <div class="w-full overflow-hidden">
            <svg viewBox="0 0 700 160" class="w-full" aria-label="Grafik tren penjualan harian Oktober 2023">

                {{-- Grid lines --}}
                <line x1="0" y1="30"  x2="700" y2="30"  stroke="#f1f5f9" stroke-width="1"/>
                <line x1="0" y1="70"  x2="700" y2="70"  stroke="#f1f5f9" stroke-width="1"/>
                <line x1="0" y1="110" x2="700" y2="110" stroke="#f1f5f9" stroke-width="1"/>
                <line x1="0" y1="140" x2="700" y2="140" stroke="#e2e8f0" stroke-width="1"/>

                {{-- Y-axis labels --}}
                <text x="0" y="28"  font-size="9" fill="#94a3b8">3Jt</text>
                <text x="0" y="68"  font-size="9" fill="#94a3b8">2Jt</text>
                <text x="0" y="108" font-size="9" fill="#94a3b8">1Jt</text>
                <text x="0" y="138" font-size="9" fill="#94a3b8">0</text>

                {{-- Target dashed line --}}
                <line x1="18" y1="85" x2="700" y2="85"
                      stroke="#bae6fd" stroke-width="1.5" stroke-dasharray="5 3"/>

                {{-- Area fill (sales) --}}
                <path class="area-fade"
                      d="M18,120 L40,100 L62,90 L84,105 L106,80 L128,70 L150,85 L172,60 L194,75
                         L216,55 L238,65 L260,50 L282,70 L304,40 L326,55 L348,35 L370,45 L392,30
                         L414,50 L436,65 L458,48 L480,60 L502,75 L524,58 L546,70 L568,55 L590,65
                         L612,80 L634,60 L656,50 L678,62 L700,55
                         L700,140 L18,140 Z"
                      fill="url(#salesGradient)" opacity="0.25"/>

                {{-- Sales line --}}
                <path class="line-draw"
                      d="M18,120 L40,100 L62,90 L84,105 L106,80 L128,70 L150,85 L172,60 L194,75
                         L216,55 L238,65 L260,50 L282,70 L304,40 L326,55 L348,35 L370,45 L392,30
                         L414,50 L436,65 L458,48 L480,60 L502,75 L524,58 L546,70 L568,55 L590,65
                         L612,80 L634,60 L656,50 L678,62 L700,55"
                      fill="none" stroke="#007A87" stroke-width="2.5" stroke-linecap="round"
                      stroke-linejoin="round"/>

                {{-- Highlight dot: tanggal 19 (active) --}}
                <circle class="chart-dot-active" cx="392" cy="30" r="0"
                        fill="#007A87" stroke="white" stroke-width="2"/>

                {{-- Gradient definition --}}
                <defs>
                    <linearGradient id="salesGradient" x1="0" y1="0" x2="0" y2="1">
                        <stop offset="0%"   stop-color="#007A87" stop-opacity="0.5"/>
                        <stop offset="100%" stop-color="#007A87" stop-opacity="0"/>
                    </linearGradient>
                </defs>

                {{-- X-axis date labels --}}
                <g font-size="9" fill="#94a3b8" text-anchor="middle">
                    <text x="18"  y="155">01</text>
                    <text x="88"  y="155">04</text>
                    <text x="160" y="155">07</text>
                    <text x="232" y="155">10</text>
                    <text x="304" y="155">13</text>
                    <text x="376" y="155">16</text>
                    <text x="392" y="155" font-weight="bold" fill="#007A87">19</text>
                    <text x="448" y="155">22</text>
                    <text x="520" y="155">25</text>
                    <text x="592" y="155">28</text>
                    <text x="678" y="155">31</text>
                </g>
            </svg>
        </div>
    </div>

    {{-- ── Produk Terlaris Top 4 (col-span-1) ── --}}
    <div class="rounded-xl border border-slate-100 bg-white p-5 shadow-sm lg:col-span-1">

        {{-- Widget Header --}}
        <div class="mb-4 flex items-center justify-between">
            <h2 class="text-base font-bold text-slate-800">Produk Terlaris</h2>
            <span class="inline-flex items-center rounded-full bg-[#007A87]/10 px-2.5 py-0.5
                         text-xs font-bold text-[#007A87]">
                Top 4
            </span>
        </div>

        {{-- Ranking List --}}
        <div class="space-y-3">

            {{-- Rank 1 --}}
            <div class="flex items-center gap-3 rounded-xl bg-[#007A87]/5 px-3 py-2.5">
                <span class="flex h-7 w-7 flex-shrink-0 items-center justify-center rounded-full
                             bg-[#007A87] text-xs font-extrabold text-white shadow-sm">1</span>
                <div class="min-w-0 flex-1">
                    <p class="truncate text-sm font-bold text-slate-800">Paracetamol 500mg</p>
                    <p class="text-[11px] text-slate-400">Obat Bebas</p>
                </div>
                <div class="text-right flex-shrink-0">
                    <p class="text-sm font-extrabold text-[#007A87]">540 <span class="text-xs font-medium">pcs</span></p>
                    <p class="text-[11px] text-slate-400">Rp 5.400.000</p>
                </div>
            </div>

            {{-- Rank 2 --}}
            <div class="flex items-center gap-3 rounded-xl bg-slate-50 px-3 py-2.5">
                <span class="flex h-7 w-7 flex-shrink-0 items-center justify-center rounded-full
                             bg-slate-400 text-xs font-extrabold text-white">2</span>
                <div class="min-w-0 flex-1">
                    <p class="truncate text-sm font-bold text-slate-800">Amoxicillin 500mg</p>
                    <p class="text-[11px] text-slate-400">Resep</p>
                </div>
                <div class="text-right flex-shrink-0">
                    <p class="text-sm font-extrabold text-slate-700">385 <span class="text-xs font-medium text-slate-400">pcs</span></p>
                    <p class="text-[11px] text-slate-400">Rp 7.700.000</p>
                </div>
            </div>

            {{-- Rank 3 --}}
            <div class="flex items-center gap-3 rounded-xl bg-slate-50 px-3 py-2.5">
                <span class="flex h-7 w-7 flex-shrink-0 items-center justify-center rounded-full
                             bg-slate-400 text-xs font-extrabold text-white">3</span>
                <div class="min-w-0 flex-1">
                    <p class="truncate text-sm font-bold text-slate-800">Vitamin C 1000mg</p>
                    <p class="text-[11px] text-slate-400">Suplemen</p>
                </div>
                <div class="text-right flex-shrink-0">
                    <p class="text-sm font-extrabold text-slate-700">310 <span class="text-xs font-medium text-slate-400">pcs</span></p>
                    <p class="text-[11px] text-slate-400">Rp 4.650.000</p>
                </div>
            </div>

            {{-- Rank 4 --}}
            <div class="flex items-center gap-3 rounded-xl bg-slate-50 px-3 py-2.5">
                <span class="flex h-7 w-7 flex-shrink-0 items-center justify-center rounded-full
                             bg-slate-300 text-xs font-extrabold text-slate-600">4</span>
                <div class="min-w-0 flex-1">
                    <p class="truncate text-sm font-bold text-slate-800">Hand Sanitizer 50ml</p>
                    <p class="text-[11px] text-slate-400">Alkes</p>
                </div>
                <div class="text-right flex-shrink-0">
                    <p class="text-sm font-extrabold text-slate-700">240 <span class="text-xs font-medium text-slate-400">pcs</span></p>
                    <p class="text-[11px] text-slate-400">Rp 3.600.000</p>
                </div>
            </div>

        </div>

        {{-- Footer --}}
        <div class="mt-4 border-t border-slate-100 pt-3">
            <a href="#"
               class="inline-flex items-center gap-1 text-xs font-semibold text-[#007A87]
                      transition hover:text-[#005f6b] hover:underline">
                Lihat semua produk
                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>
    </div>

</div>

{{-- ══════════════════════════════════════════════════════════
     TABLE 1 — Ringkasan Penjualan Produk Teratas
═══════════════════════════════════════════════════════════ --}}
<div class="mb-6 rounded-xl border border-slate-100 bg-white shadow-sm">

    {{-- Section Header --}}
    <div class="flex flex-wrap items-start justify-between gap-3 border-b border-slate-100 px-5 py-4">
        <div>
            <h2 class="text-base font-bold text-slate-800">Ringkasan Penjualan Produk Teratas</h2>
            <p class="mt-0.5 text-xs text-slate-400">
                Daftar obat dan produk dengan kontribusi penjualan tertinggi periode ini.
            </p>
        </div>
        <p class="flex-shrink-0 text-xs text-slate-400 sm:text-right">
            Menampilkan <span class="font-semibold text-slate-600">6 produk terbaik</span>
        </p>
    </div>

    {{-- Scrollable Table --}}
    <div class="overflow-x-auto w-full">
        <table class="w-full min-w-[640px] text-sm">
            <thead>
                <tr class="border-b border-slate-100 bg-slate-50/70">
                    <th class="w-10 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">No</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">Nama Obat</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">Kategori</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wider text-slate-400">Jumlah Terjual</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wider text-slate-400">Total Pendapatan</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wider text-slate-400">Status Stok</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">

                {{-- Row 1 --}}
                <tr class="tr-hover fade-row fd-1 transition-colors duration-150">
                    <td class="px-5 py-3.5 text-sm font-medium text-slate-400">1</td>
                    <td class="px-4 py-3.5">
                        <div class="flex items-center gap-2.5">
                            <div class="flex h-7 w-7 flex-shrink-0 items-center justify-center rounded-lg bg-sky-50">
                                <svg class="h-3.5 w-3.5 text-sky-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <span class="font-semibold text-slate-800">Paracetamol 500mg</span>
                        </div>
                    </td>
                    <td class="px-4 py-3.5">
                        <span class="inline-block rounded-md bg-sky-50 px-2 py-0.5 text-xs font-medium text-sky-700">Obat Bebas</span>
                    </td>
                    <td class="px-4 py-3.5 text-center">
                        <span class="font-bold text-[#007A87]">540 pcs</span>
                    </td>
                    <td class="px-4 py-3.5 text-right font-bold text-slate-800">Rp 5.400.000</td>
                    <td class="px-4 py-3.5 text-center">
                        <span class="inline-flex items-center gap-1 rounded-full bg-[#007A87]/10 px-2.5 py-1
                                     text-xs font-semibold text-[#007A87]">
                            Tersedia (180)
                        </span>
                    </td>
                </tr>

                {{-- Row 2 --}}
                <tr class="tr-hover fade-row fd-2 transition-colors duration-150">
                    <td class="px-5 py-3.5 text-sm font-medium text-slate-400">2</td>
                    <td class="px-4 py-3.5">
                        <div class="flex items-center gap-2.5">
                            <div class="flex h-7 w-7 flex-shrink-0 items-center justify-center rounded-lg bg-rose-50">
                                <svg class="h-3.5 w-3.5 text-rose-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <span class="font-semibold text-slate-800">Amoxicillin 500mg</span>
                        </div>
                    </td>
                    <td class="px-4 py-3.5">
                        <span class="inline-block rounded-md bg-rose-50 px-2 py-0.5 text-xs font-medium text-rose-700">Resep</span>
                    </td>
                    <td class="px-4 py-3.5 text-center">
                        <span class="font-bold text-[#007A87]">385 pcs</span>
                    </td>
                    <td class="px-4 py-3.5 text-right font-bold text-slate-800">Rp 7.700.000</td>
                    <td class="px-4 py-3.5 text-center">
                        <span class="inline-flex items-center gap-1 rounded-full bg-[#007A87]/10 px-2.5 py-1
                                     text-xs font-semibold text-[#007A87]">
                            Tersedia (95)
                        </span>
                    </td>
                </tr>

                {{-- Row 3 --}}
                <tr class="tr-hover fade-row fd-3 transition-colors duration-150">
                    <td class="px-5 py-3.5 text-sm font-medium text-slate-400">3</td>
                    <td class="px-4 py-3.5">
                        <div class="flex items-center gap-2.5">
                            <div class="flex h-7 w-7 flex-shrink-0 items-center justify-center rounded-lg bg-violet-50">
                                <svg class="h-3.5 w-3.5 text-violet-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <span class="font-semibold text-slate-800">Vitamin C 1000mg</span>
                        </div>
                    </td>
                    <td class="px-4 py-3.5">
                        <span class="inline-block rounded-md bg-violet-50 px-2 py-0.5 text-xs font-medium text-violet-700">Suplemen</span>
                    </td>
                    <td class="px-4 py-3.5 text-center">
                        <span class="font-bold text-[#007A87]">310 pcs</span>
                    </td>
                    <td class="px-4 py-3.5 text-right font-bold text-slate-800">Rp 4.650.000</td>
                    <td class="px-4 py-3.5 text-center">
                        <span class="inline-flex items-center gap-1 rounded-full bg-[#007A87]/10 px-2.5 py-1
                                     text-xs font-semibold text-[#007A87]">
                            Tersedia (120)
                        </span>
                    </td>
                </tr>

                {{-- Row 4 --}}
                <tr class="tr-hover fade-row fd-4 transition-colors duration-150">
                    <td class="px-5 py-3.5 text-sm font-medium text-slate-400">4</td>
                    <td class="px-4 py-3.5">
                        <div class="flex items-center gap-2.5">
                            <div class="flex h-7 w-7 flex-shrink-0 items-center justify-center rounded-lg bg-amber-50">
                                <svg class="h-3.5 w-3.5 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <span class="font-semibold text-slate-800">Hand Sanitizer 50ml</span>
                        </div>
                    </td>
                    <td class="px-4 py-3.5">
                        <span class="inline-block rounded-md bg-amber-50 px-2 py-0.5 text-xs font-medium text-amber-700">Alkes</span>
                    </td>
                    <td class="px-4 py-3.5 text-center">
                        <span class="font-bold text-[#007A87]">240 pcs</span>
                    </td>
                    <td class="px-4 py-3.5 text-right font-bold text-slate-800">Rp 3.600.000</td>
                    <td class="px-4 py-3.5 text-center">
                        <span class="inline-flex items-center gap-1 rounded-full bg-orange-100 px-2.5 py-1
                                     text-xs font-semibold text-orange-700">
                            Menipis (15)
                        </span>
                    </td>
                </tr>

                {{-- Row 5 --}}
                <tr class="tr-hover fade-row fd-5 transition-colors duration-150">
                    <td class="px-5 py-3.5 text-sm font-medium text-slate-400">5</td>
                    <td class="px-4 py-3.5">
                        <div class="flex items-center gap-2.5">
                            <div class="flex h-7 w-7 flex-shrink-0 items-center justify-center rounded-lg bg-sky-50">
                                <svg class="h-3.5 w-3.5 text-sky-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <span class="font-semibold text-slate-800">Cetirizine 10mg</span>
                        </div>
                    </td>
                    <td class="px-4 py-3.5">
                        <span class="inline-block rounded-md bg-teal-50 px-2 py-0.5 text-xs font-medium text-teal-700">Obat Bebas Terbatas</span>
                    </td>
                    <td class="px-4 py-3.5 text-center">
                        <span class="font-bold text-[#007A87]">210 pcs</span>
                    </td>
                    <td class="px-4 py-3.5 text-right font-bold text-slate-800">Rp 2.100.000</td>
                    <td class="px-4 py-3.5 text-center">
                        <span class="inline-flex items-center gap-1 rounded-full bg-[#007A87]/10 px-2.5 py-1
                                     text-xs font-semibold text-[#007A87]">
                            Tersedia (80)
                        </span>
                    </td>
                </tr>

                {{-- Row 6 --}}
                <tr class="tr-hover fade-row fd-6 transition-colors duration-150">
                    <td class="px-5 py-3.5 text-sm font-medium text-slate-400">6</td>
                    <td class="px-4 py-3.5">
                        <div class="flex items-center gap-2.5">
                            <div class="flex h-7 w-7 flex-shrink-0 items-center justify-center rounded-lg bg-rose-50">
                                <svg class="h-3.5 w-3.5 text-rose-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <span class="font-semibold text-slate-800">Omeprazole 20mg</span>
                        </div>
                    </td>
                    <td class="px-4 py-3.5">
                        <span class="inline-block rounded-md bg-rose-50 px-2 py-0.5 text-xs font-medium text-rose-700">Resep</span>
                    </td>
                    <td class="px-4 py-3.5 text-center">
                        <span class="font-bold text-[#007A87]">175 pcs</span>
                    </td>
                    <td class="px-4 py-3.5 text-right font-bold text-slate-800">Rp 3.500.000</td>
                    <td class="px-4 py-3.5 text-center">
                        <span class="inline-flex items-center gap-1 rounded-full bg-[#007A87]/10 px-2.5 py-1
                                     text-xs font-semibold text-[#007A87]">
                            Tersedia (65)
                        </span>
                    </td>
                </tr>

            </tbody>
        </table>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════
     TABLE 2 — Rincian Transaksi Penjualan Terkini
═══════════════════════════════════════════════════════════ --}}
<div class="rounded-xl border border-slate-100 bg-white shadow-sm">

    {{-- Section Header --}}
    <div class="flex flex-wrap items-start justify-between gap-3 border-b border-slate-100 px-5 py-4">
        <div>
            <h2 class="text-base font-bold text-slate-800">Rincian Transaksi Penjualan Terkini</h2>
            <p class="mt-0.5 text-xs text-slate-400">
                Log transaksi terkini termasuk rincian pelanggan dan metode pembayaran.
            </p>
        </div>
        <p class="flex-shrink-0 text-xs text-slate-400 sm:text-right">
            Menampilkan 5 dari <span class="font-semibold text-slate-600">428 transaksi</span>
        </p>
    </div>

    {{-- Scrollable Table --}}
    <div class="overflow-x-auto w-full">
        <table class="w-full min-w-[700px] text-sm">
            <thead>
                <tr class="border-b border-slate-100 bg-slate-50/70">
                    <th class="whitespace-nowrap px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">
                        No. Struk
                    </th>
                    <th class="whitespace-nowrap px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">
                        Waktu &amp; Tanggal
                    </th>
                    <th class="whitespace-nowrap px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">
                        Nama Pelanggan
                    </th>
                    <th class="whitespace-nowrap px-4 py-3 text-right text-xs font-semibold uppercase tracking-wider text-slate-400">
                        Total
                    </th>
                    <th class="whitespace-nowrap px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">
                        Metode Pembayaran
                    </th>
                    <th class="whitespace-nowrap px-4 py-3 text-center text-xs font-semibold uppercase tracking-wider text-slate-400">
                        Status
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">

                {{-- Transaksi 1 --}}
                <tr class="tr-hover fade-row fd-1 transition-colors duration-150">
                    <td class="px-5 py-3.5">
                        <span class="font-semibold text-[#007A87] hover:underline cursor-pointer">#TRX-8905</span>
                    </td>
                    <td class="whitespace-nowrap px-4 py-3.5 text-slate-600">
                        24 Okt 2023, 14:30
                    </td>
                    <td class="px-4 py-3.5 font-medium text-slate-700">Budi Santoso</td>
                    <td class="whitespace-nowrap px-4 py-3.5 text-right font-bold text-slate-800">
                        Rp 145.000
                    </td>
                    <td class="px-4 py-3.5">
                        <div class="flex items-center gap-1.5">
                            <span class="flex h-5 w-5 flex-shrink-0 items-center justify-center rounded bg-slate-100">
                                <svg class="h-3 w-3 text-slate-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"/>
                                </svg>
                            </span>
                            <span class="text-sm text-slate-600">QRIS</span>
                        </div>
                    </td>
                    <td class="px-4 py-3.5 text-center">
                        <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-2.5 py-1
                                     text-xs font-semibold text-emerald-700">
                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                            Selesai
                        </span>
                    </td>
                </tr>

                {{-- Transaksi 2 --}}
                <tr class="tr-hover fade-row fd-2 transition-colors duration-150">
                    <td class="px-5 py-3.5">
                        <span class="font-semibold text-[#007A87] hover:underline cursor-pointer">#TRX-8904</span>
                    </td>
                    <td class="whitespace-nowrap px-4 py-3.5 text-slate-600">
                        24 Okt 2023, 14:15
                    </td>
                    <td class="px-4 py-3.5 font-medium text-slate-700">Siti Aminah</td>
                    <td class="whitespace-nowrap px-4 py-3.5 text-right font-bold text-slate-800">
                        Rp 280.000
                    </td>
                    <td class="px-4 py-3.5">
                        <div class="flex items-center gap-1.5">
                            <span class="flex h-5 w-5 flex-shrink-0 items-center justify-center rounded bg-blue-50">
                                <svg class="h-3 w-3 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                </svg>
                            </span>
                            <span class="text-sm text-slate-600">Debit BCA</span>
                        </div>
                    </td>
                    <td class="px-4 py-3.5 text-center">
                        <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-2.5 py-1
                                     text-xs font-semibold text-emerald-700">
                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                            Selesai
                        </span>
                    </td>
                </tr>

                {{-- Transaksi 3 --}}
                <tr class="tr-hover fade-row fd-3 transition-colors duration-150">
                    <td class="px-5 py-3.5">
                        <span class="font-semibold text-[#007A87] hover:underline cursor-pointer">#TRX-8903</span>
                    </td>
                    <td class="whitespace-nowrap px-4 py-3.5 text-slate-600">
                        24 Okt 2023, 13:50
                    </td>
                    <td class="px-4 py-3.5 font-medium text-slate-500 italic">Umum / Non-Member</td>
                    <td class="whitespace-nowrap px-4 py-3.5 text-right font-bold text-slate-800">
                        Rp 65.000
                    </td>
                    <td class="px-4 py-3.5">
                        <div class="flex items-center gap-1.5">
                            <span class="flex h-5 w-5 flex-shrink-0 items-center justify-center rounded bg-emerald-50">
                                <svg class="h-3 w-3 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                            </span>
                            <span class="text-sm text-slate-600">Tunai</span>
                        </div>
                    </td>
                    <td class="px-4 py-3.5 text-center">
                        <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-2.5 py-1
                                     text-xs font-semibold text-emerald-700">
                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                            Selesai
                        </span>
                    </td>
                </tr>

                {{-- Transaksi 4 --}}
                <tr class="tr-hover fade-row fd-4 transition-colors duration-150">
                    <td class="px-5 py-3.5">
                        <span class="font-semibold text-[#007A87] hover:underline cursor-pointer">#TRX-8902</span>
                    </td>
                    <td class="whitespace-nowrap px-4 py-3.5 text-slate-600">
                        24 Okt 2023, 13:22
                    </td>
                    <td class="px-4 py-3.5 font-medium text-slate-700">Ahmad Fauzi</td>
                    <td class="whitespace-nowrap px-4 py-3.5 text-right font-bold text-slate-800">
                        Rp 320.000
                    </td>
                    <td class="px-4 py-3.5">
                        <div class="flex items-center gap-1.5">
                            <span class="flex h-5 w-5 flex-shrink-0 items-center justify-center rounded bg-blue-50">
                                <svg class="h-3 w-3 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                </svg>
                            </span>
                            <span class="text-sm text-slate-600">Debit Mandiri</span>
                        </div>
                    </td>
                    <td class="px-4 py-3.5 text-center">
                        <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-2.5 py-1
                                     text-xs font-semibold text-emerald-700">
                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                            Selesai
                        </span>
                    </td>
                </tr>

                {{-- Transaksi 5 --}}
                <tr class="tr-hover fade-row fd-5 transition-colors duration-150">
                    <td class="px-5 py-3.5">
                        <span class="font-semibold text-[#007A87] hover:underline cursor-pointer">#TRX-8901</span>
                    </td>
                    <td class="whitespace-nowrap px-4 py-3.5 text-slate-600">
                        24 Okt 2023, 12:45
                    </td>
                    <td class="px-4 py-3.5 font-medium text-slate-700">Dewi Lestari</td>
                    <td class="whitespace-nowrap px-4 py-3.5 text-right font-bold text-slate-800">
                        Rp 88.500
                    </td>
                    <td class="px-4 py-3.5">
                        <div class="flex items-center gap-1.5">
                            <span class="flex h-5 w-5 flex-shrink-0 items-center justify-center rounded bg-slate-100">
                                <svg class="h-3 w-3 text-slate-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"/>
                                </svg>
                            </span>
                            <span class="text-sm text-slate-600">QRIS</span>
                        </div>
                    </td>
                    <td class="px-4 py-3.5 text-center">
                        <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-2.5 py-1
                                     text-xs font-semibold text-emerald-700">
                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                            Selesai
                        </span>
                    </td>
                </tr>

            </tbody>
        </table>
    </div>

    {{-- ── Pagination Footer ── --}}
    <div class="flex flex-col items-start justify-between gap-3 border-t border-slate-100
                px-5 py-3.5 sm:flex-row sm:items-center">

        {{-- Info --}}
        <p class="text-xs text-slate-500">
            Halaman <span class="font-semibold text-slate-700">1</span> dari
            <span class="font-semibold text-slate-700">86</span>
        </p>

        {{-- Page Buttons --}}
        <nav class="flex items-center gap-1" aria-label="Paginasi transaksi penjualan">

            {{-- Sebelumnya --}}
            <button disabled
                    class="inline-flex h-8 items-center justify-center gap-1 rounded-lg border
                           border-slate-200 bg-white px-3 text-xs font-medium text-slate-400
                           cursor-not-allowed opacity-50 transition">
                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Sebelumnya
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
                86
            </button>

            {{-- Selanjutnya --}}
            <button class="inline-flex h-8 items-center justify-center gap-1 rounded-lg border
                           border-slate-200 bg-white px-3 text-xs font-medium text-slate-600 transition
                           hover:bg-slate-50 hover:border-slate-300 active:scale-95">
                Selanjutnya
                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
     * Ekspor CSV / PDF — stub untuk controller export.
     */
    document.getElementById('btn-ekspor')?.addEventListener('click', function () {
        // Export endpoint can be wired here when the report export route is available.
        alert('Ekspor CSV / PDF akan diimplementasikan via controller.');
    });

    /**
     * Quick filter "Bulan Ini" — sudah aktif secara default.
     * Bisa ditambah logika toggle untuk filter lain.
     */
    document.getElementById('btn-bulan-ini')?.addEventListener('click', function () {
        this.classList.toggle('qf-active');
    });

    /**
     * Date range input — bisa dihubungkan ke library datepicker (flatpickr, etc).
     */
    document.getElementById('filter-range')?.addEventListener('click', function () {
        // Contoh: flatpickr(this, { mode: "range", dateFormat: "d/m/Y" });
        console.log('Date range picker triggered');
    });
</script>
@endpush
