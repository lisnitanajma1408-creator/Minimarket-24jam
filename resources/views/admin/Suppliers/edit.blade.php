@extends('layouts.admin')

@section('content')
<h1>Edit Pemasok</h1>

<form action="{{ route('admin.suppliers.update', $supplier) }}" method="POST">
    @csrf
    @method('PUT')
    <label>Nama Pemasok *</label><br>
    <input type="text" name="name" value="{{ old('name', $supplier->name) }}" required><br><br>

    <label>Nama Kontak</label><br>
    <input type="text" name="contact_person" value="{{ old('contact_person', $supplier->contact_person) }}"><br><br>

    <label>Telepon</label><br>
    <input type="text" name="phone" value="{{ old('phone', $supplier->phone) }}"><br><br>

    <label>Email</label><br>
    <input type="email" name="email" value="{{ old('email', $supplier->email) }}"><br><br>

    <label>Alamat</label><br>
    <textarea name="address">{{ old('address', $supplier->address) }}</textarea><br><br>

    <button type="submit">Update</button>
    <a href="{{ route('admin.suppliers.index') }}">Batal</a>
</form>
@endsection