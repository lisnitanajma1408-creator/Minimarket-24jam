@extends('layouts.admin')

@section('title', 'Daftar Pemasok')

@section('content')
    <div class="page-header">
        <div>
            <h2>Daftar Pemasok</h2>
            <p class="page-subtitle">Kelola data pemasok dan kontak mereka.</p>
        </div>
        <a href="{{ route('admin.suppliers.create') }}" class="btn-primary" style="text-decoration:none; display:inline-flex; align-items:center;">
            + Tambah Pemasok
        </a>
    </div>

    @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif

    <div class="panel">
        <table class="product-table">
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
                @forelse($suppliers as $supplier)
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
                                <button type="submit" class="btn-link-danger">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">Belum ada pemasok.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="pagination-wrap">
        {{ $suppliers->links() }}
    </div>
@endsection