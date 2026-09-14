<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
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
}
