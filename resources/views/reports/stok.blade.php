@extends('layouts.app')

@section('title', 'Laporan Stok & Inventaris — PharmaPOS')

@push('styles')
<style>
    /* ── Progress Bar Animations ── */
    @keyframes growWidth {
        from { width: 0; }
    }
    .progress-animate {
        animation: growWidth 1s ease-out forwards;
        animation-delay: 0.2s;
        width: 0;
    }

    /* ── Metric Card hover lift ── */
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

    /* ── Alert pulse for kritis ── */
    @keyframes pulse-ring {
        0%   { box-shadow: 0 0 0 0 rgba(239,68,68,0.35); }
        70%  { box-shadow: 0 0 0 6px rgba(239,68,68,0); }
        100% { box-shadow: 0 0 0 0 rgba(239,68,68,0); }
    }
    .pulse-ring {
        animation: pulse-ring 2s cubic-bezier(0.455,0.03,0.515,0.955) infinite;
    }

    /* ── Badge pulse dot ── */
    @keyframes dot-pulse {
        0%, 100% { opacity: 1; }
        50%       { opacity: 0.3; }
    }
    .dot-pulse { animation: dot-pulse 1.6s ease-in-out infinite; }

    /* ── Fade-in slide rows ── */
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(6px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    .fade-in-row {
        animation: fadeInUp 0.35s ease-out forwards;
        opacity: 0;
    }
    .fade-delay-1 { animation-delay: 0.05s; }
    .fade-delay-2 { animation-delay: 0.10s; }
    .fade-delay-3 { animation-delay: 0.15s; }
    .fade-delay-4 { animation-delay: 0.20s; }
    .fade-delay-5 { animation-delay: 0.25s; }
</style>
@endpush

@section('content')

{{-- ══════════════════════════════════════════════════════════
     HEADER ROW — Title + Filters + Action Buttons
═══════════════════════════════════════════════════════════ --}}
<div class="mb-6 flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">

    {{-- Page Title --}}
    <div class="min-w-0">
        <h1 class="text-2xl font-bold leading-tight tracking-tight text-slate-800">
            Laporan Stok &amp; Inventaris
        </h1>
        <p class="mt-1 max-w-lg text-sm text-slate-500">
            Monitoring sisa persediaan obat, peringatan batas minimum, dan perkiraan kadaluarsa (expired date).
        </p>
    </div>

    {{-- Filters + Action Buttons --}}
    <div class="flex flex-col gap-2 sm:flex-row sm:flex-wrap sm:items-center">

        {{-- Dropdown: Kategori --}}
        <div class="relative">
            <select id="filter-kategori"
                    class="w-full appearance-none rounded-lg border border-slate-200 bg-white
                           py-2 pl-3 pr-8 text-sm text-slate-700 shadow-sm transition
                           focus:border-teal-500 focus:outline-none focus:ring-2
                           focus:ring-teal-500/30 hover:border-slate-300 sm:w-auto">
                <option value="">Semua Kategori</option>
                <option value="obat_keras">Obat Keras / Resep</option>
                <option value="obat_bebas">Obat Bebas &amp; Bebas Terbatas</option>
                <option value="suplemen">Suplemen &amp; Vitamin</option>
                <option value="alkes">Alat Kesehatan &amp; PKRT</option>
            </select>
            <span class="pointer-events-none absolute inset-y-0 right-2.5 flex items-center text-slate-400">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </span>
        </div>

        {{-- Dropdown: Status --}}
        <div class="relative">
            <select id="filter-status"
                    class="w-full appearance-none rounded-lg border border-slate-200 bg-white
                           py-2 pl-3 pr-8 text-sm text-slate-700 shadow-sm transition
                           focus:border-teal-500 focus:outline-none focus:ring-2
                           focus:ring-teal-500/30 hover:border-slate-300 sm:w-auto">
                <option value="">Semua Status</option>
                <option value="aman">Stok Aman</option>
                <option value="menipis">Menipis</option>
                <option value="kritis">Kritis</option>
                <option value="ed_dekat">Kritis &amp; ED Dekat</option>
            </select>
            <span class="pointer-events-none absolute inset-y-0 right-2.5 flex items-center text-slate-400">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </span>
        </div>

        {{-- Opname Stok Button --}}
        <button id="btn-opname"
                class="inline-flex items-center justify-center gap-2 rounded-lg border border-[#007A87]
                       bg-white px-4 py-2 text-sm font-semibold text-[#007A87] shadow-sm transition
                       hover:bg-[#007A87]/5 active:scale-95 focus:outline-none
                       focus:ring-2 focus:ring-[#007A87]/30">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2
                         M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2
                         m-6 9l2 2 4-4"/>
            </svg>
            Opname Stok
        </button>

        {{-- Ekspor Button --}}
        <button id="btn-ekspor"
                class="inline-flex items-center justify-center gap-2 rounded-lg bg-[#007A87] px-4 py-2
                       text-sm font-semibold text-white shadow-sm transition
                       hover:bg-[#005f6b] active:scale-95 focus:outline-none
                       focus:ring-2 focus:ring-[#007A87]/40">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
            </svg>
            Ekspor Laporan (Excel/PDF)
        </button>

    </div>
</div>

{{-- ══════════════════════════════════════════════════════════
     METRIC CARDS — 4 columns
═══════════════════════════════════════════════════════════ --}}
<div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">

    {{-- Card 1 – Total Item Terdaftar --}}
    <div class="metric-card relative overflow-hidden rounded-xl border border-slate-100 bg-white p-5 shadow-sm">
        {{-- Top row: label + icon --}}
        <div class="mb-3 flex items-start justify-between">
            <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                Total Item Terdaftar
            </p>
            <div class="flex h-7 w-7 flex-shrink-0 items-center justify-center rounded-lg bg-[#007A87]/10">
                <svg class="h-4 w-4 text-[#007A87]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2
                             m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                </svg>
            </div>
        </div>
        <p class="text-3xl font-extrabold text-[#007A87]">1.420 <span class="text-lg font-bold text-slate-500">SKU</span></p>
        <div class="mt-2 flex items-center gap-1.5">
            <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-2 py-0.5 text-xs font-semibold text-emerald-600">
                <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                </svg>
                +24 item baru
            </span>
            <span class="text-xs text-slate-400">bulan ini</span>
        </div>
    </div>

    {{-- Card 2 – Nilai Aset Stok --}}
    <div class="metric-card relative overflow-hidden rounded-xl border border-slate-100 bg-white p-5 shadow-sm">
        <div class="mb-3 flex items-start justify-between">
            <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                Nilai Aset Stok
            </p>
            <div class="flex h-7 w-7 flex-shrink-0 items-center justify-center rounded-lg bg-indigo-50">
                <svg class="h-4 w-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2
                             m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1
                             c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
        <p class="text-xs font-semibold text-slate-500">Rp</p>
        <p class="text-2xl font-extrabold leading-tight text-slate-800">184.650.000</p>
        <div class="mt-2 flex items-center gap-1.5">
            <svg class="h-3.5 w-3.5 flex-shrink-0 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293
                         l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <span class="text-xs text-slate-400">Total valuasi HPP gudang</span>
        </div>
    </div>

    {{-- Card 3 – Stok Menipis / Kritis --}}
    <div class="metric-card relative overflow-hidden rounded-xl border border-orange-100 bg-white p-5 shadow-sm">
        <div class="mb-3 flex items-start justify-between">
            <p class="text-xs font-semibold uppercase tracking-wider text-orange-400">
                Stok Menipis / Kritis
            </p>
            <div class="flex h-7 w-7 flex-shrink-0 items-center justify-center rounded-lg bg-orange-50">
                <svg class="h-4 w-4 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4
                             c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
        </div>
        <div class="flex items-end gap-3">
            <p class="text-4xl font-extrabold text-orange-500">18</p>
            <div class="mb-1">
                <span class="inline-block rounded-md bg-orange-100 px-2 py-0.5 text-xs font-bold text-orange-700">
                    Perlu Restok
                </span>
            </div>
        </div>
        <p class="mt-1 flex items-center gap-1 text-xs text-orange-400">
            <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" clip-rule="evenodd"
                      d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0
                         012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"/>
            </svg>
            Di bawah batas minimum
        </p>
    </div>

    {{-- Card 4 – Mendekati Kadaluarsa --}}
    <div class="metric-card relative overflow-hidden rounded-xl border border-rose-100 bg-white p-5 shadow-sm">
        <div class="mb-3 flex items-start justify-between">
            <p class="text-xs font-semibold uppercase tracking-wider text-rose-400">
                Mendekati Kadaluarsa
            </p>
            <div class="pulse-ring flex h-7 w-7 flex-shrink-0 items-center justify-center rounded-lg bg-rose-50">
                <svg class="h-4 w-4 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
        </div>
        <div class="flex items-end gap-3">
            <p class="text-4xl font-extrabold text-rose-600">7</p>
            <div class="mb-1">
                <span class="inline-block rounded-md bg-rose-100 px-2 py-0.5 text-xs font-bold text-rose-700">
                    &lt; 3 Bulan
                </span>
            </div>
        </div>
        <p class="mt-1 flex items-center gap-1 text-xs text-rose-400">
            <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 9v2m0 4h.01"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86
                         a2 2 0 00-3.42 0z"/>
            </svg>
            Segera lakukan retur / promo
        </p>
    </div>

</div>

{{-- ══════════════════════════════════════════════════════════
     MID SECTION — Category Distribution + Critical Alerts
═══════════════════════════════════════════════════════════ --}}
<div class="mb-6 grid grid-cols-1 gap-6 lg:grid-cols-3">

    {{-- ── Distribusi Kategori Stok & Valuasi (col-span-2) ── --}}
    <div class="rounded-xl border border-slate-100 bg-white p-5 shadow-sm lg:col-span-2">

        {{-- Widget Header --}}
        <div class="mb-1 flex flex-wrap items-start justify-between gap-3">
            <div>
                <h2 class="text-base font-bold text-slate-800">Distribusi Kategori Stok &amp; Valuasi</h2>
                <p class="mt-0.5 text-xs text-slate-400">
                    Proporsi 1.420 SKU berdasarkan klasifikasi obat &amp; persediaan
                </p>
            </div>
            {{-- Legend --}}
            <div class="flex items-center gap-4">
                <span class="flex items-center gap-1.5 text-xs font-medium text-slate-500">
                    <span class="h-2.5 w-2.5 rounded-full bg-[#007A87]"></span> Tersedia Aman
                </span>
                <span class="flex items-center gap-1.5 text-xs font-medium text-slate-500">
                    <span class="h-2.5 w-2.5 rounded-full bg-orange-400"></span> Kritis
                </span>
            </div>
        </div>

        {{-- Category Rows --}}
        <div class="mt-5 space-y-5">

            {{-- Row 1: Obat Keras / Resep (520 SKU) --}}
            <div>
                <div class="mb-1 flex flex-wrap items-center justify-between gap-1">
                    <div>
                        <span class="text-sm font-semibold text-slate-700">Obat Keras / Resep</span>
                        <span class="ml-2 text-xs text-slate-400">(520 SKU)</span>
                    </div>
                    <span class="text-sm font-bold text-slate-700">Rp 76.500.000
                        <span class="font-normal text-slate-400">(37%)</span>
                    </span>
                </div>
                {{-- Sub-labels --}}
                <div class="mb-1.5 flex justify-between text-[11px] text-slate-400">
                    <span>448 SKU Aman</span>
                    <span class="font-semibold text-orange-500">72 SKU Perlu Perhatian</span>
                </div>
                {{-- Stacked Progress Bar --}}
                <div class="flex h-2.5 w-full overflow-hidden rounded-full bg-slate-100">
                    <div class="progress-animate h-full rounded-l-full bg-[#007A87]" style="width: 86.1%"></div>
                    <div class="progress-animate h-full rounded-r-full bg-orange-400" style="width: 13.9%; animation-delay:0.4s"></div>
                </div>
            </div>

            {{-- Row 2: Obat Bebas & Bebas Terbatas (450 SKU) --}}
            <div>
                <div class="mb-1 flex flex-wrap items-center justify-between gap-1">
                    <div>
                        <span class="text-sm font-semibold text-slate-700">Obat Bebas &amp; Bebas Terbatas</span>
                        <span class="ml-2 text-xs text-slate-400">(450 SKU)</span>
                    </div>
                    <span class="text-sm font-bold text-slate-700">Rp 49.200.000
                        <span class="font-normal text-slate-400">(32%)</span>
                    </span>
                </div>
                <div class="mb-1.5 flex justify-between text-[11px] text-slate-400">
                    <span>414 SKU Aman</span>
                    <span class="font-semibold text-orange-500">36 SKU Perlu Perhatian</span>
                </div>
                <div class="flex h-2.5 w-full overflow-hidden rounded-full bg-slate-100">
                    <div class="progress-animate h-full rounded-l-full bg-[#4db6c2]" style="width: 92%"></div>
                    <div class="progress-animate h-full rounded-r-full bg-orange-400" style="width: 8%; animation-delay:0.45s"></div>
                </div>
            </div>

            {{-- Row 3: Suplemen & Vitamin (280 SKU) --}}
            <div>
                <div class="mb-1 flex flex-wrap items-center justify-between gap-1">
                    <div>
                        <span class="text-sm font-semibold text-slate-700">Suplemen &amp; Vitamin</span>
                        <span class="ml-2 text-xs text-slate-400">(280 SKU)</span>
                    </div>
                    <span class="text-sm font-bold text-slate-700">Rp 36.450.000
                        <span class="font-normal text-slate-400">(20%)</span>
                    </span>
                </div>
                <div class="mb-1.5 flex justify-between text-[11px] text-slate-400">
                    <span>266 SKU Aman</span>
                    <span class="font-semibold text-orange-500">14 SKU Perlu Perhatian</span>
                </div>
                <div class="flex h-2.5 w-full overflow-hidden rounded-full bg-slate-100">
                    <div class="progress-animate h-full rounded-l-full bg-emerald-400" style="width: 95%"></div>
                    <div class="progress-animate h-full rounded-r-full bg-orange-300" style="width: 5%; animation-delay:0.5s"></div>
                </div>
            </div>

            {{-- Row 4: Alat Kesehatan & PKRT (170 SKU) --}}
            <div>
                <div class="mb-1 flex flex-wrap items-center justify-between gap-1">
                    <div>
                        <span class="text-sm font-semibold text-slate-700">Alat Kesehatan &amp; PKRT</span>
                        <span class="ml-2 text-xs text-slate-400">(170 SKU)</span>
                    </div>
                    <span class="text-sm font-bold text-slate-700">Rp 20.500.000
                        <span class="font-normal text-slate-400">(11%)</span>
                    </span>
                </div>
                <div class="mb-1.5 flex justify-between text-[11px] text-slate-400">
                    <span>153 SKU Aman</span>
                    <span class="font-semibold text-orange-500">17 SKU Perlu Perhatian</span>
                </div>
                <div class="flex h-2.5 w-full overflow-hidden rounded-full bg-slate-100">
                    <div class="progress-animate h-full rounded-l-full bg-slate-400" style="width: 90%"></div>
                    <div class="progress-animate h-full rounded-r-full bg-orange-500" style="width: 10%; animation-delay:0.55s"></div>
                </div>
            </div>

        </div>
    </div>

    {{-- ── Peringatan Stok Kritis (col-span-1) ── --}}
    <div class="rounded-xl border border-slate-100 bg-white p-5 shadow-sm lg:col-span-1">

        {{-- Widget Header --}}
        <div class="mb-4 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-rose-50">
                    <svg class="h-4 w-4 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659
                                 V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595
                                 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                </div>
                <h2 class="text-base font-bold text-slate-800">Peringatan Stok Kritis</h2>
            </div>
            <span class="inline-flex items-center rounded-full bg-rose-100 px-2.5 py-0.5
                         text-xs font-bold text-rose-700">
                Urgent Restock
            </span>
        </div>

        {{-- Critical Items List --}}
        <div class="space-y-3">

            {{-- Item 1: Amoxicillin 500mg --}}
            <div class="flex items-center justify-between rounded-xl border border-rose-100
                        bg-rose-50/60 px-3.5 py-3 gap-2">
                <div class="min-w-0">
                    <p class="text-sm font-bold text-slate-800 leading-tight">Amoxicillin 500mg</p>
                    <p class="mt-0.5 text-[11px] text-rose-500 font-medium">
                        Sisa <span class="font-extrabold">8 Strip</span>
                        &bull; Min: 50 Strip
                    </p>
                </div>
                <button class="inline-flex flex-shrink-0 items-center gap-1 rounded-lg bg-[#007A87]
                               px-2.5 py-1.5 text-xs font-bold text-white transition
                               hover:bg-[#005f6b] active:scale-95">
                    <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293
                                 c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    Pesan
                </button>
            </div>

            {{-- Item 2: Paracetamol Sirup 60ml --}}
            <div class="flex items-center justify-between rounded-xl border border-orange-100
                        bg-orange-50/60 px-3.5 py-3 gap-2">
                <div class="min-w-0">
                    <p class="text-sm font-bold text-slate-800 leading-tight">Paracetamol Sirup 60ml</p>
                    <p class="mt-0.5 text-[11px] text-orange-500 font-medium">
                        Sisa <span class="font-extrabold">3 Botol</span>
                        &bull; Min: 20 Botol
                    </p>
                </div>
                <button class="inline-flex flex-shrink-0 items-center gap-1 rounded-lg bg-[#007A87]
                               px-2.5 py-1.5 text-xs font-bold text-white transition
                               hover:bg-[#005f6b] active:scale-95">
                    <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293
                                 c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    Pesan
                </button>
            </div>

            {{-- Item 3: Cefadroxil 500mg --}}
            <div class="flex items-center justify-between rounded-xl border border-rose-100
                        bg-rose-50/60 px-3.5 py-3 gap-2">
                <div class="min-w-0">
                    <p class="text-sm font-bold text-slate-800 leading-tight">Cefadroxil 500mg</p>
                    <p class="mt-0.5 text-[11px] text-rose-500 font-medium">
                        Sisa <span class="font-extrabold">5 Strip</span>
                        &bull; Min: 30 Strip
                    </p>
                </div>
                <button class="inline-flex flex-shrink-0 items-center gap-1 rounded-lg bg-[#007A87]
                               px-2.5 py-1.5 text-xs font-bold text-white transition
                               hover:bg-[#005f6b] active:scale-95">
                    <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293
                                 c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    Pesan
                </button>
            </div>

            {{-- Item 4: Betadine Antiseptic 60ml --}}
            <div class="flex items-center justify-between rounded-xl border border-orange-100
                        bg-orange-50/60 px-3.5 py-3 gap-2">
                <div class="min-w-0">
                    <p class="text-sm font-bold text-slate-800 leading-tight">Betadine Antiseptic 60ml</p>
                    <p class="mt-0.5 text-[11px] text-orange-500 font-medium">
                        Sisa <span class="font-extrabold">4 Botol</span>
                        &bull; Min: 25 Botol
                    </p>
                </div>
                <button class="inline-flex flex-shrink-0 items-center gap-1 rounded-lg bg-[#007A87]
                               px-2.5 py-1.5 text-xs font-bold text-white transition
                               hover:bg-[#005f6b] active:scale-95">
                    <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293
                                 c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    Pesan
                </button>
            </div>

        </div>

        {{-- Footer --}}
        <div class="mt-4 border-t border-slate-100 pt-3">
            <a href="#"
               class="inline-flex items-center gap-1 text-xs font-semibold text-[#007A87]
                      transition hover:text-[#005f6b] hover:underline">
                Lihat semua 18 item kritis
                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>

    </div>
</div>

{{-- ══════════════════════════════════════════════════════════
     INVENTORY TABLE SECTION
═══════════════════════════════════════════════════════════ --}}
<div class="rounded-xl border border-slate-100 bg-white shadow-sm">

    {{-- Table Header --}}
    <div class="border-b border-slate-100 px-5 py-4">
        <div class="flex flex-wrap items-start justify-between gap-3">
            <div>
                <h2 class="text-base font-bold text-slate-800">Daftar Inventaris Obat &amp; Status Stok</h2>
                <p class="mt-0.5 text-xs text-slate-400">
                    Ringkasan persediaan terkini, batas aman minimum, tanggal kadaluarsa, dan nilai valuasi aset apotek.
                </p>
            </div>
            <p class="flex-shrink-0 text-xs text-slate-400 sm:text-right">
                Menampilkan 1–5 dari
                <span class="font-semibold text-slate-600">1.420 item</span>
            </p>
        </div>
    </div>

    {{-- Scrollable Table --}}
    <div class="overflow-x-auto w-full">
        <table class="w-full min-w-[900px] text-sm">
            <thead>
                <tr class="border-b border-slate-100 bg-slate-50/70">
                    <th class="whitespace-nowrap px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">
                        Kode SKU
                    </th>
                    <th class="whitespace-nowrap px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">
                        Nama Obat
                    </th>
                    <th class="whitespace-nowrap px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">
                        Kategori
                    </th>
                    <th class="whitespace-nowrap px-4 py-3 text-center text-xs font-semibold uppercase tracking-wider text-slate-400">
                        Satuan
                    </th>
                    <th class="whitespace-nowrap px-4 py-3 text-center text-xs font-semibold uppercase tracking-wider text-slate-400">
                        Stok Saat Ini
                    </th>
                    <th class="whitespace-nowrap px-4 py-3 text-center text-xs font-semibold uppercase tracking-wider text-slate-400">
                        Stok Min.
                    </th>
                    <th class="whitespace-nowrap px-4 py-3 text-center text-xs font-semibold uppercase tracking-wider text-slate-400">
                        Kadaluarsa (ED)
                    </th>
                    <th class="whitespace-nowrap px-4 py-3 text-right text-xs font-semibold uppercase tracking-wider text-slate-400">
                        Valuasi Aset
                    </th>
                    <th class="whitespace-nowrap px-4 py-3 text-center text-xs font-semibold uppercase tracking-wider text-slate-400">
                        Status Stok
                    </th>
                    <th class="whitespace-nowrap px-4 py-3 text-center text-xs font-semibold uppercase tracking-wider text-slate-400">
                        Aksi
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">

                {{-- ── Row 1: Paracetamol 500mg — Stok Aman ── --}}
                <tr class="tr-hover fade-in-row fade-delay-1 transition-colors duration-150">
                    <td class="px-4 py-3.5">
                        <span class="font-semibold text-[#007A87] hover:underline cursor-pointer">SKU-MED-001</span>
                    </td>
                    <td class="px-4 py-3.5">
                        <div class="flex items-center gap-2">
                            <div class="flex h-7 w-7 flex-shrink-0 items-center justify-center
                                        rounded-lg bg-emerald-50 text-emerald-600">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="font-semibold text-slate-800">Paracetamol 500mg</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-3.5">
                        <span class="inline-block rounded-md bg-sky-50 px-2 py-0.5 text-xs font-medium text-sky-700">
                            Obat Bebas
                        </span>
                    </td>
                    <td class="px-4 py-3.5 text-center text-slate-500">Strip (Box)</td>
                    <td class="px-4 py-3.5 text-center">
                        <span class="font-extrabold text-emerald-600">180</span>
                        <span class="ml-0.5 text-xs text-slate-400">Strip</span>
                    </td>
                    <td class="px-4 py-3.5 text-center text-slate-500">50</td>
                    <td class="px-4 py-3.5 text-center text-slate-500 text-xs">14 Nov 2026</td>
                    <td class="px-4 py-3.5 text-right">
                        <p class="text-xs text-slate-400">Rp</p>
                        <p class="font-bold text-slate-800">900.000</p>
                    </td>
                    <td class="px-4 py-3.5 text-center">
                        <span class="inline-flex items-center gap-1 rounded-full bg-[#007A87]/10
                                     px-2.5 py-1 text-xs font-semibold text-[#007A87]">
                            <span class="h-1.5 w-1.5 rounded-full bg-[#007A87]"></span>
                            Stok Aman
                        </span>
                    </td>
                    <td class="px-4 py-3.5 text-center">
                        <button class="text-xs font-semibold text-[#007A87] hover:underline transition">
                            Detail
                        </button>
                    </td>
                </tr>

                {{-- ── Row 2: Amoxicillin 500mg — Menipis ── --}}
                <tr class="tr-hover fade-in-row fade-delay-2 transition-colors duration-150">
                    <td class="px-4 py-3.5">
                        <span class="font-semibold text-[#007A87] hover:underline cursor-pointer">SKU-MED-042</span>
                    </td>
                    <td class="px-4 py-3.5">
                        <div class="flex items-center gap-2">
                            <div class="flex h-7 w-7 flex-shrink-0 items-center justify-center
                                        rounded-lg bg-orange-50 text-orange-500">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3
                                             L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="font-semibold text-slate-800">Amoxicillin 500mg</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-3.5">
                        <span class="inline-block rounded-md bg-rose-50 px-2 py-0.5 text-xs font-medium text-rose-700">
                            Obat Keras (Resep)
                        </span>
                    </td>
                    <td class="px-4 py-3.5 text-center text-slate-500">Strip</td>
                    <td class="px-4 py-3.5 text-center">
                        <span class="font-extrabold text-orange-500">8</span>
                        <span class="ml-0.5 text-xs text-orange-400">Strip</span>
                    </td>
                    <td class="px-4 py-3.5 text-center text-slate-500">50</td>
                    <td class="px-4 py-3.5 text-center text-slate-500 text-xs">20 Agu 2025</td>
                    <td class="px-4 py-3.5 text-right">
                        <p class="text-xs text-slate-400">Rp</p>
                        <p class="font-bold text-slate-800">240.000</p>
                    </td>
                    <td class="px-4 py-3.5 text-center">
                        <span class="inline-flex items-center gap-1 rounded-full bg-orange-100
                                     px-2.5 py-1 text-xs font-semibold text-orange-700">
                            <span class="dot-pulse h-1.5 w-1.5 rounded-full bg-orange-500"></span>
                            Menipis
                        </span>
                    </td>
                    <td class="px-4 py-3.5 text-center">
                        <button class="text-xs font-semibold text-[#007A87] hover:underline transition">
                            Detail
                        </button>
                    </td>
                </tr>

                {{-- ── Row 3: Vitamin C 1000mg — Stok Aman ── --}}
                <tr class="tr-hover fade-in-row fade-delay-3 transition-colors duration-150">
                    <td class="px-4 py-3.5">
                        <span class="font-semibold text-[#007A87] hover:underline cursor-pointer">SKU-MED-105</span>
                    </td>
                    <td class="px-4 py-3.5">
                        <div class="flex items-center gap-2">
                            <div class="flex h-7 w-7 flex-shrink-0 items-center justify-center
                                        rounded-lg bg-emerald-50 text-emerald-600">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="font-semibold text-slate-800">Vitamin C 1000mg</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-3.5">
                        <span class="inline-block rounded-md bg-violet-50 px-2 py-0.5 text-xs font-medium text-violet-700">
                            Suplemen
                        </span>
                    </td>
                    <td class="px-4 py-3.5 text-center text-slate-500">Botol</td>
                    <td class="px-4 py-3.5 text-center">
                        <span class="font-extrabold text-emerald-600">120</span>
                        <span class="ml-0.5 text-xs text-slate-400">Btl</span>
                    </td>
                    <td class="px-4 py-3.5 text-center text-slate-500">30</td>
                    <td class="px-4 py-3.5 text-center text-slate-500 text-xs">05 Des 2026</td>
                    <td class="px-4 py-3.5 text-right">
                        <p class="text-xs text-slate-400">Rp</p>
                        <p class="font-bold text-slate-800">4.800.000</p>
                    </td>
                    <td class="px-4 py-3.5 text-center">
                        <span class="inline-flex items-center gap-1 rounded-full bg-[#007A87]/10
                                     px-2.5 py-1 text-xs font-semibold text-[#007A87]">
                            <span class="h-1.5 w-1.5 rounded-full bg-[#007A87]"></span>
                            Stok Aman
                        </span>
                    </td>
                    <td class="px-4 py-3.5 text-center">
                        <button class="text-xs font-semibold text-[#007A87] hover:underline transition">
                            Detail
                        </button>
                    </td>
                </tr>

                {{-- ── Row 4: Sirup Obat Batuk Anak — Kritis & ED Dekat ── --}}
                <tr class="tr-hover fade-in-row fade-delay-4 transition-colors duration-150 bg-rose-50/30">
                    <td class="px-4 py-3.5">
                        <span class="font-semibold text-[#007A87] hover:underline cursor-pointer">SKU-MED-019</span>
                    </td>
                    <td class="px-4 py-3.5">
                        <div class="flex items-center gap-2">
                            <div class="flex h-7 w-7 flex-shrink-0 items-center justify-center
                                        rounded-lg bg-rose-50 text-rose-600">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </div>
                            <div>
                                <p class="font-semibold text-slate-800">Sirup Obat Batuk Anak</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-3.5">
                        <span class="inline-block rounded-md bg-sky-50 px-2 py-0.5 text-xs font-medium text-sky-700">
                            Obat Bebas
                        </span>
                    </td>
                    <td class="px-4 py-3.5 text-center text-slate-500">Botol</td>
                    <td class="px-4 py-3.5 text-center">
                        <span class="font-extrabold text-rose-600">12</span>
                        <span class="ml-0.5 text-xs text-rose-400">Btl</span>
                    </td>
                    <td class="px-4 py-3.5 text-center text-slate-500">25</td>
                    <td class="px-4 py-3.5 text-center text-xs font-semibold text-rose-600">
                        10 Mei 2024
                    </td>
                    <td class="px-4 py-3.5 text-right">
                        <p class="text-xs text-slate-400">Rp</p>
                        <p class="font-bold text-slate-800">420.000</p>
                    </td>
                    <td class="px-4 py-3.5 text-center">
                        <span class="inline-flex items-center gap-1 rounded-full bg-rose-100
                                     px-2 py-1 text-xs font-semibold text-rose-700 whitespace-nowrap">
                            <span class="dot-pulse h-1.5 w-1.5 rounded-full bg-rose-500"></span>
                            Kritis &amp; ED Dekat
                        </span>
                    </td>
                    <td class="px-4 py-3.5 text-center">
                        <button class="text-xs font-semibold text-[#007A87] hover:underline transition">
                            Detail
                        </button>
                    </td>
                </tr>

                {{-- ── Row 5: Omeprazole 20mg — Stok Aman ── --}}
                <tr class="tr-hover fade-in-row fade-delay-5 transition-colors duration-150">
                    <td class="px-4 py-3.5">
                        <span class="font-semibold text-[#007A87] hover:underline cursor-pointer">SKU-MED-086</span>
                    </td>
                    <td class="px-4 py-3.5">
                        <div class="flex items-center gap-2">
                            <div class="flex h-7 w-7 flex-shrink-0 items-center justify-center
                                        rounded-lg bg-emerald-50 text-emerald-600">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="font-semibold text-slate-800">Omeprazole 20mg</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-3.5">
                        <span class="inline-block rounded-md bg-rose-50 px-2 py-0.5 text-xs font-medium text-rose-700">
                            Obat Keras (Resep)
                        </span>
                    </td>
                    <td class="px-4 py-3.5 text-center text-slate-500">Kapsul</td>
                    <td class="px-4 py-3.5 text-center">
                        <span class="font-extrabold text-emerald-600">95</span>
                        <span class="ml-0.5 text-xs text-slate-400">Strip</span>
                    </td>
                    <td class="px-4 py-3.5 text-center text-slate-500">40</td>
                    <td class="px-4 py-3.5 text-center text-slate-500 text-xs">18 Jan 2027</td>
                    <td class="px-4 py-3.5 text-right">
                        <p class="text-xs text-slate-400">Rp</p>
                        <p class="font-bold text-slate-800">1.425.000</p>
                    </td>
                    <td class="px-4 py-3.5 text-center">
                        <span class="inline-flex items-center gap-1 rounded-full bg-[#007A87]/10
                                     px-2.5 py-1 text-xs font-semibold text-[#007A87]">
                            <span class="h-1.5 w-1.5 rounded-full bg-[#007A87]"></span>
                            Stok Aman
                        </span>
                    </td>
                    <td class="px-4 py-3.5 text-center">
                        <button class="text-xs font-semibold text-[#007A87] hover:underline transition">
                            Detail
                        </button>
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
            <span class="font-semibold text-slate-700">28</span>
        </p>

        {{-- Pagination Nav --}}
        <nav class="flex items-center gap-1" aria-label="Paginasi inventaris">

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
                28
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
     * Ekspor Laporan button — stub untuk controller CSV/PDF.
     * Ganti dengan route ekspor yang sesuai.
     */
    document.getElementById('btn-ekspor')?.addEventListener('click', function () {
        // window.location.href = "{{ route('reports.stok.export') }}";
        alert('Ekspor Excel/PDF akan diimplementasikan via controller.');
    });

    /**
     * Opname Stok button — stub untuk modal / halaman opname.
     */
    document.getElementById('btn-opname')?.addEventListener('click', function () {
        alert('Fitur Opname Stok akan diimplementasikan.');
    });

    /**
     * Filter dropdowns — stub untuk AJAX filter / form submit.
     */
    ['filter-kategori', 'filter-status'].forEach(function (id) {
        document.getElementById(id)?.addEventListener('change', function () {
            // Bisa di-submit sebagai form atau AJAX request ke route filter
            console.log('Filter changed:', id, this.value);
        });
    });
</script>
@endpush
