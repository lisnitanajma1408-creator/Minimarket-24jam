<?php

namespace App\Services;

use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Collection;

class ForecastService
{
    /** Jumlah hari data historis yang dipelajari model. */
    public const HISTORY_DAYS = 30;

    /** Jumlah hari ke depan yang diprediksi. */
    public const HORIZON = 7;

    /** Minimal jumlah hari yang punya penjualan agar prediksi dianggap bermakna. */
    public const MIN_ACTIVE_DAYS = 2;

    /** Penjualan harian per produk: [product_id => [tanggal => qty]] */
    private function dailySales(): array
    {
        $rows = OrderItem::query()
            ->join('store_orders', 'store_orders.id', '=', 'order_items.store_order_id')
            ->whereIn('store_orders.status', SalesAnalyticsService::COUNTED_STATUSES)
            ->whereNotNull('order_items.product_id')
            ->where('store_orders.created_at', '>=', now()->subDays(self::HISTORY_DAYS)->startOfDay())
            ->selectRaw('order_items.product_id, DATE(store_orders.created_at) as d, SUM(order_items.qty) as qty')
            ->groupBy('order_items.product_id', 'd')
            ->get();

        $map = [];
        foreach ($rows as $r) {
            $map[$r->product_id][$r->d] = (int) $r->qty;
        }
        return $map;
    }

    /** Regresi linear sederhana (least squares). Mengembalikan [a, b, r2]. */
    private function linearRegression(array $y): array
    {
        $n = count($y);
        $sumX = $sumY = $sumXY = $sumXX = 0.0;
        foreach ($y as $i => $v) {
            $sumX += $i;
            $sumY += $v;
            $sumXY += $i * $v;
            $sumXX += $i * $i;
        }

        $denom = ($n * $sumXX - $sumX * $sumX);
        $b = $denom == 0 ? 0.0 : ($n * $sumXY - $sumX * $sumY) / $denom;
        $a = ($sumY - $b * $sumX) / $n;

        // R2 = 1 - SSres / SStot
        $mean = $sumY / $n;
        $ssTot = $ssRes = 0.0;
        foreach ($y as $i => $v) {
            $pred = $a + $b * $i;
            $ssRes += ($v - $pred) ** 2;
            $ssTot += ($v - $mean) ** 2;
        }
        $r2 = $ssTot == 0 ? 0.0 : max(0.0, 1 - $ssRes / $ssTot);

        return [$a, $b, $r2];
    }

    /**
     * Prediksi untuk semua produk yang punya data penjualan.
     * Hasil: Collection of object (product, forecast_7d, tren, keyakinan, saran_restok, ...)
     */
    public function forecastAll(): Collection
    {
        $sales = $this->dailySales();
        if (empty($sales)) {
            return collect();
        }

        // Sumbu waktu: HISTORY_DAYS hari terakhir sampai hari ini
        $dates = [];
        for ($i = self::HISTORY_DAYS - 1; $i >= 0; $i--) {
            $dates[] = now()->subDays($i)->toDateString();
        }

        $products = Product::with('category')->whereIn('id', array_keys($sales))->get()->keyBy('id');

        $result = collect();

        foreach ($sales as $productId => $byDate) {
            $product = $products[$productId] ?? null;
            if (!$product) {
                continue;
            }

            $series = [];
            foreach ($dates as $d) {
                $series[] = $byDate[$d] ?? 0;
            }

            $activeDays = count($byDate);
            $total = array_sum($series);

            [$a, $b, $r2] = $this->linearRegression($series);

            $n = count($series);
            $daily = [];
            $forecastTotal = 0.0;
            for ($k = 0; $k < self::HORIZON; $k++) {
                $p = max(0.0, $a + $b * ($n + $k));
                $daily[] = round($p, 2);
                $forecastTotal += $p;
            }

            // Tren: kemiringan relatif terhadap rata-rata harian
            $avg = $total / $n;
            $slopeRatio = $avg > 0 ? $b / $avg : 0;
            $trend = $slopeRatio > 0.02 ? 'naik' : ($slopeRatio < -0.02 ? 'turun' : 'stabil');

            // Keyakinan berdasarkan banyak data + kecocokan model
            if ($activeDays < self::MIN_ACTIVE_DAYS) {
                $confidence = 'rendah';
            } elseif ($activeDays >= 7 && $r2 >= 0.3) {
                $confidence = 'tinggi';
            } else {
                $confidence = 'sedang';
            }

            $forecastUnits = (int) ceil($forecastTotal);
            $shortfall = max(0, $forecastUnits - (int) $product->stock);
            $avgForecast = $forecastTotal / self::HORIZON;
            $daysLeft = $avgForecast > 0 ? (int) floor($product->stock / $avgForecast) : null;

            $result->push((object) [
                'product'        => $product,
                'total_sold'     => $total,
                'active_days'    => $activeDays,
                'forecast_daily' => $daily,
                'forecast_units' => $forecastUnits,
                'trend'          => $trend,
                'confidence'     => $confidence,
                'r2'             => round($r2, 2),
                'shortfall'      => $shortfall,
                'days_left'      => $daysLeft,
                'enough_data'    => $activeDays >= self::MIN_ACTIVE_DAYS,
            ]);
        }

        // Urutkan: yang paling butuh restok dulu, lalu prediksi terbesar
        return $result->sortByDesc(fn ($r) => [$r->shortfall, $r->forecast_units])->values();
    }
}