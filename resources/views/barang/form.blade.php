@extends('layouts.app')

@section('title', ($isEditing ? 'Edit' : 'Tambah').' Barang | PharmaPOS')
@section('breadcrumb', 'Home / Barang / '.($isEditing ? 'Edit' : 'Tambah'))

@section('content')
<div class="mx-auto max-w-3xl space-y-5">
    <div class="flex items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">{{ $isEditing ? 'Edit Barang' : 'Tambah Barang' }}</h1>
            <p class="mt-1 text-sm text-slate-600">Kelola identitas dan informasi penjualan obat.</p>
        </div>
        <a href="{{ route('barang.index') }}" class="text-sm font-medium text-[#006680] hover:underline">Kembali</a>
    </div>
    <form method="POST" action="{{ $isEditing ? route('items.update', $item) : route('items.store') }}" class="space-y-5 rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
        @csrf
        @if ($isEditing) @method('PUT') @endif
        <div class="grid gap-4 sm:grid-cols-2">
            <div>
                <label for="item_code" class="mb-1.5 block text-sm font-medium">Kode Barang</label>
                <input id="item_code" name="item_code" required maxlength="100" value="{{ old('item_code', $item->item_code) }}" class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm">
                @error('item_code') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="barcode" class="mb-1.5 block text-sm font-medium">Barcode (opsional)</label>
                <input id="barcode" name="barcode" maxlength="100" value="{{ old('barcode', $item->barcode) }}" class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm">
                @error('barcode') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
            </div>
            <div class="sm:col-span-2">
                <label for="name" class="mb-1.5 block text-sm font-medium">Nama Obat/Barang</label>
                <input id="name" name="name" required maxlength="255" value="{{ old('name', $item->name) }}" class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm">
                @error('name') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="category" class="mb-1.5 block text-sm font-medium">Kategori</label>
                <select id="category" name="category" required class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm">
                    <option value="">Pilih kategori</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category }}" @selected(old('category', $item->category) === $category)>{{ $category }}</option>
                    @endforeach
                </select>
                @error('category') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="unit" class="mb-1.5 block text-sm font-medium">Satuan</label>
                <input id="unit" name="unit" required maxlength="50" value="{{ old('unit', $item->unit ?: 'Pcs') }}" placeholder="Tablet, Botol, Pcs" class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm">
                @error('unit') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="selling_price" class="mb-1.5 block text-sm font-medium">Harga Jual</label>
                <input id="selling_price" name="selling_price" type="text" inputmode="decimal" required value="{{ old('selling_price', $item->selling_price) }}" class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm">
                @error('selling_price') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="harga_beli" class="mb-1.5 block text-sm font-medium">Harga Beli</label>
                <input id="harga_beli" name="harga_beli" type="text" inputmode="decimal" required value="{{ old('harga_beli', $item->harga_beli ?? '0.00') }}" class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm">
                @error('harga_beli') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="minimum_stock" class="mb-1.5 block text-sm font-medium">Stok Minimum</label>
                <input id="minimum_stock" name="minimum_stock" type="text" inputmode="numeric" required value="{{ old('minimum_stock', $item->minimum_stock ?? 0) }}" class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm">
                @error('minimum_stock') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="is_controlled" class="mb-1.5 block text-sm font-medium">Status Obat Keras</label>
                <select id="is_controlled" name="is_controlled" required class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm">
                    <option value="0" @selected((string) old('is_controlled', (int) $item->is_controlled) === '0')>Bukan obat keras</option>
                    <option value="1" @selected((string) old('is_controlled', (int) $item->is_controlled) === '1')>Obat keras</option>
                </select>
                @error('is_controlled') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="is_active" class="mb-1.5 block text-sm font-medium">Status Barang</label>
                <select id="is_active" name="is_active" required class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm">
                    <option value="1" @selected((string) old('is_active', $item->exists ? (int) $item->is_active : 1) === '1')>Aktif</option>
                    <option value="0" @selected((string) old('is_active', $item->exists ? (int) $item->is_active : 1) === '0')>Nonaktif</option>
                </select>
                @error('is_active') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
            </div>
        </div>
        <div class="flex justify-end gap-2 border-t border-slate-100 pt-4">
            <a href="{{ route('barang.index') }}" class="rounded-md border border-slate-300 px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">Batal</a>
            <button type="submit" class="rounded-md bg-[#006680] px-4 py-2 text-sm font-semibold text-white hover:bg-[#004d60]">{{ $isEditing ? 'Simpan Perubahan' : 'Tambah Barang' }}</button>
        </div>
    </form>
</div>
@endsection
