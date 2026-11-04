@extends('layouts.site')

@section('title', 'Kode QRIS')

@section('content')
    <div class="site-container qris-wrap">
        <h1 class="text-center">Kode QRIS</h1>

        @if(in_array($order->status, ['pending', 'menunggu_verifikasi']))
            <div class="qris-card">
                <img src="{{ asset('images/Qriss.jpeg') }}" alt="Kode QRIS" class="qris-image">
            </div>
        @endif

        <p class="text-center">
            Nomor Pesanan: <strong>{{ $order->order_number }}</strong><br>
            Total Pembayaran: <strong>Rp {{ number_format($order->total, 0, ',', '.') }}</strong>
        </p>

        @if(session('success'))
            <div class="alert-success text-center" style="max-width:500px; margin:20px auto;">{{ session('success') }}</div>
        @endif

        @if($errors->any())
            <div class="alert-error text-center" style="max-width:500px; margin:20px auto;">{{ $errors->first() }}</div>
        @endif

        @if($order->status === 'pending' && $order->payment_deadline)
            <div class="text-center" style="margin:16px 0;">
                <div style="font-size:14px; color:#64748b;">Sisa waktu pembayaran</div>
                <div id="countdown" style="font-size:38px; font-weight:800; color:#b45309; letter-spacing:2px;">--:--</div>
            </div>
        @endif

        @if($order->status === 'pending')
            <form method="POST" action="{{ route('checkout.confirm', $order->order_number) }}"
                  enctype="multipart/form-data" style="max-width:400px; margin:0 auto;">
                @csrf
                <label class="form-label">Upload Screenshot Bukti Pembayaran *</label>
                <input type="file" name="payment_proof" id="proof" accept="image/*" required
                       style="width:100%; padding:12px; margin-bottom:12px; border:2px dashed #cbd5e1; border-radius:12px; background:#f8fafc;">
                <img id="proof-preview" alt="Preview bukti"
                     style="display:none; max-width:100%; max-height:260px; margin:0 auto 12px; border-radius:12px;">
                <button type="submit" class="btn-primary-nav" style="width:100%;">Konfirmasi Sudah Bayar</button>
            </form>

        @elseif($order->status === 'menunggu_verifikasi')
            <div class="alert-success text-center" style="max-width:500px; margin:20px auto;">
                Bukti terkirim dan sedang diverifikasi admin. Halaman ini otomatis diperbarui.
                @if($order->payment_proof)
                    <br><img src="{{ asset('storage/' . $order->payment_proof) }}" alt="Bukti pembayaran"
                             style="max-width:200px; margin-top:10px; border-radius:10px;">
                @endif
            </div>

        @elseif($order->status === 'selesai')
            <div class="alert-success text-center" style="max-width:500px; margin:20px auto;">
                Pembayaran dikonfirmasi. Terima kasih!<br>
                Status pesanan:
                <strong>
                    {{ match($order->fulfillment_status) {
                        'dikirim' => 'Dikirim / siap diambil',
                        'selesai' => 'Selesai',
                        default   => 'Sedang dikemas',
                    } }}
                </strong>
            </div>

        @elseif($order->status === 'kadaluarsa')
            <div class="alert-error text-center" style="max-width:500px; margin:20px auto;">
                Waktu pembayaran habis dan pesanan dibatalkan. Silakan belanja dan checkout ulang dari awal.
            </div>
        @endif

        <div class="text-center" style="margin-top:20px;">
            <a href="{{ route('katalog') }}" class="btn-outline-nav">
                🛒 {{ $order->status === 'kadaluarsa' ? 'Belanja Ulang' : 'Lanjut Belanja' }}
            </a>
        </div>
    </div>

    <script>
        const proof = document.getElementById('proof');
        if (proof) {
            proof.addEventListener('change', function (e) {
                const f = e.target.files[0], img = document.getElementById('proof-preview');
                if (f) { img.src = URL.createObjectURL(f); img.style.display = 'block'; }
            });
        }

        @if($order->status === 'pending' && $order->payment_deadline)
            const cd = document.getElementById('countdown');
            const end = new Date('{{ $order->payment_deadline->toIso8601String() }}').getTime();
            function tick() {
                const left = Math.floor((end - Date.now()) / 1000);
                if (left <= 0) {
                    cd.textContent = '00:00';
                    setTimeout(() => location.reload(), 1500);
                    return;
                }
                cd.textContent = String(Math.floor(left / 60)).padStart(2, '0') + ':' + String(left % 60).padStart(2, '0');
                setTimeout(tick, 1000);
            }
            tick();
        @endif

        @if($order->status === 'menunggu_verifikasi')
            setTimeout(() => location.reload(), 20000);
        @endif
    </script>
@endsection