<?php

namespace App\Http\Controllers;

use App\Models\BatchObat;
use App\Models\Item;
use App\Models\Pembelian;
use App\Models\Supplier;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class PembelianController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('search', ''));
        $purchases = Pembelian::query()
            ->with('supplier')
            ->when($search !== '', function ($query) use ($search): void {
                $query->where(function ($query) use ($search): void {
                    $query->where('purchase_number', 'like', '%'.$search.'%')
                        ->orWhereHas('supplier', fn ($supplierQuery) => $supplierQuery->where('name', 'like', '%'.$search.'%'));
                });
            })
            ->latest('purchase_date')
            ->latest('id')
            ->paginate(10)
            ->withQueryString();

        return view('transactions.pembelian.index', [
            'purchases' => $purchases,
            'search' => $search,
            'monthlyTotal' => Pembelian::where('status', 'completed')
                ->whereBetween('purchase_date', [now()->startOfMonth()->toDateString(), now()->endOfMonth()->toDateString()])
                ->sum('total'),
            'pendingCount' => Pembelian::where('status', 'pending')->count(),
            'receivedCount' => Pembelian::where('status', 'completed')
                ->whereDate('purchase_date', '>=', today()->subDays(30))
                ->count(),
        ]);
    }

    public function create(): View
    {
        return view('transactions.pembelian.form', [
            'suppliers' => Supplier::where('is_active', true)->orderBy('name')->get(),
            'items' => Item::where('is_active', true)->orderBy('name')->get(),
        ]);
    }

    public function show(Pembelian $purchase): View
    {
        $purchase->load(['supplier', 'details.item']);

        return view('transactions.pembelian.show', compact('purchase'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'supplier_id' => ['required', 'integer', Rule::exists('suppliers', 'id')->where('is_active', true)],
            'purchase_date' => ['required', 'date'],
            'purchase_number' => ['required', 'string', 'max:100', 'unique:pembelian,purchase_number'],
            'status' => ['required', Rule::in(['completed', 'pending'])],
            'items' => ['required', 'array', 'min:1'],
            'items.*.item_id' => ['required', 'integer', Rule::exists('items', 'id')->where('is_active', true)],
            'items.*.quantity' => ['required', 'integer', 'min:1', 'max:1000000'],
            'items.*.unit_price' => ['required', 'numeric', 'min:0', 'decimal:0,2'],
            'items.*.discount' => ['required', 'numeric', 'min:0', 'decimal:0,2'],
            'items.*.batch_number' => ['required', 'string', 'max:100', 'distinct', 'unique:detail_pembelian,batch_number', 'unique:batch_obat,batch_number'],
            'items.*.expiration_date' => ['nullable', 'date', 'after_or_equal:purchase_date'],
        ]);

        $totalCents = 0;
        foreach ($validated['items'] as $index => $line) {
            $grossCents = self::toCents($line['unit_price']) * (int) $line['quantity'];
            $discountCents = self::toCents($line['discount']);
            if ($discountCents > $grossCents) {
                throw ValidationException::withMessages([
                    "items.{$index}.discount" => 'Diskon tidak boleh melebihi total harga barang.',
                ]);
            }
            $validated['items'][$index]['subtotal_cents'] = $grossCents - $discountCents;
            $totalCents += $grossCents - $discountCents;
        }

        DB::transaction(function () use ($validated, $request, $totalCents): void {
            $purchase = Pembelian::create([
                'supplier_id' => $validated['supplier_id'],
                'user_id' => $request->user()->id,
                'purchase_number' => $validated['purchase_number'],
                'purchase_date' => $validated['purchase_date'],
                'total' => self::fromCents($totalCents),
                'status' => $validated['status'],
            ]);

            foreach ($validated['items'] as $line) {
                $purchase->details()->create([
                    'item_id' => $line['item_id'],
                    'batch_number' => $line['batch_number'],
                    'expiration_date' => $line['expiration_date'] ?? null,
                    'quantity' => $line['quantity'],
                    'unit_price' => $line['unit_price'],
                    'discount' => $line['discount'],
                    'subtotal' => self::fromCents($line['subtotal_cents']),
                ]);

                if ($validated['status'] === 'completed') {
                    BatchObat::create([
                        'item_id' => $line['item_id'],
                        'batch_number' => $line['batch_number'],
                        'quantity' => $line['quantity'],
                        'received_at' => $validated['purchase_date'],
                        'expiration_date' => $line['expiration_date'] ?? null,
                    ]);

                    Item::whereKey($line['item_id'])->increment('stock', $line['quantity']);
                    Item::whereKey($line['item_id'])->update(['harga_beli' => $line['unit_price']]);
                }
            }
        });

        return redirect()->route('transactions.pembelian.index')
            ->with('status', 'Pembelian berhasil disimpan'.($validated['status'] === 'completed' ? ' dan stok telah diperbarui.' : '.'));
    }

    private static function toCents(string|int|float $amount): int
    {
        return (int) round((float) $amount * 100);
    }

    private static function fromCents(int $amount): string
    {
        return number_format($amount / 100, 2, '.', '');
    }
}
