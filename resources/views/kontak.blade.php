@extends('layouts.site')

@section('title', 'Hubungi Kami')

@section('content')
    <div class="site-container kontak-wrap">

        <div class="kontak-header text-center">
            <h1>Sapa Kami Kapan Saja</h1>
            <p class="section-desc">
                Kami siap membantu Anda 24 jam sehari, 7 hari seminggu. Punya pertanyaan,
                saran, atau butuh bantuan? Tim kami selalu ada untuk Anda.
            </p>
        </div>

        @if(session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif

        <div class="kontak-layout">
            {{-- KIRI: INFO --}}
            <div class="kontak-info-col">
                <div class="kontak-info-card">
                    <div class="kontak-icon">🎧</div>
                    <h4>Layanan Pelanggan</h4>
                    <p>Hubungi kami langsung melalui telepon atau email untuk respon cepat.</p>
                    <div class="kontak-contact-item">📞 {{ $store->phone ?? '-' }}</div>
                    <div class="kontak-contact-item">✉️ {{ $store->email ?? '-' }}</div>
                </div>

                <div class="kontak-info-card">
                    <div class="kontak-icon">🔗</div>
                    <h4>Ikuti Kami</h4>
                    <p>Dapatkan update terbaru, promo, dan inspirasi harian dari Instagram kami.</p>

                    <a href="https://www.instagram.com/mm24jam.berau" target="_blank" rel="noopener noreferrer"
                       class="ig-link" aria-label="Instagram Minimarket 24 Jam">
                        <span class="ig-icon">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2"
                                 stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <rect x="3" y="3" width="18" height="18" rx="5"></rect>
                                <circle cx="12" cy="12" r="4"></circle>
                                <circle cx="17.5" cy="6.5" r="1" fill="#fff" stroke="none"></circle>
                            </svg>
                        </span>
                        <span class="ig-text">
                            <strong>@mm24jam.berau</strong>
                            <small>Ikuti di Instagram</small>
                        </span>
                    </a>
                </div>
            </div>

            {{-- KANAN: FORM --}}
            <div class="kontak-form-card">
                <h3>Kirim Pesan</h3>
                <form method="POST" action="{{ route('kontak.store') }}">
                    @csrf
                    @if($errors->any())
                        <div class="alert-error">
                            <ul>
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="form-grid">
                        <div>
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" name="name" value="{{ old('name') }}" placeholder="Nama kamu" required>
                        </div>
                        <div>
                            <label class="form-label">Email</label>
                            <input type="email" name="email" value="{{ old('email') }}" placeholder="email@contoh.com" required>
                        </div>
                    </div>

                    <label class="form-label">Subjek</label>
                    <select name="subject" required>
                        <option value="">Pilih Subjek</option>
                        <option value="Pertanyaan Umum">Pertanyaan Umum</option>
                        <option value="Keluhan">Keluhan</option>
                        <option value="Saran">Saran</option>
                        <option value="Kerjasama">Kerjasama</option>
                    </select>

                    <label class="form-label" style="margin-top:16px;">Pesan Anda</label>
                    <textarea name="message" rows="5" placeholder="Tuliskan pesan Anda secara detail..." required>{{ old('message') }}</textarea>

                    <button type="submit" class="btn-primary-nav" style="width:100%; margin-top:20px; padding:14px;">
                        Kirim Pesan ➤
                    </button>
                </form>
            </div>
        </div>

        {{-- FAQ --}}
        <div class="faq-section">
            <h2 class="text-center">Pertanyaan Seputar Layanan</h2>
            <p class="text-center section-desc">Jawaban cepat untuk pertanyaan yang sering ditanyakan mengenai layanan 24 jam kami.</p>

            <div class="faq-list">
                <details class="faq-item">
                    <summary>Apakah benar toko buka selama 24 jam penuh?</summary>
                    <p>Ya, kami buka 24 jam setiap hari tanpa libur, kecuali hari besar tertentu.</p>
                </details>
                <details class="faq-item">
                    <summary>Metode pembayaran apa saja yang diterima?</summary>
                    <p>Kami menerima pembayaran via QRIS dan COD (Bayar di Tempat) untuk kemudahan Anda.</p>
                </details>
                <details class="faq-item">
                    <summary>Apakah produk segar (sayur & buah) tersedia di malam hari?</summary>
                    <p>Ya, stok produk segar kami selalu tersedia dan dipantau kesegarannya sepanjang waktu, termasuk malam hari.</p>
                </details>
            </div>
        </div>

    </div>

    <style>
        .ig-link {
            display: inline-flex;
            align-items: center;
            gap: 14px;
            margin-top: 6px;
            padding: 10px 18px 10px 10px;
            border: 1px solid #e5e7eb;
            border-radius: 999px;
            text-decoration: none;
            color: #111827;
            transition: transform .2s ease, box-shadow .2s ease, border-color .2s ease;
        }
        .ig-link:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(214, 41, 118, .18);
            border-color: #d62976;
        }
        .ig-icon {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: radial-gradient(circle at 30% 107%, #fdf497 0%, #fdf497 5%, #fd5949 45%, #d6249f 60%, #285AEB 90%);
        }
        .ig-text { display: flex; flex-direction: column; line-height: 1.25; }
        .ig-text strong { font-size: 15px; }
        .ig-text small { font-size: 12px; color: #6b7280; }
    </style>
@endsection