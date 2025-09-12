<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StakingReward extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'staking_pool_id',
        'reward_amount',
        'distributed_at',
    ];

    // Relation to User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relation to StakingPool
    public function stakingPool()
    {
        return $this->belongsTo(StakingPool::class);
    }
}
