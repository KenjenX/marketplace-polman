@extends('layouts.store')

@section('content')
@php 
    $variantCount = $product->variants->count(); 
    $minPrice = $product->variants->min('price');
    $maxPrice = $product->variants->max('price');
    $vImages = $product->variants->whereNotNull('image')->values();
    // Gambar awal
    $initialImage = $product->image ? asset('storage/' . $product->image) : ($vImages->first() ? asset('storage/' . $vImages->first()->image) : asset('assets/img/foto_tidak_tersedia.png'));
@endphp

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
    /* 1. Navigasi & Breadcrumbs */
    .breadcrumb-nav { display: flex; align-items: center; flex-wrap: wrap; padding: 15px 0 5px 0; }
    .breadcrumb-item-link { color: #013780; text-decoration: none; font-size: 11px; font-weight: 700; letter-spacing: 0.5px; text-transform: uppercase; padding: 6px 12px; border-radius: 8px; transition: 0.2s; }
    .breadcrumb-next-icon { height: 10px !important; width: auto !important; margin: 0 8px; opacity: 0.4; }
    .breadcrumb-current { color: #adb5bd; font-size: 11px; font-weight: 600; text-transform: uppercase; padding: 6px 10px; max-width: 200px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }

    /* 2. Gambar & Zoom Container */
    .img-zoom-container { 
        position: relative; 
        width: 100%; 
        height: 450px; 
        background: #fff; 
        display: flex; 
        align-items: center; 
        justify-content: center; 
        overflow: hidden; 
        cursor: crosshair; 
        border: 1px solid #f0f0f0; 
        border-radius: 12px; 
    }
    
    #main-product-display { 
        max-width: 85%; 
        max-height: 85%; 
        object-fit: contain; 
        transition: transform 0.1s ease-out, opacity 0.5s ease-in-out;
        position: relative;
        z-index: 10;
    }

    /* Thumbnail Gallery */
    .thumb-gallery { display: flex; gap: 10px; margin-top: 15px; overflow-x: auto; padding-bottom: 5px; }
    .thumb-item { width: 70px; height: 70px; border: 2px solid #eee; border-radius: 8px; cursor: pointer; object-fit: cover; transition: 0.3s; flex-shrink: 0; }
    .thumb-item.active { border-color: #013780; background-color: rgba(1, 55, 128, 0.05); }

    /* Tab & Buttons */
    .nav-tabs-custom { display: flex; gap: 20px; border-bottom: 1px solid #eee; margin-bottom: 20px; }
    .nav-tab-item { font-size: 12px; font-weight: 700; text-transform: uppercase; color: #adb5bd; padding-bottom: 10px; cursor: pointer; transition: 0.3s; }
    .nav-tab-item.active { color: #013780; border-bottom: 2px solid #013780; }
    .tab-content-area { font-size: 14px; line-height: 1.7; color: #666; height: 160px; overflow-y: auto; margin-bottom: 25px; }
    .btn-variant { font-size: 11px; font-weight: 600; transition: 0.2s; border-color: #eee; }
    #image-modal { display: none; position: fixed; z-index: 9999; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.9); align-items: center; justify-content: center; }
</style>

<div class="container-fluid py-3">
    {{-- Breadcrumbs --}}
    <div class="breadcrumb-nav">
        <a href="{{ route('home') }}" class="breadcrumb-item-link">BERANDA</a>
        <img src="{{ asset('assets/img/next.png') }}" class="breadcrumb-next-icon">
        <a href="{{ route('products.index') }}" class="breadcrumb-item-link">KATALOG</a>
        <img src="{{ asset('assets/img/next.png') }}" class="breadcrumb-next-icon">
        <span class="breadcrumb-current">{{ $product->name }}</span>
    </div>

    <div class="row g-4">
        <div class="col-lg-7">
            <div class="img-zoom-container" id="zoom-area">
                <div id="open-lightbox" style="position: absolute; top: 15px; right: 15px; width: 35px; height: 35px; background: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 10px rgba(0,0,0,0.05); cursor: pointer; z-index: 30;">
                    <img src="{{ asset('assets/img/loupe.png') }}" alt="Zoom" style="height: 16px;">
                </div>

                {{-- SATU GAMBAR UTAMA --}}
                <img src="{{ $initialImage }}" id="main-product-display" alt="{{ $product->name }}">
            </div>

            @if($vImages->count() > 0)
            <div class="thumb-gallery" id="thumbnail-container">
                @foreach($vImages as $vImg)
                    <img src="{{ asset('storage/' . $vImg->image) }}" class="thumb-item" onclick="changeMainImage('{{ asset('storage/' . $vImg->image) }}', this)" alt="Thumbnail">
                @endforeach
            </div>
            @endif
        </div>

        <div class="col-lg-5">
            {{-- Info Produk --}}
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
                <div id="tab-desc" class="tab-content-area">{!! nl2br(e($product->description)) !!}</div>
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
    const isLoggedIn = @json(auth()->check());
    const mainImg = document.getElementById('main-product-display');
    const allImages = @json($vImages->pluck('image')->map(fn($img) => asset('storage/' . $img)));
    let currentSlide = 0;
    let slideshowInterval;

    // 1. Notif Guest
    document.getElementById('add-to-cart-form').addEventListener('submit', function(e) {
        if (!isLoggedIn) {
            e.preventDefault();
            Swal.fire({
                title: 'Perlu Login',
                text: "Silakan login atau daftar akun terlebih dahulu untuk mulai berbelanja.",
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#013780',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Masuk Sekarang',
                cancelButtonText: 'Nanti Saja'
            }).then((result) => {
                if (result.isConfirmed) window.location.href = "{{ route('login') }}";
            });
        }
    });

    // 2. Fungsi Ganti Gambar
    function changeMainImage(imgUrl, thumbEl = null, isManual = true) {
        if(!imgUrl) return;
        
        // Jika user klik manual (thumbnail/varian), hentikan slideshow otomatis
        if(isManual) clearInterval(slideshowInterval);

        mainImg.style.opacity = '0';
        setTimeout(() => {
            mainImg.src = imgUrl;
            mainImg.style.opacity = '1';
        }, 250);

        document.querySelectorAll('.thumb-item').forEach(t => t.classList.remove('active'));
        if(thumbEl) thumbEl.classList.add('active');
    }

    // 3. Slideshow Otomatis (Ganti animasi CSS yang tumpuk-tumpukan)
    function startSlideshow() {
        if(allImages.length < 2) return;
        slideshowInterval = setInterval(() => {
            currentSlide = (currentSlide + 1) % allImages.length;
            const nextImg = allImages[currentSlide];
            changeMainImage(nextImg, document.querySelectorAll('.thumb-item')[currentSlide], false);
        }, 4000);
    }
    startSlideshow();

    // 4. Zoom Logic (Anti-Loncat)
    const zoomArea = document.getElementById('zoom-area');
    zoomArea.onmousemove = (e) => {
        const { left, top, width, height } = zoomArea.getBoundingClientRect();
        const x = ((e.clientX - left) / width) * 100;
        const y = ((e.clientY - top) / height) * 100;
        mainImg.style.transformOrigin = `${x}% ${y}%`;
        mainImg.style.transform = "scale(2)";
    };
    zoomArea.onmouseleave = () => {
        mainImg.style.transform = "scale(1)";
    };

    // 5. Varian Click
    document.querySelectorAll('.btn-variant').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.btn-variant').forEach(b => { 
                b.style.backgroundColor = 'transparent'; b.style.color = '#000'; b.style.borderColor = '#eee'; 
            });
            this.style.backgroundColor = '#013780'; this.style.color = '#fff';
            
            const vImg = this.getAttribute('data-image');
            if(vImg) changeMainImage(vImg);

            document.getElementById('display-price').innerHTML = 'Rp ' + new Intl.NumberFormat('id-ID').format(this.getAttribute('data-price'));
            document.getElementById('variant-spec-text').innerHTML = this.getAttribute('data-spec').replace(/\n/g, "<br>");
            
            const qtyArea = document.getElementById('quantity-area');
            qtyArea.style.display = 'flex';
            document.getElementById('variant-stock-info').innerHTML = 'STOK: ' + this.getAttribute('data-stock');
            document.getElementById('prod-quantity').max = this.getAttribute('data-stock');
            document.getElementById('btn-add-cart').disabled = (parseInt(this.getAttribute('data-stock')) <= 0);
            document.getElementById('selected-variant-id').value = this.getAttribute('data-id');
            document.getElementById('add-to-cart-form').action = `/cart/add/${this.getAttribute('data-id')}`;
        });
    });

    // Lightbox & Tab
    document.getElementById('open-lightbox').onclick = () => {
        document.getElementById('image-modal').style.display = "flex";
        document.getElementById('modal-img').src = mainImg.src;
    };
    document.getElementById('close-modal').onclick = () => document.getElementById('image-modal').style.display = "none";
    
    function switchTab(tab) {
        document.querySelectorAll('.nav-tab-item').forEach(el => el.classList.remove('active'));
        document.querySelectorAll('.tab-content-area').forEach(el => el.style.display = 'none');
        if(tab === 'desc') {
            document.querySelector('.nav-tab-item:first-child').classList.add('active');
            document.getElementById('tab-desc').style.display = 'block';
        } else {
            document.getElementById('tab-spec-header').classList.add('active');
            document.getElementById('tab-spec').style.display = 'block';
        }
    }

    const quantityInput = document.getElementById('prod-quantity');
    document.getElementById('btn-plus').onclick = () => { if(parseInt(quantityInput.value) < parseInt(quantityInput.max)) quantityInput.value = parseInt(quantityInput.value) + 1; };
    document.getElementById('btn-minus').onclick = () => { if(parseInt(quantityInput.value) > 1) quantityInput.value = parseInt(quantityInput.value) - 1; };
</script>
@endsection