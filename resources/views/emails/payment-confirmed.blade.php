<h2>Halo, {{ $order->customer_name }}</h2>

@if($order->status === 'selesai')
    <p>Pembayaran untuk pesanan <strong>{{ $order->order_number }}</strong> sudah kami <strong>konfirmasi dan lunas</strong>.</p>
    <p>Pesanan kamu akan segera kami proses. Terima kasih!</p>
@else
    <p>Mohon maaf, pembayaran untuk pesanan <strong>{{ $order->order_number }}</strong> tidak dapat kami verifikasi.</p>
    <p>Silakan hubungi kami untuk informasi lebih lanjut.</p>
@endif