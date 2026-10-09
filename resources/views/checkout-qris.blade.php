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

        @if(session('success'))
            <div class="alert-success text-center" style="max-width:500px; margin:20px auto;">
                {{ session('success') }}
            </div>
        @endif

        @if($order->status === 'pending')
            <p class="text-center" style="color:#b45309;">
                Batas waktu pembayaran: {{ $order->payment_deadline->format('H:i') }} ({{ $order->payment_deadline->diffForHumans() }})
            </p>

            @if($errors->any())
                <p class="text-center" style="color:#b91c1c;">{{ $errors->first() }}</p>
            @endif

            <form method="POST" action="{{ route('checkout.confirm', $order->order_number) }}"
                  enctype="multipart/form-data" style="max-width:400px; margin:0 auto;">
                @csrf
                <label class="form-label">Upload Screenshot Bukti Pembayaran *</label>
                <input type="file" name="payment_proof" id="proof" accept="image/*" required
                       style="width:100%; padding:12px; margin-bottom:12px; border:2px dashed #cbd5e1; border-radius:12px; background:#f8fafc;">
                <img id="proof-preview" alt="Preview bukti"
                     style="display:none; max-width:100%; max-height:260px; margin:0 auto 12px; border-radius:12px;">
                <button type="submit" class="btn-primary-nav" style="width:100%;">
                    Konfirmasi Sudah Bayar
                </button>
            </form>

            <script>
                document.getElementById('proof').addEventListener('change', function (e) {
                    const file = e.target.files[0];
                    const img = document.getElementById('proof-preview');
                    if (file) {
                        img.src = URL.createObjectURL(file);
                        img.style.display = 'block';
                    }
                });
            </script>
        @elseif($order->status === 'menunggu_verifikasi')
            <div class="alert-success text-center" style="max-width:500px; margin:20px auto;">
                Bukti pembayaran kamu sudah terkirim dan sedang diverifikasi oleh admin.
                @if($order->payment_proof)
                    <br>
                    <img src="{{ asset('storage/' . $order->payment_proof) }}" alt="Bukti pembayaran"
                         style="max-width:200px; margin-top:10px; border-radius:10px;">
                @endif
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