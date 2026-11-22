@extends('layouts.app')

@section('title', 'Data Supplier | PharmaPOS')
@section('breadcrumb', 'Home / Supplier')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-[#006680]">Data Supplier</h1>
            <p class="mt-1 text-sm text-slate-500">Kelola informasi vendor dan pemasok farmasi.</p>
        </div>
        <a href="{{ route('suppliers.create') }}" class="inline-flex items-center justify-center gap-2 rounded-lg bg-[#006680] px-4 py-2.5 text-sm font-medium text-white shadow-sm hover:bg-[#004d60]">
            <span aria-hidden="true">+</span> Tambah Supplier
        </a>
    </div>

    @if (session('status'))
        <div role="status" class="rounded-md border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">{{ session('status') }}</div>
    @endif
    @if ($errors->has('supplier'))
        <div role="alert" class="rounded-md border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">{{ $errors->first('supplier') }}</div>
    @endif

    <form method="GET" action="{{ route('master.supplier.index') }}" class="flex flex-col gap-3 sm:flex-row">
        <label class="sr-only" for="search">Cari supplier</label>
        <input id="search" type="search" name="search" value="{{ $search }}" placeholder="Cari nama, kontak, atau nomor HP..." class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm sm:max-w-md">
        <button class="rounded-md border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">Cari</button>
    </form>

    <div class="overflow-hidden rounded-lg border border-slate-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full min-w-[760px] text-left text-sm">
                <thead class="border-b border-slate-200 bg-slate-50 text-xs font-bold uppercase tracking-wide text-slate-700">
                    <tr>
                        <th class="px-5 py-3">Nama Supplier</th>
                        <th class="px-5 py-3">Kontak Person</th>
                        <th class="px-5 py-3">Alamat</th>
                        <th class="px-5 py-3 text-center">Status</th>
                        <th class="px-5 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($suppliers as $supplier)
                        <tr>
                            <td class="px-5 py-4 font-semibold text-slate-800">{{ $supplier->name }}</td>
                            <td class="px-5 py-4">
                                <div class="font-medium">{{ $supplier->contact_person }}</div>
                                <div class="mt-0.5 text-xs text-slate-500">{{ $supplier->phone }}</div>
                            </td>
                            <td class="px-5 py-4 text-slate-600">{{ $supplier->address }}</td>
                            <td class="px-5 py-4 text-center">
                                <span class="rounded-full px-3 py-1 text-xs font-medium {{ $supplier->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-200 text-slate-600' }}">{{ $supplier->is_active ? 'Aktif' : 'Nonaktif' }}</span>
                            </td>
                            <td class="px-5 py-4">
                                <div class="flex items-center justify-center gap-3">
                                    <a href="{{ route('suppliers.edit', $supplier) }}" class="text-slate-500 hover:text-[#006680]" aria-label="Edit {{ $supplier->name }}" title="Edit">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                                    </a>
                                    <form method="POST" action="{{ route('suppliers.destroy', $supplier) }}" onsubmit="return confirm('Hapus supplier {{ addslashes($supplier->name) }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="text-rose-600 hover:text-rose-800" aria-label="Hapus {{ $supplier->name }}" title="Hapus">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-5 py-12 text-center text-slate-500">Belum ada data supplier yang sesuai.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="flex flex-col justify-between gap-3 border-t border-slate-200 px-5 py-3 text-xs text-slate-500 sm:flex-row sm:items-center">
            <p>Menampilkan {{ $suppliers->firstItem() ?? 0 }} hingga {{ $suppliers->lastItem() ?? 0 }} dari {{ number_format($suppliers->total(), 0, ',', '.') }} supplier</p>
            {{ $suppliers->links() }}
        </div>
    </div>
</div>
@endsection
