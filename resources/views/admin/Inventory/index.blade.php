@extends('layouts.admin')

@section('content')
<h1>Inventaris</h1>

@if(session('success'))
    <div style="padding:10px; background:#d1fae5; margin-bottom:15px;">{{ session('success') }}</div>
@endif

<table border="1" cellpadding="8" style="width:100%; border-collapse:collapse;">
    <thead>
        <tr>
            <th>Nama Produk</th>
            <th>SKU</th>
            <th>Stok Saat Ini</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach($products as $product)
        <tr>
            <td>{{ $product->name }}</td>
            <td>{{ $product->sku ?? '-' }}</td>
            <td>{{ $product->stock }}</td>
            <td>
                <form action="{{ route('admin.inventory.adjust', $product) }}" method="POST" style="display:flex; gap:5px;">
                    @csrf
                    <select name="type" required>
                        <option value="in">Tambah</option>
                        <option value="out">Kurangi</option>
                    </select>
                    <input type="number" name="quantity" min="1" placeholder="Jumlah" required style="width:80px;">
                    <input type="text" name="note" placeholder="Catatan (opsional)">
                    <button type="submit">Simpan</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

{{ $products->links() }}
@endsection