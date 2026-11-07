<?php

namespace App\Http\Controllers;

use App\Models\BatchObat;
use App\Models\Customer;
use App\Models\Item;
use App\Models\Penjualan;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class PenjualanController extends Controller
{
    public function index(Request $request): View
    {
        $validated = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
        ]);
        $search = trim($validated['search'] ?? '');

        $items = Item::query()
            ->withSum([
                'batches as sellable_stock' => function ($query): void {
                    $query->where(function ($query): void {
                        $query->whereNull('expiration_date')
                            ->orWhereDate('expiration_date', '>=', today());
                    });
                },
            ], 'quantity')
            ->when($search !== '', function ($query) use ($search): void {
                $query->where(function ($query) use ($search): void {
                    $query->where('name', 'like', '%'.$search.'%')
                        ->orWhere('item_code', 'like', '%'.$search.'%')
                        ->orWhere('barcode', $search);
                });
            })
            ->orderBy('name')
            ->limit(30)
            ->get();

        return view('transactions.penjualan.index', [
            'items' => $items,
            'search' => $search,
            'customers' => Customer::query()->orderBy('name')->limit(200)->get(),
            'setting' => Setting::query()->first(),
        ]);
    }

    public function checkout(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'items' => ['required', 'array', 'min:1'],
            'items.*' => ['required', 'array:item_id,quantity'],
            'items.*.item_id' => ['required', 'integer', 'distinct', 'exists:items,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1', 'max:100000'],
            'customer_id' => ['nullable', 'integer', 'exists:customers,id'],
            'discount' => ['required', 'numeric', 'min:0', 'max:9999999999999', 'regex:/^\d+(?:\.\d{1,2})?$/'],
            'amount_paid' => ['required', 'numeric', 'min:0', 'max:9999999999999', 'regex:/^\d+(?:\.\d{1,2})?$/'],
        ]);

        $sale = DB::transaction(function () use ($validated, $request): Penjualan {
            $quantities = collect($validated['items'])
                ->mapWithKeys(fn (array $line): array => [(int) $line['item_id'] => (int) $line['quantity']]);

            $items = Item::query()
                ->whereIn('id', $quantities->keys())
                ->orderBy('id')
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            if ($items->count() !== $quantities->count()) {
                throw ValidationException::withMessages([
                    'items' => 'Salah satu obat tidak lagi tersedia.',
                ]);
            }

            $allocations = [];
            $subtotalCents = 0;
            $batchesByItem = [];

            foreach ($quantities as $itemId => $quantity) {
                $item = $items->get($itemId);
                $batches = BatchObat::query()
                    ->where('item_id', $itemId)
                    ->where(function ($query): void {
                        $query->whereNull('expiration_date')
                            ->orWhereDate('expiration_date', '>=', today());
                    })
                    ->orderBy('received_at')
                    ->orderBy('id')
                    ->lockForUpdate()
                    ->get();
                $available = $batches->sum('quantity');

                if ($available < $quantity) {
                    throw ValidationException::withMessages([
                        'items' => "Stok {$item->name} tidak mencukupi. Tersedia {$available}, diminta {$quantity}.",
                    ]);
                }

                $batchesByItem[$itemId] = $batches;
                $remaining = $quantity;
                $unitPriceCents = self::decimalToCents($item->selling_price);

                foreach ($batches as $batch) {
                    if ($remaining === 0) {
                        break;
                    }

                    $taken = min($remaining, $batch->quantity);
                    if ($taken === 0) {
                        continue;
                    }

                    $allocations[] = [
                        'item_id' => $item->id,
                        'batch' => $batch,
                        'quantity' => $taken,
                        'unit_price_cents' => $unitPriceCents,
                    ];
                    $subtotalCents += $unitPriceCents * $taken;
                    $remaining -= $taken;
                }
            }

            $discountCents = self::decimalToCents($validated['discount']);
            if ($discountCents > $subtotalCents) {
                throw ValidationException::withMessages([
                    'discount' => 'Diskon tidak boleh melebihi subtotal.',
                ]);
            }

            $setting = Setting::query()->first();
            $vatRateHundredths = self::decimalToScaledInteger($setting?->vat_percent ?? '11', 2);
            $taxableCents = $subtotalCents - $discountCents;
            $taxCents = (intdiv($taxableCents, 10000) * $vatRateHundredths)
                + intdiv((($taxableCents % 10000) * $vatRateHundredths) + 5000, 10000);
            $packagingCents = (int) ($setting?->default_packaging_fee ?? 2500) * 100;
            $totalCents = $taxableCents + $taxCents + $packagingCents;
            $amountPaidCents = self::decimalToCents($validated['amount_paid']);

            if ($amountPaidCents < $totalCents) {
                throw ValidationException::withMessages([
                    'amount_paid' => 'Jumlah pembayaran kurang dari total transaksi.',
                ]);
            }

            $sale = Penjualan::create([
                'transaction_number' => 'SALE-'.Str::upper((string) Str::ulid()),
                'user_id' => $request->user()->id,
                'customer_id' => $validated['customer_id'] ?? null,
                'subtotal' => self::centsToDecimal($subtotalCents),
                'discount' => self::centsToDecimal($discountCents),
                'tax' => self::centsToDecimal($taxCents),
                'packaging_fee' => self::centsToDecimal($packagingCents),
                'total' => self::centsToDecimal($totalCents),
                'amount_paid' => self::centsToDecimal($amountPaidCents),
                'change' => self::centsToDecimal($amountPaidCents - $totalCents),
                'status' => 'completed',
            ]);

            foreach ($allocations as $allocation) {
                $batch = $allocation['batch'];
                $batch->decrement('quantity', $allocation['quantity']);

                $sale->details()->create([
                    'item_id' => $allocation['item_id'],
                    'batch_obat_id' => $batch->id,
                    'quantity' => $allocation['quantity'],
                    'unit_price' => self::centsToDecimal($allocation['unit_price_cents']),
                    'subtotal' => self::centsToDecimal($allocation['unit_price_cents'] * $allocation['quantity']),
                ]);
            }

            foreach ($items as $itemId => $item) {
                $item->update(['stock' => $batchesByItem[$itemId]->sum('quantity')]);
            }

            return $sale;
        });

        $destination = $request->user()->hasRole('kasir')
            ? 'kasir.penjualan'
            : 'transactions.penjualan.index';

        return redirect()->route($destination)->with('status', [
            'message' => 'Transaksi berhasil disimpan.',
            'transaction_number' => $sale->transaction_number,
            'total' => $sale->total,
            'change' => $sale->change,
        ]);
    }

    private static function decimalToCents(string|int|float $value): int
    {
        $decimal = (string) $value;
        [$whole, $fraction] = array_pad(explode('.', $decimal, 2), 2, '');

        return ((int) $whole * 100) + (int) str_pad(substr($fraction, 0, 2), 2, '0');
    }

    private static function decimalToScaledInteger(string|int|float $value, int $scale): int
    {
        $decimal = (string) $value;
        [$whole, $fraction] = array_pad(explode('.', $decimal, 2), 2, '');

        return ((int) $whole * (10 ** $scale))
            + (int) str_pad(substr($fraction, 0, $scale), $scale, '0');
    }

    private static function centsToDecimal(int $cents): string
    {
        return intdiv($cents, 100).'.'.str_pad((string) ($cents % 100), 2, '0', STR_PAD_LEFT);
    }
}
