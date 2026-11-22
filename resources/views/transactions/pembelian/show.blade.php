@extends('layouts.app')

@section('title', 'Detail Pembelian | PharmaPOS')
@section('breadcrumb', 'Home / Transaksi Pembelian / Detail')

@section('content')
<div class="mx-auto max-w-5xl space-y-5">
    <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Detail Pembelian</h1>
            <p class="mt-1 text-sm text-slate-600">Nota {{ $purchase->purchase_number }}</p>
        </div>
        <a href="{{ route('transactions.pembelian.index') }}" class="text-sm font-medium text-[#006680] hover:underline">Kembali ke daftar pembelian</a>
    </div>

    <section class="grid gap-4 rounded-lg border border-slate-200 bg-white p-5 shadow-sm sm:grid-cols-2 lg:grid-cols-4" aria-label="Informasi pembelian">
        <div>
            <p class="text-xs font-medium uppercase text-slate-500">Supplier</p>
            <p class="mt-1 font-semibold text-slate-900">{{ $purchase->supplier->name }}</p>
        </div>
        <div>
            <p class="text-xs font-medium uppercase text-slate-500">Tanggal Pembelian</p>
            <p class="mt-1 font-semibold text-slate-900">{{ $purchase->purchase_date->format('d M Y') }}</p>
        </div>
        <div>
            <p class="text-xs font-medium uppercase text-slate-500">Status</p>
            <p class="mt-1 font-semibold text-slate-900">{{ $purchase->status === 'completed' ? 'Selesai' : 'Menunggu Pembayaran' }}</p>
        </div>
        <div>
            <p class="text-xs font-medium uppercase text-slate-500">Total Nota</p>
            <p class="mt-1 font-bold text-slate-900">Rp {{ number_format($purchase->total, 2, ',', '.') }}</p>
        </div>
    </section>

    <section class="overflow-hidden rounded-lg border border-slate-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full min-w-[760px] text-left text-sm">
                <thead class="border-b border-slate-200 bg-slate-50 text-xs font-bold uppercase tracking-wide text-slate-700">
                    <tr>
                        <th class="px-5 py-3.5">Barang</th>
                        <th class="px-5 py-3.5">No. Batch</th>
                        <th class="px-5 py-3.5 text-right">Jumlah</th>
                        <th class="px-5 py-3.5 text-right">Harga Beli</th>
                        <th class="px-5 py-3.5 text-right">Diskon</th>
                        <th class="px-5 py-3.5 text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($purchase->details as $detail)
                        <tr>
                            <td class="px-5 py-4">
                                <p class="font-semibold text-slate-900">{{ $detail->item->name }}</p>
                                <p class="mt-0.5 text-xs text-slate-500">{{ $detail->item->item_code }}</p>
                            </td>
                            <td class="px-5 py-4 text-slate-600">
                                {{ $detail->batch_number }}
                                @if ($detail->expiration_date)
                                    <p class="mt-0.5 text-xs text-slate-500">ED {{ $detail->expiration_date->format('d M Y') }}</p>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-right text-slate-700">{{ number_format($detail->quantity, 0, ',', '.') }} {{ $detail->item->unit }}</td>
                            <td class="px-5 py-4 text-right text-slate-700">Rp {{ number_format($detail->unit_price, 2, ',', '.') }}</td>
                            <td class="px-5 py-4 text-right text-slate-700">Rp {{ number_format($detail->discount, 2, ',', '.') }}</td>
                            <td class="px-5 py-4 text-right font-semibold text-slate-900">Rp {{ number_format($detail->subtotal, 2, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-5 py-10 text-center text-slate-500">Nota ini belum memiliki rincian barang.</td></tr>
                    @endforelse
                </tbody>
                <tfoot class="border-t border-slate-200 bg-slate-50">
                    <tr>
                        <th colspan="5" class="px-5 py-4 text-right text-sm font-bold text-slate-700">Total Nota</th>
                        <td class="px-5 py-4 text-right text-base font-bold text-slate-900">Rp {{ number_format($purchase->total, 2, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </section>
</div>
@endsection
