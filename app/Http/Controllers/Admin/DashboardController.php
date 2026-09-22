<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;

class DashboardController extends Controller
{
    public function index()
    {
        $totalProduk = Product::count();
        $stokRendah = Product::where('stock', '>', 0)->where('stock', '<=', 15)->count();
        $stokHabis = Product::where('stock', '<=', 0)->count();

        $kategoriTerlaris = Category::withCount('products')
            ->orderByDesc('products_count')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalProduk',
            'stokRendah',
            'stokHabis',
            'kategoriTerlaris'
        ));
    }
}