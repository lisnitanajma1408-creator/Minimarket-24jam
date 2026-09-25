@extends('layouts.admin')

@section('title', 'Inventaris')

@section('content')
    <div class="page-header">
        <div>
            <h2>Inventaris</h2>
            <p class="page-subtitle">Kelola dan sesuaikan stok produk secara langsung.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif

    <div class="panel">
        <table class="product-table">
            <thead>
                <tr>
                    <th>Nama Produk</th>
                    <th>SKU</th>
                    <th>Stok Saat Ini</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                    <tr>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->sku ?? '-' }}</td>
                        <td>
                            <span class="badge {{ $product->stock <= 0 ? 'badge-danger' : ($product->stock <= 15 ? 'badge-warning' : 'badge-success') }}">
                                {{ $product->stock }}
                            </span>
                        </td>
                        <td>
                            <form action="{{ route('admin.inventory.adjust', $product) }}" method="POST" style="display:flex; gap:8px; align-items:center;">
                                @csrf
                                <select name="type" required style="padding:8px 10px; border:1px solid #d1d5db; border-radius:8px; font-size:13px;">
                                    <option value="in">Tambah</option>
                                    <option value="out">Kurangi</option>
                                </select>
                                <input type="number" name="quantity" min="1" placeholder="Jumlah" required
                                       style="width:80px; padding:8px 10px; border:1px solid #d1d5db; border-radius:8px; font-size:13px;">
                                <input type="text" name="note" placeholder="Catatan (opsional)"
                                       style="width:160px; padding:8px 10px; border:1px solid #d1d5db; border-radius:8px; font-size:13px;">
                                <button type="submit" class="btn-primary" style="padding:8px 16px; font-size:13px;">Simpan</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">Belum ada produk.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="pagination-wrap">
        {{ $products->links() }}
    </div>
@endsection