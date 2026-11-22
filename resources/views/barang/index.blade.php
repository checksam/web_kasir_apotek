@extends('layouts.app')

@section('title', 'Data Barang | PharmaPOS')
@section('breadcrumb', 'Home / Barang')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col justify-between gap-4 lg:flex-row lg:items-center">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-[#006680]">Data Barang</h1>
            <p class="mt-1 text-sm text-slate-500">Kelola inventaris obat dan perlengkapan medis.</p>
        </div>
        @if (auth()->user()->hasRole('admin'))
            <a href="{{ route('items.create') }}" class="inline-flex items-center justify-center gap-2 rounded-md bg-[#006680] px-4 py-2.5 text-sm font-medium text-white hover:bg-[#004d60]">+ Tambah Barang</a>
        @endif
    </div>

    @if (session('status'))
        <div role="status" class="rounded-md border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">{{ session('status') }}</div>
    @endif
    @if ($errors->has('item'))
        <div role="alert" class="rounded-md border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">{{ $errors->first('item') }}</div>
    @endif

    <form method="GET" action="{{ route('barang.index') }}" class="rounded-lg border border-slate-200 bg-white p-4 shadow-sm">
        <div class="flex flex-col gap-3 lg:flex-row lg:items-center">
            <label class="flex-1">
                <span class="sr-only">Cari barang</span>
                <input type="search" name="search" value="{{ $search }}" placeholder="Cari kode, barcode, atau nama obat..." class="w-full rounded-md border border-slate-300 px-3 py-2.5 text-sm">
            </label>
            <div class="flex gap-2">
                <label class="sr-only" for="category">Kategori</label>
                <select id="category" name="category" class="rounded-md border border-slate-300 bg-white px-3 py-2.5 text-sm">
                    <option value="">Semua Kategori</option>
                    @foreach ($categories as $option)
                        <option value="{{ $option }}" @selected($category === $option)>{{ $option }}</option>
                    @endforeach
                </select>
                <button type="submit" class="rounded-md border border-slate-300 bg-slate-50 px-4 text-sm text-slate-700 hover:bg-slate-100">Cari</button>
            </div>
        </div>
    </form>

    <div class="overflow-hidden rounded-lg border border-slate-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full min-w-[900px] border-collapse text-left text-sm">
                <thead class="bg-slate-50 text-xs font-bold tracking-wide text-slate-800">
                    <tr class="border-b border-slate-200">
                        <th class="px-5 py-3.5">Kode / Barcode</th>
                        <th class="px-5 py-3.5">Nama Barang</th>
                        <th class="px-5 py-3.5">Kategori / Satuan</th>
                        <th class="px-5 py-3.5">Harga Jual</th>
                        @if (auth()->user()->hasRole('admin')) <th class="px-5 py-3.5">Harga Beli</th> @endif
                        <th class="px-5 py-3.5">Stok</th>
                        <th class="px-5 py-3.5">Status</th>
                        @if (auth()->user()->hasRole('admin')) <th class="px-5 py-3.5 text-center">Aksi</th> @endif
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($items as $item)
                        <tr class="hover:bg-slate-50/70">
                            <td class="whitespace-nowrap px-5 py-4 font-medium text-slate-600">
                                {{ $item->item_code }}
                                @if ($item->barcode) <div class="mt-0.5 text-xs text-slate-400">{{ $item->barcode }}</div> @endif
                            </td>
                            <td class="px-5 py-4 font-semibold text-slate-800">
                                {{ $item->name }}
                                @if ($item->is_controlled) <span class="ml-1 rounded-full bg-rose-100 px-2 py-0.5 text-xs font-medium text-rose-700">Obat keras</span> @endif
                            </td>
                            <td class="whitespace-nowrap px-5 py-4 text-slate-500">{{ $item->category }} / {{ $item->unit }}</td>
                            <td class="whitespace-nowrap px-5 py-4 text-slate-700">Rp {{ number_format($item->selling_price, 0, ',', '.') }}</td>
                            @if (auth()->user()->hasRole('admin'))
                                <td class="whitespace-nowrap px-5 py-4 text-slate-700">Rp {{ number_format($item->harga_beli, 0, ',', '.') }}</td>
                            @endif
                            <td class="whitespace-nowrap px-5 py-4 {{ $item->stock <= $item->minimum_stock ? 'text-rose-600' : 'text-slate-700' }}">
                                {{ number_format($item->stock, 0, ',', '.') }} {{ $item->unit }}
                                <div class="text-xs text-slate-400">Min. {{ number_format($item->minimum_stock, 0, ',', '.') }}</div>
                            </td>
                            <td class="whitespace-nowrap px-5 py-4">
                                <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-medium {{ $item->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-200 text-slate-600' }}">{{ $item->is_active ? 'Aktif' : 'Nonaktif' }}</span>
                            </td>
                            @if (auth()->user()->hasRole('admin'))
                                <td class="px-5 py-4">
                                    <div class="flex items-center justify-center gap-3">
                                        <a href="{{ route('items.edit', $item) }}" class="text-slate-500 hover:text-[#006680]" aria-label="Edit {{ $item->name }}">Edit</a>
                                        <form method="POST" action="{{ route('items.destroy', $item) }}" onsubmit="return confirm('Hapus barang {{ addslashes($item->name) }}?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="text-rose-600 hover:text-rose-800" aria-label="Hapus {{ $item->name }}">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr><td colspan="{{ auth()->user()->hasRole('admin') ? 8 : 6 }}" class="px-5 py-12 text-center text-slate-500">Belum ada data barang yang sesuai.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="flex flex-col items-center justify-between gap-3 border-t border-slate-200 px-5 py-3.5 text-xs text-slate-500 sm:flex-row">
            <p>Menampilkan {{ $items->firstItem() ?? 0 }} hingga {{ $items->lastItem() ?? 0 }} dari {{ number_format($items->total(), 0, ',', '.') }} hasil</p>
            {{ $items->links() }}
        </div>
    </div>
</div>
@endsection
