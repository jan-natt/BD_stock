<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trade extends Model
{
    use HasFactory;

    public $timestamps = false; // কারণ trade_time আলাদা

    protected $fillable = [
        'buy_order_id',
        'sell_order_id',
        'market_id',
        'price',
        'quantity',
        'fee',
        'trade_time',
    ];

    protected $casts = [
        'price' => 'decimal:8',
        'quantity' => 'decimal:8',
        'fee' => 'decimal:8',
        'trade_time' => 'datetime',
    ];

    // রিলেশন
    public function buyOrder()
    {
        return $this->belongsTo(Order::class, 'buy_order_id');
    }

    public function sellOrder()
    {
        return $this->belongsTo(Order::class, 'sell_order_id');
    }

    public function market()
    {
        return $this->belongsTo(Market::class);
    }
}
