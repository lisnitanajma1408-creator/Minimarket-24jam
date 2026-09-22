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
        Schema::create('reviews', function (Blueprint $table) {
        $table->id();
        $table->string('customer_name');
        $table->string('role_label')->nullable(); // contoh: "Pelanggan Setia", "Ibu Rumah Tangga"
        $table->unsignedTinyInteger('rating'); // 1-5
        $table->text('comment');
        $table->string('photo')->nullable(); // foto testimoni (opsional, kayak punya Ibu Ratna)
        $table->boolean('is_published')->default(true); // admin bisa sembunyikan review kalau perlu
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
