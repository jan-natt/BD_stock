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
         Schema::create('trades', function (Blueprint $table) {
    $table->id();
    $table->foreignId('buy_order_id')->constrained('orders')->onDelete('cascade');
    $table->foreignId('sell_order_id')->constrained('orders')->onDelete('cascade');
    $table->foreignId('market_id')->constrained('markets')->onDelete('cascade');
    $table->foreignId('buyer_id')->constrained('users')->onDelete('cascade'); // Add this
    $table->foreignId('seller_id')->nullable()->constrained('users')->onDelete('cascade'); // Add this
    $table->decimal('price', 20, 8);
    $table->decimal('quantity', 20, 8);
    $table->decimal('fee', 20, 8)->default(0);
    $table->timestamp('trade_time');

    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trades');
    }
};
