<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'market_id',
        'order_type',
        'order_kind',
        'price',
        'quantity',
        'filled_quantity',
        'status',
    ];

    protected $casts = [
        'price' => 'decimal:8',
        'quantity' => 'decimal:8',
        'filled_quantity' => 'decimal:8',
    ];

    // রিলেশন
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function market()
    {
        return $this->belongsTo(Market::class);
    }
}
