@extends('layouts.store')

@section('content')
<div class="container py-5">
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
                <h4 class="fw-bold mb-4">Informasi Pengiriman</h4>
                
                @if($errors->any())
                    <div class="alert alert-danger border-0 rounded-3">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('checkout.store') }}" method="POST">
                    @csrf
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nama Penerima</label>
                            <input type="text" name="recipient_name"
                                   value="{{ old('recipient_name', auth()->user()->default_recipient_name ?: auth()->user()->display_name) }}"
                                   class="form-control bg-light border-0 py-2 shadow-none" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">No. HP</label>
                            <input type="text" name="phone"
                                   value="{{ old('phone', auth()->user()->phone) }}"
                                   class="form-control bg-light border-0 py-2 shadow-none" required>
                        </div>

                        {{-- PROVINSI --}}
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Provinsi</label>
                            <input type="text" class="form-control bg-light border-0 py-2 shadow-none"
                                   value="{{ $userAddress->province ?? auth()->user()->default_province }}" readonly>
                            <input type="hidden" name="province" value="{{ $userAddress->province ?? auth()->user()->default_province }}">
                        </div>

                        {{-- KOTA / KABUPATEN --}}
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Kota / Kabupaten</label>
                            <input type="text" class="form-control bg-light border-0 py-2 shadow-none"
                                   value="{{ $userAddress->city ?? auth()->user()->default_city }}" readonly>
                            <input type="hidden" name="city_id" value="55">
                            <input type="hidden" name="city" value="{{ $userAddress->city ?? auth()->user()->default_city }}">
                        </div>

                        {{-- KECAMATAN --}}
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Kecamatan</label>
                            <input type="text" class="form-control bg-light border-0 py-2 shadow-none"
                                   value="{{ $userAddress->district ?? auth()->user()->default_district }}" readonly>
                            <input type="hidden" name="district" value="{{ $userAddress->district ?? auth()->user()->default_district }}">
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold">Alamat Lengkap</label>
                            <textarea name="full_address" class="form-control bg-light border-0 shadow-none" rows="3" required>{{ old('full_address', auth()->user()->default_full_address) }}</textarea>
                        </div>
                    </div>

                    <hr class="my-4 opacity-25">

                    {{-- METODE PENGIRIMAN --}}
                    <h4 class="fw-bold mb-3">Metode Pengiriman</h4>
                    <div class="p-3 bg-light rounded-3 border d-flex align-items-center mb-4">
                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                            <i class="bi bi-truck-flatbed" style="font-size: 1.2rem;"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-0 text-dark">J&T Kargo (Layanan Kargo Khusus Barang Besar)</h6>
                            <small class="text-muted">Metode pengiriman otomatis disesuaikan untuk efisiensi ongkos kirim volume besar.</small>
                        </div>
                        <input type="hidden" name="shipping_method" value="jnt_kargo">
                    </div>

                    <hr class="my-4 opacity-25">

                    {{-- SELECTION CARD PEMBAYARAN --}}
                    <h4 class="fw-bold mb-2">Pembayaran</h4>
                    <p class="text-muted small mb-4">Pilih metode pembayaran :</p>
                    
                    <div class="d-flex flex-column gap-3 payment-container">
                        
                        {{-- ==================== 1. CARD MANDIRI MANUAL (STALIK / URUTAN PERTAMA) ==================== --}}
                        <label class="payment-card-wrapper m-0">
                            <input type="radio" name="payment_method_id" value="manual_mandiri" 
                                   class="payment-radio-input" required 
                                   {{ old('payment_method_id') == '3' ? 'checked' : '' }}>
                            
                            <div class="card payment-custom-card p-4 rounded-4 shadow-sm border">
                                <div class="d-flex justify-content-between align-items-start w-100">
                                    <div class="flex-grow-1 w-100">
                                        <h5 class="fw-bold text-dark mb-2">Transfer Bank Mandiri (Manual Verification)</h5>
                                        <p class="text-muted small mb-3">Transfer manual ke rekening Mandiri kami. Proses verifikasi membutuhkan upload bukti transfer.</p>
                                        
                                        <div class="p-3 bg-light rounded-3 border mt-2 border-dashed" style="max-width: 450px;">
                                            <div class="small text-dark mb-1"><span class="text-muted">Bank:</span> <strong style="color: #1c3f60;">Bank Mandiri</strong></div>
                                            <div class="small text-dark mb-1"><span class="text-muted">No. Rekening:</span> <strong class="fs-6 text-primary">123-00-9876543-21</strong></div>
                                            <div class="small text-dark"><span class="text-muted">Atas Nama:</span> <strong>Politeknik Manufaktur Bandung</strong></div>
                                        </div>
                                    </div>
                                    
                                    <div class="selection-indicator-checkmark ms-3">
                                        <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center" style="width: 24px; height: 24px;">
                                            <i class="bi bi-check-lg" style="font-size: 0.9rem; stroke-width: 2px;"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </label>

                        {{-- ==================== METODE DARI DATABASE (DYNAMIC CARDS) ==================== --}}
                        @if($paymentMethods->count() > 0)
                            @foreach($paymentMethods as $paymentMethod)
                                @php
                                    $slug = Str::slug($paymentMethod->name);
                                    $isXendit = Str::contains(Str::lower($paymentMethod->name), 'xendit');
                                    $isMandiri = Str::contains(Str::lower($paymentMethod->name), 'mandiri');
                                @endphp

                                {{-- Lewati looping jika nama payment method dari DB mengandung kata mandiri (agar tidak double karena sudah dihandle manual di atas) --}}
                                @if($isMandiri) @continue @endif

                                <label class="payment-card-wrapper m-0">
                                    <input type="radio" name="payment_method_id" value="{{ $paymentMethod->id }}" 
                                            class="payment-radio-input" required 
                                            {{ old('payment_method_id') == $paymentMethod->id ? 'checked' : '' }}>
                                    
                                    <div class="card payment-custom-card p-4 rounded-4 shadow-sm border">
                                        <div class="d-flex justify-content-between align-items-start w-100">
                                            <div class="flex-grow-1 w-100">
                                                <h5 class="fw-bold text-dark mb-3">
                                                    @if($isXendit)
                                                        Transfer Virtual Account
                                                    @else
                                                        {{ $paymentMethod->name }}
                                                    @endif
                                                </h5>
                                                
                                                {{-- ==================== KONTEN VIRTUAL ACCOUNT (XENDIT) ==================== --}}
                                                @if(Str::contains($slug, 'virtual-account') || Str::contains($slug, 'va') || $isXendit)
                                                    <p class="text-muted small mb-3">Opsi Transfer Virtual Account hanya tersedia pada bank berikut:</p>
                                                    
                                                    <div class="d-flex flex-wrap gap-2 border-top pt-3 mt-2">
                                                        <div class="d-flex align-items-center gap-2 px-3 py-2 rounded-3 border bg-light style-bank-bri">
                                                            <span class="fw-extrabold text-primary fs-5 italic-style"><i class="bi bi-bank"></i> BANK BRI</span>
                                                        </div>
                                                        
                                                        <div class="d-flex align-items-center gap-2 px-3 py-2 rounded-3 border bg-light style-bank-bni">
                                                            <span class="fw-bold fs-5 italic-style" style="letter-spacing: -1px; color: #005EAC !important;">BNI</span>
                                                        </div>
                                                    </div>

                                                {{-- ==================== KONTEN QRIS ==================== --}}
                                                @elseif(Str::contains($slug, 'qris'))
                                                    <p class="text-muted small mb-0">Transaksi QRIS dapat dilakukan di semua aplikasi yang mendukung QRIS.</p>
                                                
                                                {{-- ==================== KONTEN E-WALLET ==================== --}}
                                                @elseif(Str::contains($slug, 'e-wallet') || Str::contains($slug, 'wallet'))
                                                    <p class="text-muted small mb-3">Pilih salah satu E-wallet:</p>
                                                    
                                                    <div class="d-flex flex-column gap-2 border-top pt-3 mt-2 ewallet-sub-options">
                                                        <label class="d-flex align-items-center gap-3 p-2 rounded-3 border bg-white cursor-pointer sub-wallet-item">
                                                            <input type="radio" name="ewallet_type" value="dana" class="sub-wallet-radio" {{ old('ewallet_type') == 'dana' ? 'checked' : '' }}>
                                                            <div class="d-flex align-items-center gap-2 text-dark fw-semibold small">
                                                                <span class="text-info fw-bold fs-6"><i class="bi bi-wallet2"></i></span> Dana
                                                            </div>
                                                        </label>
                                                        
                                                        <label class="d-flex align-items-center gap-3 p-2 rounded-3 border bg-white cursor-pointer sub-wallet-item">
                                                            <input type="radio" name="ewallet_type" value="ovo" class="sub-wallet-radio" {{ old('ewallet_type') == 'ovo' ? 'checked' : '' }}>
                                                            <div class="d-flex align-items-center gap-2 text-dark fw-semibold small">
                                                                <span class="text-primary fw-bold fs-6"><i class="bi bi-wallet2"></i></span> OVO
                                                            </div>
                                                        </label>
                                                        
                                                        <label class="d-flex align-items-center gap-3 p-2 rounded-3 border bg-white cursor-pointer sub-wallet-item">
                                                            <input type="radio" name="ewallet_type" value="linkaja" class="sub-wallet-radio" {{ old('ewallet_type') == 'linkaja' ? 'checked' : '' }}>
                                                            <div class="d-flex align-items-center gap-2 text-dark fw-semibold small">
                                                                <span class="text-danger fw-bold fs-6"><i class="bi bi-wallet2"></i></span> LinkAja
                                                            </div>
                                                        </label>
                                                    </div>

                                                    <div class="ewallet-phone-field mt-3 pt-2 border-top" style="display: none;">
                                                        <label class="form-label small fw-bold text-dark">Nomor HP E-Wallet</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text bg-light border text-muted small">+62</span>
                                                            <input type="text" name="ewallet_phone" id="ewallet_phone_input"
                                                                   class="form-control bg-white border small shadow-none" 
                                                                   placeholder="8xxxxxxxx" 
                                                                   value="{{ old('ewallet_phone') }}">
                                                        </div>
                                                        <div class="text-danger small mt-2 fw-semibold" style="font-size: 0.8rem;">
                                                            *Pastikan No HP yang Kamu isi terdaftar di OVO/DANA/LinkAja.
                                                        </div>
                                                    </div>

                                                {{-- ==================== KONTEN GERAI RETAIL ==================== --}}
                                                @elseif(Str::contains($slug, 'alfamart') || Str::contains($slug, 'gerai'))
                                                    <div class="d-flex align-items-center gap-2 mt-1">
                                                        <span class="badge bg-danger text-white px-3 py-2 fs-6 fw-bold text-uppercase" style="letter-spacing: 0.5px;">Alfamart</span>
                                                    </div>
                                                @else
                                                    <p class="text-muted small mb-0">Selesaikan transaksi menggunakan layanan pembayaran {{ $paymentMethod->name }}.</p>
                                                @endif
                                            </div>
                                            
                                            <div class="selection-indicator-checkmark ms-3">
                                                <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center" style="width: 24px; height: 24px;">
                                                    <i class="bi bi-check-lg" style="font-size: 0.9rem; stroke-width: 2px;"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </label>
                            @endforeach
                        @endif
                    </div>

                    <div class="mb-4 mt-4">
                        <label class="form-label fw-semibold">Catatan Pesanan (Opsional)</label>
                        <textarea name="notes" class="form-control bg-light border-0 shadow-none" rows="2" placeholder="Contoh: Titip di satpam">{{ old('notes') }}</textarea>
                    </div>

                    <div class="d-flex gap-2 mt-5">
                        <a href="{{ route('cart.index') }}" class="btn btn-light px-4 fw-semibold text-muted rounded-pill">Kembali</a>
                        <button type="submit" class="btn btn-primary px-5 fw-bold rounded-pill">Buat Pesanan Sekarang</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- RINGKASAN PESANAN --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 p-4 sticky-top" style="top: 100px; z-index: 10;">
                <h5 class="fw-bold mb-4">Ringkasan Pesanan</h5>

                @php $grandTotal = 0; @endphp
                @foreach($cart->items as $item)
                    @php
                        $subtotalItem = $item->variant->price * $item->quantity;
                        $grandTotal += $subtotalItem;
                    @endphp
                    <div class="d-flex align-items-center mb-3">
                        <div class="rounded-3 bg-light p-1 me-3 shadow-sm" style="width: 65px; height: 65px; flex-shrink: 0;">
                            <img src="{{ $item->variant->product->image ? asset('storage/' . $item->variant->product->image) : asset('assets/img/no-image.png') }}" class="w-100 h-100 object-fit-cover rounded-2">
                        </div>
                        <div class="flex-grow-1 overflow-hidden">
                            <h6 class="mb-0 fw-bold small text-truncate">{{ $item->variant->product->name }}</h6>
                            <small class="text-muted d-block">{{ $item->variant->name }} (x{{ $item->quantity }})</small>
                            <span class="fw-bold small text-primary">Rp {{ number_format($subtotalItem, 0, ',', '.') }}</span>
                        </div>
                    </div>
                @endforeach

                <hr class="opacity-25 my-4">
                
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Subtotal</span>
                    <span class="fw-bold">Rp {{ number_format($grandTotal, 0, ',', '.') }}</span>
                </div>
                <div class="d-flex justify-content-between mb-4">
                    <span class="text-muted">Ongkos Kirim</span>
                    <span class="fw-bold text-success italic" style="font-size: 0.85rem;"><i class="bi bi-shield-check"></i> J&T Kargo Terpilih</span>
                </div>
                
                <div class="d-flex justify-content-between align-items-center border-top pt-3">
                    <h5 class="fw-bold mb-0">Total Harga Barang</h5>
                    <h4 class="fw-bold text-primary mb-0">Rp {{ number_format($grandTotal, 0, ',', '.') }}</h4>
                </div>
                <p class="text-muted small mt-2">*Belum termasuk biaya pengiriman resmi dari kargo.</p>
            </div>
        </div>
    </div>
</div>

{{-- MODIFIKASI CSS & INTERAKSI SELECTION --}}
<style>
    .navbar { z-index: 1050 !important; }
    .form-control:focus {
        background-color: #f1f3f5 !important;
        border: 1px solid #dee2e6 !important;
    }
    .cursor-pointer { cursor: pointer; }
    .italic-style { font-style: italic; }
    .border-dashed { border-style: dashed !important; }

    /* CSS Utama Card Payment */
    .payment-card-wrapper {
        display: block;
        cursor: pointer;
        position: relative;
    }
    .payment-radio-input {
        position: absolute;
        opacity: 0;
        width: 0;
        height: 0;
    }
    .payment-custom-card {
        background-color: #ffffff !important;
        border: 1px solid #e2e8f0 !important;
        transition: all 0.25s ease-in-out;
    }
    .selection-indicator-checkmark {
        display: none;
    }

    /* AKSI KETIKA CARD UTAMA TERPILIH */
    .payment-radio-input:checked + .payment-custom-card {
        border-color: #0d6efd !important;
        box-shadow: 0 0.5rem 1rem rgba(13, 110, 253, 0.08) !important;
    }
    .payment-radio-input:checked + .payment-custom-card .selection-indicator-checkmark {
        display: block;
    }

    /* CSS SUB-PILIHAN E-WALLET */
    .sub-wallet-item {
        transition: all 0.2s ease;
        border: 1px solid #e2e8f0 !important;
    }
    .sub-wallet-radio {
        transform: scale(1.1);
        cursor: pointer;
    }
    
    /* Highlight Background Biru Muda saat sub-pilihan aktif */
    .sub-wallet-item:has(.sub-wallet-radio:checked) {
        border-color: #0d6efd !important;
        background-color: #f8f9ff !important;
    }

    .style-bank-bri span, .style-bank-bni span {
        font-family: 'Arial Black', sans-serif;
    }
</style>

{{-- JAVASCRIPT INTERAKTIF DINAMIS --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const paymentRadios = document.querySelectorAll('.payment-radio-input');
        
        function updatePaymentFields() {
            paymentRadios.forEach(radio => {
                const card = radio.closest('.payment-custom-card');
                if (!card) return;
                
                // Elemen kontrol E-Wallet 
                const ewalletField = card.querySelector('.ewallet-phone-field');
                const subWalletRadios = card.querySelectorAll('.sub-wallet-radio');
                const phoneInput = card.querySelector('#ewallet_phone_input');

                if (radio.checked) {
                    // Jika card E-wallet aktif
                    if (ewalletField) {
                        ewalletField.style.display = 'block';
                        subWalletRadios.forEach(input => input.required = true);
                        if (phoneInput) phoneInput.required = true;
                    }
                } else {
                    // Reset field E-wallet jika tidak dipilih
                    if (ewalletField) {
                        ewalletField.style.display = 'none';
                        subWalletRadios.forEach(input => {
                            input.required = false;
                            input.checked = false;
                        });
                        if (phoneInput) {
                            phoneInput.required = false;
                            phoneInput.value = '';
                        }
                    }
                }
            });
        }

        // Menjalankan fungsi saat halaman pertama kali dimuat
        updatePaymentFields();

        // Mendaftarkan event listener perubahan pilihan card
        paymentRadios.forEach(radio => {
            radio.addEventListener('change', updatePaymentFields);
        });
    });
</script>
@endsection