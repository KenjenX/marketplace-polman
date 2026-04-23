@extends('layouts.admin')

@section('content')
<div class="admin-card">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">Daftar Produk</h2>
            <p class="text-muted mb-0">Kelola produk utama marketplace.</p>
        </div>
        <a href="{{ route('admin.products.create') }}" class="btn btn-primary">Tambah Produk</a>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kategori</th>
                    <th>Nama Produk</th>
                    <th>Slug</th>
                    <th>Jumlah Variasi</th>
                    <th>Harga Mulai Dari</th>
                    <th>Status</th>
                    <th width="240">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $product->category->name }}</td>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->slug }}</td>
                        <td>{{ $product->variants->count() }}</td>
                        <td>
                            @if($product->variants->count() > 0)
                                Rp {{ number_format($product->variants->min('price'), 0, ',', '.') }}
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            <span class="badge {{ $product->status === 'active' ? 'text-bg-success' : 'text-bg-secondary' }}">
                                {{ $product->status }}
                            </span>
                        </td>
                        <td>
                            <div class="d-flex flex-wrap gap-2">
                                <a href="{{ route('admin.products.variants.index', $product->id) }}" class="btn btn-outline-dark btn-sm">
                                    Variasi
                                </a>
                                <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-outline-primary btn-sm">
                                    Edit
                                </a>

                                <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Yakin hapus produk ini?')">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted">Belum ada produk</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection