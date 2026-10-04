@extends('layouts.app')

@section('title', 'Dashboard Kasir | PharmaPOS')

@section('breadcrumb', 'Home / Dashboard')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <p class="text-sm font-medium text-[#007A87]">Selamat datang, {{ $user['name'] ?? 'Kasir' }}</p>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Dashboard Kasir</h1>
        </div>
        <span class="rounded-full bg-[#007A87]/10 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-[#007A87]">kasir</span>
    </div>

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        @php
            $metrics = [
                ['label' => 'Pengguna', 'value' => 3, 'accent' => '#007A87', 'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z', 'link' => '#'],
                ['label' => 'Supplier', 'value' => 2, 'accent' => '#22c55e', 'icon' => 'M3 7h11l4 4v6H3V7zm11 0V4H7v3m-1 10a2 2 0 104 0m5 0a2 2 0 104 0', 'link' => '#'],
                ['label' => 'Pelanggan', 'value' => 2, 'accent' => '#eab308', 'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2a5 5 0 00-10 0v2m5-9a3 3 0 110-6 3 3 0 010 6z', 'link' => route('master.customer.index')],
                ['label' => 'Item Barang', 'value' => 4, 'accent' => '#ef4444', 'icon' => 'M3 3h2l.4 2M7 13h10l4-8H5.4M7 13l-2 2h12m-2 4a2 2 0 11-4 0m-4 0a2 2 0 114 0', 'link' => route('barang.index')],
            ];
        @endphp

        @foreach ($metrics as $metric)
            <div class="flex flex-col overflow-hidden rounded-lg border border-slate-200 border-t-4 bg-white shadow-sm" style="border-top-color: {{ $metric['accent'] }}">
                <div class="relative flex-1 p-5">
                    <svg class="absolute right-3 top-3 h-16 w-16 opacity-10" style="color: {{ $metric['accent'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $metric['icon'] }}" />
                    </svg>
                    <div class="relative text-4xl font-bold tracking-tight text-slate-800">{{ $metric['value'] }}</div>
                    <div class="relative mt-1 text-sm font-medium text-slate-500">{{ $metric['label'] }}</div>
                </div>
                <a href="{{ $metric['link'] }}" class="flex items-center justify-center gap-1.5 border-t border-slate-100 bg-slate-50 px-4 py-2.5 text-xs font-medium text-[#007A87] transition-colors hover:bg-slate-100">
                    More info
                    <span aria-hidden="true">&#8594;</span>
                </a>
            </div>
        @endforeach
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        <section class="overflow-hidden rounded-lg border border-slate-200 border-t-4 border-t-red-500 bg-white shadow-sm">
            <div class="border-b border-slate-200 px-5 py-4">
                <h2 class="font-bold text-[#007A87]">Info Stok Barang</h2>
            </div>
            <div class="divide-y divide-slate-100">
                @foreach (['Minyak goreng isi ulang 1 ltr', 'Susu kental manis 350 gr'] as $item)
                    <div class="flex items-center justify-between gap-4 px-5 py-3.5">
                        <span class="text-sm font-medium text-slate-700">{{ $item }}</span>
                        <span class="inline-flex shrink-0 items-center gap-1 rounded-full border border-red-100 bg-red-50 px-3 py-1 text-xs font-medium text-red-600">&#9888; Stok Kurang</span>
                    </div>
                @endforeach
            </div>
        </section>

        <section class="overflow-hidden rounded-lg border border-slate-200 border-t-4 border-t-[#007A87] bg-white shadow-sm">
            <div class="border-b border-slate-200 px-5 py-4">
                <h2 class="font-bold text-[#007A87]">Omzet Penjualan</h2>
            </div>
            <div class="flex min-h-[140px] flex-col items-center justify-center p-8 text-center">
                <div class="mb-2 text-xs font-semibold uppercase tracking-wider text-slate-500">TOTAL HARI INI</div>
                <div class="text-4xl font-bold tracking-tight text-[#007A87] sm:text-5xl">{{ $dailyIncome ?? 'Rp 400.000' }}</div>
            </div>
        </section>
    </div>
</div>
@endsection