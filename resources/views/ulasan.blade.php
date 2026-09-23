@extends('layouts.site')

@section('title', 'Ulasan Pelanggan')

@section('content')
    <div class="site-container ulasan-wrap">

        <div class="ulasan-header">
            <div>
                <h1>Suara Pelanggan Kami</h1>
                <p class="section-desc">
                    Komitmen kami pada kesegaran dan layanan 24 jam tercermin dari
                    pengalaman nyata mereka yang berbelanja di Minimarket 24 Jam.
                </p>
            </div>
            <div class="rating-summary">
                <div class="rating-number">{{ number_format($avgRating ?? 0, 1) }}</div>
                <div>
                    <div class="rating-stars">
                        @for($i = 1; $i <= 5; $i++)
                            <span>{{ $i <= round($avgRating ?? 0) ? '★' : '☆' }}</span>
                        @endfor
                    </div>
                    <span class="rating-count">Dari {{ $totalReviews }}+ ulasan</span>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif

        <div class="ulasan-grid">
            @foreach($reviews as $review)
                <div class="review-card">
                    <div class="review-stars">
                        @for($i = 1; $i <= 5; $i++)
                            <span>{{ $i <= $review->rating ? '★' : '☆' }}</span>
                        @endfor
                        <span class="review-time">{{ $review->created_at->diffForHumans() }}</span>
                    </div>
                    <p class="review-comment">"{{ $review->comment }}"</p>
                    <div class="review-author">
                        <div class="review-avatar">{{ strtoupper(substr($review->customer_name, 0, 1)) }}</div>
                        <div>
                            <strong>{{ $review->customer_name }}</strong>
                            <span>{{ $review->role_label ?? 'Pelanggan' }}</span>
                        </div>
                    </div>
                </div>
            @endforeach

            <div class="review-card write-review-card">
                <div class="write-review-icon">✏️</div>
                <h4>Bagikan Pengalaman Anda</h4>
                <p>Ulasan Anda membantu kami menjaga standar kesegaran dan layanan terbaik.</p>
                <button type="button" class="btn-primary-nav" onclick="document.getElementById('reviewForm').scrollIntoView({behavior:'smooth'})">
                    Tulis Ulasan
                </button>
            </div>
        </div>

        <div class="pagination-wrap">
            {{ $reviews->links() }}
        </div>

        {{-- FORM TULIS ULASAN --}}
        <div class="write-review-form" id="reviewForm">
            <h3>Tulis Ulasan Kamu</h3>
            <form method="POST" action="{{ route('ulasan.store') }}">
                @csrf
                <div class="form-grid">
                    <div>
                        <label class="form-label">Nama</label>
                        <input type="text" name="customer_name" value="{{ old('customer_name') }}" required>
                    </div>
                    <div>
                        <label class="form-label">Status (opsional)</label>
                        <input type="text" name="role_label" value="{{ old('role_label') }}" placeholder="cth. Ibu Rumah Tangga">
                    </div>
                </div>

                <label class="form-label">Rating</label>
                <select name="rating" required>
                    <option value="5">★★★★★ (5)</option>
                    <option value="4">★★★★☆ (4)</option>
                    <option value="3">★★★☆☆ (3)</option>
                    <option value="2">★★☆☆☆ (2)</option>
                    <option value="1">★☆☆☆☆ (1)</option>
                </select>

                <label class="form-label" style="margin-top:16px;">Komentar</label>
                <textarea name="comment" rows="4" required>{{ old('comment') }}</textarea>

                <button type="submit" class="btn-primary-nav" style="margin-top:16px;">Kirim Ulasan</button>
            </form>
        </div>

    </div>
@endsection