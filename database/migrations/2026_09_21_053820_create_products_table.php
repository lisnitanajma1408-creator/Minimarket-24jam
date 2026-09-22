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
        Schema::create('products', function (Blueprint $table) {
        $table->id();
        $table->foreignId('category_id')->constrained()->onDelete('cascade');
        $table->string('name');
        $table->string('sku')->unique();
        $table->string('image')->nullable(); // path foto produk
        $table->unsignedBigInteger('price');  // simpan dalam Rupiah bulat, misal 45000
        $table->string('unit')->nullable();   // contoh: "per 500g", "per 1kg", "12 Butir/Pack"
        $table->unsignedInteger('stock')->default(0);
        $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
