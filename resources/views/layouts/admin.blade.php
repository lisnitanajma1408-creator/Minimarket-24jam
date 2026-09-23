<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin') - Supermarket 24jam</title>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body>
    <div class="admin-layout">

        {{-- SIDEBAR --}}
        <aside class="admin-sidebar">
            <div class="sidebar-brand">
                <div class="brand-icon">🏪</div>
                <div>
                    <div class="brand-title">Operasional</div>
                    <div class="brand-subtitle">Sinkronisasi Langsung 24/7</div>
                </div>
            </div>

            <a href="{{ route('admin.products.create') }}" class="btn-tambah-produk">
                + Tambah Produk
            </a>

            <nav class="sidebar-nav">
                <a href="{{ route('admin.dashboard') }}"
                   class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    Ringkasan
                </a>
                <a href="{{ route('admin.products.index') }}"
                   class="{{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                    Daftar Produk
                </a>
                <a href="{{ route('admin.inventory.index') }}"
                   class="{{ request()->routeIs('admin.inventory.*') ? 'active' : '' }}">
                    Inventaris
                </a>
                <a href="{{ route('admin.suppliers.index') }}"
                   class="{{ request()->routeIs('admin.suppliers.*') ? 'active' : '' }}">
                    Pemasok
                </a>
            </nav>

            <div class="sidebar-footer">
                <a href="#">Pusat Bantuan</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit">Keluar</button>
                </form>
            </div>
        </aside>

        {{-- MAIN CONTENT --}}
        <div class="admin-main">
            <header class="admin-header">
                <h1>Supermarket 24jam</h1>
            </header>

            <main class="admin-content">
                @yield('content')
            </main>
        </div>

    </div>
</body>
</html>