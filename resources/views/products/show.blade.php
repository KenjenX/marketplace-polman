@extends('layouts.store')

@section('content')
@php 
    $variantCount = $product->variants->count(); 
    $minPrice = $product->variants->min('price');
    $maxPrice = $product->variants->max('price');
@endphp

<style>
    /* 1. Navigasi Alamat (Breadcrumbs) */
    .breadcrumb-nav {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        padding: 20px 0 10px 0;
    }

    .breadcrumb-item-link {
        color: #013780; 
        text-decoration: none;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 1px;
        text-transform: uppercase;
        padding: 10px 18px;
        border-radius: 10px;
        transition: all 0.2s ease-in-out;
    }

    .breadcrumb-item-link:hover {
        background-color: rgba(1, 55, 128, 0.05);
        color: #013780;
        text-decoration: none;
    }

    .breadcrumb-next-icon {
        height: 10px;
        width: auto;
        margin: 0 5px;
        opacity: 0.3;
    }

    .breadcrumb-current {
        color: #adb5bd;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        padding: 10px 12px;
        letter-spacing: 0.5px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 300px;
    }

    /* 2. Tombol Kembali ke Halaman Sebelumnya */
    .back-prev-container {
        margin-bottom: 30px;
        padding-left: 10px;
    }

    .btn-back-prev {
        display: inline-flex;
        align-items: center;
        color: #6c757d;
        text-decoration: none;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 1.2px;
        text-transform: uppercase;
        transition: 0.3s;
    }

    .btn-back-prev img {
        height: 12px;
        width: auto;
        margin-right: 10px;
        transform: rotate(180deg); /* Memutar next.png jadi ikon back */
        transition: 0.3s;
        opacity: 0.5;
    }

    .btn-back-prev:hover {
        color: #013780;
        text-decoration: none;
    }

    .btn-back-prev:hover img {
        opacity: 1;
        transform: rotate(180deg) translateX(5px); /* Efek dorong ke kiri */
    }

    /* 3. Detail Gambar Section */
    .img-zoom-container {
        position: relative;
        width: 100%;
        height: 550px;
        background: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        cursor: crosshair;
        border: 1px solid #f0f0f0;
        border-radius: 15px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.03);
    }
    .main-product-img {
        max-width: 85%;
        max-height: 85%;
        object-fit: contain;
        transition: transform 0.1s ease-out;
    }
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
    .loupe-icon { height: 20px; width: auto; transition: 0.3s; }
    .fullscreen-btn:hover { transform: scale(1.1); background: #013780; }
    .fullscreen-btn:hover .loupe-icon { filter: brightness(0) invert(1); }

    #image-modal {
        display: none;
        position: fixed;
        z-index: 9999;
        top: 0; left: 0;
        width: 100%; height: 100%;
        background: rgba(0,0,0,0.95);
        align-items: center; justify-content: center;
    }
    #modal-img { max-width: 90%; max-height: 90%; object-fit: contain; }
</style>

<div class="container-fluid py-4">
    
    {{-- NAVIGASI ALAMAT (BREADCRUMBS) --}}
    <div class="breadcrumb-nav">
        <a href="{{ route('home') }}" class="breadcrumb-item-link">BERANDA</a>
        
        <img src="{{ asset('assets/img/next.png') }}" class="breadcrumb-next-icon" alt=">">
        
        <a href="{{ route('products.index') }}" class="breadcrumb-item-link">KATALOG</a>
        
        <img src="{{ asset('assets/img/next.png') }}" class="breadcrumb-next-icon" alt=">">
        
        <a href="{{ route('products.index', ['categories' => [$product->category->slug]]) }}" class="breadcrumb-item-link">
            {{ $product->category->name }}
        </a>
        
        <img src="{{ asset('assets/img/next.png') }}" class="breadcrumb-next-icon" alt=">">
        
        <span class="breadcrumb-current">{{ $product->name }}</span>
    </div>

    {{-- TOMBOL KEMBALI KE SEBELUMNYA (MEMPERTAHANKAN FILTER) --}}
    <div class="back-prev-container">
        <a href="{{ url()->previous() }}" class="btn-back-prev">
            <img src="{{ asset('assets/img/next.png') }}" alt="Back">
            Kembali ke Halaman Sebelumnya
        </a>
    </div>

    <div class="row g-5">
        {{-- SISI KIRI: FOTO PRODUK --}}
        <div class="col-lg-7">
            <div class="img-zoom-container" id="zoom-area">
                <div class="fullscreen-btn" id="open-lightbox">
                    <img src="{{ asset('assets/img/loupe.png') }}" alt="Zoom" class="loupe-icon">
                </div>
                @if($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}" id="product-image" alt="{{ $product->name }}" class="main-product-img">
                @else
                    <img src="{{ asset('assets/img/foto_tidak_tersedia.png') }}" id="product-image" class="main-product-img" style="opacity: 0.1;" alt="Foto Tidak Tersedia">
                @endif
            </div>
        </div>

        {{-- SISI KANAN: DETAIL --}}
        <div class="col-lg-5">
            <div class="ps-lg-4">
                <div class="mb-2" style="font-size: 11px; font-weight: 800; letter-spacing: 2px; text-transform: uppercase; color: #013780; opacity: 0.6;">
                    {{ $product->category->name }}
                </div>

                <h1 class="fw-bold mb-3" style="font-size: 42px; color: #1a1a1a; line-height: 1.1; letter-spacing: -1px;">
                    {{ $product->name }}
                </h1>

                <div class="product-price mb-4">
                    <span id="display-price" style="font-size: 28px; font-weight: 800; color: #013780;">
                        @if($variantCount > 1 && $minPrice != $maxPrice)
                            Rp{{ number_format($minPrice, 0, ',', '.') }} - Rp{{ number_format($maxPrice, 0, ',', '.') }}
                        @elseif($variantCount > 0)
                            Rp{{ number_format($minPrice, 0, ',', '.') }}
                        @else
                            Stok Belum Tersedia
                        @endif
                    </span>
                </div>

                <div class="mb-5 text-muted" style="font-size: 15px; line-height: 1.8; text-align: justify; color: #444 !important;">
                    {!! nl2br(e($product->description)) !!}
                </div>

                @if($variantCount > 0)
                <div class="border-top pt-4 mb-4">
                    <h6 class="fw-bold mb-3" style="font-size: 11px; letter-spacing: 1.5px; text-transform: uppercase; color: #666;">
                        Pilih Variasi
                    </h6>
                    <div class="d-flex flex-wrap gap-2">
                        @foreach($product->variants as $variant)
                            <button type="button" class="btn btn-sm btn-variant rounded-pill px-4 py-2 border"
                                    data-price="{{ $variant->price }}" data-stock="{{ $variant->stock }}" data-id="{{ $variant->id }}"
                                    style="font-size: 12px; font-weight: 600; transition: 0.3s; border-color: #eee;">
                                {{ $variant->name }}
                            </button>
                        @endforeach
                    </div>
                </div>

                @auth
                    <form action="" method="POST" id="add-to-cart-form">
                        @csrf
                        <input type="hidden" name="variant_id" id="selected-variant-id">
                        <div id="quantity-area" class="mb-4 align-items-center gap-3" style="display: none;">
                            <div class="input-group input-group-sm" style="width: 130px;">
                                <button class="btn btn-outline-dark rounded-start-pill px-3" type="button" id="btn-minus">-</button>
                                <input type="number" name="quantity" id="prod-quantity" value="1" min="1" class="form-control text-center border-dark shadow-none" readonly>
                                <button class="btn btn-outline-dark rounded-end-pill px-3" type="button" id="btn-plus">+</button>
                            </div>
                            <div id="variant-stock-info" class="fw-bold text-muted" style="font-size: 12px;"></div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" id="btn-add-cart" disabled class="btn btn-dark btn-lg rounded-pill fw-bold shadow" style="font-size: 14px; letter-spacing: 1px; padding: 18px;">
                                TAMBAHKAN KE KERANJANG
                            </button>
                        </div>
                    </form>
                @endauth
                @endif
            </div>
        </div>
    </div>
</div>

{{-- MODAL LIGHTBOX --}}
<div id="image-modal">
    <span style="position: absolute; top: 20px; right: 30px; color: white; font-size: 45px; cursor: pointer; z-index: 10000;" id="close-modal">&times;</span>
    <img id="modal-img" alt="">
</div>

<script>
    const zoomArea = document.getElementById('zoom-area');
    const productImage = document.getElementById('product-image');
    const imageModal = document.getElementById('image-modal');
    const modalImg = document.getElementById('modal-img');

    if(productImage && zoomArea) {
        zoomArea.addEventListener('mousemove', function(e) {
            const { left, top, width, height } = zoomArea.getBoundingClientRect();
            const x = ((e.clientX - left) / width) * 100;
            const y = ((e.clientY - top) / height) * 100;
            productImage.style.transformOrigin = `${x}% ${y}%`;
            productImage.style.transform = "scale(2.2)";
        });
        zoomArea.addEventListener('mouseleave', () => {
            productImage.style.transform = "scale(1)";
            productImage.style.transformOrigin = "center center";
        });
    }

    document.getElementById('open-lightbox').onclick = () => {
        imageModal.style.display = "flex";
        modalImg.src = productImage.src;
    };
    document.getElementById('close-modal').onclick = () => imageModal.style.display = "none";
    imageModal.onclick = (e) => { if(e.target === imageModal) imageModal.style.display = "none"; };

    document.querySelectorAll('.btn-variant').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.btn-variant').forEach(b => {
                b.style.backgroundColor = 'transparent';
                b.style.color = '#000';
                b.style.borderColor = '#eee';
            });
            this.style.backgroundColor = '#013780';
            this.style.color = '#fff';
            this.style.borderColor = '#013780';
            
            const price = this.getAttribute('data-price');
            const stock = parseInt(this.getAttribute('data-stock'));
            document.getElementById('display-price').innerHTML = 'Rp ' + new Intl.NumberFormat('id-ID').format(price);
            document.getElementById('quantity-area').style.display = 'flex';
            document.getElementById('selected-variant-id').value = this.getAttribute('data-id');
            document.getElementById('variant-stock-info').innerHTML = 'STOK: ' + stock;
            document.getElementById('prod-quantity').max = stock;
            document.getElementById('btn-add-cart').disabled = (stock <= 0);
        });
    });

    document.getElementById('btn-plus').onclick = () => {
        const q = document.getElementById('prod-quantity');
        if(parseInt(q.value) < parseInt(q.max)) q.value = parseInt(q.value) + 1;
    };
    document.getElementById('btn-minus').onclick = () => {
        const q = document.getElementById('prod-quantity');
        if(parseInt(q.value) > 1) q.value = parseInt(q.value) - 1;
    };
    const form = document.getElementById('add-to-cart-form');

    document.querySelectorAll('.btn-variant').forEach(btn => {
        btn.addEventListener('click', function() {
            const variantId = this.getAttribute('data-id');

            // SET ACTION ROUTE
            form.action = `/cart/add/${variantId}`;

            document.getElementById('selected-variant-id').value = variantId;

            // sisanya biarkan
        });
    });
</script>
@endsection