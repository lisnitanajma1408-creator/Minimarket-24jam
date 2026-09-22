@extends('layouts.admin')

@section('title', 'Edit Produk')

@section('content')
    <div class="form-card">
        <div class="form-card-header">
            <div>
                <h2>Edit Produk</h2>
                <p class="page-subtitle">Perbarui detail produk "{{ $product->name }}".</p>
            </div>
        </div>

        @if($errors->any())
            <div class="alert-error">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <label class="form-label">Foto Produk</label>
            @if($product->image)
                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                     style="width:120px; height:120px; object-fit:cover; border-radius:8px; margin-bottom:12px;">
            @endif
            <div class="upload-box">
                <input type="file" name="image" id="image" accept="image/*">
                <label for="image" class="upload-label">
                    <span class="upload-icon">⬆️</span>
                    <span>Click to upload or drag and drop</span>
                    <span class="upload-hint">Kosongkan jika tidak ingin mengubah foto</span>
                </label>
            </div>

            <div class="form-grid">
                <div>
                    <label class="form-label">Nama Produk *</label>
                    <input type="text" name="name" value="{{ old('name', $product->name) }}" required>
                </div>

                <div>
                    <label class="form-label">SKU *</label>
                    <input type="text" name="sku" value="{{ old('sku', $product->sku) }}" required>
                </div>

                <div>
                    <label class="form-label">Kategori</label>
                    <select name="category_id" required>
                        <option value="">Pilih Kategori</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}"
                                {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="form-label">Satuan</label>
                    <input type="text" name="unit" value="{{ old('unit', $product->unit) }}">
                </div>

                <div>
                    <label class="form-label">Harga Satuan (Rp) *</label>
                    <input type="number" name="price" value="{{ old('price', $product->price) }}" required min="0">
                </div>

                <div>
                    <label class="form-label">Stok</label>
                    <input type="number" name="stock" value="{{ old('stock', $product->stock) }}" min="0">
                </div>
            </div>

            <div class="form-actions">
                <a href="{{ route('admin.products.index') }}" class="btn-outline">Batal</a>
                <button type="submit" class="btn-primary">Update Produk</button>
            </div>
        </form>
    </div>
@endsection