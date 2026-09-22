<h2>Pesanan Baru Masuk!</h2>
<p><strong>Nomor Pesanan:</strong> {{ $order->order_number }}</p>
<p><strong>Nama:</strong> {{ $order->customer_name }}</p>
<p><strong>Email:</strong> {{ $order->customer_email }}</p>
<p><strong>Alamat:</strong> {{ $order->address_detail }}</p>
<p><strong>Metode Pengiriman:</strong> {{ $order->shipping_method == 'ojek_online' ? 'Diambil lewat Ojek Online' : 'Ambil di Toko' }}</p>
<p><strong>Metode Pembayaran:</strong> {{ strtoupper($order->payment_method) }}</p>

<h3>Detail Produk:</h3>
<table border="1" cellpadding="8" style="border-collapse: collapse;">
    <tr>
        <th>Produk</th>
        <th>Qty</th>
        <th>Harga</th>
        <th>Subtotal</th>
    </tr>
    @foreach($order->items as $item)
        <tr>
            <td>{{ $item->product_name }}</td>
            <td>{{ $item->qty }}</td>
            <td>Rp{{ number_format($item->price, 0, ',', '.') }}</td>
            <td>Rp{{ number_format($item->subtotal, 0, ',', '.') }}</td>
        </tr>
    @endforeach
</table>

<p><strong>Subtotal:</strong> Rp{{ number_format($order->subtotal, 0, ',', '.') }}</p>
<p><strong>Ongkos Kirim:</strong> Rp{{ number_format($order->shipping_cost, 0, ',', '.') }}</p>
<p><strong>Total:</strong> Rp{{ number_format($order->total, 0, ',', '.') }}</p>