@extends('layouts.site')

@section('title', 'Kode QRIS')

@section('content')
    <div class="site-container qris-wrap">
        <h1 class="text-center">Kode Qris</h1>

        <div class="qris-card">
            <img src="{{ asset('images/Qriss.jpeg') }}" alt="Kode QRIS" class="qris-image">
        </div>

        <div class="text-center">
            <a href="{{ route('checkout.success', $order->order_number) }}" class="btn-outline-nav">
                🛒 Lanjut Belanja
            </a>
        </div>
    </div>
@endsection