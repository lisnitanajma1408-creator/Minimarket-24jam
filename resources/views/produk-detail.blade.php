@extends('layouts.site')

@section('title', $product->name)

@section('content')
    <div class="site-container" style="padding:40px 0;">

        <a href="{{ route('katalog') }}" style="display:inline-block; margin-bottom:20px;">&larr; Kembali ke Katalog</a>

        @if(session('success'))
            <div class="alert-success" style="max-width:500px; margin-bottom:24px;">{{ session('success') }}</div>
        @endif

        <div style="display:grid; grid-template-columns:1fr 1fr; gap:40px; align-items:start;">

            {{-- GAMBAR PRODUK --}}
            <div class="product-image" style="border-radius:12px; overflow:hidden;">
                @if($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" style="width:100%; height:auto; display:block;">
                @else
                    <div class="product-image-placeholder" style="height:400px; display:flex; align-items:center; justify-content:center;">
                        {{ $product->name }}
                    </div>
                @endif
            </div>

            {{-- DETAIL PRODUK --}}
            <div>
                <span class="section-label">{{ $product->category->name ?? '-' }}</span>
                <h1 style="margin:8px 0;">{{ $product->name }}</h1>
                <p style="color:#666;">SKU: {{ $product->sku }}</p>
                <p style="color:#666;">{{ $product->unit ?? '-' }}</p>

                <div style="font-size:28px; font-weight:bold; margin:20px 0;">
                    {{ $product->formatted_price }}
                </div>

                <p style="margin-bottom:24px;">
                    Stok tersedia: <strong>{{ $product->stock }}</strong>
                </p>

                <form method="POST" action="{{ route('cart.add', $product) }}" style="display:flex; gap:10px; align-items:center;">
                    @csrf
                    <input type="number" name="qty" value="1" min="1" max="{{ $product->stock }}" style="width:80px; padding:10px;">
                    <button class="btn-primary-nav" type="submit" style="padding:12px 24px;">
                        Tambah ke Keranjang
                    </button>
                </form>
            </div>

        </div>

        {{-- PRODUK TERKAIT --}}
        @if($relatedProducts->count() > 0)
            <div style="margin-top:60px;">
                <h2>Produk Terkait</h2>
                <div class="katalog-grid" style="margin-top:20px;">
                    @foreach($relatedProducts as $related)
                        <a href="{{ route('produk.show', $related) }}" style="text-decoration:none; color:inherit;">
                            <div class="product-card">
                                <div class="product-image">
                                    @if($related->image)
                                        <img src="{{ asset('storage/' . $related->image) }}" alt="{{ $related->name }}">
                                    @else
                                        <div class="product-image-placeholder">{{ $related->name }}</div>
                                    @endif
                                </div>
                                <div class="product-info">
                                    <h4>{{ $related->name }}</h4>
                                    <span class="product-price">{{ $related->formatted_price }}</span>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

    </div>
@endsection