<?php

namespace App\Services;

use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Collection;

class SalesAnalyticsService
{
    /** Hanya pesanan yang sudah diterima/dikonfirmasi admin dihitung sebagai penjualan. */
    public const COUNTED_STATUSES = ['selesai'];

    private function baseQuery(int $days)
    {
        return OrderItem::query()
            ->join('store_orders', 'store_orders.id', '=', 'order_items.store_order_id')
            ->leftJoin('products', 'products.id', '=', 'order_items.product_id')
            ->whereIn('store_orders.status', self::COUNTED_STATUSES)
            ->where('store_orders.created_at', '>=', now()->subDays($days));
    }

    public function totals(int $days = 30): array
    {
        $row = $this->baseQuery($days)
            ->selectRaw('
                COALESCE(SUM(order_items.subtotal), 0) as revenue,
                COALESCE(SUM(order_items.qty), 0) as units,
                COALESCE(SUM(order_items.qty * COALESCE(products.cost_price, 0)), 0) as cost,
                COUNT(DISTINCT order_items.store_order_id) as orders
            ')
            ->first();

        return [
            'revenue' => (int) $row->revenue,
            'profit'  => (int) $row->revenue - (int) $row->cost,
            'units'   => (int) $row->units,
            'orders'  => (int) $row->orders,
        ];
    }

    public function bestSellers(int $days = 30, int $limit = 10): Collection
    {
        return $this->baseQuery($days)
            ->groupBy('order_items.product_id', 'order_items.product_name')
            ->selectRaw('
                order_items.product_id,
                order_items.product_name,
                SUM(order_items.qty) as total_qty,
                SUM(order_items.subtotal) as revenue,
                SUM(order_items.qty * COALESCE(products.cost_price, 0)) as total_cost,
                COUNT(DISTINCT order_items.store_order_id) as order_count
            ')
            ->orderByDesc('total_qty')
            ->limit($limit)
            ->get()
            ->map(function ($row) {
                $row->profit = (int) $row->revenue - (int) $row->total_cost;
                return $row;
            });
    }

    /** ID produk terlaris untuk badge di katalog. */
    public function bestSellerIds(int $days = 30, int $limit = 5): array
    {
        return $this->bestSellers($days, $limit)
            ->pluck('product_id')->filter()->values()->all();
    }

    public function lowStock(int $days = 30): Collection
    {
        $soldMap = $this->baseQuery($days)
            ->whereNotNull('order_items.product_id')
            ->groupBy('order_items.product_id')
            ->selectRaw('order_items.product_id, SUM(order_items.qty) as sold')
            ->pluck('sold', 'product_id');

        return Product::with('category')
            ->whereColumn('stock', '<=', 'min_stock')
            ->get()
            ->map(function ($p) use ($soldMap, $days) {
                $sold = (int) ($soldMap[$p->id] ?? 0);
                $avgDaily = $sold / max($days, 1);
                $daysLeft = $avgDaily > 0 ? (int) floor($p->stock / $avgDaily) : null;
                $target = max((int) ceil($avgDaily * 14), $p->min_stock * 2);

                $p->sold_period = $sold;
                $p->avg_daily = round($avgDaily, 2);
                $p->days_left = $daysLeft;
                $p->suggested_restock = max($target - $p->stock, 0);
                $p->urgency = ($p->stock <= 0 || ($daysLeft !== null && $daysLeft <= 3)) ? 'kritis' : 'rendah';

                return $p;
            })
            ->sortBy(fn ($p) => [$p->urgency === 'kritis' ? 0 : 1, $p->stock])
            ->values();
    }
}