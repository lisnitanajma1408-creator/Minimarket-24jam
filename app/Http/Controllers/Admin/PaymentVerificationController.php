<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\PaymentConfirmed;
use App\Models\StoreOrder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class PaymentVerificationController extends Controller
{
    public function index()
    {
        StoreOrder::expireOverdue();

        // COD baru: belum diterima admin
        $codPending = StoreOrder::with('items')
            ->where('payment_method', 'cod')
            ->where('status', 'pending')
            ->oldest()->get();

        $pending = StoreOrder::with('items')
            ->where('status', 'menunggu_verifikasi')
            ->oldest()->get();

        $packing = StoreOrder::with('items')
            ->where('status', 'selesai')
            ->whereIn('fulfillment_status', ['dikemas', 'dikirim'])
            ->oldest()->get();

        $done = StoreOrder::where('status', 'selesai')
            ->where('fulfillment_status', 'selesai')
            ->latest()->take(15)->get();

        $expiredCount = StoreOrder::where('status', 'kadaluarsa')->count();

        return view('admin.payments.index', compact('codPending', 'pending', 'packing', 'done', 'expiredCount'));
    }

    /** Kurangi stok dan masuk ke antrean packing. */
    private function acceptOrder(StoreOrder $order, bool $paidNow): void
    {
        DB::transaction(function () use ($order, $paidNow) {
            $order->update([
                'status'             => 'selesai',
                'paid_at'            => $paidNow ? now() : null,
                'fulfillment_status' => 'dikemas',
            ]);

            foreach ($order->items as $item) {
                if ($item->product_id) {
                    DB::table('products')
                        ->where('id', $item->product_id)
                        ->update(['stock' => DB::raw('GREATEST(stock - ' . (int) $item->qty . ', 0)')]);
                }
            }
        });
    }

    // ===== QRIS =====
    public function confirm(StoreOrder $order)
    {
        StoreOrder::expireOverdue();
        $order->refresh();

        if ($order->status !== 'menunggu_verifikasi') {
            return back()->with('error', "Pesanan {$order->order_number} sudah tidak bisa dikonfirmasi (kedaluwarsa atau sudah diproses).");
        }

        $this->acceptOrder($order, true);

        try {
            Mail::to($order->customer_email)->send(new PaymentConfirmed($order));
        } catch (\Throwable $e) {
            Log::warning('Email konfirmasi gagal: ' . $e->getMessage());
        }

        return back()->with('success', "Pesanan {$order->order_number} dikonfirmasi. Silakan kemas barangnya.");
    }

    public function reject(StoreOrder $order)
    {
        if ($order->status === 'menunggu_verifikasi') {
            $order->update(['status' => 'pending']);
        }

        return back()->with('success', "Bukti {$order->order_number} ditolak. Customer bisa upload ulang selama waktu belum habis.");
    }

    // ===== COD =====
    public function acceptCod(StoreOrder $order)
    {
        if ($order->payment_method !== 'cod' || $order->status !== 'pending') {
            return back()->with('error', "Pesanan {$order->order_number} tidak bisa diterima.");
        }

        $this->acceptOrder($order, false); // uang tunai belum diterima

        return back()->with('success', "Pesanan COD {$order->order_number} diterima. Silakan kemas barangnya.");
    }

    public function rejectCod(StoreOrder $order)
    {
        if ($order->payment_method === 'cod' && $order->status === 'pending') {
            $order->update(['status' => 'kadaluarsa']); // dianggap dibatalkan
        }

        return back()->with('success', "Pesanan COD {$order->order_number} ditolak/dibatalkan.");
    }

    // ===== Packing (QRIS & COD) =====
    public function fulfillment(StoreOrder $order)
    {
        $next = match ($order->fulfillment_status) {
            'dikemas' => 'dikirim',
            'dikirim' => 'selesai',
            default   => null,
        };

        if ($order->status === 'selesai' && $next) {
            $data = ['fulfillment_status' => $next];

            // COD: uang tunai diterima saat pesanan selesai
            if ($next === 'selesai' && $order->payment_method === 'cod' && !$order->paid_at) {
                $data['paid_at'] = now();
            }

            $order->update($data);
        }

        return back()->with('success', "Status {$order->order_number} diperbarui.");
    }
}