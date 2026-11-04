@extends('layouts.admin')

@section('title', 'Analisis Penjualan')

@section('content')
    <div class="page-header">
        <div>
            <h2>Analisis Penjualan & Stok</h2>
            <p class="page-subtitle">Dihitung otomatis dari pesanan yang sudah dikonfirmasi admin.</p>
        </div>
        <div style="display:flex; gap:8px;">
            @foreach([7 => '7 Hari', 30 => '30 Hari', 90 => '90 Hari'] as $d => $label)
                <a href="{{ route('admin.insights.index', ['days' => $d]) }}"
                   class="{{ $days == $d ? 'btn-primary' : 'btn-outline' }}"
                   style="text-decoration:none; display:inline-block;">{{ $label }}</a>
            @endforeach
        </div>
    </div>

    <div class="stat-cards">
        <div class="stat-card"><div class="stat-label">Pendapatan</div>
            <div class="stat-value" style="font-size:22px;">Rp {{ number_format($totals['revenue'], 0, ',', '.') }}</div></div>
        <div class="stat-card"><div class="stat-label">Estimasi Laba</div>
            <div class="stat-value" style="font-size:22px;">Rp {{ number_format($totals['profit'], 0, ',', '.') }}</div></div>
        <div class="stat-card"><div class="stat-label">Unit Terjual</div>
            <div class="stat-value">{{ number_format($totals['units'], 0, ',', '.') }}</div></div>
        <div class="stat-card"><div class="stat-label">Jumlah Pesanan</div>
            <div class="stat-value">{{ number_format($totals['orders'], 0, ',', '.') }}</div></div>
    </div>

    <div class="panel" style="margin-bottom:18px;">
        <h3>📊 Ringkasan Otomatis</h3>
        <div style="white-space:pre-wrap; line-height:1.7; color:#374151;">{{ $ruleSummary['text'] }}</div>
    </div>

    <div class="panel" style="margin-bottom:18px;">
        <h3>🔥 Produk Terlaris ({{ $days }} hari terakhir)</h3>
        <table class="product-table">
            <thead><tr><th>#</th><th>Produk</th><th>Terjual</th><th>Pesanan</th><th>Pendapatan</th><th>Est. Laba</th><th>Margin</th></tr></thead>
            <tbody>
                @forelse($bestSellers as $i => $row)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $row->product_name }}</td>
                        <td>{{ number_format($row->total_qty, 0, ',', '.') }}</td>
                        <td>{{ $row->order_count }}</td>
                        <td>Rp {{ number_format($row->revenue, 0, ',', '.') }}</td>
                        <td>Rp {{ number_format($row->profit, 0, ',', '.') }}</td>
                        <td><span class="badge badge-success">{{ $row->revenue > 0 ? round($row->profit / $row->revenue * 100) : 0 }}%</span></td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center">Belum ada penjualan. Pesanan dihitung setelah admin menekan "Sudah Bayar" atau "Terima Pesanan".</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="panel" style="margin-bottom:18px;">
        <h3>⚠️ Stok Menipis / Habis (perlu restok)</h3>
        <table class="product-table">
            <thead><tr><th>Produk</th><th>Kategori</th><th>Stok</th><th>Batas Min.</th><th>Rata-rata/Hari</th><th>Perkiraan Habis</th><th>Saran Restok</th><th>Status</th></tr></thead>
            <tbody>
                @forelse($lowStock as $p)
                    <tr>
                        <td>{{ $p->name }}</td>
                        <td>{{ $p->category->name ?? '-' }}</td>
                        <td>{{ $p->stock }}</td>
                        <td>{{ $p->min_stock }}</td>
                        <td>{{ $p->avg_daily }}</td>
                        <td>{{ is_null($p->days_left) ? 'Belum ada data penjualan' : $p->days_left . ' hari' }}</td>
                        <td><strong>+{{ $p->suggested_restock }}</strong></td>
                        <td><span class="badge {{ $p->urgency === 'kritis' ? 'badge-danger' : 'badge-warning' }}">
                            {{ $p->stock <= 0 ? 'Habis' : ($p->urgency === 'kritis' ? 'Kritis' : 'Rendah') }}</span></td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="text-center">Semua stok masih aman 🎉</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="panel">
        <h3>🤖 Analisis & Rekomendasi AI</h3>

        @if($aiEnabled)
            <p class="page-subtitle" style="margin-bottom:10px;">
                Diperbarui otomatis tiap beberapa jam. Terakhir:
                <strong id="ai-time">{{ $aiCached ? \Carbon\Carbon::parse($aiCached['at'])->timezone(config('app.timezone'))->format('d M Y H:i') : 'belum ada' }}</strong>
                <button type="button" id="btn-ai" class="btn-outline" style="margin-left:10px;">Perbarui sekarang</button>
            </p>
            <div id="ai-result" style="white-space:pre-wrap; line-height:1.7; color:#374151;">{{ $aiCached['text'] ?? 'Menyusun analisis...' }}</div>

            <script>
                const btn = document.getElementById('btn-ai');
                const out = document.getElementById('ai-result');
                const timeEl = document.getElementById('ai-time');

                async function loadAi() {
                    btn.disabled = true;
                    btn.textContent = 'Menganalisis...';
                    try {
                        const res = await fetch('{{ route('admin.insights.ai') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({ days: {{ $days }} })
                        });
                        const data = await res.json();
                        if (res.ok) {
                            out.textContent = data.summary;
                            timeEl.textContent = data.at;
                        } else {
                            out.textContent = data.error || 'Terjadi kesalahan.';
                        }
                    } catch (e) {
                        out.textContent = 'Tidak bisa terhubung ke server.';
                    }
                    btn.disabled = false;
                    btn.textContent = 'Perbarui sekarang';
                }

                btn.addEventListener('click', loadAi);

                @if($aiStale)
                    loadAi();
                @endif
            </script>
        @else
            <div class="alert-error" style="margin-bottom:0;">
                Analisis Claude belum aktif. Isi <code>ANTHROPIC_API_KEY</code> di <code>.env</code>, lalu jalankan <code>php artisan config:clear</code>.
                Ringkasan otomatis di atas tetap berjalan tanpa API.
            </div>
        @endif
    </div>
@endsection