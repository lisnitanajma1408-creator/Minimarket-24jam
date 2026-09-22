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
                    <a href="#" class="btn-primary-nav">Lihat Produk</a>
                    <a href="#" class="btn-outline-nav">Lihat Lokasi</a>
                </div>
            </div>
            <div class="hero-image-wrap">
                <img src="{{ asset('images/toko-depan.jpg') }}" alt="Foto Toko" class="hero-image">
            </div>
        </div>
    </section>

    {{-- TENTANG KAMI --}}
    <section class="about-section">
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
                    <a href="#" class="category-card">
                        {{ $category->name }}
                    </a>
                @endforeach
            </div>
        </div>
    </section>

@endsection