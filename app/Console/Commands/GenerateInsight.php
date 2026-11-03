<?php

namespace App\Console\Commands;

use App\Services\AiInsightService;
use Illuminate\Console\Command;

class GenerateInsight extends Command
{
    protected $signature = 'insight:generate';
    protected $description = 'Perbarui analisis AI penjualan dan stok secara otomatis';

    public function handle(AiInsightService $ai): int
    {
        if (!$ai->enabled()) {
            $this->info('API key belum diatur, dilewati.');
            return self::SUCCESS;
        }

        foreach ([7, 30, 90] as $days) {
            if (!$ai->isStale($days)) {
                continue;
            }
            try {
                $ai->generate($days);
                $this->info("Analisis {$days} hari diperbarui.");
            } catch (\Throwable $e) {
                $this->error("Gagal ({$days} hari): " . $e->getMessage());
            }
        }

        return self::SUCCESS;
    }
}