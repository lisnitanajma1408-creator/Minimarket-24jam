@extends('layouts.admin')

@section('title', 'Verifikasi Pembayaran')

@section('content')
    <div class="page-header">
        <div>
            <h2>Verifikasi Pembayaran & Packing</h2>
            <p class="page-subtitle">Cocokkan bukti dengan mutasi rekening/e-wallet toko. Pesanan QRIS yang melewati 30 menit tanpa konfirmasi otomatis hangus ({{ $expiredCount }} pesanan sejauh ini).</p>
        </div>
    </div>

    @if(session('success'))<div class="alert-success" style="margin-bottom:16px;">{{ session('success') }}</div>@endif
    @if(session('error'))<div class="alert-error" style="margin-bottom:16px;">{{ session('error') }}</div>@endif

    {{-- PESANAN COD BARU --}}
    <div class="panel" style="margin-bottom:18px;">
        <h3>💵 Pesanan COD Baru ({{ $codPending->count() }})</h3>
        <table class="product-table">
            <thead>
                <tr><th>Pesanan</th><th>Pembeli</th><th>Barang</th><th>Total Ditagih</th><th>Pengiriman</th><th>Aksi</th></tr>
            </thead>
            <tbody>
                @forelse($codPending as $o)
                    <tr>
                        <td><strong>{{ $o->order_number }}</strong><br><small>{{ $o->created_at->diffForHumans() }}</small></td>
                        <td>{{ $o->customer_name }}<br><small>{{ $o->address_detail }}</small></td>
                        <td>@foreach($o->items as $i){{ $i->qty }}x {{ $i->product_name }}<br>@endforeach</td>
                        <td><strong>Rp {{ number_format($o->total, 0, ',', '.') }}</strong></td>
                        <td>{{ $o->shipping_method === 'ambil_toko' ? 'Ambil di toko' : 'Ojek online' }}</td>
                        <td>
                            <form action="{{ route('admin.payments.acceptCod', $o->id) }}" method="POST" style="display:inline;">
                                @csrf
                                <button class="btn-primary" onclick="return confirm('Terima pesanan COD ini?')">Terima Pesanan</button>
                            </form>
                            <form action="{{ route('admin.payments.rejectCod', $o->id) }}" method="POST" style="display:inline;">
                                @csrf
                                <button class="btn-outline" onclick="return confirm('Tolak pesanan ini?')">Tolak</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center">Tidak ada pesanan COD baru.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- MENUNGGU VERIFIKASI (QRIS) --}}
    <div class="panel" style="margin-bottom:18px;">
        <h3>⏳ Menunggu Verifikasi ({{ $pending->count() }})</h3>
        <table class="product-table">
            <thead>
                <tr><th>Pesanan</th><th>Pembeli</th><th>Barang</th><th>Total</th><th>Bukti</th><th>Sisa Waktu</th><th>Aksi</th></tr>
            </thead>
            <tbody>
                @forelse($pending as $o)
                    <tr>
                        <td><strong>{{ $o->order_number }}</strong></td>
                        <td>{{ $o->customer_name }}<br><small>{{ $o->address_detail }}</small></td>
                        <td>@foreach($o->items as $i){{ $i->qty }}x {{ $i->product_name }}<br>@endforeach</td>
                        <td>Rp {{ number_format($o->total, 0, ',', '.') }}</td>
                        <td>
                            @if($o->payment_proof)
                                <a href="{{ asset('storage/' . $o->payment_proof) }}" target="_blank">
                                    <img src="{{ asset('storage/' . $o->payment_proof) }}" alt="Bukti"
                                         style="width:80px; height:80px; object-fit:cover; border-radius:8px;">
                                </a>
                            @else - @endif
                        </td>
                        <td><strong class="js-countdown" data-end="{{ $o->payment_deadline->toIso8601String() }}" style="color:#b45309;">--:--</strong></td>
                        <td>
                            <form action="{{ route('admin.payments.confirm', $o->id) }}" method="POST" style="display:inline;">
                                @csrf
                                <button class="btn-primary" onclick="return confirm('Yakin uang sudah masuk?')">Sudah Bayar</button>
                            </form>
                            <form action="{{ route('admin.payments.reject', $o->id) }}" method="POST" style="display:inline;">
                                @csrf
                                <button class="btn-outline" onclick="return confirm('Tolak bukti ini?')">Tolak</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center">Tidak ada pembayaran yang menunggu verifikasi.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- PACKING & DISTRIBUSI --}}
    <div class="panel" style="margin-bottom:18px;">
        <h3>📦 Packing & Distribusi ({{ $packing->count() }})</h3>
        <table class="product-table">
            <thead><tr><th>Pesanan</th><th>Pembeli</th><th>Barang</th><th>Pengiriman</th><th>Status</th><th>Aksi</th></tr></thead>
            <tbody>
                @forelse($packing as $o)
                    <tr>
                        <td><strong>{{ $o->order_number }}</strong></td>
                        <td>{{ $o->customer_name }}<br><small>{{ $o->address_detail }}</small></td>
                        <td>@foreach($o->items as $i){{ $i->qty }}x {{ $i->product_name }}<br>@endforeach</td>
                        <td>
                            {{ $o->shipping_method === 'ambil_toko' ? 'Ambil di toko' : 'Ojek online' }}<br>
                            @if($o->payment_method === 'cod')
                                <span class="badge badge-warning">COD: tagih Rp {{ number_format($o->total, 0, ',', '.') }}</span>
                            @else
                                <span class="badge badge-success">QRIS lunas</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge {{ $o->fulfillment_status === 'dikirim' ? 'badge-success' : 'badge-warning' }}">
                                {{ $o->fulfillment_status === 'dikirim' ? 'Dikirim / siap diambil' : 'Dikemas' }}
                            </span>
                        </td>
                        <td>
                            <form action="{{ route('admin.payments.fulfillment', $o->id) }}" method="POST">
                                @csrf
                                <button class="btn-primary">
                                    {{ $o->fulfillment_status === 'dikirim' ? 'Tandai Selesai' : 'Tandai Dikirim / Siap Diambil' }}
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center">Tidak ada pesanan yang perlu dikemas.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- SELESAI --}}
    <div class="panel">
        <h3>✅ Selesai (terakhir)</h3>
        <table class="product-table">
            <thead><tr><th>Pesanan</th><th>Pembeli</th><th>Total</th><th>Dibayar</th></tr></thead>
            <tbody>
                @forelse($done as $o)
                    <tr>
                        <td>{{ $o->order_number }}</td>
                        <td>{{ $o->customer_name }}</td>
                        <td>Rp {{ number_format($o->total, 0, ',', '.') }}</td>
                        <td>{{ optional($o->paid_at)->format('d M Y H:i') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center">Belum ada.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <script>
        function tickAll() {
            let expired = false;
            document.querySelectorAll('.js-countdown').forEach(el => {
                const left = Math.floor((new Date(el.dataset.end).getTime() - Date.now()) / 1000);
                if (left <= 0) { el.textContent = 'Hangus'; expired = true; return; }
                el.textContent = String(Math.floor(left / 60)).padStart(2, '0') + ':' + String(left % 60).padStart(2, '0');
            });
            if (expired) { setTimeout(() => location.reload(), 1500); return; }
            setTimeout(tickAll, 1000);
        }
        tickAll();
    </script>
@endsection