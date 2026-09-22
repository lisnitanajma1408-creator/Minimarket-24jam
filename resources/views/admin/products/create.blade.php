@extends('layouts.admin')

@section('title', 'Tambah Produk Baru')

@section('content')
    <div class="form-card">
        <div class="form-card-header">
            <div>
                <h2>Tambah Produk Baru</h2>
                <p class="page-subtitle">Masukkan detail untuk mendaftarkan SKU baru ke dalam sistem inventaris.</p>
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

        <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data">
            @csrf

            <label class="form-label">Foto Produk</label>
            <div class="upload-box">
                <input type="file" name="image" id="image" accept="image/*">
                <label for="image" class="upload-label">
                    <span class="upload-icon">⬆️</span>
                    <span>Click to upload or drag and drop</span>
                    <span class="upload-hint">SVG, PNG, JPG or GIF (max. 800×400px)</span>
                </label>
            </div>

            <div class="form-grid">
                <div>
                    <label class="form-label">Nama Produk *</label>
                    <input type="text" name="name" value="{{ old('name') }}"
                           placeholder="cth. Minyak Goreng Tropical" required>
                </div>

                <div>
                    <label class="form-label">SKU *</label>
                    <input type="text" name="sku" value="{{ old('sku') }}"
                           placeholder="cth. BRC-1049-ALM" required>
                </div>

                <div>
                    <label class="form-label">Kategori</label>
                    <select name="category_id" required>
                        <option value="">Pilih Kategori</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="form-label">Satuan</label>
                    <input type="text" name="unit" value="{{ old('unit') }}"
                           placeholder="cth. per 1kg, 12 Butir/Pack">
                </div>

                <div>
                    <label class="form-label">Harga Satuan (Rp) *</label>
                    <input type="number" name="price" value="{{ old('price') }}"
                           placeholder="0" required min="0">
                </div>

                <div>
                    <label class="form-label">Level Stok Awal</label>
                    <input type="number" name="stock" value="{{ old('stock', 0) }}"
                           placeholder="0" min="0">
                </div>
            </div>

            <div class="form-actions">
                <a href="{{ route('admin.products.index') }}" class="btn-outline">Batal</a>
                <button type="submit" class="btn-primary">Simpan Produk</button>
            </div>
        </form>
    </div>
@endsection