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
         Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('market_id')->constrained('markets')->onDelete('cascade');
            $table->enum('order_type', ['buy','sell']);
            $table->enum('order_kind', ['limit','market','stop-loss','take-profit']);
            $table->decimal('price', 20, 8)->nullable();
            $table->decimal('quantity', 20, 8);
            $table->decimal('filled_quantity', 20, 8)->default(0);
            $table->enum('status', ['open','filled','partial','cancelled'])->default('open');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
