@extends('layouts.site')

@section('title', 'Keunggulan')

@section('content')

    {{-- HERO KEUNGGULAN --}}
    <section class="keunggulan-hero">
        <img src="{{ asset('images/toko-depan.jpg') }}" alt="Toko" class="keunggulan-hero-bg">
        <div class="keunggulan-hero-overlay">
            <span class="badge-24jam">Mengapa Memilih Kami</span>
            <h1>Standar Baru Kenyamanan 24 Jam</h1>
            <p>
                Kami mendefinisikan ulang pengalaman minimarket dengan fokus pada kualitas premium,
                kesegaran terjamin, dan desain ruang yang menenangkan — kapan pun Anda membutuhkan kami.
            </p>
        </div>
    </section>

    <div class="site-container">
        <div class="keunggulan-grid">
            <div class="keunggulan-card">
                <div class="keunggulan-icon">🕐</div>
                <h4>Selalu Hadir, 24 Jam Sehari</h4>
                <img src="{{ asset('images/toko-suasana.jpg') }}" alt="Suasana Toko" class="keunggulan-card-img">
                <p>
                    Kami memahami bahwa kebutuhan tidak mengenal waktu. Baik untuk kopi segar
                    di pagi buta atau camilan premium larut malam, pintu kami selalu terbuka
                    dengan pencahayaan terang dan staf yang siap membantu.
                </p>
            </div>

            <div class="keunggulan-card">
                <div class="keunggulan-icon">📍</div>
                <h4>Lokasi Strategis di Pusat Kota</h4>
                <p>
                    Terletak di persimpangan utama distrik bisnis dan pemukiman premium.
                    Mudah diakses dengan area parkir yang luas dan aman.
                </p>
                <a href="{{ route('lokasi') }}" class="keunggulan-link">Lihat Peta Lokasi →</a>
            </div>
        </div>

        <div class="keunggulan-grid keunggulan-grid-2">
            <div class="keunggulan-card keunggulan-card-dark">
                <div class="keunggulan-icon-white">🚽</div>
                <h4>Tersedia Toilet</h4>
                <p>Pasokan harian langsung dari perkebunan lokal dan kurasi produk impor premium. Setiap rak dirancang untuk mempertahankan kualitas optimal.</p>
            </div>

            <div class="keunggulan-card">
                <div class="keunggulan-icon">✉️</div>
                <h4>Fasilitas Toko yang Nyaman & Estetik</h4>
                <div class="fasilitas-list-grid">
                    <div>
                        <strong>📶 Wi-Fi Berkecepatan Tinggi</strong>
                        <p>Tersedia gratis untuk mendukung produktivitas Anda di area duduk kami.</p>
                    </div>
                    <div>
                        <strong>🛋️ Lounge Premium</strong>
                        <p>Area bersantai ber-AC dengan desain interior bersih dan pencahayaan optimal.</p>
                    </div>
                    <div>
                        <strong>☕ Fresh Bar</strong>
                        <p>Nikmati kopi seduh manual, jus peras dingin, dan aneka pastry hangat.</p>
                    </div>
                    <div>
                        <strong>🧼 Sanitasi Terjaga</strong>
                        <p>Pembersihan berkala standar farmasi untuk memastikan kenyamanan berbelanja.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection