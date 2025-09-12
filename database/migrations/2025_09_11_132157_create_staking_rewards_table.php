<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('staking_rewards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('staking_pool_id')->constrained('staking_pools')->cascadeOnDelete();
            $table->decimal('reward_amount', 20, 8);
            $table->timestamp('distributed_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('staking_rewards');
    }
};
