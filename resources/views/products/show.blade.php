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
            <div style="width: 100%; height: 550px; background: transparent; display: flex; align-items: center; justify-content: flex-start; overflow: hidden;">
                @if($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" style="max-width: 100%; max-height: 100%; object-fit: contain; object-position: left;">
                @else
                    <img src="{{ asset('assets/img/foto_tidak_tersedia.png') }}" alt="Foto tidak tersedia" style="max-width: 100%; max-height: 100%; object-fit: contain; object-position: left; opacity: 0.3;">
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
                        
                        {{-- Fitur Plus Minus Quantity --}}
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

<script>
    const btnMinus = document.getElementById('btn-minus');
    const btnPlus = document.getElementById('btn-plus');
    const qtyInput = document.getElementById('prod-quantity');

    // --- 1. LOGIKA PILIH VARIAN ---
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

            const form = document.getElementById('add-to-cart-form');
            if(form) form.action = "{{ url('cart/add') }}/" + id;
        });
    });

    // --- 2. LOGIKA PLUS MINUS ---
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

    // --- 3. POPUP BERHASIL ---
    @if(session('success'))
        Swal.fire({
            title: 'Berhasil!',
            text: "{{ session('success') }}",
            icon: 'success',
            showCancelButton: true,
            confirmButtonColor: '#013780',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Lihat Keranjang',
            cancelButtonText: 'Lanjut Belanja',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "{{ route('cart.index') }}";
            }
        });
    @endif
</script>
@endsection