<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ipo_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ipo_id')->constrained('ipos')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->integer('shares_subscribed');
            $table->integer('shares_allocated')->nullable();
            $table->decimal('amount_paid', 10, 2);
            $table->enum('status', ['subscribed', 'allocated', 'refunded'])->default('subscribed');
            $table->timestamps();

            $table->unique(['ipo_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ipo_subscriptions');
    }
};
