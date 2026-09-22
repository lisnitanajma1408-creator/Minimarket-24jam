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
        Schema::create('store_orders', function (Blueprint $table) {
        $table->id();
        $table->string('order_number')->unique(); // contoh: FM-2026-00128
        $table->string('customer_name');
        $table->string('customer_email');
        $table->text('address_detail'); // alamat + no HP jadi satu textarea
        $table->string('postal_code')->nullable();
        $table->enum('shipping_method', ['ojek_online', 'ambil_toko']);
        $table->enum('payment_method', ['qris', 'cod']);
        $table->unsignedBigInteger('subtotal');
        $table->unsignedBigInteger('shipping_cost')->default(0);
        $table->unsignedBigInteger('total');
        $table->enum('status', ['pending', 'diproses', 'selesai', 'dibatalkan'])->default('pending');
        $table->boolean('send_receipt_email')->default(true); // toggle "Kirim struk ke email"
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('store_orders');
    }
};
