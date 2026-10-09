<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StoreOrder;
use App\Models\OrderItem;
use App\Models\User;
use App\Mail\NewOrderNotification;
use App\Mail\OrderReceipt;
use App\Mail\PaymentConfirmed;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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

        $shippingCost = 0;
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

        $shippingCost = 0;
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
            'payment_deadline' => $validated['payment_method'] === 'qris' ? now()->addMinutes(30) : null,
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

        $adminEmails = User::where('role', 'admin')->pluck('email');
        foreach ($adminEmails as $email) {
            Mail::to($email)->send(new NewOrderNotification($order));
        }

        if ($order->send_receipt_email) {
            Mail::to($order->customer_email)->send(new OrderReceipt($order));
        }

        session()->forget('cart');

        if ($order->payment_method === 'qris') {
            return redirect()->route('checkout.qris', $order->order_number);
        }

        return redirect()->route('checkout.success', $order->order_number);
    }

    public function qris($orderNumber)
    {
        $order = StoreOrder::where('order_number', $orderNumber)->firstOrFail();
        return view('checkout-qris', compact('order'));
    }

    public function confirmPayment(Request $request, $orderNumber)
    {
        $order = StoreOrder::where('order_number', $orderNumber)->firstOrFail();

        $request->validate([
            'payment_proof' => 'required|image|mimes:jpg,jpeg,png,webp|max:4096',
        ], [
            'payment_proof.required' => 'Upload screenshot bukti pembayaran dulu.',
            'payment_proof.image'    => 'File harus berupa gambar.',
            'payment_proof.mimes'    => 'Format gambar harus jpg, jpeg, png, atau webp.',
            'payment_proof.max'      => 'Ukuran gambar maksimal 4 MB.',
        ]);

        $order->update([
            'payment_proof' => $request->file('payment_proof')->store('payment-proofs', 'public'),
            'status' => 'menunggu_verifikasi',
        ]);

        try {
            $adminEmails = User::where('role', 'admin')->pluck('email');
            foreach ($adminEmails as $email) {
                Mail::to($email)->send(new NewOrderNotification($order));
            }
        } catch (\Throwable $e) {
            Log::warning('Email notifikasi admin gagal: ' . $e->getMessage());
        }

        return redirect()->route('checkout.success', $order->order_number)
            ->with('success', 'Terima kasih! Bukti pembayaran kamu sedang kami verifikasi.');
    }

    public function success($orderNumber)
    {
        $order = StoreOrder::where('order_number', $orderNumber)->firstOrFail();
        return view('checkout-success', compact('order'));
    }
}