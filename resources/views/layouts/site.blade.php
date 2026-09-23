<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Beranda') - Minimarket 24 Jam</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>

    {{-- NAVBAR --}}
    <header class="site-header">
        <div class="site-container navbar-inner">
            <a href="{{ route('home') }}" class="site-logo">Minimarket 24 Jam</a>

            <nav class="site-nav">
                <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Beranda</a>
                <a href="{{ route('home') }}#tentang-kami">Tentang Kami</a>
                <a href="{{ route('katalog') }}" class="{{ request()->routeIs('katalog') ? 'active' : '' }}">Katalog</a>
                <a href="{{ route('keunggulan') }}" class="{{ request()->routeIs('keunggulan') ? 'active' : '' }}">Keunggulan</a>
                <a href="{{ route('ulasan') }}" class="{{ request()->routeIs('ulasan') ? 'active' : '' }}">Ulasan</a>
                <a href="{{ route('lokasi') }}" class="{{ request()->routeIs('lokasi') ? 'active' : '' }}">Lokasi</a>
            </nav>

            <div style="display:flex; align-items:center;">
                <a href="{{ route('cart.index') }}" class="cart-icon-link">
                    🛒
                    @php $cartCount = count(session('cart', [])); @endphp
                    @if($cartCount > 0)
                        <span class="cart-badge">{{ $cartCount }}</span>
                    @endif
                </a>
                <a href="{{ route('kontak') }}" class="btn-primary-nav">Hubungi Kami</a>
            </div>
        </div>
    </header>

    {{-- MAIN CONTENT --}}
    <main>
        @yield('content')
    </main>

    {{-- FOOTER --}}
    <footer class="site-footer">
        <div class="site-container">
            <h3>Minimarket 24 Jam</h3>
            <p>Mendefinisikan ulang kemudahan berbelanja dengan produk premium, lingkungan bersih, dan layanan tanpa henti.</p>
            <p class="footer-copy">© {{ date('Y') }} Minimarket 24 Jam. Kebutuhan Anda, Kapan Saja.</p>
        </div>
    </footer>

</body>
</html>