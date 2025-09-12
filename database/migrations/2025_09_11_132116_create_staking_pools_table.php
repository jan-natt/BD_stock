<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('staking_pools', function (Blueprint $table) {
            $table->id(); // unsignedBigInteger (AUTO_INCREMENT)
            $table->string('name');
            $table->decimal('apy', 5, 2); // Annual Percentage Yield
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('staking_pools');
    }
};
