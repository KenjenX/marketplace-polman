@extends('layouts.store')

@section('content')
@php 
    $variantCount = $product->variants->count(); 
    $minPrice = $product->variants->min('price');
    $maxPrice = $product->variants->max('price');
@endphp

<style>
    .back-link:hover { color: #013780 !important; }
    .back-link:hover .back-icon { transform: translateX(-5px); }
    
    /* Menghilangkan arrow input number */
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
    .qty-input:focus { box-shadow: none; border-color: #013780; }

    /* --- REVISI ZOOM & KACA PEMBESAR (LOUPE.PNG) --- */
    .img-zoom-container {
        position: relative;
        width: 100%;
        height: 550px;
        background: #fdfdfd;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        cursor: crosshair;
        border: 1px solid #f0f0f0;
        border-radius: 4px;
    }

    .main-product-img {
        max-width: 90%;
        max-height: 90%;
        object-fit: contain;
        transition: transform 0.1s ease-out;
        transform-origin: center center;
    }

    /* Ikon Kaca Pembesar (loupe.png) */
    .fullscreen-btn {
        position: absolute;
        top: 20px;
        right: 20px;
        background: white;
        width: 45px;
        height: 45px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        cursor: pointer;
        z-index: 10;
        transition: 0.3s;
        border: 1px solid #eee;
    }

    .loupe-icon {
        height: 22px;
        width: auto;
        transition: 0.3s;
    }

    .fullscreen-btn:hover {
        transform: scale(1.1);
        background: #013780;
    }

    .fullscreen-btn:hover .loupe-icon {
        filter: brightness(0) invert(1); /* Jadi putih saat hover background biru */
    }

    /* Modal Fullscreen (Lightbox) */
    #image-modal {
        display: none;
        position: fixed;
        z-index: 9999;
        top: 0; left: 0;
        width: 100%; height: 100%;
        background: rgba(0,0,0,0.95);
        align-items: center;
        justify-content: center;
    }

    #modal-img {
        max-width: 90%;
        max-height: 90%;
        object-fit: contain;
        animation: zoomIn 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
    }

    @keyframes zoomIn {
        from { transform: scale(0.7); opacity: 0; }
        to { transform: scale(1); opacity: 1; }
    }
</style>

<div class="container-fluid py-4">
    {{-- 1. TOMBOL KEMBALI --}}
    <div class="mb-4">
        <a href="{{ route('products.index') }}" class="text-decoration-none text-muted small d-inline-flex align-items-center back-link" style="letter-spacing: 1px; font-weight: 600; transition: 0.3s;">
            <img src="{{ asset('assets/img/back.png') }}" alt="Back" style="height: 18px; width: auto; margin-right: 12px; transition: 0.3s;" class="back-icon">
            KEMBALI KE KATALOG
        </a>
    </div>

    <div class="row g-5">
        {{-- 2. SISI KIRI: FOTO PRODUK --}}
        <div class="col-lg-7">
            <div class="img-zoom-container shadow-sm" id="zoom-area">
                {{-- Tombol Kaca Pembesar Menggunakan loupe.png --}}
                <div class="fullscreen-btn" id="open-lightbox" title="Perbesar Gambar">
                    <img src="{{ asset('assets/img/loupe.png') }}" alt="Zoom" class="loupe-icon">
                </div>

                @if($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}" 
                         id="product-image" 
                         alt="{{ $product->name }}" 
                         class="main-product-img">
                @else
                    <img src="{{ asset('assets/img/foto_tidak_tersedia.png') }}" 
                         alt="Foto tidak tersedia" 
                         id="product-image"
                         class="main-product-img" style="opacity: 0.2;">
                @endif
            </div>
        </div>

        {{-- 3. SISI KANAN: DETAIL --}}
        <div class="col-lg-5">
            <div class="ps-lg-4">
                <div class="text-muted mb-2" style="font-size: 11px; font-weight: 600; letter-spacing: 1.5px; text-transform: uppercase;">
                    {{ $product->category->name }}
                </div>

                <h1 class="fw-bold mb-3" style="font-size: 38px; color: #1a1a1a; line-height: 1.2;">
                    {{ $product->name }}
                </h1>

                <div class="product-price mb-4">
                    <span id="display-price" 
                        class="{{ ($variantCount > 1 && $minPrice != $maxPrice) ? 'text-muted' : '' }}" 
                        style="{{ ($variantCount > 1 && $minPrice != $maxPrice) ? 'font-size: 18px;' : 'font-size: 24px; font-weight: 700; color: #1a1a1a;' }}">
                        
                        @if($variantCount > 1 && $minPrice != $maxPrice)
                            Rp{{ number_format($minPrice, 0, ',', '.') }} - Rp{{ number_format($maxPrice, 0, ',', '.') }}
                        @elseif($variantCount > 0)
                            Rp{{ number_format($minPrice, 0, ',', '.') }}
                        @else
                            Stok Belum Tersedia
                        @endif
                    </span>
                </div>

                <div class="mb-5 text-muted" style="font-size: 14px; line-height: 1.8; text-align: justify;">
                    {!! nl2br(e($product->description)) !!}
                </div>

                {{-- VARIASI & BELI --}}
                @if($variantCount > 0)
                <div class="border-top pt-4 mb-4">
                    <h6 class="fw-bold mb-3" style="font-size: 12px; letter-spacing: 1px; text-transform: uppercase;">
                        Pilih Spesifikasi / Variasi
                    </h6>
                    <div class="d-flex flex-wrap gap-2">
                        @foreach($product->variants as $variant)
                            <button type="button" 
                                    class="btn btn-sm btn-variant rounded-pill px-3 py-2 border"
                                    data-price="{{ $variant->price }}"
                                    data-stock="{{ $variant->stock }}"
                                    data-id="{{ $variant->id }}"
                                    style="font-size: 12px; transition: 0.3s; background-color: transparent; color: #000; border-color: #dee2e6;">
                                {{ $variant->name }}
                            </button>
                        @endforeach
                    </div>
                </div>

                @auth
                    <form action="{{ url('cart/add') }}" method="POST" id="add-to-cart-form">
                        @csrf
                        <input type="hidden" name="variant_id" id="selected-variant-id">
                        
                        <div id="quantity-area" class="mb-4 align-items-center gap-3" style="display: none;">
                            <label class="small fw-bold text-uppercase" style="letter-spacing: 1px; font-size: 10px;">Jumlah</label>
                            <div class="input-group input-group-sm" style="width: 120px;">
                                <button class="btn btn-outline-primary rounded-start-pill px-3" type="button" id="btn-minus">-</button>
                                <input type="number" name="quantity" id="prod-quantity" value="1" min="1" class="form-control text-center border-primary-subtle qty-input" readonly>
                                <button class="btn btn-outline-primary rounded-end-pill px-3" type="button" id="btn-plus">+</button>
                            </div>
                            <div id="variant-stock-info" class="text-muted" style="font-size: 11px;"></div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" id="btn-add-cart" disabled class="btn btn-dark btn-lg rounded-pill fw-bold shadow-sm" style="font-size: 14px; letter-spacing: 1px; padding: 15px;">
                                TAMBAHKAN KE KERANJANG
                            </button>
                        </div>
                    </form>
                @else
                    <div class="d-grid">
                        <a href="{{ route('login') }}" class="btn btn-outline-dark rounded-pill py-3" style="font-size: 12px; letter-spacing: 1px; text-decoration: none; text-align: center;">
                            LOGIN UNTUK MEMBELI
                        </a>
                    </div>
                @endauth
                @endif
            </div>
        </div>
    </div>
</div>

{{-- MODAL LIGHTBOX --}}
<div id="image-modal">
    <span style="position: absolute; top: 20px; right: 30px; color: white; font-size: 45px; cursor: pointer; z-index: 10000; font-family: Arial;" id="close-modal">&times;</span>
    <img id="modal-img">
</div>

<script>
    // --- 1. LOGIKA ZOOM & FULLSCREEN ---
    const zoomArea = document.getElementById('zoom-area');
    const productImage = document.getElementById('product-image');
    const openLightbox = document.getElementById('open-lightbox');
    const imageModal = document.getElementById('image-modal');
    const modalImg = document.getElementById('modal-img');
    const closeModal = document.getElementById('close-modal');

    if(productImage && zoomArea) {
        zoomArea.addEventListener('mousemove', function(e) {
            const { left, top, width, height } = zoomArea.getBoundingClientRect();
            const x = ((e.clientX - left) / width) * 100;
            const y = ((e.clientY - top) / height) * 100;

            productImage.style.transformOrigin = `${x}% ${y}%`;
            productImage.style.transform = "scale(2.2)"; // Sedikit lebih besar biar detail kerasa
        });

        zoomArea.addEventListener('mouseleave', function() {
            productImage.style.transform = "scale(1)";
            productImage.style.transformOrigin = "center center";
        });
    }

    if(openLightbox) {
        openLightbox.addEventListener('click', function(e) {
            e.preventDefault();
            imageModal.style.display = "flex";
            modalImg.src = productImage.src;
        });
    }

    if(closeModal) {
        closeModal.addEventListener('click', () => imageModal.style.display = "none");
    }

    imageModal.addEventListener('click', (e) => {
        if(e.target === imageModal) imageModal.style.display = "none";
    });

    // --- 2. LOGIKA PILIH VARIAN ---
    const qtyInput = document.getElementById('prod-quantity');
    const btnMinus = document.getElementById('btn-minus');
    const btnPlus = document.getElementById('btn-plus');

    document.querySelectorAll('.btn-variant').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.btn-variant').forEach(b => {
                b.style.backgroundColor = 'transparent';
                b.style.color = '#000';
                b.style.borderColor = '#dee2e6';
            });

            this.style.backgroundColor = '#013780';
            this.style.color = '#fff';
            this.style.borderColor = '#013780';

            const price = this.getAttribute('data-price');
            const stock = parseInt(this.getAttribute('data-stock'));
            const id = this.getAttribute('data-id');

            const priceDisplay = document.getElementById('display-price');
            priceDisplay.innerHTML = 'Rp ' + new Intl.NumberFormat('id-ID').format(price);
            priceDisplay.classList.remove('text-muted');
            priceDisplay.style.color = '#1a1a1a';
            priceDisplay.style.fontSize = '24px';
            priceDisplay.style.fontWeight = '700';

            const qtyArea = document.getElementById('quantity-area');
            if(qtyArea) qtyArea.style.display = 'flex';

            document.getElementById('selected-variant-id').value = id;
            document.getElementById('variant-stock-info').innerHTML = 'Stok: <b>' + stock + '</b>';

            if(qtyInput) {
                qtyInput.max = stock;
                qtyInput.value = 1;
            }

            const cartBtn = document.getElementById('btn-add-cart');
            if(cartBtn) cartBtn.disabled = (stock <= 0);
        });
    });

    // --- 3. LOGIKA PLUS MINUS ---
    if(btnPlus) {
        btnPlus.addEventListener('click', function() {
            let max = parseInt(qtyInput.max);
            let current = parseInt(qtyInput.value);
            if(current < max) qtyInput.value = current + 1;
        });
    }

    if(btnMinus) {
        btnMinus.addEventListener('click', function() {
            let current = parseInt(qtyInput.value);
            if(current > 1) qtyInput.value = current - 1;
        });
    }
</script>
@endsection