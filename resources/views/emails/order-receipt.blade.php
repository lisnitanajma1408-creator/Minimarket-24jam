<h2>Terima kasih, {{ $order->customer_name }}!</h2>
<p>Pesanan kamu dengan nomor <strong>{{ $order->order_number }}</strong> sudah kami terima.</p>

<table border="1" cellpadding="8" style="border-collapse:collapse; width:100%;">
    <tr>
        <td>Metode Pembayaran</td>
        <td>{{ strtoupper($order->payment_method) }}</td>
    </tr>
    <tr>
        <td>Subtotal</td>
        <td>Rp {{ number_format($order->subtotal, 0, ',', '.') }}</td>
    </tr>
    <tr>
        <td>Ongkir</td>
        <td>Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</td>
    </tr>
    <tr>
        <td><strong>Total</strong></td>
        <td><strong>Rp {{ number_format($order->total, 0, ',', '.') }}</strong></td>
    </tr>
</table>

@if($order->payment_method === 'qris')
    <p>Silakan lakukan pembayaran dalam <strong>30 menit</strong> sejak pesanan dibuat, lalu masukkan nomor referensi transfer di halaman QRIS.</p>
@else
    <p>Pembayaran dilakukan secara tunai (COD) saat barang diterima.</p>
@endif

<p>Terima kasih sudah berbelanja di Minimarket 24 Jam!</p>