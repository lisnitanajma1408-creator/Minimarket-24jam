@extends('layouts.site')

@section('title', 'Beranda')

@section('content')

    {{-- HERO SECTION --}}
    <section class="hero-section">
        <div class="site-container hero-grid">
            <div>
                <span class="badge-24jam">🕐 BUKA 24 JAM</span>
                <h1 class="hero-title">Kebutuhan Sehari-hari, Kapan Saja.</h1>
                <p class="hero-desc">
                    Nikmati kenyamanan berbelanja di tempat yang bersih, terang, dan selalu siap melayani Anda,
                    baik siang maupun malam. Kami menghadirkan produk segar dan berkualitas premium untuk kebutuhan harian Anda.
                </p>
                <div class="hero-buttons">
                    <a href="{{ route('katalog') }}" class="btn-primary-nav">Lihat Produk</a>
                    <a href="#lokasi" class="btn-outline-nav">Lihat Lokasi</a>
                </div>
            </div>
            <div class="hero-image-wrap">
                <img src="{{ asset('images/toko-depan.jpg') }}" alt="Foto Toko" class="hero-image">
            </div>
        </div>
    </section>

    {{-- TENTANG KAMI --}}
    <section class="about-section" id="tentang-kami">
        <div class="site-container about-grid">
            <img src="{{ asset('images/toko-suasana.jpg') }}" alt="Suasana Toko" class="about-image">
            <div>
                <span class="section-label">TENTANG KAMI</span>
                <h2>Lebih dari Sekadar Minimarket.</h2>
                <p class="section-desc">
                    Kami meredefinisi pengalaman minimarket dengan menghadirkan suasana ala butik grocer
                    yang premium, bersih, dan terorganisir rapi. Berkomitmen untuk menyediakan produk segar
                    dan kebutuhan esensial dengan standar kualitas tertinggi, kapan pun Anda membutuhkannya.
                </p>
                <div class="about-stats">
                    <div class="about-stat">
                        <strong>24 Jam</strong>
                        <span>Layanan Tanpa Henti</span>
                    </div>
                    <div class="about-stat">
                        <strong>7 Hari</strong>
                        <span>Buka Setiap Hari</span>
                    </div>
                    <div class="about-stat">
                        <strong>{{ $categories->count() }}+ Kategori</strong>
                        <span>Produk Lengkap</span>
                    </div>
                    <div class="about-stat">
                        <strong>1 Tempat</strong>
                        <span>Untuk Semua Kebutuhan</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- KATEGORI PRODUK --}}
    <section class="category-section">
        <div class="site-container">
            <h2 class="text-center">Kategori Produk</h2>
            <p class="text-center section-desc">Pilihan lengkap untuk penuhi kebutuhan harian Anda.</p>

            <div class="category-grid">
                @foreach($categories as $category)
                    <a href="{{ route('katalog', ['category' => $category->id]) }}" class="category-card">
                        {{ $category->name }}
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    {{-- KEUNGGULAN --}}
    <section class="advantage-section" id="keunggulan">
        <div class="site-container">
            <h2 class="text-center">Keunggulan Kami</h2>
            <p class="text-center section-desc">Alasan kenapa Anda harus belanja di sini.</p>
            <div class="advantage-grid">
                <div class="advantage-card">
                    <h3>Buka 24 Jam</h3>
                    <p>Siap melayani kapan pun Anda butuh, siang maupun malam.</p>
                </div>
                <div class="advantage-card">
                    <h3>Produk Segar</h3>
                    <p>Kualitas premium dengan standar kesegaran terjaga.</p>
                </div>
                <div class="advantage-card">
                    <h3>Harga Bersahabat</h3>
                    <p>Kebutuhan harian dengan harga yang ramah di kantong.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- ULASAN --}}
    <section class="review-section" id="ulasan">
        <div class="site-container">
            <h2 class="text-center">Apa Kata Pelanggan</h2>
            <p class="text-center section-desc">Pengalaman belanja dari pelanggan setia kami.</p>
            <div class="review-grid">
                <div class="review-card">
                    <p>"Tempatnya bersih, produk lengkap, dan buka 24 jam jadi sangat membantu!"</p>
                    <strong>- Pelanggan Setia</strong>
                </div>
            </div>
        </div>
    </section>

    {{-- LOKASI --}}
    <section class="location-section" id="lokasi">
        <div class="site-container">
            <h2 class="text-center">Lokasi Kami</h2>
            <p class="text-center section-desc">Kunjungi toko kami atau hubungi untuk informasi lebih lanjut.</p>
            <div class="location-info text-center">
                <p><strong>Alamat:</strong> (isi alamat toko di sini)</p>
                <p><strong>Telepon:</strong> (isi nomor telepon di sini)</p>
            </div>
        </div>
    </section>

@endsection