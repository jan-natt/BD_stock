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
         Schema::create('ipos', function (Blueprint $table) {
            $table->id();
            $table->string('company_name');
            $table->string('symbol')->unique();
            $table->unsignedBigInteger('issue_manager_id');
            $table->decimal('price_per_share', 20, 2);
            $table->bigInteger('total_shares');
            $table->bigInteger('available_shares');
            $table->timestamp('ipo_start')->nullable();
            $table->timestamp('ipo_end')->nullable();
            $table->enum('status',['open','closed','cancelled'])->default('open');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ipos');
    }
};
