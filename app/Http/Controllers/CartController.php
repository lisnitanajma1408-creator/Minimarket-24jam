<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = session('cart', []);
        $items = [];
        $subtotal = 0;

        foreach ($cart as $productId => $qty) {
            $product = Product::find($productId);
            if ($product) {
                $itemSubtotal = $product->price * $qty;
                $items[] = [
                    'product' => $product,
                    'qty' => $qty,
                    'subtotal' => $itemSubtotal,
                ];
                $subtotal += $itemSubtotal;
            }
        }

        return view('cart', compact('items', 'subtotal'));
    }

    public function add(Request $request, Product $product)
    {
        $cart = session('cart', []);
        $qty = $request->input('qty', 1);

        if (isset($cart[$product->id])) {
            $cart[$product->id] += $qty;
        } else {
            $cart[$product->id] = $qty;
        }

        session(['cart' => $cart]);

        return back()->with('success', 'Produk ditambahkan ke keranjang!');
    }

    public function update(Request $request, Product $product)
    {
        $cart = session('cart', []);
        $qty = (int) $request->input('qty', 1);

        if ($qty <= 0) {
            unset($cart[$product->id]);
        } else {
            $cart[$product->id] = $qty;
        }

        session(['cart' => $cart]);

        return back();
    }

    public function remove(Product $product)
    {
        $cart = session('cart', []);
        unset($cart[$product->id]);
        session(['cart' => $cart]);

        return back()->with('success', 'Produk dihapus dari keranjang.');
    }
}