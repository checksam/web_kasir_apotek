@extends('layouts.app')

@section('title', 'Transaksi Pembelian | PharmaPOS')
@section('breadcrumb', 'Home / Transaksi Pembelian')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Transaksi Pembelian</h1>
            <p class="mt-1 text-sm text-slate-500">Kelola riwayat pembelian obat dan alat kesehatan dari supplier.</p>
        </div>
        <a href="{{ route('purchases.create') }}" class="inline-flex items-center justify-center gap-2 rounded-lg bg-[#006680] px-4 py-2.5 text-xs font-semibold uppercase tracking-wider text-white hover:bg-[#004d60]">+ Input Pembelian Baru</a>
    </div>
    @if (session('status'))
        <div role="status" class="rounded-md border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">{{ session('status') }}</div>
    @endif

    <div class="grid gap-4 md:grid-cols-3">
        <div class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Total Pembelian (Bulan Ini)</p>
            <p class="mt-2 text-2xl font-bold text-slate-900">Rp {{ number_format($monthlyTotal, 0, ',', '.') }}</p>
        </div>
        <div class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Menunggu Pembayaran</p>
            <p class="mt-2 text-2xl font-bold text-slate-900">{{ number_format($pendingCount, 0, ',', '.') }} Pembelian</p>
        </div>
        <div class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Barang Diterima (30 Hari)</p>
            <p class="mt-2 text-2xl font-bold text-slate-900">{{ number_format($receivedCount, 0, ',', '.') }} Pembelian</p>
        </div>
    </div>

    <form method="GET" action="{{ route('transactions.pembelian.index') }}" class="flex gap-2">
        <label for="search" class="sr-only">Cari pembelian</label>
        <input id="search" type="search" name="search" value="{{ $search }}" placeholder="Cari nomor nota atau supplier..." class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm sm:max-w-md">
        <button class="rounded-md border border-slate-300 bg-white px-4 py-2 text-sm hover:bg-slate-50">Cari</button>
    </form>

    <div class="overflow-hidden rounded-lg border border-slate-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full min-w-[700px] text-left text-sm">
                <thead class="border-b border-slate-200 bg-slate-50 text-xs font-bold uppercase tracking-wide text-slate-700">
                    <tr>
                        <th class="px-5 py-3.5">No. Nota</th>
                        <th class="px-5 py-3.5">Tanggal</th>
                        <th class="px-5 py-3.5">Supplier</th>
                        <th class="px-5 py-3.5">Total Biaya</th>
                        <th class="px-5 py-3.5 text-center">Status</th>
                        <th class="px-5 py-3.5 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($purchases as $purchase)
                        <tr>
                            <td class="px-5 py-4 font-semibold text-[#006680]">{{ $purchase->purchase_number }}</td>
                            <td class="px-5 py-4 text-slate-600">{{ $purchase->purchase_date->format('d M Y') }}</td>
                            <td class="px-5 py-4 font-medium text-slate-800">{{ $purchase->supplier->name }}</td>
                            <td class="px-5 py-4 font-bold text-slate-900">Rp {{ number_format($purchase->total, 0, ',', '.') }}</td>
                            <td class="px-5 py-4 text-center">
                                <span class="rounded-full px-3 py-1 text-xs font-medium {{ $purchase->status === 'completed' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">{{ $purchase->status === 'completed' ? 'Selesai' : 'Menunggu Pembayaran' }}</span>
                            </td>
                            <td class="px-5 py-4 text-center">
                                <a href="{{ route('purchases.show', $purchase) }}" class="font-medium text-[#006680] hover:underline">Detail</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-5 py-12 text-center text-slate-500">Belum ada transaksi pembelian.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="flex flex-col justify-between gap-3 border-t border-slate-200 px-5 py-3 text-xs text-slate-500 sm:flex-row sm:items-center">
            <p>Menampilkan {{ $purchases->firstItem() ?? 0 }} hingga {{ $purchases->lastItem() ?? 0 }} dari {{ number_format($purchases->total(), 0, ',', '.') }} pembelian</p>
            {{ $purchases->links() }}
        </div>
    </div>
</div>
@endsection
