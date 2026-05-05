@extends('layouts.store')

@section('content')
{{-- 1. Hero Section: Full Background dengan Overlay --}}
<div class="position-relative overflow-hidden w-100" style="min-height: 600px; background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.4)), url('https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?q=80&w=1600&auto=format&fit=crop') center/cover no-repeat; margin-top: -24px;">
    <div class="container h-100">
        <div class="row h-100">
            <div class="col-lg-8">
                <div class="d-flex flex-column justify-content-center h-100 text-white p-4 p-md-5" style="min-height: 600px;">
                    <div class="mb-3">
                        {{-- Background diubah jadi Biru Polman, teks jadi Putih agar terbaca --}}
                        <span class="text-uppercase fw-bold px-3 py-2" style="background: #013780; color: #fff; font-size: 11px; letter-spacing: 2px;">
                            Marketplace Polman
                        </span>
                    </div>

                    <h1 class="display-3 fw-bold mb-4" style="line-height: 1.1; letter-spacing: -2px;">
                        Solusi Manufaktur, <br> Pengecoran Logam & Elektronik
                    </h1>

                    <p class="lead mb-5 opacity-75" style="max-width: 650px; font-size: 1.2rem; line-height: 1.6;">
                        Temukan produk manufaktur, pengecoran logam, elektronik industri, dan layanan desain dalam satu marketplace yang terstruktur dan mudah digunakan.
                    </p>

                    <div class="d-flex flex-wrap gap-3">
                        {{-- Tombol utama diubah jadi Biru Polman --}}
                        <a href="{{ route('products.index') }}" class="btn rounded-0 px-5 py-3 fw-bold text-uppercase" style="background-color: #013780; color: white; border: 1px solid #013780; font-size: 13px; letter-spacing: 1px;">
                            Lihat Produk
                        </a>
                        <a href="#kategori" class="btn btn-outline-light rounded-0 px-5 py-3 fw-bold text-uppercase" style="font-size: 13px; letter-spacing: 1px;">
                            Jelajahi Kategori
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- TAMBAHAN: Stats Bar (Kredibilitas) --}}
<div class="bg-white border-bottom py-4">
    <div class="container">
        <div class="row text-center g-4">
            <div class="col-6 col-md-3">
                <h3 class="fw-bold mb-0" style="color: #013780;">500+</h3>
                <small class="text-muted text-uppercase fw-bold" style="font-size: 10px; letter-spacing: 1px;">Produk Industri</small>
            </div>
            <div class="col-6 col-md-3">
                <h3 class="fw-bold mb-0" style="color: #013780;">10+</h3>
                <small class="text-muted text-uppercase fw-bold" style="font-size: 10px; letter-spacing: 1px;">Lab Pendukung</small>
            </div>
            <div class="col-6 col-md-3">
                <h3 class="fw-bold mb-0" style="color: #013780;">100%</h3>
                <small class="text-muted text-uppercase fw-bold" style="font-size: 10px; letter-spacing: 1px;">Karya Anak Bangsa</small>
            </div>
            <div class="col-6 col-md-3">
                <h3 class="fw-bold mb-0" style="color: #013780;">24/7</h3>
                <small class="text-muted text-uppercase fw-bold" style="font-size: 10px; letter-spacing: 1px;">Dukungan Teknis</small>
            </div>
        </div>
    </div>
</div>

{{-- 2. Section Kategori --}}
<section id="kategori" style="background: linear-gradient(180deg, #f0f7ff 0%, #ffffff 100%);">
    <div class="container py-5 position-relative">
        <div class="d-flex justify-content-between align-items-end mb-4">
            <div>
                <h2 class="fw-bold text-dark mb-1" style="letter-spacing: -1px;">Daftar Kategori</h2>
                <p class="text-muted mb-0">Pilih kategori sesuai kebutuhan industri dan pembelajaran.</p>
            </div>
        </div>

        <div class="position-relative slider-wrapper">
            @if($categories->count() >= 4)
            <div class="slider-nav nav-left" id="slide-left">
                <img src="{{ asset('assets/img/kiri.png') }}" alt="Kiri" style="width: 40px; height: auto;">
            </div>
            @endif

            <div class="category-slider" id="category-slider">
                @foreach($categories as $category)
                <div class="category-item">
                    <a href="{{ route('products.index', ['category' => $category->slug ?? $category->id]) }}" class="text-decoration-none">
                        <div class="card h-100 border-0 shadow-sm rounded-0 p-4 category-card">
                            <div class="card-body p-0 d-flex flex-column">
                                <div class="d-flex justify-content-between align-items-start mb-4">
                                    <div class="badge rounded-0 bg-white text-primary px-3 py-2 shadow-sm" style="font-size: 10px; font-weight: 700; letter-spacing: 0.5px; text-transform: uppercase;">
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

            @if($categories->count() >= 4)
            <div class="slider-nav nav-right" id="slide-right">
                <img src="{{ asset('assets/img/kanan.png') }}" alt="Kanan" style="width: 40px; height: auto;">
            </div>
            @endif
        </div>
    </div>
</section>

{{-- TAMBAHAN: Value Proposition (Alasan Beli) --}}
<section class="py-5 bg-white">
    <div class="container">
        <div class="row g-4 text-center">
            <div class="col-md-4">
                <div class="p-4">
                    <i class="bi bi-gear-fill fs-1 text-primary mb-3"></i>
                    <h5 class="fw-bold">Produk Presisi</h5>
                    <p class="text-muted small mb-0">Dikerjakan dengan standar manufaktur Polman Bandung yang sudah teruji di dunia industri.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-4 border-start border-end border-md-0">
                    <i class="bi bi-lightning-charge-fill fs-1 text-primary mb-3"></i>
                    <h5 class="fw-bold">Inovasi Terkini</h5>
                    <p class="text-muted small mb-0">Menyediakan komponen mekatronika dan IoT terbaru untuk mendukung pembelajaran Anda.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-4">
                    <i class="bi bi-people-fill fs-1 text-primary mb-3"></i>
                    <h5 class="fw-bold">Dukungan Ahli</h5>
                    <p class="text-muted small mb-0">Konsultasi langsung dengan lab teknis kami untuk setiap produk yang Anda beli.</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- 3. Section Produk Terbaru --}}
<section id="produk-terbaru" class="py-5" style="background: linear-gradient(180deg, #ffffff 0%, #f8f9fa 100%);">
    <div class="container">
        <div class="d-flex justify-content-between align-items-end mb-5">
            <div>
                <h2 class="fw-bold text-dark mb-1" style="letter-spacing: -1px;">Produk Terbaru</h2>
                <p class="text-muted mb-0">Lihat koleksi peralatan manufaktur dan elektronik terkini.</p>
            </div>
            <a href="{{ route('products.index') }}" class="text-primary fw-bold text-decoration-none small" style="letter-spacing: 1px;">
                LIHAT SEMUA <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>

        <div class="row g-4">
            @foreach($products->take(4) as $product)
            <div class="col-lg-3 col-md-6">
                {{-- SEKARANG MENGGUNAKAN SLUG --}}
                <a href="{{ route('products.show', $product->slug) }}" class="text-decoration-none group">
                    <div class="card border-0 h-100 product-card-modern bg-white shadow-sm">
                        <div class="card-img-top bg-light d-flex align-items-center justify-content-center overflow-hidden position-relative" style="height: 280px;">
                            @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" 
                                     alt="{{ $product->name }}" 
                                     class="img-fluid transition-transform duration-500 product-img"
                                     style="max-height: 80%; object-fit: contain;">
                            @else
                                <img src="{{ asset('assets/img/foto_tidak_tersedia.png') }}" 
                                     class="opacity-25" style="width: 80px;">
                            @endif
                        </div>

                        <div class="card-body px-3 pt-3">
                            <div class="text-muted text-uppercase mb-1" style="font-size: 10px; font-weight: 600; letter-spacing: 1px;">
                                {{ $product->category->name }}
                            </div>
                            
                            <h5 class="fw-bold text-dark mb-2" style="font-size: 18px; transition: 0.3s;">
                                {{ $product->name }}
                            </h5>
                            
                            <div class="text-primary fw-bold mb-3" style="font-size: 15px;">
                                @php
                                    $minPrice = $product->variants->min('price');
                                    $maxPrice = $product->variants->max('price');
                                @endphp
                                
                                @if($minPrice != $maxPrice)
                                    Rp{{ number_format($minPrice, 0, ',', '.') }} - Rp{{ number_format($maxPrice, 0, ',', '.') }}
                                @else
                                    Rp{{ number_format($minPrice, 0, ',', '.') }}
                                @endif
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- TAMBAHAN: Call to Action Bottom (Penutup) --}}
<section class="py-5" style="background: #013780;">
    <div class="container text-center text-white py-4">
        <h2 class="fw-bold mb-3">Siap Mewujudkan Proyek Anda?</h2>
        <p class="opacity-75 mb-4 mx-auto" style="max-width: 600px;">Dapatkan komponen industri berkualitas tinggi langsung dari ahlinya di Polman Bandung.</p>
        <a href="{{ route('products.index') }}" class="btn btn-light rounded-0 px-5 py-3 fw-bold text-uppercase" style="font-size: 13px; letter-spacing: 1px;">
            Mulai Belanja
        </a>
    </div>
</section>

<style>
    /* Global Reset Radius */
    #kategori .card, #produk-terbaru .card, .badge, .btn {
        border-radius: 0 !important;
    }

    /* Kategori Slider Styles */
    .slider-wrapper { padding: 0 10px; position: relative; }
    .category-slider {
        display: flex; overflow-x: auto; gap: 20px;
        padding: 15px 5px 35px 5px; scroll-snap-type: x mandatory;
        scrollbar-width: none; -ms-overflow-style: none;
        scroll-behavior: smooth; justify-content: flex-start;
    }
    .category-slider::-webkit-scrollbar { display: none; }
    .category-item { flex: 0 0 300px; scroll-snap-align: start; }
    @media (min-width: 992px) { .category-item { flex: 0 0 350px; } }

    .slider-nav {
        position: absolute; top: 50%; transform: translateY(-50%);
        z-index: 10; cursor: pointer; transition: all 0.3s ease;
    }
    .nav-left { left: -30px; }
    .nav-right { right: -30px; }
    .slider-nav:hover { transform: translateY(-50%) scale(1.1); filter: brightness(1.1); }

    .category-card {
        transition: all 0.3s ease; background-color: #ffffff;
        border: 1px solid #eee !important;
    }
    .category-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.08) !important;
        border-color: #013780 !important;
    }

    /* Produk Terbaru Styles */
    .product-card-modern { transition: all 0.3s ease; }
    .group:hover .product-img { transform: scale(1.1); }
    .group:hover h5 { color: #013780 !important; }
    .group:hover .product-card-modern { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.05) !important; }
    .transition-transform { transition: transform 0.5s cubic-bezier(0.4, 0, 0.2, 1); }
    .card-img-top { padding: 20px; }
    
    @media (max-width: 768px) {
        .border-md-0 { border: none !important; }
    }
</style>

@if($categories->count() >= 4)
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const slider = document.getElementById('category-slider');
        const btnLeft = document.getElementById('slide-left');
        const btnRight = document.getElementById('slide-right');
        const scrollAmount = 370; 
        btnRight.addEventListener('click', () => { slider.scrollBy({ left: scrollAmount, behavior: 'smooth' }); });
        btnLeft.addEventListener('click', () => { slider.scrollBy({ left: -scrollAmount, behavior: 'smooth' }); });
    });
</script>
@endif
@endsection