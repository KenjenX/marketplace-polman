@extends('layouts.store')

@section('content')
<div class="content-card">
    <div class="row g-4">
        <div class="col-lg-5">
            <div class="bg-light border rounded-4 d-flex align-items-center justify-content-center" style="min-height: 320px;">
                <span class="text-muted">Gambar produk akan ditampilkan di sini</span>
            </div>
        </div>

        <div class="col-lg-7">
            <div class="mb-2">
                <span class="badge bg-primary-subtle text-primary border">{{ $product->category->name }}</span>
            </div>

            <h1 class="mb-3">{{ $product->name }}</h1>
            <p class="text-muted">{{ $product->description }}</p>

            <hr>

            <h4 class="mb-3">Variasi / Spesifikasi</h4>

            @forelse($product->variants as $variant)
                <div class="border rounded-4 p-3 mb-3">
                    <div class="d-flex flex-column flex-md-row justify-content-between gap-3">
                        <div>
                            <h5 class="mb-1">{{ $variant->name }}</h5>
                            <p class="mb-2 text-muted">{{ $variant->specification }}</p>
                            <div class="fw-semibold mb-1">
                                Rp {{ number_format($variant->price, 0, ',', '.') }}
                            </div>
                            <small class="text-muted">Stok tersedia: {{ $variant->stock }}</small>
                        </div>

                        <div class="d-flex align-items-center">
                            @auth
                                @if($variant->stock > 0)
                                    <form action="{{ route('cart.add', $variant->id) }}" method="POST" class="d-flex flex-column gap-2">
                                        @csrf
                                        <input type="number" name="quantity" value="1" min="1" max="{{ $variant->stock }}" class="form-control">
                                        <button type="submit" class="btn btn-primary">
                                            Tambah ke Keranjang
                                        </button>
                                    </form>
                                @else
                                    <span class="badge text-bg-secondary">Stok Habis</span>
                                @endif
                            @else
                                <a href="{{ route('login') }}" class="btn btn-outline-primary">
                                    Login untuk membeli
                                </a>
                            @endauth
                        </div>
                    </div>
                </div>
            @empty
                <div class="alert alert-secondary mb-0">
                    Belum ada variasi untuk produk ini.
                </div>
            @endforelse

            <a href="{{ route('products.index') }}" class="btn btn-link px-0 mt-3">← Kembali ke daftar produk</a>
        </div>
    </div>
</div>
@endsection