@extends('layouts.admin')

@section('title', 'Daftar Produk')

@section('content')
    <div class="page-header">
        <div>
            <h2>Katalog Produk</h2>
            <p class="page-subtitle">Kelola dan pantau inventaris toko secara langsung.</p>
        </div>
        <form method="GET" class="search-form">
            <input type="text" name="search" placeholder="Cari SKU atau Nama..."
                   value="{{ request('search') }}">
            <button type="submit" class="btn-outline">Cari</button>
        </form>
    </div>

    @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif

    <div class="stat-cards">
        <div class="stat-card">
            <div class="stat-label">TOTAL PRODUK</div>
            <div class="stat-value">{{ $totalProduk }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">PERINGATAN STOK RENDAH</div>
            <div class="stat-value">{{ $stokRendah }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">STOK HABIS</div>
            <div class="stat-value">{{ $stokHabis }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">STATUS SINKRONISASI</div>
            <div class="stat-value stat-status">● Langsung</div>
        </div>
    </div>

    <div class="panel">
        <table class="product-table">
            <thead>
                <tr>
                    <th>Nama Produk</th>
                    <th>SKU</th>
                    <th>Kategori</th>
                    <th>Harga</th>
                    <th>Stok</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                    <tr>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->sku }}</td>
                        <td>{{ $product->category->name ?? '-' }}</td>
                        <td>{{ $product->formatted_price }}</td>
                        <td>{{ $product->stock }}</td>
                        <td>
                            <span class="badge badge-{{ $product->stock <= 0 ? 'danger' : ($product->stock <= 15 ? 'warning' : 'success') }}">
                                {{ $product->status }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('admin.products.edit', $product) }}">Edit</a>
                            <form method="POST" action="{{ route('admin.products.destroy', $product) }}"
                                  style="display:inline"
                                  onsubmit="return confirm('Yakin hapus produk ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-link-danger">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">Belum ada produk. Yuk tambah produk pertama!</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="pagination-wrap">
            {{ $products->links() }}
        </div>
    </div>
@endsection