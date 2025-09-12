<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PriceHistory extends Model
{
    use HasFactory;

    protected $table = 'price_history'; // ✅ কারণ টেবিলের নাম plural নয়

    protected $fillable = [
        'asset_id',
        'timestamp',
        'open',
        'high',
        'low',
        'close',
        'volume',
    ];

    protected $casts = [
        'timestamp' => 'datetime',
        'open' => 'decimal:8',
        'high' => 'decimal:8',
        'low' => 'decimal:8',
        'close' => 'decimal:8',
        'volume' => 'decimal:8',
    ];

    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }
}
