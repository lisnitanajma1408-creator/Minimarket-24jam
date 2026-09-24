<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('store_orders', function (Blueprint $table) {
            $table->string('payment_reference')->nullable()->after('payment_method');
            $table->timestamp('payment_deadline')->nullable()->after('payment_reference');
            $table->timestamp('paid_at')->nullable()->after('payment_deadline');
        });

        DB::statement("ALTER TABLE store_orders MODIFY COLUMN status ENUM('pending', 'menunggu_verifikasi', 'diproses', 'selesai', 'dibatalkan', 'kadaluarsa') DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('store_orders', function (Blueprint $table) {
            $table->dropColumn(['payment_reference', 'payment_deadline', 'paid_at']);
        });

        DB::statement("ALTER TABLE store_orders MODIFY COLUMN status ENUM('pending', 'diproses', 'selesai', 'dibatalkan') DEFAULT 'pending'");
    }
};