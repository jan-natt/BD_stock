<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ipo extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_name',
        'symbol',
        'issue_manager_id',
        'price_per_share',
        'total_shares',
        'available_shares',
        'ipo_start',
        'ipo_end',
        'status',
    ];

    protected $casts = [
        'ipo_start' => 'datetime',
        'ipo_end'   => 'datetime',
        'price_per_share' => 'decimal:2',
    ];

    // Relation (যদি IssueManager টেবিল থাকে)
    public function issueManager()
    {
        return $this->belongsTo(User::class, 'issue_manager_id');
    }
}
