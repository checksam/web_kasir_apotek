@extends('layouts.app')

@section('title', 'Input Pembelian | PharmaPOS')
@section('breadcrumb', 'Home / Transaksi Pembelian / Input')

@section('content')
@php
    $initialLines = old('items', [[
        'item_id' => '',
        'quantity' => 1,
        'unit_price' => '',
        'discount' => '0',
        'batch_number' => '',
        'expiration_date' => '',
    ]]);
    $itemOptions = $items->map(fn ($item) => [
        'id' => $item->id,
        'label' => $item->item_code.' - '.$item->name,
    ])->values();
@endphp
<div class="mx-auto max-w-5xl space-y-5">
    <div class="flex items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Input Pembelian Baru</h1>
            <p class="mt-1 text-sm text-slate-600">Pembelian berstatus selesai akan langsung menambahkan stok melalui batch.</p>
        </div>
        <a href="{{ route('transactions.pembelian.index') }}" class="text-sm font-medium text-[#006680] hover:underline">Kembali</a>
    </div>

    @if ($errors->any())
        <div role="alert" class="rounded-md border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
            <p class="font-semibold">Periksa kembali data pembelian:</p>
            <ul class="mt-1 list-inside list-disc">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif
    @if ($suppliers->isEmpty() || $items->isEmpty())
        <div class="rounded-md border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800">Tambahkan minimal satu supplier aktif dan satu barang aktif sebelum membuat pembelian.</div>
    @endif

    <form method="POST" action="{{ route('purchases.store') }}" x-data="{ rows: @js($initialLines), itemOptions: @js($itemOptions) }" class="space-y-5 rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
        @csrf
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <div class="lg:col-span-2">
                <label for="supplier_id" class="mb-1.5 block text-sm font-medium">Supplier</label>
                <select id="supplier_id" name="supplier_id" required class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm">
                    <option value="">Pilih supplier</option>
                    @foreach ($suppliers as $supplier)
                        <option value="{{ $supplier->id }}" @selected((string) old('supplier_id') === (string) $supplier->id)>{{ $supplier->name }}</option>
                    @endforeach
                </select>
                @error('supplier_id') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="purchase_date" class="mb-1.5 block text-sm font-medium">Tanggal Pembelian</label>
                <input id="purchase_date" type="date" name="purchase_date" required value="{{ old('purchase_date', today()->toDateString()) }}" class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm">
                @error('purchase_date') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="purchase_number" class="mb-1.5 block text-sm font-medium">Nomor Nota</label>
                <input id="purchase_number" name="purchase_number" required maxlength="100" value="{{ old('purchase_number') }}" class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm">
                @error('purchase_number') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
            </div>
            <div class="sm:col-span-2">
                <label for="status" class="mb-1.5 block text-sm font-medium">Status Pembelian</label>
                <select id="status" name="status" required class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm">
                    <option value="completed" @selected(old('status', 'completed') === 'completed')>Selesai (stok langsung diterima)</option>
                    <option value="pending" @selected(old('status') === 'pending')>Menunggu Pembayaran (stok belum diterima)</option>
                </select>
                @error('status') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="space-y-3 border-t border-slate-100 pt-5">
            <div class="flex items-center justify-between gap-3">
                <h2 class="font-semibold text-slate-900">Barang yang Dibeli</h2>
                <button type="button" @click="rows.push({ item_id: '', quantity: 1, unit_price: '', discount: '0', batch_number: '', expiration_date: '' })" class="rounded-md border border-slate-300 px-3 py-2 text-sm font-medium text-[#006680] hover:bg-slate-50">+ Tambah Barang</button>
            </div>
            <template x-for="(row, index) in rows" :key="index">
                <fieldset class="grid gap-3 rounded-md border border-slate-200 p-4 sm:grid-cols-2 lg:grid-cols-4">
                    <legend class="px-1 text-xs font-semibold text-slate-500" x-text="'Barang ' + (index + 1)"></legend>
                    <div class="sm:col-span-2 lg:col-span-2">
                        <label :for="'item-' + index" class="mb-1 block text-xs font-medium text-slate-600">Obat/Barang</label>
                        <select :id="'item-' + index" :name="'items[' + index + '][item_id]'" x-model="row.item_id" required class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm">
                            <option value="">Pilih barang</option>
                            <template x-for="option in itemOptions" :key="option.id">
                                <option :value="option.id" x-text="option.label"></option>
                            </template>
                        </select>
                    </div>
                    <div>
                        <label :for="'quantity-' + index" class="mb-1 block text-xs font-medium text-slate-600">Jumlah</label>
                        <input :id="'quantity-' + index" :name="'items[' + index + '][quantity]'" type="text" inputmode="numeric" x-model="row.quantity" required class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label :for="'price-' + index" class="mb-1 block text-xs font-medium text-slate-600">Harga Beli / unit</label>
                        <input :id="'price-' + index" :name="'items[' + index + '][unit_price]'" type="text" inputmode="decimal" x-model="row.unit_price" required class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label :for="'discount-' + index" class="mb-1 block text-xs font-medium text-slate-600">Diskon (nominal)</label>
                        <input :id="'discount-' + index" :name="'items[' + index + '][discount]'" type="text" inputmode="decimal" x-model="row.discount" required class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label :for="'batch-' + index" class="mb-1 block text-xs font-medium text-slate-600">Nomor Batch</label>
                        <input :id="'batch-' + index" :name="'items[' + index + '][batch_number]'" maxlength="100" x-model="row.batch_number" required class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label :for="'expiration-' + index" class="mb-1 block text-xs font-medium text-slate-600">Tanggal Kadaluarsa</label>
                        <input :id="'expiration-' + index" :name="'items[' + index + '][expiration_date]'" type="date" x-model="row.expiration_date" class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm">
                    </div>
                    <div class="flex items-end">
                        <button type="button" @click="rows.length > 1 && rows.splice(index, 1)" :disabled="rows.length === 1" class="rounded-md px-3 py-2 text-sm text-rose-600 hover:bg-rose-50 disabled:cursor-not-allowed disabled:opacity-40">Hapus baris</button>
                    </div>
                </fieldset>
            </template>
        </div>

        <div class="flex flex-col-reverse justify-end gap-2 border-t border-slate-100 pt-4 sm:flex-row">
            <a href="{{ route('transactions.pembelian.index') }}" class="rounded-md border border-slate-300 px-4 py-2 text-center text-sm text-slate-700 hover:bg-slate-50">Batal</a>
            <button type="submit" @disabled($suppliers->isEmpty() || $items->isEmpty()) class="rounded-md bg-[#006680] px-4 py-2 text-sm font-semibold text-white hover:bg-[#004d60] disabled:cursor-not-allowed disabled:opacity-50">Simpan Pembelian</button>
        </div>
    </form>
</div>
@endsection
