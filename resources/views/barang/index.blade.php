@extends('layouts.app')

@section('title', 'Data Barang | PharmaPOS')
@section('breadcrumb', 'Home / Barang')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-[#006680]">Data Barang</h1>
            <p class="mt-1 text-sm text-slate-500">Kelola inventaris obat dan perlengkapan medis.</p>
        </div>
        <div class="flex w-full flex-col gap-2 sm:flex-row lg:w-auto">
            <button type="button" class="inline-flex items-center gap-2 rounded-md border border-slate-300 bg-white px-3.5 py-2 text-sm font-medium text-slate-700 shadow-sm transition hover:bg-slate-50">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v12m0 0l-4-4m4 4l4-4M5 21h14a2 2 0 002-2v-3M3 16v3a2 2 0 002 2" />
                </svg>
                Export
            </button>
            <button type="button" class="inline-flex items-center gap-2 rounded-md bg-[#006680] px-3.5 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-[#004d60]">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Tambah Barang
            </button>
        </div>
    </div>

    <form method="GET" action="{{ route('barang.index') }}" class="rounded-lg border border-slate-200 bg-white p-4 shadow-sm">
        <div class="flex flex-col gap-3 lg:flex-row lg:items-center">
            <label class="relative flex-1">
                <span class="sr-only">Cari barang</span>
                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </span>
                <input type="search" name="search" value="{{ $search }}" placeholder="Cari Kode, Nama Obat..." class="w-full rounded-md border border-slate-300 py-2.5 pl-10 pr-4 text-sm text-slate-800 placeholder-slate-400 outline-none transition focus:border-[#006680] focus:ring-2 focus:ring-[#006680]/20">
            </label>
            <div class="flex w-full flex-col gap-2 sm:flex-row lg:w-auto">
                <label class="sr-only" for="category">Kategori</label>
                <select id="category" name="category" class="w-full rounded-md border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-700 outline-none transition focus:border-[#006680] focus:ring-2 focus:ring-[#006680]/20 sm:min-w-48 sm:flex-1 lg:w-auto lg:flex-none">
                    <option value="">Semua Kategori</option>
                    @foreach ($categories as $option)
                        <option value="{{ $option }}" @selected($category === $option)>{{ $option }}</option>
                    @endforeach
                </select>
                <button type="submit" class="inline-flex min-h-11 items-center justify-center rounded-md border border-slate-300 bg-slate-50 px-3 text-slate-600 transition hover:bg-slate-100 sm:min-h-0" title="Terapkan filter" aria-label="Terapkan filter">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                </button>
            </div>
        </div>
    </form>

    <div class="overflow-hidden rounded-lg border border-slate-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full min-w-[780px] border-collapse text-left">
                <thead class="bg-slate-50 text-xs font-bold tracking-wide text-slate-800">
                    <tr class="border-b border-slate-200">
                        <th class="px-5 py-3.5">Kode Item</th>
                        <th class="px-5 py-3.5">Nama Barang</th>
                        <th class="px-5 py-3.5">Kategori</th>
                        <th class="px-5 py-3.5">Harga Jual</th>
                        <th class="px-5 py-3.5">Stok</th>
                        <th class="px-5 py-3.5">Status</th>
                        <th class="px-5 py-3.5 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @forelse ($items as $item)
                        <tr class="transition-colors hover:bg-slate-50/70">
                            <td class="whitespace-nowrap px-5 py-4 font-medium text-slate-600">{{ $item->item_code }}</td>
                            <td class="whitespace-nowrap px-5 py-4 font-semibold text-slate-800">{{ $item->name }}</td>
                            <td class="whitespace-nowrap px-5 py-4 text-slate-500">{{ $item->category }}</td>
                            <td class="whitespace-nowrap px-5 py-4 text-slate-700">Rp {{ number_format($item->selling_price, 0, ',', '.') }}</td>
                            <td class="whitespace-nowrap px-5 py-4 font-medium {{ $item->stock <= 15 ? 'text-rose-600' : 'text-slate-700' }}">{{ number_format($item->stock, 0, ',', '.') }}</td>
                            <td class="whitespace-nowrap px-5 py-4">
                                <span class="inline-flex whitespace-nowrap rounded-full px-2.5 py-1 text-xs font-medium {{ $item->stock_status['class'] }}">{{ $item->stock_status['label'] }}</span>
                            </td>
                            <td class="px-5 py-4 text-center">
                                <button type="button" class="text-slate-400 transition hover:text-[#006680]" title="Lihat detail {{ $item->name }}" aria-label="Lihat detail {{ $item->name }}">
                                    <svg class="mx-auto h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-12 text-center text-sm text-slate-500">Belum ada data barang yang sesuai.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="flex flex-col items-center justify-between gap-3 border-t border-slate-200 px-5 py-3.5 text-xs text-slate-500 sm:flex-row">
            <p>Menampilkan {{ $items->firstItem() ?? 0 }} hingga {{ $items->lastItem() ?? 0 }} dari {{ number_format($items->total(), 0, ',', '.') }} hasil</p>
            <nav class="flex max-w-full flex-wrap items-center justify-center gap-1" aria-label="Pagination">
                <a href="{{ $items->previousPageUrl() ?: '#' }}" class="rounded border border-slate-200 px-2.5 py-1.5 {{ $items->onFirstPage() ? 'pointer-events-none text-slate-300' : 'text-slate-700 hover:bg-slate-50' }}" aria-label="Halaman sebelumnya">&lsaquo;</a>
                @foreach ($items->getUrlRange(1, $items->lastPage()) as $page => $url)
                    <a href="{{ $url }}" class="rounded border px-3 py-1.5 {{ $page === $items->currentPage() ? 'border-[#006680] bg-[#006680] font-medium text-white' : 'border-slate-200 text-slate-700 hover:bg-slate-50' }}">{{ $page }}</a>
                @endforeach
                <a href="{{ $items->nextPageUrl() ?: '#' }}" class="rounded border border-slate-200 px-2.5 py-1.5 {{ $items->hasMorePages() ? 'text-slate-700 hover:bg-slate-50' : 'pointer-events-none text-slate-300' }}" aria-label="Halaman berikutnya">&rsaquo;</a>
            </nav>
        </div>
    </div>
</div>
@endsection
