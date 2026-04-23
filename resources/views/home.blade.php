@extends('layouts.store')

@section('content')
<div class="rounded-4 overflow-hidden mb-5 position-relative" style="min-height: 520px; background: linear-gradient(rgba(13,59,102,.65), rgba(13,59,102,.65)), url('https://images.unsplash.com/photo-1497366754035-f200968a6e72?q=80&w=1600&auto=format&fit=crop') center/cover no-repeat;">
    <div class="row g-0 h-100">
        <div class="col-lg-8">
            <div class="d-flex flex-column justify-content-center h-100 text-white p-4 p-md-5" style="min-height: 520px;">
                <span class="badge bg-light text-primary mb-3 px-3 py-2">Marketplace Polman</span>

                <h1 class="display-4 fw-bold mb-4">
                    Solusi Manufaktur, Pengecoran & Elektronik dalam Satu Platform
                </h1>

                <p class="lead mb-4" style="max-width: 760px;">
                    Temukan produk manufaktur, pengecoran logam, elektronik industri, dan layanan desain
                    dalam satu marketplace yang terstruktur dan mudah digunakan.
                </p>

                <div class="d-flex flex-wrap gap-3">
                    <a href="{{ route('products.index') }}" class="btn btn-light btn-lg px-4">
                        Lihat Produk
                    </a>
                    <a href="#kategori" class="btn btn-outline-light btn-lg px-4">
                        Jelajahi Kategori
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<section id="kategori" class="mb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">Kategori Utama</h2>
            <p class="text-muted mb-0">Pilih kategori sesuai kebutuhan industri dan pembelajaran.</p>
        </div>
    </div>

    <div class="row g-4">
        @forelse($categories as $category)
            <div class="col-md-6 col-xl-3">
                <div class="card border-0 shadow-sm h-100 rounded-4">
                    <div class="card-body">
                        <div class="mb-3">
                            <span class="badge bg-primary-subtle text-primary border">Kategori</span>
                        </div>
                        <h5 class="card-title">{{ $category->name }}</h5>
                        <p class="card-text text-muted">
                            {{ $category->description ?: 'Kategori produk untuk kebutuhan marketplace Polman.' }}
                        </p>
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">{{ $category->products_count }} produk</small>
                            <a href="{{ route('products.index', ['category' => $category->id]) }}" class="btn btn-outline-primary btn-sm">
                                Lihat
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-secondary mb-0">
                    Belum ada kategori.
                </div>
            </div>
        @endforelse
    </div>
</section>

<section class="mb-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">Produk Terbaru</h2>
            <p class="text-muted mb-0">Beberapa produk yang sudah tersedia di marketplace.</p>
        </div>
        <a href="{{ route('products.index') }}" class="btn btn-outline-primary">
            Semua Produk
        </a>
    </div>

    <div class="row g-4">
        @forelse($latestProducts as $product)
            <div class="col-md-6 col-xl-4">
                <div class="card border-0 shadow-sm h-100 rounded-4">
                    <div class="card-body d-flex flex-column">
                        @if($product->image)
                            <img
                                src="{{ asset('storage/' . $product->image) }}"
                                alt="{{ $product->name }}"
                                class="w-100 rounded-4 border mb-3"
                                style="height: 180px; object-fit: cover;"
                            >
                        @else
                            <div class="bg-light border rounded-4 d-flex align-items-center justify-content-center mb-3" style="height: 180px;">
                                <span class="text-muted small">Preview Produk</span>
                            </div>
                        @endif

                        <div class="mb-2">
                            <span class="badge bg-light text-dark border">{{ $product->category->name }}</span>
                        </div>

                        <h5 class="card-title">{{ $product->name }}</h5>
                        <p class="card-text text-muted small">
                            {{ \Illuminate\Support\Str::limit($product->description, 100) }}
                        </p>

                        <div class="mt-auto">
                            <p class="fw-semibold mb-3">
                                Harga mulai dari:
                                @if($product->variants->count() > 0)
                                    Rp {{ number_format($product->variants->min('price'), 0, ',', '.') }}
                                @else
                                    -
                                @endif
                            </p>

                            <a href="{{ route('products.show', $product->slug) }}" class="btn btn-primary w-100">
                                Lihat Detail
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-secondary mb-0">
                    Belum ada produk terbaru.
                </div>
            </div>
        @endforelse
    </div>
</section>
@endsection