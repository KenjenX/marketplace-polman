@extends('layouts.store')

@section('content')
@php 
    $variantCount = $product->variants->count(); 
    $minPrice = $product->variants->min('price');
    $maxPrice = $product->variants->max('price');
    $vImages = $product->variants->whereNotNull('image')->values();
@endphp

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
    /* 1. Navigasi & Breadcrumbs - FIX UKURAN IKON */
    .breadcrumb-nav { display: flex; align-items: center; flex-wrap: wrap; padding: 15px 0 5px 0; }
    .breadcrumb-item-link { color: #013780; text-decoration: none; font-size: 11px; font-weight: 700; letter-spacing: 0.5px; text-transform: uppercase; padding: 6px 12px; border-radius: 8px; transition: 0.2s; }
    .breadcrumb-item-link:hover { background-color: rgba(1, 55, 128, 0.05); }
    
    /* FIX: Kunci ukuran ikon next agar tidak melar */
    .breadcrumb-next-icon { height: 10px !important; width: auto !important; margin: 0 8px; opacity: 0.4; flex-shrink: 0; }
    
    .breadcrumb-current { color: #adb5bd; font-size: 11px; font-weight: 600; text-transform: uppercase; padding: 6px 10px; max-width: 200px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }

    .back-prev-container { margin-bottom: 20px; padding-left: 10px; }
    .btn-back-prev { display: inline-flex; align-items: center; color: #6c757d; text-decoration: none; font-size: 10px; font-weight: 700; letter-spacing: 1px; text-transform: uppercase; transition: 0.3s; }
    .btn-back-prev img { height: 10px !important; width: auto !important; margin-right: 8px; transform: rotate(180deg); opacity: 0.5; }

    /* 2. Gambar & Zoom Container */
    .img-zoom-container { position: relative; width: 100%; height: 450px; background: #fff; display: flex; align-items: center; justify-content: center; overflow: hidden; cursor: crosshair; border: 1px solid #f0f0f0; border-radius: 12px; }
    .main-product-img { max-width: 85%; max-height: 85%; object-fit: contain; transition: transform 0.1s ease-out, opacity 0.3s ease; }
    
    /* Thumbnail Gallery */
    .thumb-gallery { display: flex; gap: 10px; margin-top: 15px; overflow-x: auto; padding-bottom: 5px; }
    .thumb-item { width: 70px; height: 70px; border: 2px solid #eee; border-radius: 8px; cursor: pointer; object-fit: cover; transition: 0.3s; flex-shrink: 0; }
    .thumb-item.active { border-color: #013780; background-color: rgba(1, 55, 128, 0.05); }

    /* Slideshow Animation */
    @keyframes fadeSlideshow { 0%, 45% { opacity: 1; } 50%, 95% { opacity: 0; } 100% { opacity: 1; } }
    .slideshow-active { animation: fadeSlideshow 6s infinite; position: absolute; }

    /* 3. Tab System */
    .nav-tabs-custom { display: flex; gap: 20px; border-bottom: 1px solid #eee; margin-bottom: 20px; }
    .nav-tab-item { font-size: 12px; font-weight: 700; text-transform: uppercase; color: #adb5bd; padding-bottom: 10px; cursor: pointer; position: relative; transition: 0.3s; }
    .nav-tab-item.active { color: #013780; }
    .nav-tab-item.active::after { content: ''; position: absolute; bottom: -1px; left: 0; width: 100%; height: 2px; background: #013780; }
    
    .tab-content-area { font-size: 14px; line-height: 1.7; color: #666; height: 160px; overflow-y: auto; margin-bottom: 25px; padding-right: 10px; }
    .tab-content-area::-webkit-scrollbar { width: 4px; }
    .tab-content-area::-webkit-scrollbar-thumb { background: #eee; border-radius: 10px; }

    .btn-variant { font-size: 11px; font-weight: 600; transition: 0.2s; border-color: #eee; }
    #image-modal { display: none; position: fixed; z-index: 9999; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.9); align-items: center; justify-content: center; }
</style>

<div class="container-fluid py-3">
    {{-- Breadcrumbs Section --}}
    <div class="breadcrumb-nav">
        <a href="{{ route('home') }}" class="breadcrumb-item-link">BERANDA</a>
        <img src="{{ asset('assets/img/next.png') }}" class="breadcrumb-next-icon">
        <a href="{{ route('products.index') }}" class="breadcrumb-item-link">KATALOG</a>
        <img src="{{ asset('assets/img/next.png') }}" class="breadcrumb-next-icon">
        <span class="breadcrumb-current">{{ $product->name }}</span>
    </div>

    <div class="back-prev-container">
        <a href="{{ url()->previous() }}" class="btn-back-prev">
            <img src="{{ asset('assets/img/next.png') }}"> Kembali
        </a>
    </div>

    <div class="row g-4">
        {{-- SISI KIRI: MEDIA --}}
        <div class="col-lg-7">
            <div class="img-zoom-container" id="zoom-area">
                <div id="open-lightbox" style="position: absolute; top: 15px; right: 15px; width: 35px; height: 35px; background: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 10px rgba(0,0,0,0.05); cursor: pointer; z-index: 20;">
                    <img src="{{ asset('assets/img/loupe.png') }}" alt="Zoom" style="height: 16px;">
                </div>

                @if($vImages->count() >= 2)
                    @foreach($vImages->take(2) as $index => $vImg)
                        <img src="{{ asset('storage/' . $vImg->image) }}" 
                             id="slide-{{ $index }}" 
                             class="main-product-img slideshow-active" 
                             style="animation-delay: {{ $index * 3 }}s; z-index: {{ 10 - $index }};"
                             alt="{{ $product->name }}">
                    @endforeach
                    <img src="" id="product-image" class="main-product-img d-none" alt="{{ $product->name }}">
                @else
                    <img src="{{ $product->image ? asset('storage/' . $product->image) : asset('assets/img/foto_tidak_tersedia.png') }}" 
                         id="product-image" class="main-product-img" alt="{{ $product->name }}">
                @endif
            </div>

            @if($vImages->count() > 0)
            <div class="thumb-gallery" id="thumbnail-container">
                @foreach($vImages as $vImg)
                    <img src="{{ asset('storage/' . $vImg->image) }}" 
                         class="thumb-item" 
                         onclick="changeMainImage('{{ asset('storage/' . $vImg->image) }}', this)"
                         alt="Thumbnail">
                @endforeach
            </div>
            @endif
        </div>

        {{-- SISI KANAN: INFO --}}
        <div class="col-lg-5">
            <div class="ps-lg-3">
                <div class="mb-1 small text-uppercase fw-bold text-primary opacity-75">{{ $product->category->name }}</div>
                <h2 class="fw-bold mb-2 text-dark">{{ $product->name }}</h2>
                
                <div class="mb-3">
                    <span id="display-price" class="h4 fw-bold text-primary">
                        Rp{{ number_format($minPrice, 0, ',', '.') }} @if($minPrice != $maxPrice) - Rp{{ number_format($maxPrice, 0, ',', '.') }} @endif
                    </span>
                </div>

                <div class="nav-tabs-custom">
                    <div class="nav-tab-item active" onclick="switchTab('desc')">Deskripsi</div>
                    <div class="nav-tab-item" id="tab-spec-header" onclick="switchTab('spec')">Spesifikasi</div>
                </div>

                <div id="tab-desc" class="tab-content-area">
                    {!! nl2br(e($product->description)) !!}
                </div>

                <div id="tab-spec" class="tab-content-area" style="display: none;">
                    <div id="variant-spec-text" class="text-muted italic">Pilih variasi untuk detail spesifikasi.</div>
                </div>

                @if($variantCount > 0)
                <div class="border-top pt-3 mb-3">
                    <h6 class="fw-bold mb-2 small text-muted text-uppercase">Pilih Variasi</h6>
                    <div class="d-flex flex-wrap gap-2">
                        @foreach($product->variants as $variant)
                            <button type="button" class="btn btn-sm btn-variant rounded-pill px-3 py-1 border"
                                    data-price="{{ $variant->price }}" 
                                    data-stock="{{ $variant->stock }}" 
                                    data-id="{{ $variant->id }}"
                                    data-image="{{ $variant->image ? asset('storage/' . $variant->image) : '' }}"
                                    data-spec="{{ $variant->specification ?? 'Spesifikasi detail tidak tersedia.' }}">
                                {{ $variant->name }}
                            </button>
                        @endforeach
                    </div>
                </div>

                @auth
                    <form action="" method="POST" id="add-to-cart-form">
                        @csrf
                        <input type="hidden" name="variant_id" id="selected-variant-id">
                        <div id="quantity-area" class="mb-3 align-items-center gap-3" style="display: none;">
                            <div class="input-group input-group-sm" style="width: 120px;">
                                <button class="btn btn-outline-dark" type="button" id="btn-minus">-</button>
                                <input type="number" name="quantity" id="prod-quantity" value="1" min="1" class="form-control text-center shadow-none">
                                <button class="btn btn-outline-dark" type="button" id="btn-plus">+</button>
                            </div>
                            <div id="variant-stock-info" class="fw-bold text-muted small"></div>
                        </div>
                        <div class="d-grid">
                            <button type="submit" id="btn-add-cart" disabled class="btn btn-dark rounded-pill fw-bold py-3">TAMBAHKAN KE KERANJANG</button>
                        </div>
                    </form>
                @endauth
                @endif
            </div>
        </div>
    </div>
</div>

<div id="image-modal">
    <span style="position: absolute; top: 20px; right: 30px; color: white; font-size: 30px; cursor: pointer;" id="close-modal">&times;</span>
    <img id="modal-img" src="" style="max-width: 90%; max-height: 90%; object-fit: contain;">
</div>

<script>
    // Fungsi Ganti Gambar Manual (Sinkronisasi dengan Thumbnail)
    function changeMainImage(imgUrl, thumbEl = null) {
        if(!imgUrl) return;

        // Sembunyikan slideshow
        document.querySelectorAll('.slideshow-active').forEach(s => s.classList.add('d-none'));

        const mainImg = document.getElementById('product-image');
        mainImg.classList.remove('d-none');
        mainImg.src = imgUrl;

        // SINKRONISASI: Cari thumbnail yang punya src sama dengan gambar yang dipilih
        if(!thumbEl) {
            const allThumbs = document.querySelectorAll('.thumb-item');
            allThumbs.forEach(t => {
                // Gunakan URL objek atau pembanding string sederhana
                if(t.src.includes(imgUrl.split('/').pop())) {
                    thumbEl = t;
                }
            });
        }

        // Tandai thumbnail yang aktif
        document.querySelectorAll('.thumb-item').forEach(t => t.classList.remove('active'));
        if(thumbEl) thumbEl.classList.add('active');
    }

    // Tab Logic
    function switchTab(tab) {
        document.querySelectorAll('.nav-tab-item').forEach(el => el.classList.remove('active'));
        document.querySelectorAll('.tab-content-area').forEach(el => el.style.display = 'none');
        if(tab === 'desc') {
            document.querySelector('.nav-tab-item:nth-child(1)').classList.add('active');
            document.getElementById('tab-desc').style.display = 'block';
        } else {
            document.getElementById('tab-spec-header').classList.add('active');
            document.getElementById('tab-spec').style.display = 'block';
        }
    }

    // Zoom Logic
    const zoomArea = document.getElementById('zoom-area');
    if(zoomArea) {
        zoomArea.onmousemove = (e) => {
            const img = document.querySelector('.main-product-img:not(.d-none)');
            if(!img) return;
            const { left, top, width, height } = zoomArea.getBoundingClientRect();
            img.style.transformOrigin = `${((e.clientX - left) / width) * 100}% ${((e.clientY - top) / height) * 100}%`;
            img.style.transform = "scale(1.8)";
        };
        zoomArea.onmouseleave = () => {
            document.querySelectorAll('.main-product-img').forEach(img => img.style.transform = "scale(1)");
        };
    }

    // Lightbox
    document.getElementById('open-lightbox').onclick = () => {
        const activeImg = document.querySelector('.main-product-img:not(.d-none)');
        document.getElementById('image-modal').style.display = "flex";
        document.getElementById('modal-img').src = activeImg.src;
    };
    document.getElementById('close-modal').onclick = () => document.getElementById('image-modal').style.display = "none";

    // Varian Click Logic
    const quantityInput = document.getElementById('prod-quantity');
    document.querySelectorAll('.btn-variant').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.btn-variant').forEach(b => { 
                b.style.backgroundColor = 'transparent'; b.style.color = '#000'; b.style.borderColor = '#eee'; 
            });
            this.style.backgroundColor = '#013780'; this.style.color = '#fff'; this.style.borderColor = '#013780';
            
            // Ganti gambar UTAMA dan SINKRONKAN THUMBNAIL
            const vImg = this.getAttribute('data-image');
            if(vImg) changeMainImage(vImg);

            // Update Info & Price
            document.getElementById('display-price').innerHTML = 'Rp ' + new Intl.NumberFormat('id-ID').format(this.getAttribute('data-price'));
            document.getElementById('variant-spec-text').innerHTML = this.getAttribute('data-spec').replace(/\n/g, "<br>");
            switchTab('spec');

            const qtyArea = document.getElementById('quantity-area');
            if (qtyArea) {
                qtyArea.style.display = 'flex';
                document.getElementById('variant-stock-info').innerHTML = 'STOK: ' + this.getAttribute('data-stock');
                quantityInput.max = this.getAttribute('data-stock');
                quantityInput.value = 1;
                document.getElementById('btn-add-cart').disabled = (parseInt(this.getAttribute('data-stock')) <= 0);
                document.getElementById('selected-variant-id').value = this.getAttribute('data-id');
                document.getElementById('add-to-cart-form').action = `/cart/add/${this.getAttribute('data-id')}`;
            }
        });
    });

    // Quantity Logic
    if(document.getElementById('btn-plus')) {
        document.getElementById('btn-plus').onclick = () => { if(parseInt(quantityInput.value) < parseInt(quantityInput.max)) quantityInput.value = parseInt(quantityInput.value) + 1; };
        document.getElementById('btn-minus').onclick = () => { if(parseInt(quantityInput.value) > 1) quantityInput.value = parseInt(quantityInput.value) - 1; };
    }
</script>
@endsection