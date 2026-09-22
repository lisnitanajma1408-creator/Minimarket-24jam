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
                <a href="#">Tentang Kami</a>
                <a href="#">Katalog</a>
                <a href="#">Keunggulan</a>
                <a href="#">Ulasan</a>
                <a href="#">Lokasi</a>
            </nav>

            <a href="#" class="btn-primary-nav">Hubungi Kami</a>
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