<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BatchObat extends Model
{
    protected $table = 'batch_obat';

    protected $fillable = [
        'item_id',
        'batch_number',
        'quantity',
        'received_at',
        'expiration_date',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'integer',
            'received_at' => 'date',
            'expiration_date' => 'date',
        ];
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    public function saleDetails(): HasMany
    {
        return $this->hasMany(DetailPenjualan::class);
    }
}
