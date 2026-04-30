@extends('layouts.store')

<style>
    .product-price span {
        letter-spacing: -0.2px; /* Biar angka harga lebih rapat dan elegan */
    }
    
    /* Hover effect untuk card agar lebih hidup di monitor Omen */
    .product-card-clean:hover {
        transform: translateY(-5px);
    }
</style>

@section('content')
<div class="container-fluid py-4">
    {{-- 1. HEADER & SEARCH --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-5">
        <h2 class="fw-bold mb-3 mb-md-0">Produk</h2>
        
        {{-- Search Bar Minimalis --}}
        <form method="GET" action="{{ route('products.index') }}" style="max-width: 300px; width: 100%;">
            <div class="input-group input-group-sm border-bottom">
                <input type="text" name="search" value="{{ request('search') }}" class="form-control border-0 bg-transparent px-0 shadow-none" placeholder="Cari produk...">
                <button class="btn border-0 text-muted" type="submit">
                    <i class="bi bi-search"></i>
                </button>
            </div>
        </form>
    </div>

    {{-- 2. NAVIGASI FILTER --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-5 py-2 border-top border-bottom">
        
        {{-- List Kategori Horizontal Menggunakan Slug --}}
        <div class="d-flex flex-wrap gap-2 align-items-center">
            <a href="{{ route('products.index') }}" 
               class="btn btn-sm rounded-pill px-3 {{ !request('category') ? 'btn-primary' : 'btn-outline-secondary border-0' }}">
                All Product
            </a>
            @foreach($categories as $category)
                {{-- PERBAIKAN: Menggunakan $category->slug untuk filter --}}
                <a href="{{ route('products.index', ['category' => $category->slug]) }}" 
                   class="btn btn-sm rounded-pill px-3 {{ request('category') == $category->slug ? 'btn-primary' : 'btn-outline-secondary border-0' }}">
                   {{ $category->name }}
                </a>
            @endforeach
        </div>

        {{-- Sort By --}}
        <div class="dropdown">
            <button class="btn btn-sm btn-outline-secondary border-0 dropdown-toggle rounded-pill px-3" type="button" data-bs-toggle="dropdown">
                @switch(request('sort'))
                    @case('az') A - Z @break
                    @case('za') Z - A @break
                    @case('price_low') Termurah @break
                    @case('price_high') Termahal @break
                    @default Sort By
                @endswitch
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-0">
                <li><a class="dropdown-item small" href="{{ request()->fullUrlWithQuery(['sort' => 'newest']) }}">Terbaru</a></li>
                <li><a class="dropdown-item small" href="{{ request()->fullUrlWithQuery(['sort' => 'az']) }}">A - Z (Nama)</a></li>
                <li><a class="dropdown-item small" href="{{ request()->fullUrlWithQuery(['sort' => 'za']) }}">Z - A (Nama)</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item small" href="{{ request()->fullUrlWithQuery(['sort' => 'price_low']) }}">Harga: Rendah ke Tinggi</a></li>
                <li><a class="dropdown-item small" href="{{ request()->fullUrlWithQuery(['sort' => 'price_high']) }}">Harga: Tinggi ke Rendah</a></li>
            </ul>
        </div>
    </div>

    {{-- 3. GRID PRODUK --}}
    <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5 g-4">
        @forelse($products as $product)
            <div class="col">
                <a href="{{ route('products.show', $product->slug) }}" class="text-decoration-none text-dark">
                    <div class="product-card-clean" style="transition: 0.3s;">
                        
                        {{-- Gambar --}}
                        <div style="width: 100%; height: 280px; background: #f9f9f9; border-radius: 0; overflow: hidden; display: flex; align-items: center; justify-content: center;">
                            @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" style="max-width: 90%; max-height: 90%; object-fit: contain;">
                            @else
                                <img src="{{ asset('assets/img/foto_tidak_tersedia.png') }}" style="max-width: 60px; opacity: 0.1;">
                            @endif
                        </div>

                        {{-- Info --}}
                        <div style="padding-top: 15px; text-align: left;">
                            <small style="font-size: 10px; text-transform: uppercase; color: #999; letter-spacing: 1px; display: block; margin-bottom: 4px;">
                                {{ $product->category->name }}
                            </small>
                            <h6 style="font-size: 14px; font-weight: 700; margin-bottom: 4px;">
                                {{ $product->name }}
                            </h6>
                            <div class="product-price" style="font-size: 13px; color: #013780; font-weight: 600;">
                                @php 
                                    $minPrice = $product->variants->min('price');
                                    $maxPrice = $product->variants->max('price');
                                @endphp

                                @if($product->variants->count() > 1 && $minPrice != $maxPrice)
                                    Rp{{ number_format($minPrice, 0, ',', '.') }} - Rp{{ number_format($maxPrice, 0, ',', '.') }}
                                @elseif($product->variants->count() > 0)
                                    Rp{{ number_format($minPrice, 0, ',', '.') }}
                                @else
                                    <span class="text-muted fw-normal italic">Harga belum tersedia</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        @empty
            <div class="col-12 text-center py-5 text-muted">
                Produk tidak ditemukan.
            </div>
        @endforelse
    </div>

    {{-- 4. PAGINATION --}}
    <div class="d-flex justify-content-center mt-5 mb-5">
        <div class="custom-pagination">
            @if ($products->total() <= $products->perPage())
                <ul class="pagination">
                    <li class="page-item active">
                        <span class="page-link">1</span>
                    </li>
                </ul>
            @else
                {{ $products->links() }}
            @endif
        </div>
    </div>

    <style>
        .custom-pagination nav div:first-child { display: none; }
        .custom-pagination .pagination {
            display: flex;
            gap: 12px;
            list-style: none;
            padding: 0;
        }
        .custom-pagination .page-item .page-link {
            border: none;
            background: transparent;
            color: #888;
            width: 35px;
            height: 35px;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            font-weight: 600;
        }
        .custom-pagination .page-item.active .page-link {
            background-color: #013780; 
            color: #fff !important;
            border-radius: 50%;
        }
    </style>
</div>
@endsection