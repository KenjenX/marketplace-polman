@extends('layouts.store')

@section('content')
<style>
    .product-price span { letter-spacing: -0.2px; }
    .product-card-clean:hover { transform: translateY(-5px); }
    .sidebar-filter { position: sticky; top: 100px; }
    
    .form-check-input:checked {
        background-color: #013780;
        border-color: #013780;
    }

    .modern-select {
        background-color: #f8f9fa;
        border: none;
        border-radius: 12px;
        box-shadow: inset 0 1px 3px rgba(0,0,0,0.02);
        transition: all 0.2s ease;
    }

    /* Range Slider Styling */
    .range-slider-container { position: relative; width: 100%; height: 40px; margin-top: 10px; }
    .slider-track { width: 100%; height: 4px; background-color: #e9ecef; position: absolute; margin: auto; top: 0; bottom: 0; border-radius: 5px; }
    .range-slider-container input[type="range"] { -webkit-appearance: none; appearance: none; width: 100%; outline: none; position: absolute; margin: auto; top: 0; bottom: 0; background-color: transparent; pointer-events: none; }
    .range-slider-container input[type="range"]::-webkit-slider-thumb { -webkit-appearance: none; height: 18px; width: 18px; background-color: #fff; border: 2px solid #013780; border-radius: 50%; cursor: pointer; pointer-events: auto; box-shadow: 0 2px 6px rgba(0,0,0,0.2); }
    
    /* CSS SLIDESHOW OTOMATIS */
    .product-img-container {
        width: 100%;
        height: 220px;
        background: #fff;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
        border-bottom: 1px solid #eee;
        position: relative;
    }

    .slideshow-wrapper {
        position: relative;
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .slide-img {
        position: absolute;
        max-width: 85%;
        max-height: 85%;
        object-fit: contain;
        opacity: 0;
        transition: opacity 1s ease-in-out;
    }

    /* Animasi untuk 2 Gambar */
    @keyframes slideAnim2 {
        0%, 45% { opacity: 1; }
        50%, 95% { opacity: 0; }
        100% { opacity: 1; }
    }

    /* Animasi untuk 3 Gambar */
    @keyframes slideAnim3 {
        0%, 28% { opacity: 1; }
        33%, 95% { opacity: 0; }
        100% { opacity: 1; }
    }

    .single-img, .no-img {
        max-width: 85%;
        max-height: 85%;
        object-fit: contain;
    }

    .price-input-container input::-webkit-outer-spin-button,
    .price-input-container input::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
</style>

<div class="container-fluid px-lg-5 py-4">
    <div class="row">
        
        {{-- SIDEBAR FILTER --}}
        <div class="col-lg-3 mb-4">
            <div class="sidebar-filter pe-lg-4">
                <h2 class="fw-bold mb-4">Produk</h2>

                <form method="GET" action="{{ route('products.index') }}" id="filterForm">
                    <div class="input-group input-group-sm border-bottom border-secondary pb-1 mb-4">
                        <input type="text" name="search" value="{{ request('search') }}" class="form-control border-0 bg-transparent px-0 shadow-none" style="font-size: 14px;" placeholder="Cari produk...">
                        <button class="btn border-0 text-muted" type="submit">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>

                    <h6 class="fw-bold mb-2 text-uppercase text-muted" style="font-size: 12px; letter-spacing: 1px;">Urutkan</h6>
                    <div class="mb-4">
                        <select name="sort" class="form-select modern-select py-2 px-3 text-dark fw-medium shadow-none" onchange="submitFilter()">
                            <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Terbaru</option>
                            <option value="az" {{ request('sort') == 'az' ? 'selected' : '' }}>A - Z</option>
                            <option value="za" {{ request('sort') == 'za' ? 'selected' : '' }}>Z - A</option>
                            <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Harga Terendah</option>
                            <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Harga Tertinggi</option>
                        </select>
                    </div>

                    <h6 class="fw-bold mb-2 text-uppercase text-muted" style="font-size: 12px; letter-spacing: 1px;">Harga</h6>
                    <div class="mb-4">
                        <div class="range-slider-container">
                            <div class="slider-track"></div>
                            <input type="range" id="slider-1" min="0" max="100" step="0.1" oninput="slideOne()" onchange="submitFilter()">
                            <input type="range" id="slider-2" min="0" max="100" step="0.1" oninput="slideTwo()" onchange="submitFilter()">
                        </div>
                        <input type="hidden" name="min_price" id="hidden-min" value="{{ request('min_price', $globalMinPrice) }}">
                        <input type="hidden" name="max_price" id="hidden-max" value="{{ request('max_price', $globalMaxPrice) }}">
                        <div class="row g-2 mt-1 price-input-container">
                            <div class="col-6">
                                <input type="number" id="input-min" class="form-control form-control-sm border shadow-none" style="font-size: 12px; font-weight: 600;" value="{{ request('min_price', $globalMinPrice) }}" onchange="syncSliderWithInput()">
                            </div>
                            <div class="col-6">
                                <input type="number" id="input-max" class="form-control form-control-sm border shadow-none" style="font-size: 12px; font-weight: 600;" value="{{ request('max_price', $globalMaxPrice) }}" onchange="syncSliderWithInput()">
                            </div>
                        </div>
                    </div>

                    <h6 class="fw-bold mb-3 text-uppercase text-muted" style="font-size: 12px; letter-spacing: 1px;">Kategori</h6>
                    <div class="mb-4">
                        @foreach($categories as $category)
                            <div class="form-check mb-2">
                                <input class="form-check-input shadow-none" type="checkbox" name="categories[]" value="{{ $category->slug }}" id="cat_{{ $category->id }}"
                                       {{ in_array($category->slug, request('categories', [])) ? 'checked' : '' }} onchange="submitFilter()">
                                <label class="form-check-label text-dark w-100 d-flex justify-content-between" style="font-size: 14px; cursor: pointer;" for="cat_{{ $category->id }}">
                                    <span>{{ $category->name }}</span>
                                    <span class="text-muted">({{ $category->products_count }})</span>
                                </label>
                            </div>
                        @endforeach
                    </div>

                    @if(request()->hasAny(['search', 'categories', 'min_price', 'max_price']))
                        <div class="d-grid mt-4">
                            <a href="{{ route('products.index') }}" class="btn btn-light border text-danger fw-bold rounded-3">Hapus Filter</a>
                        </div>
                    @endif
                </form>
            </div>
        </div>

        {{-- MAIN CONTENT --}}
        <div class="col-lg-9">
            <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-4">
                <h5 class="fw-bold mb-0">Katalog Produk</h5>
                <span class="text-muted small">Menampilkan <strong>{{ $products->total() }}</strong> produk</span>
            </div>

            <div class="row row-cols-2 row-cols-md-3 g-4">
                @forelse($products as $product)
                    <div class="col">
                        <a href="{{ route('products.show', $product->slug) }}" class="text-decoration-none text-dark">
                            <div class="product-card-clean border shadow-sm" style="transition: 0.3s; border-radius: 12px; overflow: hidden; background: #fff;">
                                
                                <div class="product-img-container">
                                    @php
                                        $vImages = $product->variants->whereNotNull('image')->values();
                                        $imgCount = $vImages->count();
                                    @endphp

                                    @if($imgCount >= 2)
                                        <div class="slideshow-wrapper">
                                            @php 
                                                $displayCount = min($imgCount, 3); 
                                                $totalDuration = $displayCount * 3; // 3 detik per gambar
                                            @endphp
                                            @foreach($vImages->take(3) as $index => $vImg)
                                                <img src="{{ asset('storage/' . $vImg->image) }}" 
                                                     class="slide-img" 
                                                     style="animation: slideAnim{{ $displayCount }} {{ $totalDuration }}s infinite; animation-delay: {{ $index * 3 }}s;"
                                                     alt="{{ $product->name }}">
                                            @endforeach
                                        </div>
                                    @elseif($imgCount == 1)
                                        <img src="{{ asset('storage/' . $vImages->first()->image) }}" class="single-img" alt="{{ $product->name }}">
                                    @elseif($product->image)
                                        <img src="{{ asset('storage/' . $product->image) }}" class="single-img" alt="{{ $product->name }}">
                                    @else
                                        <img src="{{ asset('assets/img/foto_tidak_tersedia.png') }}" class="no-img" style="opacity: 0.1;">
                                    @endif
                                </div>

                                <div style="padding: 15px;">
                                    <small class="text-muted text-uppercase" style="font-size: 10px; letter-spacing: 1px;">{{ $product->category->name }}</small>
                                    <h6 class="fw-bold text-dark mt-1" style="font-size: 14px; line-height: 1.4; height: 40px; overflow: hidden;">{{ $product->name }}</h6>
                                    
                                    <div class="product-price mt-2" style="font-size: 14px; color: #013780; font-weight: 800;">
                                        @php 
                                            $minP = $product->variants->min('price');
                                            $maxP = $product->variants->max('price');
                                        @endphp
                                        @if($product->variants->count() > 1 && $minP != $maxP)
                                            Rp{{ number_format($minP, 0, ',', '.') }} - Rp{{ number_format($maxP, 0, ',', '.') }}
                                        @elseif($product->variants->count() > 0)
                                            Rp{{ number_format($minP, 0, ',', '.') }}
                                        @else
                                            <span class="text-muted small italic">Harga belum tersedia</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <p class="text-muted">Produk tidak ditemukan.</p>
                    </div>
                @endforelse
            </div>

            <div class="d-flex justify-content-center mt-5">
                {{ $products->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>

<script>
    const minP = {{ $globalMinPrice }};
    const maxP = {{ $globalMaxPrice }};
    const minLog = Math.log10(minP || 1); 
    const maxLog = Math.log10(maxP);

    const slider1 = document.getElementById("slider-1");
    const slider2 = document.getElementById("slider-2");
    const inputMin = document.getElementById("input-min");
    const inputMax = document.getElementById("input-max");
    const hiddenMin = document.getElementById("hidden-min");
    const hiddenMax = document.getElementById("hidden-max");
    const sliderTrack = document.querySelector(".slider-track");

    function positionToValue(pos) { return Math.round(Math.pow(10, minLog + (pos / 100) * (maxLog - minLog))); }
    function valueToPosition(val) { return ((Math.log10(val || 1) - minLog) / (maxLog - minLog)) * 100; }

    function slideOne() {
        if(parseFloat(slider2.value) - parseFloat(slider1.value) <= 1) slider1.value = parseFloat(slider2.value) - 1;
        const val = positionToValue(slider1.value);
        inputMin.value = val; hiddenMin.value = val;
        fillColor();
    }

    function slideTwo() {
        if(parseFloat(slider2.value) - parseFloat(slider1.value) <= 1) slider2.value = parseFloat(slider1.value) + 1;
        const val = positionToValue(slider2.value);
        inputMax.value = val; hiddenMax.value = val;
        fillColor();
    }

    function fillColor() {
        const p1 = slider1.value; const p2 = slider2.value;
        sliderTrack.style.background = `linear-gradient(to right, #e9ecef ${p1}%, #013780 ${p1}%, #013780 ${p2}%, #e9ecef ${p2}%)`;
    }

    function submitFilter() { document.getElementById("filterForm").submit(); }

    window.onload = function() {
        slider1.value = valueToPosition(parseInt(hiddenMin.value));
        slider2.value = valueToPosition(parseInt(hiddenMax.value));
        fillColor();
    }
</script>
@endsection