@extends('layouts.store')

@section('content')
@php 
    $variantCount = $product->variants->count(); 
    $minPrice = $product->variants->min('price');
    $maxPrice = $product->variants->max('price');
@endphp

<!-- SweetAlert2 Library -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
    /* 1. Navigasi Alamat (Breadcrumbs) - Compact */
    .breadcrumb-nav { display: flex; align-items: center; flex-wrap: wrap; padding: 15px 0 5px 0; }
    .breadcrumb-item-link { color: #013780; text-decoration: none; font-size: 11px; font-weight: 700; letter-spacing: 0.5px; text-transform: uppercase; padding: 6px 12px; border-radius: 8px; transition: 0.2s; }
    .breadcrumb-item-link:hover { background-color: rgba(1, 55, 128, 0.05); }
    .breadcrumb-next-icon { height: 8px; width: auto; margin: 0 4px; opacity: 0.3; }
    .breadcrumb-current { color: #adb5bd; font-size: 11px; font-weight: 600; text-transform: uppercase; padding: 6px 10px; max-width: 200px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }

    /* 2. Tombol Kembali */
    .back-prev-container { margin-bottom: 20px; padding-left: 10px; }
    .btn-back-prev { display: inline-flex; align-items: center; color: #6c757d; text-decoration: none; font-size: 10px; font-weight: 700; letter-spacing: 1px; text-transform: uppercase; transition: 0.3s; }
    .btn-back-prev img { height: 10px; width: auto; margin-right: 8px; transform: rotate(180deg); opacity: 0.5; }
    .btn-back-prev:hover { color: #013780; }
    .btn-back-prev:hover img { transform: rotate(180deg) translateX(3px); opacity: 1; }

    /* 3. Detail Gambar Section */
    .img-zoom-container { position: relative; width: 100%; height: 500px; background: #fff; display: flex; align-items: center; justify-content: center; overflow: hidden; cursor: crosshair; border: 1px solid #f0f0f0; border-radius: 12px; }
    .main-product-img { max-width: 80%; max-height: 80%; object-fit: contain; transition: transform 0.1s ease-out; }

    #image-modal { display: none; position: fixed; z-index: 9999; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.9); align-items: center; justify-content: center; }
    #modal-img { max-width: 90%; max-height: 90%; object-fit: contain; }

    /* SweetAlert Custom Style */
    .swal2-popup { border-radius: 20px !important; font-family: sans-serif !important; }
    .swal2-styled.swal2-confirm { background-color: #013780 !important; border-radius: 50px !important; padding: 12px 30px !important; font-size: 13px !important; }
    .swal2-styled.swal2-cancel { border-radius: 50px !important; padding: 12px 30px !important; font-size: 13px !important; }
</style>

<div class="container-fluid py-3">
    {{-- NAVIGASI ALAMAT --}}
    <div class="breadcrumb-nav">
        <a href="{{ route('home') }}" class="breadcrumb-item-link">BERANDA</a>
        <img src="{{ asset('assets/img/next.png') }}" class="breadcrumb-next-icon" alt=">">
        <a href="{{ route('products.index') }}" class="breadcrumb-item-link">KATALOG</a>
        <img src="{{ asset('assets/img/next.png') }}" class="breadcrumb-next-icon" alt=">">
        <a href="{{ route('products.index', ['categories' => [$product->category->slug]]) }}" class="breadcrumb-item-link">{{ $product->category->name }}</a>
        <img src="{{ asset('assets/img/next.png') }}" class="breadcrumb-next-icon" alt=">">
        <span class="breadcrumb-current">{{ $product->name }}</span>
    </div>

    {{-- TOMBOL KEMBALI --}}
    <div class="back-prev-container">
        <a href="{{ url()->previous() }}" class="btn-back-prev"><img src="{{ asset('assets/img/next.png') }}" alt="Back"> Kembali</a>
    </div>

    <div class="row g-4">
        {{-- FOTO PRODUK --}}
        <div class="col-lg-7">
            <div class="img-zoom-container" id="zoom-area">
                <div class="fullscreen-btn" id="open-lightbox" style="position: absolute; top: 15px; right: 15px; width: 35px; height: 35px; background: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 10px rgba(0,0,0,0.05); cursor: pointer;">
                    <img src="{{ asset('assets/img/loupe.png') }}" alt="Zoom" style="height: 16px;">
                </div>
                @if($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}" id="product-image" alt="{{ $product->name }}" class="main-product-img">
                @else
                    <img src="{{ asset('assets/img/foto_tidak_tersedia.png') }}" id="product-image" class="main-product-img" style="opacity: 0.1;" alt="Foto Tidak Tersedia">
                @endif
            </div>
        </div>

        {{-- DETAIL --}}
        <div class="col-lg-5">
            <div class="ps-lg-3">
                <div class="mb-1" style="font-size: 10px; font-weight: 800; letter-spacing: 1.5px; text-transform: uppercase; color: #013780; opacity: 0.6;">{{ $product->category->name }}</div>
                <h2 class="fw-bold mb-2" style="font-size: 32px; color: #1a1a1a; line-height: 1.2;">{{ $product->name }}</h2>
                <div class="mb-3">
                    <span id="display-price" style="font-size: 22px; font-weight: 800; color: #013780;">
                        @if($variantCount > 1 && $minPrice != $maxPrice)
                            Rp{{ number_format($minPrice, 0, ',', '.') }} - Rp{{ number_format($maxPrice, 0, ',', '.') }}
                        @elseif($variantCount > 0)
                            Rp{{ number_format($minPrice, 0, ',', '.') }}
                        @else
                            Stok Belum Tersedia
                        @endif
                    </span>
                </div>

                <div class="mb-4 text-muted" style="font-size: 14px; line-height: 1.6; text-align: justify;">{!! nl2br(e($product->description)) !!}</div>

                @if($variantCount > 0)
                <div class="border-top pt-3 mb-3">
                    <h6 class="fw-bold mb-2" style="font-size: 10px; letter-spacing: 1px; text-transform: uppercase; color: #666;">Variasi</h6>
                    <div class="d-flex flex-wrap gap-2">
                        @foreach($product->variants as $variant)
                            <button type="button" class="btn btn-sm btn-variant rounded-pill px-3 py-1 border"
                                    data-price="{{ $variant->price }}" data-stock="{{ $variant->stock }}" data-id="{{ $variant->id }}"
                                    style="font-size: 11px; font-weight: 600; transition: 0.2s; border-color: #eee;">
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
                            <div class="input-group input-group-sm" style="width: 110px;">
                                <button class="btn btn-outline-dark px-2" type="button" id="btn-minus">-</button>
                                <input type="number" name="quantity" id="prod-quantity" value="1" min="1" class="form-control text-center border-dark shadow-none" readonly>
                                <button class="btn btn-outline-dark px-2" type="button" id="btn-plus">+</button>
                            </div>
                            <div id="variant-stock-info" class="fw-bold text-muted" style="font-size: 11px;"></div>
                        </div>
                        <div class="d-grid">
                            <button type="submit" id="btn-add-cart" disabled class="btn btn-dark rounded-pill fw-bold" style="font-size: 13px; padding: 14px;">TAMBAHKAN KE KERANJANG</button>
                        </div>
                    </form>
                @else
                    <div id="guest-action-area" class="d-grid" style="display: none !important;">
                        <button type="button" class="btn btn-dark rounded-pill fw-bold opacity-50" style="font-size: 13px; padding: 14px;" disabled>PILIH VARIASI UNTUK MEMESAN</button>
                    </div>
                @endauth
                @endif
            </div>
        </div>
    </div>
</div>

<div id="image-modal">
    <span style="position: absolute; top: 20px; right: 30px; color: white; font-size: 30px; cursor: pointer;" id="close-modal">&times;</span>
    <img id="modal-img" alt="">
</div>

<script>
    const zoomArea = document.getElementById('zoom-area');
    const productImage = document.getElementById('product-image');
    const imageModal = document.getElementById('image-modal');
    const modalImg = document.getElementById('modal-img');
    const form = document.getElementById('add-to-cart-form');

    // Zoom Logic
    if(productImage && zoomArea) {
        zoomArea.addEventListener('mousemove', function(e) {
            const { left, top, width, height } = zoomArea.getBoundingClientRect();
            const x = ((e.clientX - left) / width) * 100;
            const y = ((e.clientY - top) / height) * 100;
            productImage.style.transformOrigin = `${x}% ${y}%`;
            productImage.style.transform = "scale(1.8)";
        });
        zoomArea.addEventListener('mouseleave', () => { productImage.style.transform = "scale(1)"; });
    }

    document.getElementById('open-lightbox').onclick = () => { imageModal.style.display = "flex"; modalImg.src = productImage.src; };
    document.getElementById('close-modal').onclick = () => imageModal.style.display = "none";

    // Variasi Click Logic
    document.querySelectorAll('.btn-variant').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.btn-variant').forEach(b => { b.style.backgroundColor = 'transparent'; b.style.color = '#000'; b.style.borderColor = '#eee'; });
            this.style.backgroundColor = '#013780'; this.style.color = '#fff'; this.style.borderColor = '#013780';
            
            const price = this.getAttribute('data-price');
            const stock = parseInt(this.getAttribute('data-stock'));
            const variantId = this.getAttribute('data-id');
            document.getElementById('display-price').innerHTML = 'Rp ' + new Intl.NumberFormat('id-ID').format(price);
            
            const qtyArea = document.getElementById('quantity-area');

            if (qtyArea) {
                // USER SUDAH LOGIN
                qtyArea.style.display = 'flex';
                document.getElementById('variant-stock-info').innerHTML = 'STOK: ' + stock;
                document.getElementById('prod-quantity').max = stock;
                document.getElementById('btn-add-cart').disabled = (stock <= 0);
                document.getElementById('selected-variant-id').value = variantId;
                if(form) form.action = `/cart/add/${variantId}`;
            } else {
                // USER BELUM LOGIN - Tampilkan SweetAlert
                Swal.fire({
                    title: 'Ingin memesan produk?',
                    text: "Silakan login terlebih dahulu untuk menambahkan produk inovasi Polman ke keranjang.",
                    icon: 'info',
                    showCancelButton: true,
                    confirmButtonText: 'LOGIN SEKARANG',
                    cancelButtonText: 'DAFTAR AKUN',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = "{{ route('login') }}";
                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                        window.location.href = "{{ route('register') }}";
                    }
                });
            }
        });
    });

    if(document.getElementById('btn-plus')) {
        document.getElementById('btn-plus').onclick = () => { const q = document.getElementById('prod-quantity'); if(parseInt(q.value) < parseInt(q.max)) q.value = parseInt(q.value) + 1; };
    }
    if(document.getElementById('btn-minus')) {
        document.getElementById('btn-minus').onclick = () => { const q = document.getElementById('prod-quantity'); if(parseInt(q.value) > 1) q.value = parseInt(q.value) - 1; };
    }
</script>
@endsection