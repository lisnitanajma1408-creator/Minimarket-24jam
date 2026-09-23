@extends('layouts.admin')

@section('content')
<h1>Daftar Pemasok</h1>

@if(session('success'))
    <div style="padding:10px; background:#d1fae5; margin-bottom:15px;">{{ session('success') }}</div>
@endif

<a href="{{ route('admin.suppliers.create') }}">+ Tambah Pemasok</a>

<table border="1" cellpadding="8" style="width:100%; border-collapse:collapse; margin-top:15px;">
    <thead>
        <tr>
            <th>Nama</th>
            <th>Kontak</th>
            <th>Telepon</th>
            <th>Email</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach($suppliers as $supplier)
        <tr>
            <td>{{ $supplier->name }}</td>
            <td>{{ $supplier->contact_person ?? '-' }}</td>
            <td>{{ $supplier->phone ?? '-' }}</td>
            <td>{{ $supplier->email ?? '-' }}</td>
            <td>
                <a href="{{ route('admin.suppliers.edit', $supplier) }}">Edit</a>
                <form action="{{ route('admin.suppliers.destroy', $supplier) }}" method="POST" style="display:inline;" onsubmit="return confirm('Yakin hapus?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit">Hapus</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

{{ $suppliers->links() }}
@endsection