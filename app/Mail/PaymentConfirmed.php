<?php

namespace App\Mail;

use App\Models\StoreOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PaymentConfirmed extends Mailable
{
    use Queueable, SerializesModels;

    public $order;

    public function __construct(StoreOrder $order)
    {
        $this->order = $order;
    }

    public function build()
    {
        $subject = $this->order->status === 'selesai'
            ? 'Pembayaran Dikonfirmasi - ' . $this->order->order_number
            : 'Pembayaran Ditolak - ' . $this->order->order_number;

        return $this->subject($subject)->view('emails.payment-confirmed');
    }
}