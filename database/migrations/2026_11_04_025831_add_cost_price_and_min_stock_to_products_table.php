<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'cost_price')) {
                $table->unsignedBigInteger('cost_price')->default(0);
            }
            if (!Schema::hasColumn('products', 'min_stock')) {
                $table->unsignedInteger('min_stock')->default(15);
            }
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'cost_price')) {
                $table->dropColumn('cost_price');
            }
            if (Schema::hasColumn('products', 'min_stock')) {
                $table->dropColumn('min_stock');
            }
        });
    }
};