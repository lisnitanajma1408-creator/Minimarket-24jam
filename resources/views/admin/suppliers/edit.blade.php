@extends('layouts.admin')

@section('title', 'Edit Pemasok')

@section('content')
    <div class="form-card">
        <div class="form-card-header">
            <div>
                <h2>Edit Pemasok</h2>
                <p class="page-subtitle">Perbarui detail pemasok.</p>
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

        <form action="{{ route('admin.suppliers.update', $supplier) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-grid">
                <div>
                    <label class="form-label">Nama Pemasok *</label>
                    <input type="text" name="name" value="{{ old('name', $supplier->name) }}" required>
                </div>
                <div>
                    <label class="form-label">Nama Kontak</label>
                    <input type="text" name="contact_person" value="{{ old('contact_person', $supplier->contact_person) }}">
                </div>
                <div>
                    <label class="form-label">Telepon</label>
                    <input type="text" name="phone" value="{{ old('phone', $supplier->phone) }}">
                </div>
                <div>
                    <label class="form-label">Email</label>
                    <input type="email" name="email" value="{{ old('email', $supplier->email) }}">
                </div>
            </div>

            <div>
                <label class="form-label">Alamat</label>
                <textarea name="address" rows="3" style="width:100%; padding:12px 14px; border:1px solid #d1d5db; border-radius:8px; font-size:14px; font-family:inherit;">{{ old('address', $supplier->address) }}</textarea>
            </div>

            <div class="form-actions">
                <a href="{{ route('admin.suppliers.index') }}" class="btn-outline">Batal</a>
                <button type="submit" class="btn-primary">Update</button>
            </div>
        </form>
    </div>
@endsection