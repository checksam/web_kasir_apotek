<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'pharmacy_name',
        'address',
        'phone',
        'official_email',
        'receipt_footer',
        'show_pharmacist_name',
        'vat_percent',
        'default_packaging_fee',
    ];

    protected function casts(): array
    {
        return [
            'show_pharmacist_name' => 'boolean',
            'vat_percent' => 'decimal:2',
            'default_packaging_fee' => 'integer',
        ];
    }
}