<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductCatalogController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::all();

        $query = Product::with('category');

        if ($request->filled('kategori')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->kategori);
            });
        }

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $products = $query->latest()->paginate(20);

        $activeCategory = $request->kategori
            ? Category::where('slug', $request->kategori)->first()
            : null;

        return view('katalog', compact('products', 'categories', 'activeCategory'));
    }
}