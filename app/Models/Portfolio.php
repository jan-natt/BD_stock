<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Portfolio extends Model
{
    use HasFactory;

    protected $table = 'portfolio'; // ✅ কারণ টেবিলের নাম plural নয়

    protected $fillable = [
        'user_id',
        'asset_id',
        'quantity',
        'avg_buy_price',
    ];

    protected $casts = [
        'quantity' => 'decimal:8',
        'avg_buy_price' => 'decimal:8',
    ];

    // Relations
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }
}
