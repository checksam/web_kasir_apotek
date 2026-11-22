<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SupplierController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('search', ''));
        $suppliers = Supplier::query()
            ->when($search !== '', function ($query) use ($search): void {
                $query->where(function ($query) use ($search): void {
                    $query->where('name', 'like', '%'.$search.'%')
                        ->orWhere('contact_person', 'like', '%'.$search.'%')
                        ->orWhere('phone', 'like', '%'.$search.'%');
                });
            })
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('master.supplier.index', compact('suppliers', 'search'));
    }

    public function create(): View
    {
        return view('master.supplier.form', [
            'supplier' => new Supplier,
            'isEditing' => false,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $supplier = Supplier::create($this->validatedAttributes($request));

        return redirect()->route('master.supplier.index')
            ->with('status', 'Supplier '.$supplier->name.' berhasil ditambahkan.');
    }

    public function edit(Supplier $supplier): View
    {
        return view('master.supplier.form', [
            'supplier' => $supplier,
            'isEditing' => true,
        ]);
    }

    public function update(Request $request, Supplier $supplier): RedirectResponse
    {
        $supplier->update($this->validatedAttributes($request));

        return redirect()->route('master.supplier.index')
            ->with('status', 'Supplier berhasil diperbarui.');
    }

    public function destroy(Supplier $supplier): RedirectResponse
    {
        if ($supplier->purchases()->exists()) {
            return redirect()->route('master.supplier.index')
                ->withErrors(['supplier' => 'Supplier tidak dapat dihapus karena sudah memiliki riwayat pembelian.']);
        }

        $supplier->delete();

        return redirect()->route('master.supplier.index')
            ->with('status', 'Supplier berhasil dihapus.');
    }

    private function validatedAttributes(Request $request): array
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'contact_person' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:30'],
            'address' => ['required', 'string', 'max:2000'],
            'is_active' => ['required', 'boolean'],
        ]);

        return [
            ...$validated,
            'is_active' => (bool) $validated['is_active'],
        ];
    }
}
