<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    use HasFactory;

    protected $fillable = [
        'method_name',
        'details',
        'status',
    ];

    protected $casts = [
        'details' => 'array', // JSON কে array হিসেবে cast করবে
        'status' => 'boolean',
    ];
}
