<?php

namespace App\Console\Commands;

use App\Models\StoreOrder;
use Illuminate\Console\Command;

class ExpireOrders extends Command
{
    protected $signature = 'orders:expire';
    protected $description = 'Hanguskan pesanan yang lewat batas waktu pembayaran';

    public function handle(): int
    {
        $this->info(StoreOrder::expireOverdue() . ' pesanan dihanguskan.');
        return self::SUCCESS;
    }
}