@extends('layouts.app')

@section('title', 'Pengaturan Sistem | PharmaPOS')
@section('breadcrumb', 'Home / Pengaturan')

@section('content')
<div class="mx-auto max-w-7xl space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-[#006680]">Pengaturan Sistem</h1>
        <p class="mt-1 text-sm text-slate-600">Konfigurasi profil apotek, cetak struk, dan perpajakan.</p>
    </div>

    @if (session('status'))
        <div role="status" class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('settings.update') }}" class="grid grid-cols-1 items-start gap-5 lg:grid-cols-[minmax(0,1.65fr)_minmax(240px,0.8fr)]">
        @csrf
        @method('PUT')

        <div class="space-y-5">
            <section class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
                <div class="mb-5 flex items-center gap-2 border-b border-slate-200 pb-3">
                    <span class="text-lg text-[#007A87]" aria-hidden="true">＋</span>
                    <h2 class="font-semibold text-slate-800">Profil Apotek</h2>
                </div>
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div class="sm:col-span-2">
                        <label for="pharmacy_name" class="mb-1.5 block text-xs font-medium text-slate-600">Nama Apotek</label>
                        <input id="pharmacy_name" name="pharmacy_name" value="{{ old('pharmacy_name', $setting->pharmacy_name) }}" required maxlength="255" class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-[#007A87] focus:outline-none focus:ring-2 focus:ring-[#007A87]/20">
                        @error('pharmacy_name') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                    </div>
                    <div class="sm:col-span-2">
                        <label for="address" class="mb-1.5 block text-xs font-medium text-slate-600">Alamat Lengkap</label>
                        <textarea id="address" name="address" rows="3" maxlength="2000" class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-[#007A87] focus:outline-none focus:ring-2 focus:ring-[#007A87]/20">{{ old('address', $setting->address) }}</textarea>
                        @error('address') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="phone" class="mb-1.5 block text-xs font-medium text-slate-600">Nomor Telepon</label>
                        <input id="phone" name="phone" value="{{ old('phone', $setting->phone) }}" maxlength="40" class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-[#007A87] focus:outline-none focus:ring-2 focus:ring-[#007A87]/20">
                        @error('phone') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="official_email" class="mb-1.5 block text-xs font-medium text-slate-600">Email Resmi</label>
                        <input id="official_email" name="official_email" type="email" value="{{ old('official_email', $setting->official_email) }}" maxlength="255" class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-[#007A87] focus:outline-none focus:ring-2 focus:ring-[#007A87]/20">
                        @error('official_email') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                    </div>
                </div>
            </section>

            <section class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
                <div class="mb-5 flex items-center gap-2 border-b border-slate-200 pb-3">
                    <span class="text-lg text-[#007A87]" aria-hidden="true">▤</span>
                    <h2 class="font-semibold text-slate-800">Konfigurasi Struk</h2>
                </div>
                <label for="receipt_footer" class="mb-1.5 block text-xs font-medium text-slate-600">Pesan Penutup (Footer)</label>
                <textarea id="receipt_footer" name="receipt_footer" rows="3" maxlength="2000" class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-[#007A87] focus:outline-none focus:ring-2 focus:ring-[#007A87]/20">{{ old('receipt_footer', $setting->receipt_footer) }}</textarea>
                @error('receipt_footer') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                <label class="mt-4 flex cursor-pointer items-start gap-3 rounded-md bg-slate-50 p-3">
                    <input type="hidden" name="show_pharmacist_name" value="0">
                    <input type="checkbox" name="show_pharmacist_name" value="1" @checked(old('show_pharmacist_name', $setting->show_pharmacist_name)) class="mt-0.5 h-4 w-4 rounded border-slate-300 text-[#006680] focus:ring-[#006680]">
                    <span><span class="block text-sm font-medium text-slate-700">Tampilkan Nama Apoteker</span><span class="mt-0.5 block text-xs text-slate-500">Cetak nama apoteker pada struk transaksi.</span></span>
                </label>
            </section>
        </div>

        <div class="space-y-5">
            <section class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
                <div class="mb-5 flex items-center gap-2 border-b border-slate-200 pb-3">
                    <span class="text-lg text-[#007A87]" aria-hidden="true">▥</span>
                    <h2 class="font-semibold text-slate-800">Pajak &amp; Biaya</h2>
                </div>
                <div>
                    <label for="vat_percent" class="mb-1.5 block text-xs font-medium text-slate-600">Pajak Pertambahan Nilai (PPN) %</label>
                    <div class="flex items-center gap-2">
                        <input id="vat_percent" name="vat_percent" type="text" inputmode="decimal" required value="{{ old('vat_percent', $setting->vat_percent) }}" class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-[#007A87] focus:outline-none focus:ring-2 focus:ring-[#007A87]/20">
                        <span class="text-sm text-slate-500">%</span>
                    </div>
                    @error('vat_percent') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                </div>
                <div class="mt-4">
                    <label for="default_packaging_fee" class="mb-1.5 block text-xs font-medium text-slate-600">Biaya Embalase Default (Rp)</label>
                    <div class="flex items-center overflow-hidden rounded-md border border-slate-300 focus-within:border-[#007A87] focus-within:ring-2 focus-within:ring-[#007A87]/20">
                        <span class="bg-slate-50 px-3 py-2 text-sm text-slate-500">Rp</span>
                        <input id="default_packaging_fee" name="default_packaging_fee" type="text" inputmode="numeric" required value="{{ old('default_packaging_fee', $setting->default_packaging_fee) }}" class="w-full border-0 px-3 py-2 text-sm focus:outline-none focus:ring-0">
                    </div>
                    @error('default_packaging_fee') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                </div>
            </section>

            <div class="rounded-lg border border-slate-200 bg-white p-4 shadow-sm">
                <button type="submit" class="flex w-full items-center justify-center gap-2 rounded-md bg-[#006680] px-4 py-2.5 text-sm font-semibold text-white transition-colors hover:bg-[#004d60]">
                    <span aria-hidden="true">▣</span> Simpan Perubahan
                </button>
                <a href="{{ route('settings.edit') }}" class="mt-2 flex w-full items-center justify-center rounded-md border border-[#006680] px-4 py-2 text-sm font-medium text-[#006680] hover:bg-slate-50">Batal</a>
            </div>
        </div>
    </form>
</div>
@endsection