@extends('layouts.admin')

@section('content')
<div class="admin-card">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">Variasi Produk</h2>
            <p class="text-muted mb-0">Kelola variasi untuk produk: <strong>{{ $product->name }}</strong></p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">Kembali ke Produk</a>
            <a href="{{ route('admin.products.variants.create', $product->id) }}" class="btn btn-primary">Tambah Variasi</a>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Variasi</th>
                    <th>Detail</th>
                    <th>Harga</th>
                    <th>Stok</th>
                    <th>Status</th>
                    <th width="180">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($variants as $variant)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $variant->name }}</td>
                        <td>{{ $variant->specification }}</td>
                        <td>Rp {{ number_format($variant->price, 0, ',', '.') }}</td>
                        <td>{{ $variant->stock }}</td>
                        <td>
                            <span class="badge {{ $variant->status === 'active' ? 'text-bg-success' : 'text-bg-secondary' }}">
                                {{ $variant->status }}
                            </span>
                        </td>
                        <td>
                            <div class="d-flex gap-2">
                                <a href="{{ route('admin.variants.edit', $variant->id) }}" class="btn btn-outline-primary btn-sm">
                                    Edit
                                </a>

                                <form action="{{ route('admin.variants.destroy', $variant->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Yakin hapus variasi ini?')">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted">Belum ada variasi</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection