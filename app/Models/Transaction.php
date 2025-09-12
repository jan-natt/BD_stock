<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'wallet_id',
        'type',
        'amount',
        'fee',
        'status',
        'transaction_hash',
    ];

    // Relation to User
    public function user() {
        return $this->belongsTo(User::class);
    }

    // Relation to Wallet
    public function wallet() {
        return $this->belongsTo(Wallet::class);
    }
}
