@extends('layouts.site')

@section('title', 'Kode QRIS')

@section('content')
    <div class="site-container qris-wrap">
        <h1 class="text-center">Kode QRIS</h1>

        <div class="qris-card">
            <img src="{{ asset('images/Qriss.jpeg') }}" alt="Kode QRIS" class="qris-image">
        </div>

        <p class="text-center">
            Nomor Pesanan: <strong>{{ $order->order_number }}</strong><br>
            Total Pembayaran: <strong>Rp {{ number_format($order->total, 0, ',', '.') }}</strong>
        </p>

        @if($order->status === 'pending')
            <p class="text-center" style="color:#b45309;">
                Batas waktu pembayaran: {{ $order->payment_deadline->format('H:i') }} ({{ $order->payment_deadline->diffForHumans() }})
            </p>

            <form method="POST" action="{{ route('checkout.confirm', $order->order_number) }}" style="max-width:400px; margin:0 auto;">
                @csrf
                <label class="form-label">Nomor Referensi / Bukti Transfer *</label>
                <input type="text" name="payment_reference" placeholder="cth. TRX123456789" required
                       style="width:100%; padding:10px; margin-bottom:12px;">
                <button type="submit" class="btn-primary-nav" style="width:100%;">
                    Konfirmasi Sudah Bayar
                </button>
            </form>
        @elseif($order->status === 'menunggu_verifikasi')
            <div class="alert-success text-center" style="max-width:500px; margin:20px auto;">
                Pembayaran kamu sedang diverifikasi oleh admin. Nomor referensi: {{ $order->payment_reference }}
            </div>
        @elseif($order->status === 'selesai')
            <div class="alert-success text-center" style="max-width:500px; margin:20px auto;">
                Pembayaran sudah dikonfirmasi. Terima kasih!
            </div>
        @elseif($order->status === 'kadaluarsa')
            <div class="alert-error text-center" style="max-width:500px; margin:20px auto;">
                Batas waktu pembayaran sudah habis. Silakan lakukan pemesanan ulang.
            </div>
        @endif

        <div class="text-center" style="margin-top:20px;">
            <a href="{{ route('katalog') }}" class="btn-outline-nav">
                🛒 Lanjut Belanja
            </a>
        </div>
    </div>
@endsection