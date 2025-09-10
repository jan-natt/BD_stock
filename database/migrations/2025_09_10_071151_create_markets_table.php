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
        Schema::create('markets', function (Blueprint $table) {
            $table->id();
            $table->string('base_asset');
            $table->string('quote_asset');
            $table->enum('market_type', ['spot','margin','futures']);
            $table->decimal('min_order_size', 20, 8)->default(0);
            $table->decimal('max_order_size', 20, 8)->default(0);
            $table->decimal('fee_rate', 5, 2)->default(0);
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('markets');
    }
};
