<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StoreOrder;
use App\Models\OrderItem;
use App\Models\User;
use App\Mail\NewOrderNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = session('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Keranjang kamu masih kosong.');
        }

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

        $shippingCost = 15000;
        $total = $subtotal + $shippingCost;

        return view('checkout', compact('items', 'subtotal', 'shippingCost', 'total'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email',
            'address_detail' => 'required|string',
            'postal_code' => 'nullable|string|max:10',
            'shipping_method' => 'required|in:ojek_online,ambil_toko',
            'payment_method' => 'required|in:qris,cod',
        ]);

        $cart = session('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Keranjang kamu masih kosong.');
        }

        $items = [];
        $subtotal = 0;

        foreach ($cart as $productId => $qty) {
            $product = Product::find($productId);
            if ($product) {
                $itemSubtotal = $product->price * $qty;
                $items[] = ['product' => $product, 'qty' => $qty, 'subtotal' => $itemSubtotal];
                $subtotal += $itemSubtotal;
            }
        }

        $shippingCost = 15000;
        $total = $subtotal + $shippingCost;

        $orderNumber = 'FM-' . date('Y') . '-' . str_pad(StoreOrder::count() + 1, 5, '0', STR_PAD_LEFT);

        $order = StoreOrder::create([
            'order_number' => $orderNumber,
            'customer_name' => $validated['customer_name'],
            'customer_email' => $validated['customer_email'],
            'address_detail' => $validated['address_detail'],
            'postal_code' => $validated['postal_code'] ?? null,
            'shipping_method' => $validated['shipping_method'],
            'payment_method' => $validated['payment_method'],
            'subtotal' => $subtotal,
            'shipping_cost' => $shippingCost,
            'total' => $total,
            'status' => 'pending',
            'send_receipt_email' => $request->has('send_receipt_email'),
        ]);

        foreach ($items as $item) {
            OrderItem::create([
                'store_order_id' => $order->id,
                'product_id' => $item['product']->id,
                'product_name' => $item['product']->name,
                'price' => $item['product']->price,
                'qty' => $item['qty'],
                'subtotal' => $item['subtotal'],
            ]);
        }

        // Kirim email ke semua admin
        $adminEmails = User::where('role', 'admin')->pluck('email');
        foreach ($adminEmails as $email) {
            Mail::to($email)->send(new NewOrderNotification($order));
        }

        session()->forget('cart');

        return redirect()->route('checkout.success', $order->order_number);
    }

    public function success($orderNumber)
    {
        $order = StoreOrder::where('order_number', $orderNumber)->firstOrFail();
        return view('checkout-success', compact('order'));
    }
}