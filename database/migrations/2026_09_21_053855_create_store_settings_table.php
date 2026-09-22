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
        Schema::create('store_settings', function (Blueprint $table) {
        $table->id();
        $table->string('store_name')->default('Minimarket 24 Jam');
        $table->text('address'); // dipakai buat generate embed Google Maps
        $table->string('phone')->nullable();
        $table->string('email')->nullable();
        $table->string('operating_hours')->default('24 Jam Buka'); // Senin - Minggu, 24 Jam Buka
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('store_settings');
    }
};
