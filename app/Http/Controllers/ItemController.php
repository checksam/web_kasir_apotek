<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ItemController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->string('search'));
        $category = $request->string('category')->toString();

        $items = Item::query()
            ->when($search !== '', function ($query) use ($search): void {
                $query->where(function ($query) use ($search): void {
                    $query->where('item_code', 'like', "%{$search}%")
                        ->orWhere('barcode', 'like', "%{$search}%")
                        ->orWhere('name', 'like', "%{$search}%");
                });
            })
            ->when(in_array($category, Item::CATEGORIES, true), function ($query) use ($category): void {
                $query->where('category', $category);
            })
            ->orderBy('item_code')
            ->paginate(5)
            ->withQueryString();

        return view('barang.index', [
            'items' => $items,
            'categories' => Item::CATEGORIES,
            'search' => $search,
            'category' => $category,
        ]);
    }

    public function create(): View
    {
        return view('barang.form', [
            'item' => new Item,
            'isEditing' => false,
            'categories' => Item::CATEGORIES,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        Item::create($this->validatedAttributes($request));

        return redirect()->route('barang.index')->with('status', 'Barang berhasil ditambahkan.');
    }

    public function edit(Item $item): View
    {
        return view('barang.form', [
            'item' => $item,
            'isEditing' => true,
            'categories' => Item::CATEGORIES,
        ]);
    }

    public function update(Request $request, Item $item): RedirectResponse
    {
        $item->update($this->validatedAttributes($request, $item));

        return redirect()->route('barang.index')->with('status', 'Barang berhasil diperbarui.');
    }

    public function destroy(Item $item): RedirectResponse
    {
        if ($item->batches()->exists() || $item->saleDetails()->exists() || $item->purchaseDetails()->exists()) {
            return redirect()->route('barang.index')
                ->withErrors(['item' => 'Barang tidak dapat dihapus karena memiliki riwayat batch atau transaksi.']);
        }

        $item->delete();

        return redirect()->route('barang.index')->with('status', 'Barang berhasil dihapus.');
    }

    private function validatedAttributes(Request $request, ?Item $item = null): array
    {
        $validated = $request->validate([
            'item_code' => ['required', 'string', 'max:100', Rule::unique('items', 'item_code')->ignore($item?->id)],
            'barcode' => ['nullable', 'string', 'max:100', Rule::unique('items', 'barcode')->ignore($item?->id)],
            'name' => ['required', 'string', 'max:255'],
            'category' => ['required', Rule::in(Item::CATEGORIES)],
            'unit' => ['required', 'string', 'max:50'],
            'selling_price' => ['required', 'numeric', 'min:0', 'decimal:0,2'],
            'harga_beli' => ['required', 'numeric', 'min:0', 'decimal:0,2'],
            'minimum_stock' => ['required', 'integer', 'min:0', 'max:1000000'],
            'is_controlled' => ['required', 'boolean'],
            'is_active' => ['required', 'boolean'],
        ]);

        if (! $item) {
            $validated['stock'] = 0;
        }

        return $validated;
    }
}
