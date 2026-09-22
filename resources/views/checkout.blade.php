@extends('layouts.site')

@section('title', 'Checkout')

@section('content')
    <div class="site-container checkout-wrap">

        <div class="checkout-steps">
            <div class="step done">✓ <span>Keranjang</span></div>
            <div class="step-line"></div>
            <div class="step active">2 <span>Pengiriman</span></div>
            <div class="step-line"></div>
            <div class="step">3 <span>Pembayaran</span></div>
            <div class="step-line"></div>
            <div class="step">4 <span>Selesai</span></div>
        </div>

        <div class="checkout-layout">

            {{-- FORM KIRI --}}
            <div class="checkout-form-card">
                <form method="POST" action="{{ route('checkout.store') }}">
                    @csrf

                    <h3>📍 Alamat Pengiriman</h3>

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
                            <label class="form-label">Nama Penerima</label>
                            <input type="text" name="customer_name" value="{{ old('customer_name') }}"
                                   placeholder="Masukkan nama lengkap" required>
                        </div>
                        <div>
                            <label class="form-label">Email</label>
                            <input type="email" name="customer_email" value="{{ old('customer_email') }}"
                                   placeholder="Contoh: nama@gmail.com" required>
                        </div>
                    </div>

                    <label class="form-label">Informasi Lengkap</label>
                    <textarea name="address_detail" rows="3"
                              placeholder="Nama jalan, gedung, no. rumah/unit, no.handphone" required>{{ old('address_detail') }}</textarea>

                    <div style="max-width:220px; margin-top:20px;">
                        <label class="form-label">Kode Pos</label>
                        <input type="text" name="postal_code" value="{{ old('postal_code') }}" placeholder="Contoh: 12345">
                    </div>

                    <h3 style="margin-top:32px;">🚚 Pilihan Pengiriman</h3>
                    <div class="option-grid">
                        <label class="option-card">
                            <input type="radio" name="shipping_method" value="ojek_online" checked>
                            <strong>Di ambil lewat Ojek Online</strong>
                            <span>Ambil hari ini</span>
                            <span class="option-price">Gratis</span>
                        </label>
                        <label class="option-card">
                            <input type="radio" name="shipping_method" value="ambil_toko">
                            <strong>Ambil di Toko</strong>
                            <span>Ambil hari ini</span>
                            <span class="option-price">Gratis</span>
                        </label>
                    </div>

                    <h3 style="margin-top:32px;">💳 Metode Pembayaran</h3>
                    <div class="option-grid">
                        <label class="option-card">
                            <input type="radio" name="payment_method" value="qris" checked>
                            <strong>💳 Kode QR</strong>
                        </label>
                        <label class="option-card">
                            <input type="radio" name="payment_method" value="cod">
                            <strong>💵 Bayar di Tempat (COD)</strong>
                        </label>
                    </div>

                    <label style="display:flex; align-items:center; gap:8px; margin-top:24px; font-size:14px;">
                        <input type="checkbox" name="send_receipt_email" value="1" checked>
                        Kirim struk ke email
                    </label>

                    <button type="submit" class="btn-primary-nav" style="width:100%; margin-top:24px; padding:14px;">
                        Buat Pesanan
                    </button>
                </form>
            </div>

            {{-- RINGKASAN KANAN --}}
            <div class="checkout-summary-card">
                <h3>📋 Ringkasan Pesanan</h3>
                @foreach($items as $item)
                    <div class="summary-item-row">
                        <span>{{ $item['product']->name }} ({{ $item['qty'] }})</span>
                        <span>{{ 'Rp' . number_format($item['subtotal'], 0, ',', '.') }}</span>
                    </div>
                @endforeach

                <div class="summary-divider"></div>

                <div class="summary-item-row">
                    <span>Subtotal</span>
                    <span>{{ 'Rp' . number_format($subtotal, 0, ',', '.') }}</span>
                </div>
                <div class="summary-item-row">
                    <span>Ongkos Kirim</span>
                    <span>{{ 'Rp' . number_format($shippingCost, 0, ',', '.') }}</span>
                </div>

                <div class="summary-divider"></div>

                <div class="summary-total-row">
                    <span>Total</span>
                    <strong>{{ 'Rp' . number_format($total, 0, ',', '.') }}</strong>
                </div>
                <p class="summary-note">Termasuk PPN jika berlaku</p>
            </div>

        </div>
    </div>
@endsection