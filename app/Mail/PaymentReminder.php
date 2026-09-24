<?php

namespace App\Mail;

use App\Models\StoreOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PaymentReminder extends Mailable
{
    use Queueable, SerializesModels;

    public $order;

    public function __construct(StoreOrder $order)
    {
        $this->order = $order;
    }

    public function build()
    {
        return $this->subject('Batas Waktu Pembayaran - ' . $this->order->order_number)
            ->view('emails.payment-reminder');
    }
}