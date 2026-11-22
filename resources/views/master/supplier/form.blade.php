@extends('layouts.app')

@section('title', ($isEditing ? 'Edit' : 'Tambah').' Supplier | PharmaPOS')
@section('breadcrumb', 'Home / Supplier / '.($isEditing ? 'Edit' : 'Tambah'))

@section('content')
<div class="mx-auto max-w-3xl space-y-5">
    <div class="flex items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">{{ $isEditing ? 'Edit Supplier' : 'Tambah Supplier' }}</h1>
            <p class="mt-1 text-sm text-slate-600">Lengkapi informasi pemasok.</p>
        </div>
        <a href="{{ route('master.supplier.index') }}" class="text-sm font-medium text-[#006680] hover:underline">Kembali</a>
    </div>

    <form method="POST" action="{{ $isEditing ? route('suppliers.update', $supplier) : route('suppliers.store') }}" class="space-y-5 rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
        @csrf
        @if ($isEditing) @method('PUT') @endif
        <div class="grid gap-4 sm:grid-cols-2">
            @foreach (['name' => 'Nama Supplier', 'contact_person' => 'Kontak Person', 'phone' => 'No HP'] as $field => $label)
                <div>
                    <label for="{{ $field }}" class="mb-1.5 block text-sm font-medium text-slate-700">{{ $label }}</label>
                    <input id="{{ $field }}" name="{{ $field }}" value="{{ old($field, $supplier->{$field}) }}" required maxlength="{{ $field === 'phone' ? 30 : 255 }}" class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-[#006680] focus:outline-none focus:ring-2 focus:ring-[#006680]/20">
                    @error($field) <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                </div>
            @endforeach
            <div class="sm:col-span-2">
                <label for="address" class="mb-1.5 block text-sm font-medium text-slate-700">Alamat</label>
                <textarea id="address" name="address" rows="3" required maxlength="2000" class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-[#006680] focus:outline-none focus:ring-2 focus:ring-[#006680]/20">{{ old('address', $supplier->address) }}</textarea>
                @error('address') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="is_active" class="mb-1.5 block text-sm font-medium text-slate-700">Status</label>
                <select id="is_active" name="is_active" required class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm">
                    <option value="1" @selected((string) old('is_active', $supplier->exists ? (int) $supplier->is_active : 1) === '1')>Aktif</option>
                    <option value="0" @selected((string) old('is_active', $supplier->exists ? (int) $supplier->is_active : 1) === '0')>Nonaktif</option>
                </select>
                @error('is_active') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
            </div>
        </div>
        <div class="flex justify-end gap-2 border-t border-slate-100 pt-4">
            <a href="{{ route('master.supplier.index') }}" class="rounded-md border border-slate-300 px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">Batal</a>
            <button type="submit" class="rounded-md bg-[#006680] px-4 py-2 text-sm font-semibold text-white hover:bg-[#004d60]">{{ $isEditing ? 'Simpan Perubahan' : 'Tambah Supplier' }}</button>
        </div>
    </form>
</div>
@endsection
