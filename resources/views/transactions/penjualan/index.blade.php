@extends('layouts.app')

@section('title', 'POS Penjualan | PharmaPOS')
@section('breadcrumb', 'Home / Penjualan')

@section('content')
@php
    $isCashier = auth()->user()->hasRole('kasir');
    $checkoutRoute = $isCashier ? 'kasir.penjualan.checkout' : 'transactions.penjualan.checkout';
    $pageRoute = $isCashier ? 'kasir.penjualan' : 'transactions.penjualan.index';
    $vatPercent = $setting?->vat_percent ?? '11.00';
    $packagingFee = $setting?->default_packaging_fee ?? 2500;
@endphp
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold tracking-tight text-slate-900">POS Penjualan</h1>
        <p class="mt-1 text-sm text-slate-500">Cari nama/kode obat atau pindai barcode, lalu tambahkan ke keranjang.</p>
    </div>

    @if (session('status'))
        <div role="status" class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
            <p class="font-semibold">{{ session('status.message') }}</p>
            <p class="mt-1">{{ session('status.transaction_number') }} · Total Rp {{ number_format((float) session('status.total'), 2, ',', '.') }} · Kembalian Rp {{ number_format((float) session('status.change'), 2, ',', '.') }}</p>
        </div>
    @endif

    @if ($errors->any())
        <div role="alert" class="rounded-lg border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
            <ul class="list-inside list-disc">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 items-start gap-6 xl:grid-cols-[minmax(0,1.1fr)_minmax(420px,0.9fr)]">
        <section class="space-y-4 rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
            <h2 class="font-semibold text-slate-800">Cari Obat</h2>
            <form method="GET" action="{{ route($pageRoute) }}" class="flex flex-col gap-2 sm:flex-row">
                <label for="search" class="sr-only">Nama, kode, atau barcode</label>
                <input id="search" name="search" value="{{ $search }}" autofocus placeholder="Nama, kode, atau pindai barcode" class="min-w-0 flex-1 rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-[#007A87] focus:outline-none focus:ring-2 focus:ring-[#007A87]/20">
                <button type="submit" class="rounded-md bg-[#006680] px-4 py-2 text-sm font-semibold text-white hover:bg-[#004d60]">Cari</button>
            </form>

            @if ($search !== '' && $items->isEmpty())
                <p class="rounded-md bg-slate-50 p-4 text-sm text-slate-500">Obat tidak ditemukan.</p>
            @elseif ($items->isNotEmpty())
                <div class="divide-y divide-slate-100">
                    @foreach ($items as $item)
                        <div class="flex items-center justify-between gap-3 py-3">
                            <div class="min-w-0">
                                <p class="truncate text-sm font-medium text-slate-800">{{ $item->name }}</p>
                                <p class="mt-0.5 text-xs text-slate-500">{{ $item->item_code }}{{ $item->barcode ? ' · '.$item->barcode : '' }} · Stok {{ $item->sellable_stock ?? 0 }}</p>
                                <p class="text-sm font-semibold text-[#006680]">Rp {{ number_format((float) $item->selling_price, 2, ',', '.') }}</p>
                            </div>
                            <button type="button" data-item-id="{{ $item->id }}" data-item-name="{{ $item->name }}" data-item-price="{{ $item->selling_price }}" data-item-stock="{{ $item->sellable_stock ?? 0 }}" class="shrink-0 rounded-md border border-[#006680] px-3 py-2 text-xs font-semibold text-[#006680] hover:bg-slate-50 disabled:cursor-not-allowed disabled:opacity-40" @disabled(($item->sellable_stock ?? 0) < 1)>
                                Tambah
                            </button>
                        </div>
                    @endforeach
                </div>
                <p class="text-xs text-slate-500">Stok batch yang sudah kedaluwarsa tidak dapat dijual.</p>
            @else
                <p class="rounded-md bg-slate-50 p-4 text-sm text-slate-500">Masukkan nama, kode, atau barcode untuk mencari obat.</p>
            @endif
        </section>

        <form id="checkout-form" method="POST" action="{{ route($checkoutRoute) }}" class="space-y-4 rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
            @csrf
            <div class="flex items-center justify-between">
                <h2 class="font-semibold text-slate-800">Keranjang</h2>
                <button id="clear-cart" type="button" class="text-xs font-medium text-rose-600 hover:underline">Kosongkan</button>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full min-w-[380px] text-left text-sm">
                    <thead class="border-b border-slate-200 text-xs text-slate-500">
                        <tr><th class="pb-2 font-medium">Obat</th><th class="pb-2 font-medium">Qty</th><th class="pb-2 text-right font-medium">Subtotal</th><th></th></tr>
                    </thead>
                    <tbody id="cart-lines" class="divide-y divide-slate-100">
                        <tr id="empty-cart"><td colspan="4" class="py-6 text-center text-slate-400">Keranjang masih kosong.</td></tr>
                    </tbody>
                </table>
            </div>

            <div>
                <label for="customer_id" class="mb-1 block text-xs font-medium text-slate-600">Pelanggan (opsional)</label>
                <select id="customer_id" name="customer_id" class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm">
                    <option value="">Pelanggan umum</option>
                    @foreach ($customers as $customer)
                        <option value="{{ $customer->id }}" @selected(old('customer_id') == $customer->id)>{{ $customer->name }}{{ $customer->phone ? ' · '.$customer->phone : '' }}</option>
                    @endforeach
                </select>
                @error('customer_id') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                <div>
                    <label for="discount" class="mb-1 block text-xs font-medium text-slate-600">Diskon (Rp)</label>
                    <input id="discount" name="discount" type="text" inputmode="decimal" value="{{ old('discount', '0') }}" required class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm">
                    @error('discount') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="amount_paid" class="mb-1 block text-xs font-medium text-slate-600">Dibayar (Rp)</label>
                    <input id="amount_paid" name="amount_paid" type="text" inputmode="decimal" value="{{ old('amount_paid', '0') }}" required class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm">
                    @error('amount_paid') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                </div>
            </div>

            <dl class="space-y-1 border-t border-slate-100 pt-3 text-sm">
                <div class="flex justify-between"><dt class="text-slate-500">Subtotal</dt><dd id="subtotal-view">Rp 0</dd></div>
                <div class="flex justify-between"><dt class="text-slate-500">PPN ({{ number_format((float) $vatPercent, 2, ',', '.') }}%)</dt><dd id="tax-view">Rp 0</dd></div>
                <div class="flex justify-between"><dt class="text-slate-500">Embalase</dt><dd id="fee-view">Rp {{ number_format((float) $packagingFee, 2, ',', '.') }}</dd></div>
                <div class="flex justify-between text-base font-bold text-slate-900"><dt>Total</dt><dd id="total-view">Rp 0</dd></div>
                <div class="flex justify-between"><dt class="text-slate-500">Kembalian</dt><dd id="change-view">Rp 0</dd></div>
            </dl>

            <button type="submit" class="w-full rounded-md bg-[#006680] px-4 py-3 text-sm font-semibold text-white hover:bg-[#004d60]">Proses Pembayaran</button>
        </form>
    </div>
</div>

<script>
(() => {
    const cart = new Map();
    const cartLines = document.getElementById('cart-lines');
    const emptyCart = document.getElementById('empty-cart');
    const discountInput = document.getElementById('discount');
    const paidInput = document.getElementById('amount_paid');
    const vatRate = Math.round(Number(@json($vatPercent)) * 100);
    const feeCents = Math.round(Number(@json($packagingFee)) * 100);
    let paymentEdited = false;
    const rupiah = new Intl.NumberFormat('id-ID', { maximumFractionDigits: 2 });
    const format = (cents) => 'Rp ' + rupiah.format(cents / 100);
    const toCents = (value) => {
        const [whole, fraction = ''] = String(value || '0').split('.');
        return (Number.parseInt(whole, 10) || 0) * 100 + Number.parseInt((fraction + '00').slice(0, 2), 10);
    };

    const render = () => {
        cartLines.replaceChildren();
        emptyCart.hidden = cart.size > 0;
        if (cart.size === 0) cartLines.append(emptyCart);

        let subtotal = 0;
        let index = 0;
        for (const [id, item] of cart) {
            subtotal += item.price * item.quantity;
            const row = document.createElement('tr');
            const nameCell = document.createElement('td');
            nameCell.className = 'py-3 pr-2';
            const name = document.createElement('span');
            name.className = 'block max-w-[180px] truncate font-medium';
            name.textContent = item.name;
            const itemId = document.createElement('input');
            itemId.type = 'hidden';
            itemId.name = `items[${index}][item_id]`;
            itemId.value = id;
            nameCell.append(name, itemId);

            const quantityCell = document.createElement('td');
            quantityCell.className = 'py-3';
            const quantityInput = document.createElement('input');
            quantityInput.type = 'number';
            quantityInput.name = `items[${index}][quantity]`;
            quantityInput.min = '1';
            quantityInput.max = String(item.stock);
            quantityInput.value = String(item.quantity);
            quantityInput.setAttribute('aria-label', `Jumlah ${item.name}`);
            quantityInput.className = 'w-16 rounded border border-slate-300 px-2 py-1 text-sm';
            quantityCell.append(quantityInput);

            const subtotalCell = document.createElement('td');
            subtotalCell.className = 'py-3 text-right whitespace-nowrap';
            subtotalCell.textContent = format(item.price * item.quantity);

            const actionCell = document.createElement('td');
            actionCell.className = 'py-3 pl-2 text-right';
            const removeButton = document.createElement('button');
            removeButton.type = 'button';
            removeButton.className = 'text-rose-600 hover:underline';
            removeButton.textContent = '×';
            removeButton.setAttribute('aria-label', `Hapus ${item.name}`);
            actionCell.append(removeButton);
            row.append(nameCell, quantityCell, subtotalCell, actionCell);

            quantityInput.addEventListener('change', () => {
                item.quantity = Math.max(1, Math.min(item.stock, Number.parseInt(quantityInput.value, 10) || 1));
                render();
            });
            removeButton.addEventListener('click', () => {
                cart.delete(id);
                render();
            });
            cartLines.append(row);
            index++;
        }

        const discount = Math.min(subtotal, toCents(discountInput.value));
        const taxable = subtotal - discount;
        const tax = Math.floor((taxable * vatRate + 5000) / 10000);
        const total = taxable + tax + feeCents;
        if (!paymentEdited) paidInput.value = String(Math.ceil(total / 100));
        const paid = toCents(paidInput.value);
        document.getElementById('subtotal-view').textContent = format(subtotal);
        document.getElementById('tax-view').textContent = format(tax);
        document.getElementById('total-view').textContent = format(total);
        document.getElementById('change-view').textContent = format(Math.max(0, paid - total));
    };

    document.querySelectorAll('[data-item-id]').forEach((button) => {
        button.addEventListener('click', () => {
            const id = button.dataset.itemId;
            const stock = Number.parseInt(button.dataset.itemStock, 10);
            const item = cart.get(id) || {
                name: button.dataset.itemName,
                price: toCents(button.dataset.itemPrice),
                stock,
                quantity: 0,
            };
            if (item.quantity < stock) item.quantity++;
            cart.set(id, item);
            render();
        });
    });

    discountInput.addEventListener('input', render);
    paidInput.addEventListener('input', () => {
        paymentEdited = true;
        render();
    });
    document.getElementById('clear-cart').addEventListener('click', () => {
        cart.clear();
        render();
    });
    document.getElementById('checkout-form').addEventListener('submit', (event) => {
        if (cart.size === 0) {
            event.preventDefault();
            window.alert('Keranjang masih kosong.');
        }
    });
    render();
})();
</script>
@endsection