@extends('layouts.admin')

@section('content')
<h1>Tambah Pemasok</h1>

<form action="{{ route('admin.suppliers.store') }}" method="POST">
    @csrf
    <label>Nama Pemasok *</label><br>
    <input type="text" name="name" value="{{ old('name') }}" required><br><br>

    <label>Nama Kontak</label><br>
    <input type="text" name="contact_person" value="{{ old('contact_person') }}"><br><br>

    <label>Telepon</label><br>
    <input type="text" name="phone" value="{{ old('phone') }}"><br><br>

    <label>Email</label><br>
    <input type="email" name="email" value="{{ old('email') }}"><br><br>

    <label>Alamat</label><br>
    <textarea name="address">{{ old('address') }}</textarea><br><br>

    <button type="submit">Simpan</button>
    <a href="{{ route('admin.suppliers.index') }}">Batal</a>
</form>
@endsection