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
   {{-- Bagian Kategori Utama dengan Tombol Custom kiri.png & kanan.png --}}
<div class="container py-5 position-relative">
    <div class="d-flex justify-content-between align-items-end mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1" style="letter-spacing: -1px;">Shop by Category</h2>
            <p class="text-muted mb-0">Pilih kategori sesuai kebutuhan industri dan pembelajaran.</p>
        </div>
    </div>

    <div class="position-relative slider-wrapper">
        <div class="slider-nav nav-left" id="slide-left">
            <img src="{{ asset('assets/img/kiri.png') }}" alt="Kiri" style="width: 40px; height: auto;">
        </div>

        <div class="category-slider" id="category-slider">
            @foreach($categories as $category)
            <div class="category-item">
                <a href="{{ route('products.index', ['category' => $category->slug ?? $category->id]) }}" class="text-decoration-none">
                    <div class="card h-100 border-0 shadow-sm rounded-4 p-4 category-card">
                        <div class="card-body p-0 d-flex flex-column">
                            <div class="d-flex justify-content-between align-items-start mb-4">
                                <div class="badge rounded-pill bg-white text-primary px-3 py-2 shadow-sm" style="font-size: 10px; font-weight: 700; letter-spacing: 0.5px; text-transform: uppercase;">
                                    Kategori
                                </div>
                                <div class="arrow-icon">
                                    <i class="bi bi-arrow-up-right fs-5 text-muted"></i>
                                </div>
                            </div>
                            
                            <h3 class="fw-bold text-dark mb-3" style="font-size: 20px; letter-spacing: -0.5px;">
                                {{ $category->name }}
                            </h3>
                            
                            <p class="text-muted mb-4" style="font-size: 13px; line-height: 1.6; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;">
                                {{ $category->description ?? 'Macam-macam produk kategori ' . $category->name }}
                            </p>

                            <div class="mt-auto pt-2 d-flex align-items-center text-primary fw-bold" style="font-size: 12px;">
                                <span>{{ $category->products_count ?? 0 }} Produk</span>
                                <div class="flex-grow-1 border-bottom ms-2 opacity-25"></div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            @endforeach
        </div>

        <div class="slider-nav nav-right" id="slide-right">
            <img src="{{ asset('assets/img/kanan.png') }}" alt="Kanan" style="width: 40px; height: auto;">
        </div>
    </div>
</div>

<style>
    .slider-wrapper { padding: 0 10px; position: relative; }
    
    .category-slider {
        display: flex;
        overflow-x: auto;
        gap: 20px;
        padding: 15px 5px 35px 5px;
        scroll-snap-type: x mandatory;
        scrollbar-width: none;
        -ms-overflow-style: none;
        scroll-behavior: smooth;
    }

    .category-slider::-webkit-scrollbar { display: none; }

    .category-item {
        flex: 0 0 300px;
        scroll-snap-align: start;
    }

    @media (min-width: 992px) {
        .category-item { flex: 0 0 350px; }
    }

    /* Styling Tombol Navigasi Custom */
    .slider-nav {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        z-index: 10;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .nav-left { left: -30px; }
    .nav-right { right: -30px; }

    .slider-nav:hover {
        transform: translateY(-50%) scale(1.1);
        filter: brightness(1.2);
    }

    .slider-nav:active {
        transform: translateY(-50%) scale(0.9);
    }

    .category-card {
        transition: all 0.3s ease;
        background-color: #f8f9fa;
        border: 1px solid transparent !important;
    }

    .category-card:hover {
        background-color: #ffffff !important;
        transform: translateY(-8px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.08) !important;
        border-color: #0d6efd !important;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const slider = document.getElementById('category-slider');
        const btnLeft = document.getElementById('slide-left');
        const btnRight = document.getElementById('slide-right');

        // Sesuaikan jumlah scroll dengan lebar card
        const scrollAmount = 370; 

        btnRight.addEventListener('click', () => {
            slider.scrollBy({ left: scrollAmount, behavior: 'smooth' });
        });

        btnLeft.addEventListener('click', () => {
            slider.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
        });
    });
</script>
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