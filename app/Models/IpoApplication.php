<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IpoApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'ipo_id',
        'applied_shares',
        'total_cost',
        'status',
        'applied_at',
    ];

    protected $casts = [
        'applied_at' => 'datetime',
        'total_cost' => 'decimal:2',
    ];

    // Relations
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function ipo()
    {
        return $this->belongsTo(Ipo::class);
    }
}
