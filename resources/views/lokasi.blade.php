@extends('layouts.site')

@section('title', 'Lokasi')

@section('content')
    <div class="site-container lokasi-wrap">

        <div class="lokasi-header text-center">
            <h1>Temukan Kesegaran <span class="text-accent">Kapan Saja</span></h1>
            <p class="section-desc">
                Minimarket 24 Jam hadir di pusat kota, menyajikan produk segar dan berkualitas tinggi
                setiap saat, siang dan malam. Kunjungi kami untuk pengalaman berbelanja premium.
            </p>
        </div>

        <div class="lokasi-layout">
            {{-- PETA --}}
            <div class="map-embed">
                @if($store && $store->address)
                    <iframe
                        src="https://www.google.com/maps?q={{ urlencode($store->address) }}&output=embed"
                        width="100%" height="100%" style="border:0;" allowfullscreen loading="lazy">
                    </iframe>
                @else
                    <div class="map-placeholder">Alamat toko belum diatur.</div>
                @endif
            </div>

            {{-- INFO KANAN --}}
            <div class="lokasi-info">
                <div class="lokasi-info-card">
                    <h4>🕐 Jam Operasional</h4>
                    <div class="lokasi-hours-row">
                        <span>Senin - Minggu</span>
                        <strong>{{ $store->operating_hours ?? '24 Jam Buka' }}</strong>
                    </div>
                    <div class="lokasi-status">
                        <span class="dot-online"></span> Saat ini Buka - Melayani Anda
                    </div>
                </div>

                <div class="lokasi-info-card">
                    <h4>🅿️ Fasilitas Toko</h4>
                    <ul class="facility-list">
                        <li>✅ Area Parkir Luas & Mobil/Motor</li>
                        <li>♿ Toilet</li>
                        <li>🛡️ Operasional 24jam & Security</li>
                        <li>📶 Free Wi-Fi Area</li>
                    </ul>
                </div>
            </div>
        </div>

        @if($store)
        <div class="lokasi-address-box">
            <h3>{{ $store->store_name }}</h3>
            <p>{{ $store->address }}</p>
            <a href="https://www.google.com/maps?q={{ urlencode($store->address) }}" target="_blank" class="btn-primary-nav">
                Buka di Google Maps
            </a>
        </div>
        @endif

    </div>
@endsection