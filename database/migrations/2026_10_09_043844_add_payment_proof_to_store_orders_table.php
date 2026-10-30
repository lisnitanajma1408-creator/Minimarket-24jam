<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('store_orders', 'payment_proof')) {
            Schema::table('store_orders', function (Blueprint $table) {
                $table->string('payment_proof')->nullable();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('store_orders', 'payment_proof')) {
            Schema::table('store_orders', function (Blueprint $table) {
                $table->dropColumn('payment_proof');
            });
        }
    }
};