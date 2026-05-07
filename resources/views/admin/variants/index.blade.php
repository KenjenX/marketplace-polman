@extends('layouts.admin')

@section('content')
<style>
    /* Styling agar tabel terlihat lebih lega dan profesional */
    .admin-card { border: none; box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.05); }
    .table thead th { 
        background-color: #f8f9fa; 
        text-transform: uppercase; 
        font-size: 11px; 
        letter-spacing: 0.5px;
        font-weight: 700;
        color: #6c757d;
        padding: 15px 10px;
    }
    .variant-img {
        width: 50px;
        height: 50px;
        object-fit: cover;
        border-radius: 8px;
        border: 1px solid #eee;
    }
    /* Memastikan tombol aksi terlihat jelas dan tidak gepeng */
    .btn-custom-action {
        padding: 6px 15px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        border-radius: 6px;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        text-decoration: none;
    }
</style>

<div class="admin-card p-4 rounded-4 bg-white">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1" style="color: #013780;">Variasi Produk</h2>
            <p class="text-muted small mb-0">Produk: <strong>{{ $product->name }}</strong></p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary btn-sm px-3 rounded-pill fw-bold">KEMBALI</a>
            <a href="{{ route('admin.products.variants.create', $product->id) }}" class="btn btn-primary btn-sm px-3 rounded-pill fw-bold" style="background-color: #013780; border: none;">TAMBAH VARIASI</a>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle border">
            <thead>
                <tr>
                    <th class="text-center" width="50">No</th>
                    <th width="70">Foto</th>
                    <th>Nama Variasi</th>
                    <th>Detail</th>
                    <th>Harga</th>
                    <th class="text-center">Stok</th>
                    <th width="200" class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($variants as $variant)
                    <tr>
                        <td class="text-center fw-bold text-muted">{{ $loop->iteration }}</td>
                        <td>
                            @if($variant->image)
                                <img src="{{ asset('storage/' . $variant->image) }}" class="variant-img shadow-sm">
                            @else
                                <div class="variant-img bg-light d-flex align-items-center justify-content-center text-muted small">
                                    N/A
                                </div>
                            @endif
                        </td>
                        <td class="fw-bold">{{ $variant->name }}</td>
                        <td><small class="text-muted">{{ Str::limit($variant->specification, 40) }}</small></td>
                        <td class="fw-bold text-primary">Rp {{ number_format($variant->price, 0, ',', '.') }}</td>
                        <td class="text-center">
                            @if($variant->stock <= 5)
                                <span class="badge bg-danger">Hampir Habis: {{ $variant->stock }}</span>
                            @else
                                <span class="fw-bold">{{ $variant->stock }}</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex gap-2 justify-content-center">
                                {{-- Tombol Edit dengan Teks Jelas --}}
                                <a href="{{ route('admin.variants.edit', $variant->id) }}" class="btn btn-primary btn-custom-action">
                                   EDIT
                                </a>

                                {{-- Tombol Hapus dengan Teks Jelas --}}
                                <form action="{{ route('admin.variants.destroy', $variant->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-custom-action" onclick="return confirm('Yakin ingin menghapus variasi ini?')">
                                        HAPUS
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted italic">Belum ada variasi produk.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection