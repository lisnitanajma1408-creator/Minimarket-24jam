<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AiInsightService
{
    /** Analisis Claude dianggap basi setelah N jam, lalu dibuat ulang otomatis. */
    public const STALE_HOURS = 6;

    public function __construct(private SalesAnalyticsService $analytics)
    {
    }

    public function enabled(): bool
    {
        return filled(config('services.anthropic.key'));
    }

    private function key(int $days): string
    {
        return "insight.ai.{$days}";
    }

    public function cached(int $days): ?array
    {
        return Cache::get($this->key($days));
    }

    public function isStale(int $days): bool
    {
        $c = $this->cached($days);
        if (!$c) {
            return true;
        }
        return Carbon::parse($c['at'])->diffInHours(now()) >= self::STALE_HOURS;
    }

    /** Ringkasan berbasis aturan: otomatis, gratis, tanpa API. */
    public function ruleBased(int $days): array
    {
        $t = $this->analytics->totals($days);
        $best = $this->analytics->bestSellers($days, 3);
        $low = $this->analytics->lowStock($days);
        $lines = [];

        if ($t['orders'] === 0) {
            $lines[] = "Belum ada penjualan terkonfirmasi dalam {$days} hari terakhir, jadi analisis terlaris belum bisa dibuat.";
        } else {
            $lines[] = "Dalam {$days} hari terakhir ada {$t['orders']} pesanan terkonfirmasi dengan {$t['units']} unit terjual, pendapatan Rp "
                . number_format($t['revenue'], 0, ',', '.') . ' dan estimasi laba Rp ' . number_format($t['profit'], 0, ',', '.') . '.';
            if ($best->isNotEmpty()) {
                $names = $best->map(fn ($b) => "{$b->product_name} ({$b->total_qty} unit)")->implode(', ');
                $lines[] = "Produk terlaris: {$names}.";
            }
            if ($t['revenue'] > 0 && $t['profit'] === $t['revenue']) {
                $lines[] = 'Harga modal belum diisi, jadi estimasi laba sama dengan pendapatan. Isi Harga Modal di data produk.';
            }
        }

        $critical = $low->where('urgency', 'kritis');
        if ($low->isEmpty()) {
            $lines[] = 'Semua stok masih di atas batas minimum.';
        } else {
            $lines[] = "{$low->count()} produk perlu restok, {$critical->count()} di antaranya kritis (habis atau diperkirakan habis dalam 3 hari).";
            foreach ($critical->take(5) as $p) {
                $lines[] = "- Restok {$p->name}: stok {$p->stock}, saran tambah {$p->suggested_restock}.";
            }
        }

        return ['text' => implode("\n", $lines), 'at' => now()->toIso8601String(), 'source' => 'aturan'];
    }

    /** Panggil Claude API, simpan di cache. Melempar \RuntimeException jika gagal. */
    public function generate(int $days): array
    {
        $apiKey = config('services.anthropic.key');
        if (blank($apiKey)) {
            throw new \RuntimeException('API key belum diatur di file .env.');
        }

        $totals = $this->analytics->totals($days);

        $best = $this->analytics->bestSellers($days, 10)->map(fn ($r) => [
            'produk' => $r->product_name,
            'terjual' => (int) $r->total_qty,
            'pendapatan' => (int) $r->revenue,
            'laba' => (int) $r->profit,
        ])->values();

        $low = $this->analytics->lowStock($days)->take(20)->map(fn ($p) => [
            'produk' => $p->name,
            'stok' => (int) $p->stock,
            'batas_minimum' => (int) $p->min_stock,
            'perkiraan_habis_hari' => $p->days_left,
            'saran_restok' => (int) $p->suggested_restock,
        ])->values();

        $system = 'Kamu adalah analis operasional untuk minimarket 24 jam. '
            . 'Jawab dalam Bahasa Indonesia yang ringkas dan praktis. '
            . 'Gunakan HANYA angka pada data yang diberikan; jangan mengarang angka. '
            . 'Jika data penjualan kosong atau sedikit, katakan jujur bahwa kesimpulan masih terbatas.';

        $prompt = "Periode analisis: {$days} hari terakhir.\n\n"
            . 'Ringkasan: ' . json_encode($totals) . "\n\n"
            . 'Produk terlaris: ' . json_encode($best, JSON_UNESCAPED_UNICODE) . "\n\n"
            . 'Produk stok menipis/habis: ' . json_encode($low, JSON_UNESCAPED_UNICODE) . "\n\n"
            . 'Buat: (1) ringkasan kondisi penjualan 2-3 kalimat, '
            . '(2) daftar prioritas restok beserta alasannya, '
            . '(3) 2-3 saran meningkatkan keuntungan. Maksimal 250 kata.';

        try {
            $response = Http::withHeaders([
                'x-api-key' => $apiKey,
                'anthropic-version' => '2023-06-01',
                'content-type' => 'application/json',
            ])->timeout(60)->post('https://api.anthropic.com/v1/messages', [
                'model' => config('services.anthropic.model'),
                'max_tokens' => 1000,
                'system' => $system,
                'messages' => [['role' => 'user', 'content' => $prompt]],
            ]);
        } catch (\Throwable $e) {
            Log::warning('AI insight gagal terhubung: ' . $e->getMessage());
            throw new \RuntimeException('Tidak bisa terhubung ke layanan AI.');
        }

        if ($response->failed()) {
            Log::warning('AI insight error ' . $response->status() . ': ' . $response->body());
            throw new \RuntimeException('Layanan AI menolak permintaan (kode ' . $response->status() . '). Cek API key dan saldo.');
        }

        $text = data_get($response->json(), 'content.0.text');
        if (blank($text)) {
            throw new \RuntimeException('Layanan AI tidak memberi jawaban.');
        }

        $result = ['text' => $text, 'at' => now()->toIso8601String(), 'source' => 'ai'];
        Cache::put($this->key($days), $result, now()->addDays(2));

        return $result;
    }
}