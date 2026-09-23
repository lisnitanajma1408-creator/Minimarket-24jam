<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InventoryController extends Controller
{
    public function index()
    {
        $products = Product::orderBy('name')->paginate(10);
        return view('admin.inventory.index', compact('products'));
    }

    public function adjust(Request $request, Product $product)
    {
        $validated = $request->validate([
            'type' => 'required|in:in,out',
            'quantity' => 'required|integer|min:1',
            'note' => 'nullable|string|max:255',
        ]);

        StockMovement::create([
            'product_id' => $product->id,
            'type' => $validated['type'],
            'quantity' => $validated['quantity'],
            'note' => $validated['note'] ?? null,
            'user_id' => Auth::id(),
        ]);

        if ($validated['type'] === 'in') {
            $product->increment('stock', $validated['quantity']);
        } else {
            $product->decrement('stock', min($validated['quantity'], $product->stock));
        }

        return redirect()->route('admin.inventory.index')->with('success', 'Stok berhasil diperbarui.');
    }
}