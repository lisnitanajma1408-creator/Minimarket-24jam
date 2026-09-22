<?php

namespace App\Mail;

use App\Models\StoreOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewOrderNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $order;

    public function __construct(StoreOrder $order)
    {
        $this->order = $order;
    }

    public function build()
    {
        return $this->subject('Pesanan Baru: ' . $this->order->order_number)
            ->view('emails.new-order');
    }
}