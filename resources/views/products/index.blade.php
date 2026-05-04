@extends('layouts.store')

<style>
    .product-price span { letter-spacing: -0.2px; }
    .product-card-clean:hover { transform: translateY(-5px); }
    .sidebar-filter { position: sticky; top: 100px; }
    
    .form-check-input:checked {
        background-color: #013780;
        border-color: #013780;
    }

    /* CSS Select Modern */
    .modern-select {
        background-color: #f8f9fa;
        border: none;
        border-radius: 12px;
        box-shadow: inset 0 1px 3px rgba(0,0,0,0.02);
        transition: all 0.2s ease;
    }
    .modern-select:hover, .modern-select:focus {
        background-color: #f1f3f5;
        box-shadow: 0 0 0 3px rgba(1, 55, 128, 0.1);
    }

    .range-slider-container {
        position: relative;
        width: 100%;
        height: 40px;
        margin-top: 10px;
    }
    .slider-track {
        width: 100%;
        height: 4px;
        background-color: #e9ecef;
        position: absolute;
        margin: auto;
        top: 0; bottom: 0;
        border-radius: 5px;
    }
    .range-slider-container input[type="range"] {
        -webkit-appearance: none;
        appearance: none;
        width: 100%;
        outline: none;
        position: absolute;
        margin: auto;
        top: 0; bottom: 0;
        background-color: transparent;
        pointer-events: none;
    }
    .range-slider-container input[type="range"]::-webkit-slider-thumb {
        -webkit-appearance: none;
        height: 18px; width: 18px;
        background-color: #fff;
        border: 2px solid #013780;
        border-radius: 50%;
        cursor: pointer;
        pointer-events: auto;
        box-shadow: 0 2px 6px rgba(0,0,0,0.2);
    }
    .range-slider-container input[type="range"]::-moz-range-thumb {
        -moz-appearance: none;
        height: 18px; width: 18px;
        background-color: #fff;
        border: 2px solid #013780;
        border-radius: 50%;
        cursor: pointer;
        pointer-events: auto;
        box-shadow: 0 2px 6px rgba(0,0,0,0.2);
    }
</style>

@section('content')
<div class="container-fluid px-lg-5 py-4">
    <div class="row">
        
        {{-- ==========================================
             1. SIDEBAR KIRI (FILTER, SEARCH & SORT)
        =========================================== --}}
        <div class="col-lg-3 mb-4">
            <div class="sidebar-filter pe-lg-4">
                <h2 class="fw-bold mb-4">Produk</h2>

                <form method="GET" action="{{ route('products.index') }}" id="filterForm">
                    
                    {{-- Search Bar --}}
                    <div class="input-group input-group-sm border-bottom border-secondary pb-1 mb-4">
                        <input type="text" name="search" value="{{ request('search') }}" class="form-control border-0 bg-transparent px-0 shadow-none" style="font-size: 14px;" placeholder="Cari produk...">
                        <button class="btn border-0 text-muted" type="submit">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>

                    {{-- Urutkan Berdasarkan (Desain Dipercantik) --}}
                    <h6 class="fw-bold mb-2 text-uppercase text-muted" style="font-size: 12px; letter-spacing: 1px;">Urutkan</h6>
                    <div class="mb-4">
                        <select name="sort" class="form-select modern-select py-2 px-3 text-dark fw-medium shadow-none" style="font-size: 13px; cursor: pointer;" onchange="submitFilter()">
                            <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Terbaru</option>
                            <option value="az" {{ request('sort') == 'az' ? 'selected' : '' }}>A - Z (Nama)</option>
                            <option value="za" {{ request('sort') == 'za' ? 'selected' : '' }}>Z - A (Nama)</option>
                            <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Harga: Terendah</option>
                            <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Harga: Tertinggi</option>
                        </select>
                    </div>

                    {{-- Filter Harga --}}
                    <h6 class="fw-bold mb-2 text-uppercase text-muted" style="font-size: 12px; letter-spacing: 1px;">Harga</h6>
                    <div class="mb-4">
                        <div class="range-slider-container">
                            <div class="slider-track"></div>
                            <input type="range" name="min_price" id="slider-1" 
                                   min="{{ $globalMinPrice }}" max="{{ $globalMaxPrice }}" 
                                   value="{{ request('min_price', $globalMinPrice) }}" 
                                   oninput="slideOne()" onchange="submitFilter()">
                                   
                            <input type="range" name="max_price" id="slider-2" 
                                   min="{{ $globalMinPrice }}" max="{{ $globalMaxPrice }}" 
                                   value="{{ request('max_price', $globalMaxPrice) }}" 
                                   oninput="slideTwo()" onchange="submitFilter()">
                        </div>
                        <div class="d-flex justify-content-between text-dark fw-bold" style="font-size: 13px;">
                            <span id="display-min">Rp0</span>
                            <span id="display-max">Rp0</span>
                        </div>
                    </div>

                    {{-- Kategori List --}}
                    <h6 class="fw-bold mb-3 text-uppercase text-muted" style="font-size: 12px; letter-spacing: 1px;">Bidang / Kategori</h6>
                    <div class="mb-4">
                        @foreach($categories as $category)
                            <div class="form-check mb-2">
                                <input class="form-check-input shadow-none" type="checkbox" name="categories[]" value="{{ $category->slug }}" id="cat_{{ $category->id }}"
                                       {{ in_array($category->slug, request('categories', [])) ? 'checked' : '' }}
                                       onchange="submitFilter()">
                                <label class="form-check-label text-dark w-100 d-flex justify-content-between" style="font-size: 14px; cursor: pointer;" for="cat_{{ $category->id }}">
                                    <span>{{ $category->name }}</span>
                                    <span class="text-muted">({{ $category->products_count }})</span>
                                </label>
                            </div>
                        @endforeach
                    </div>

                    {{-- Tombol Reset --}}
                    @if(request()->hasAny(['search', 'categories', 'min_price', 'max_price', 'sort']))
                        <div class="d-grid mt-4">
                            <a href="{{ route('products.index') }}" class="btn btn-light border shadow-sm fw-bold text-danger" style="border-radius: 12px;">
                                <i class="bi bi-trash3 me-1"></i> Hapus Filter
                            </a>
                        </div>
                    @endif
                </form>

            </div>
        </div>

        {{-- ==========================================
             2. MAIN CONTENT KANAN (PRODUK)
        =========================================== --}}
        <div class="col-lg-9">
            
            <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-4">
                <h5 class="fw-bold mb-0 text-dark">Katalog Produk</h5>
                <span class="text-muted small">
                    Menampilkan <strong class="text-dark">{{ $products->total() }}</strong> dari <strong class="text-dark">{{ $totalAllProducts }}</strong> produk
                </span>
            </div>

            {{-- GRID PRODUK --}}
            <div class="row row-cols-2 row-cols-md-3 g-4">
                @forelse($products as $product)
                    <div class="col">
                        <a href="{{ route('products.show', $product->slug) }}" class="text-decoration-none text-dark">
                            <div class="product-card-clean border shadow-sm" style="transition: 0.3s; border-radius: 12px; overflow: hidden;">
                                <div style="width: 100%; height: 220px; background: #fff; overflow: hidden; display: flex; align-items: center; justify-content: center; border-bottom: 1px solid #eee;">
                                    @if($product->image)
                                        <img src="{{ asset('storage/' . $product->image) }}" style="max-width: 90%; max-height: 90%; object-fit: contain;">
                                    @else
                                        <img src="{{ asset('assets/img/foto_tidak_tersedia.png') }}" style="max-width: 60px; opacity: 0.1;">
                                    @endif
                                </div>
                                <div style="padding: 15px; text-align: left; background: #fff;">
                                    <small style="font-size: 10px; text-transform: uppercase; color: #999; letter-spacing: 1px; display: block; margin-bottom: 4px;">
                                        {{ $product->category->name }}
                                    </small>
                                    <h6 style="font-size: 14px; font-weight: 700; margin-bottom: 4px; line-height: 1.4; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; color: #333;">
                                        {{ $product->name }}
                                    </h6>
                                    <div class="product-price mt-2" style="font-size: 14px; color: #013780; font-weight: 700;">
                                        @php 
                                            $minPrice = $product->variants->min('price');
                                            $maxPrice = $product->variants->max('price');
                                        @endphp
                                        @if($product->variants->count() > 1 && $minPrice != $maxPrice)
                                            Rp{{ number_format($minPrice, 0, ',', '.') }} - Rp{{ number_format($maxPrice, 0, ',', '.') }}
                                        @elseif($product->variants->count() > 0)
                                            Rp{{ number_format($minPrice, 0, ',', '.') }}
                                        @else
                                            <span class="text-muted fw-normal fst-italic" style="font-size: 12px;">Harga belum tersedia</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <div class="mb-3">
                            <img src="{{ asset('assets/img/foto_tidak_tersedia.png') }}" style="width: 100px; opacity: 0.15;">
                        </div>
                        <h6 class="fw-bold text-dark mb-1">Produk tidak ditemukan</h6>
                        <p class="text-muted small">Coba sesuaikan urutan, filter kategori, atau rentang harga.</p>
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
                        {{ $products->appends(request()->query())->links() }}
                    @endif
                </div>
            </div>

        </div>
    </div>
    
    <style>
        .custom-pagination nav div:first-child { display: none; }
        .custom-pagination .pagination { display: flex; gap: 12px; list-style: none; padding: 0; }
        .custom-pagination .page-item .page-link { border: none; background: transparent; color: #888; width: 35px; height: 35px; display: flex; align-items: center; justify-content: center; text-decoration: none; font-weight: 600; border-radius: 50%; }
        .custom-pagination .page-item.active .page-link { background-color: #013780; color: #fff !important; box-shadow: 0 4px 10px rgba(1, 55, 128, 0.2); }
        .custom-pagination .page-link:hover:not(.active) { background-color: #f0f7ff; color: #013780; }
    </style>
</div>

<script>
    let sliderOne = document.getElementById("slider-1");
    let sliderTwo = document.getElementById("slider-2");
    let displayValOne = document.getElementById("display-min");
    let displayValTwo = document.getElementById("display-max");
    let minGap = 0;
    let sliderTrack = document.querySelector(".slider-track");
    
    let sliderMinValue = sliderOne ? parseInt(sliderOne.min) : 0;
    let sliderMaxValue = sliderOne ? parseInt(sliderOne.max) : 100000;

    const formatRupiah = (number) => {
        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(number);
    }

    function slideOne() {
        if (!sliderOne) return;
        if(parseInt(sliderTwo.value) - parseInt(sliderOne.value) <= minGap){
            sliderOne.value = parseInt(sliderTwo.value) - minGap;
        }
        displayValOne.textContent = formatRupiah(sliderOne.value);
        fillColor();
    }

    function slideTwo() {
        if (!sliderTwo) return;
        if(parseInt(sliderTwo.value) - parseInt(sliderOne.value) <= minGap){
            sliderTwo.value = parseInt(sliderOne.value) + minGap;
        }
        displayValTwo.textContent = formatRupiah(sliderTwo.value);
        fillColor();
    }

    function fillColor() {
        if (!sliderTrack) return;
        let percent1 = ((sliderOne.value - sliderMinValue) / (sliderMaxValue - sliderMinValue)) * 100;
        let percent2 = ((sliderTwo.value - sliderMinValue) / (sliderMaxValue - sliderMinValue)) * 100;
        
        if (sliderMaxValue === sliderMinValue) {
            percent1 = 0; percent2 = 100;
        }

        sliderTrack.style.background = `linear-gradient(to right, #e9ecef ${percent1}%, #013780 ${percent1}%, #013780 ${percent2}%, #e9ecef ${percent2}%)`;
    }

    function submitFilter() {
        document.getElementById("filterForm").submit();
    }

    window.onload = function() {
        slideOne();
        slideTwo();
    }
</script>
@endsection