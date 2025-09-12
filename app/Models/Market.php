<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Market extends Model
{
    use HasFactory;

    protected $fillable = [
        'base_asset',
        'quote_asset',
        'market_type',
        'min_order_size',
        'max_order_size',
        'fee_rate',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
        'min_order_size' => 'decimal:8',
        'max_order_size' => 'decimal:8',
        'fee_rate' => 'decimal:2',
    ];
}
