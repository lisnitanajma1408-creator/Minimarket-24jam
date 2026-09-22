@extends('layouts.admin')

@section('title', 'Ringkasan')

@section('content')
    <div class="page-header">
        <div>
            <h2>Ringkasan</h2>
            <p class="page-subtitle">Metrik toko real-time.</p>
        </div>
        <button class="btn-outline">Ekspor Laporan</button>
    </div>

    <div class="stat-cards">
        <div class="stat-card">
            <div class="stat-label">TOTAL PRODUK</div>
            <div class="stat-value">{{ $totalProduk }}</div>
        </div>

        <div class="stat-card">
            <div class="stat-label">PERINGATAN STOK RENDAH</div>
            <div class="stat-value">{{ $stokRendah }}</div>
            <div class="stat-note">Memerlukan tindakan segera</div>
        </div>

        <div class="stat-card">
            <div class="stat-label">STOK HABIS</div>
            <div class="stat-value">{{ $stokHabis }}</div>
        </div>

        <div class="stat-card">
            <div class="stat-label">STATUS SISTEM</div>
            <div class="stat-value stat-status">● Operasional</div>
        </div>
    </div>

    <div class="dashboard-grid">
        <div class="panel">
            <h3>Kategori Terlaris</h3>
            <table class="simple-table">
                <thead>
                    <tr>
                        <th>Kategori</th>
                        <th>Jumlah Produk</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kategoriTerlaris as $kategori)
                        <tr>
                            <td>{{ $kategori->name }}</td>
                            <td>{{ $kategori->products_count }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2">Belum ada data kategori.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="panel">
            <h3>Aktivitas Terbaru</h3>
            <p class="text-muted">Belum ada aktivitas tercatat.</p>
        </div>
    </div>
@endsection