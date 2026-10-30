<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoreOrder extends Model
{
    protected $fillable = [
        'order_number', 'customer_name', 'customer_email', 'address_detail',
        'postal_code', 'shipping_method', 'payment_method',
        'subtotal', 'shipping_cost', 'total', 'status', 'send_receipt_email',
        'payment_reference', 'payment_deadline', 'paid_at', 'payment_proof', 'fulfillment_status',
    ];

    protected $casts = [
        'payment_deadline' => 'datetime',
        'paid_at' => 'datetime',
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class, 'store_order_id');
    }

    /**
     * Hanguskan pesanan yang lewat batas waktu dan belum dikonfirmasi admin.
     */
    public static function expireOverdue(): int
    {
        return static::whereIn('status', ['pending', 'menunggu_verifikasi'])
            ->whereNotNull('payment_deadline')
            ->where('payment_deadline', '<', now())
            ->update(['status' => 'kadaluarsa']);
    }
}