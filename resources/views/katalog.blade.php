@extends('layouts.site')

@section('title', $activeCategory ? $activeCategory->name : 'Katalog Produk')

@section('content')
    <div class="site-container katalog-wrap">

        <form method="GET" class="katalog-search">
            <input type="text" name="search" placeholder="Cari produk..." value="{{ request('search') }}">
            @if(request('kategori'))
                <input type="hidden" name="kategori" value="{{ request('kategori') }}">
            @endif
        </form>

        @if(session('success'))
            <div class="alert-success" style="max-width:500px;margin:0 auto 24px;">{{ session('success') }}</div>
        @endif

        <div class="katalog-header text-center">
            <h1>{{ $activeCategory ? $activeCategory->name : 'Semua Produk' }}</h1>
            <p class="section-desc">
                Jelajahi berbagai kategori produk segar dan berkualitas yang selalu tersedia
                24 jam untuk memenuhi kebutuhan harian Anda. Komitmen kami pada kesegaran tanpa kompromi.
            </p>
        </div>

        <div class="katalog-layout">
            {{-- SIDEBAR KATEGORI --}}
            <aside class="katalog-sidebar">
                <a href="{{ route('katalog') }}" class="{{ !request('kategori') ? 'active' : '' }}">
                    Semua Produk
                </a>
                @foreach($categories as $category)
                    <a href="{{ route('katalog', ['kategori' => $category->slug]) }}"
                       class="{{ request('kategori') == $category->slug ? 'active' : '' }}">
                        {{ $category->name }}
                    </a>
                @endforeach
            </aside>

            {{-- GRID PRODUK --}}
            <div class="katalog-grid">
                @forelse($products as $product)
                    <div class="product-card">
                        <div class="product-image">
                            @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}">
                            @else
                                <div class="product-image-placeholder">{{ $product->name }}</div>
                            @endif
                        </div>
                        <div class="product-info">
                            <h4>{{ $product->name }}</h4>
                            <span class="product-unit">{{ $product->unit ?? '-' }}</span>
                            <div class="product-price-row">
                                <span class="product-price">{{ $product->formatted_price }}</span>
                                <form method="POST" action="{{ route('cart.add', $product) }}">
                                    @csrf
                                    <input type="hidden" name="qty" value="1">
                                    <button class="btn-add-cart" type="submit">+</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-muted">Belum ada produk di kategori ini.</p>
                @endforelse
            </div>
        </div>

        <div class="pagination-wrap">
            {{ $products->links() }}
        </div>

    </div>
@endsection