<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    public const CATEGORIES = [
        'Tablet',
        'Kapsul',
        'Sirup',
        'Alat Kesehatan',
        'Suplemen',
    ];

    protected $fillable = [
        'item_code',
        'name',
        'category',
        'selling_price',
        'stock',
    ];

    protected function casts(): array
    {
        return [
            'selling_price' => 'decimal:2',
            'stock' => 'integer',
        ];
    }

    public function getStockStatusAttribute(): array
    {
        return match (true) {
            $this->stock === 0 => [
                'label' => 'Habis',
                'class' => 'bg-slate-200 text-slate-600',
            ],
            $this->stock <= 15 => [
                'label' => 'Stok Menipis',
                'class' => 'bg-rose-100 text-rose-700',
            ],
            default => [
                'label' => 'Tersedia',
                'class' => 'bg-emerald-100 text-emerald-700',
            ],
        };
    }
}
