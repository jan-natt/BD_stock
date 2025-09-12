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
    Schema::create('ipo_applications', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
        $table->foreignId('ipo_id')->constrained('ipos')->onDelete('cascade');
        $table->bigInteger('applied_shares');
        $table->decimal('total_cost',20,2);
        $table->enum('status',['pending','allocated','rejected'])->default('pending');
        $table->timestamp('applied_at');
        $table->timestamps(); // ✅ এটি যোগ করতে হবে
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ipo_applications');
    }
};
