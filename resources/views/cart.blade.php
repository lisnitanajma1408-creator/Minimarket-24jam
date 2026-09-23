@extends('layouts.site')

@section('title', 'Keranjang Belanja')

@section('content')
    <div class="site-container cart-wrap">

        <div class="cart-header">
            <h1>Keranjang Belanja</h1>
        </div>

        @if(session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif

        @if(count($items) > 0)
            <div class="cart-items">
                @foreach($items as $item)
                    <div class="cart-item">
                        <div class="cart-item-image">
                            @if($item['product']->image)
                                <img src="{{ asset('storage/' . $item['product']->image) }}" alt="{{ $item['product']->name }}">
                            @else
                                <div class="product-image-placeholder">{{ $item['product']->name }}</div>
                            @endif
                        </div>

                        <div class="cart-item-info">
                            <h4>{{ $item['product']->name }}</h4>
                            <span class="product-unit">{{ $item['product']->unit ?? '-' }}</span>
                            <div class="cart-item-price">{{ $item['product']->formatted_price }}</div>
                        </div>

                        <form method="POST" action="{{ route('cart.update', $item['product']) }}" class="cart-qty-form">
                            @csrf
                            @method('PATCH')
                            <button type="submit" name="qty" value="{{ $item['qty'] - 1 }}" class="qty-btn">−</button>
                            <span class="qty-value">{{ $item['qty'] }}</span>
                            <button type="submit" name="qty" value="{{ $item['qty'] + 1 }}" class="qty-btn">+</button>
                        </form>

                        <form method="POST" action="{{ route('cart.remove', $item['product']) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-remove">🗑</button>
                        </form>
                    </div>
                @endforeach
            </div>

            <div class="cart-summary">
                <div class="cart-summary-row">
                    <span>Total Produk</span>
                    <strong>{{ count($items) }}</strong>
                </div>
                <div class="cart-summary-row cart-total">
                    <span>Subtotal</span>
                    <strong>Rp{{ number_format($subtotal, 0, ',', '.') }}</strong>
                </div>

                <div class="cart-actions">
                    <a href="{{ route('katalog') }}" class="btn-outline-nav">Tambah Belanja</a>
                    <a href="{{ route('checkout.index') }}" class="btn-primary-nav">Beli Sekarang</a>
                </div>
            </div>
        @else
            <div class="cart-empty">
                <p>Keranjang belanja kamu masih kosong.</p>
                <a href="{{ route('katalog') }}" class="btn-primary-nav">Mulai Belanja</a>
            </div>
        @endif

    </div>
@endsection