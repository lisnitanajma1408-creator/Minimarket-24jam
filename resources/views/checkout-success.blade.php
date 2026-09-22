@extends('layouts.site')

@section('title', 'Pesanan Berhasil')

@section('content')
    <div class="site-container success-wrap">
        <div class="success-card">
            <div class="success-icon">✓</div>
            <h1>Pesanan Berhasil!</h1>
            <p class="success-sub">Terima kasih telah berbelanja di Minimarket 24 Jam.</p>

            <div class="success-box">
                <div class="success-order-number">
                    <span>Nomor Pesanan</span>
                    <strong>{{ $order->order_number }}</strong>
                </div>
                <div class="success-divider"></div>
                <div class="success-detail-grid">
                    <div>
                        <span class="success-label">📍 ALAMAT PENGIRIMAN</span>
                        <p>{{ $order->address_detail }}</p>
                    </div>
                    <div>
                        <span class="success-label">💳 METODE PEMBAYARAN</span>
                        <p>{{ $order->payment_method == 'qris' ? 'Kode QR' : 'Bayar di Tempat (COD)' }}</p>
                    </div>
                </div>
            </div>

            <a href="{{ route('katalog') }}" class="btn-outline-nav">🛒 Lanjut Belanja</a>
        </div>
    </div>
@endsection