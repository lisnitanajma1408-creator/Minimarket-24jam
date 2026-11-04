@extends('layouts.admin')

@section('title', 'Tambah Pemasok')

@section('content')
    <div class="form-card">
        <div class="form-card-header">
            <div>
                <h2>Tambah Pemasok</h2>
                <p class="page-subtitle">Masukkan detail pemasok baru.</p>
            </div>
        </div>

        @if($errors->any())
            <div class="alert-error">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.suppliers.store') }}" method="POST">
            @csrf
            <div class="form-grid">
                <div>
                    <label class="form-label">Nama Pemasok *</label>
                    <input type="text" name="name" value="{{ old('name') }}" placeholder="cth. PT Sumber Rezeki" required>
                </div>
                <div>
                    <label class="form-label">Nama Kontak</label>
                    <input type="text" name="contact_person" value="{{ old('contact_person') }}" placeholder="cth. Budi Santoso">
                </div>
                <div>
                    <label class="form-label">Telepon</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" placeholder="cth. 08123456789">
                </div>
                <div>
                    <label class="form-label">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="cth. supplier@email.com">
                </div>
            </div>

            <div>
                <label class="form-label">Alamat</label>
                <textarea name="address" rows="3" style="width:100%; padding:12px 14px; border:1px solid #d1d5db; border-radius:8px; font-size:14px; font-family:inherit;">{{ old('address') }}</textarea>
            </div>

            <div class="form-actions">
                <a href="{{ route('admin.suppliers.index') }}" class="btn-outline">Batal</a>
                <button type="submit" class="btn-primary">Simpan</button>
            </div>
        </form>
    </div>
@endsection