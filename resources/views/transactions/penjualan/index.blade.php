@extends('layouts.app')

@section('title', 'Penjualan | PharmaPOS')

@section('breadcrumb', 'Home / Penjualan')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold tracking-tight text-slate-900">Penjualan</h1>
        <p class="mt-1 text-sm text-slate-500">Kelola transaksi penjualan obat dan alat kesehatan.</p>
    </div>

    <section class="rounded-lg border border-slate-200 border-t-4 border-t-[#007A87] bg-white p-6 shadow-sm">
        <h2 class="font-bold text-[#007A87]">Transaksi Penjualan</h2>
        <p class="mt-2 text-sm text-slate-500">Modul transaksi penjualan siap digunakan oleh admin dan kasir.</p>
    </section>
</div>
@endsection