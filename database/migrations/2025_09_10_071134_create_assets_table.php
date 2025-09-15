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
        Schema::create('assets', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
        $table->string('symbol');
        $table->string('name');
        $table->enum('type', ['stock', 'crypto', 'forex', 'commodity', 'ipo']);
        $table->integer('precision')->default(8);
        $table->boolean('status')->default(true);
        $table->timestamps();
        
        // Add indexes
        $table->index('symbol');
        $table->index('user_id');
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assets');
    }
};
