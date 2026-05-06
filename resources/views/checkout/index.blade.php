@extends('layouts.store')

@section('content')
<style>
    /* Konsistensi Tipografi & Warna Polman */
    .checkout-page { font-family: sans-serif; color: #2d3436; }
    .section-title { font-weight: 800; letter-spacing: -0.5px; color: #1a1a1a; margin-bottom: 1.5rem; }
    .label-custom { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; color: #adb5bd; margin-bottom: 6px; }
    
    /* Form Styling */
    .form-control-custom { 
        background-color: #f8f9fa !important; 
        border: none !important; 
        border-radius: 10px !important; 
        padding: 10px 15px !important; 
        font-size: 14px !important;
        font-weight: 600 !important;
    }
    .form-control-custom:focus { background-color: #f1f3f5 !important; box-shadow: none !important; }

    /* Ringkasan Pesanan Card */
    .summary-card { border: 1px solid #f0f0f0; background: #fff; position: sticky; top: 100px; z-index: 10; }
    .product-thumb { width: 55px; height: 55px; object-fit: cover; border-radius: 8px; background: #f8f9fa; }
    
    /* Button Styling */
    .btn-polman-primary { background: #013780; color: #fff; border-radius: 50px; font-weight: 700; font-size: 13px; padding: 12px 30px; border: none; transition: 0.3s; }
    .btn-polman-primary:hover { background: #012a61; transform: translateY(-2px); color: #fff; }
    .btn-polman-light { background: #f8f9fa; color: #6c757d; border-radius: 50px; font-weight: 700; font-size: 13px; padding: 12px 25px; border: none; transition: 0.3s; }
    .btn-polman-light:hover { background: #e9ecef; color: #495057; }

    /* Utility */
    .rounded-4 { border-radius: 1.25rem !important; }
    .rounded-5 { border-radius: 2rem !important; }
</style>

<div class="container py-5 checkout-page">
    <div class="row g-4">
        <!-- KOLOM KIRI: FORM PENGIRIMAN -->
        <div class="col-lg-8">
            <div class="bg-white rounded-5 shadow-sm p-4 p-md-5">
                <form action="{{ route('checkout.store') }}" method="POST">
                    @csrf
                    
                    <h4 class="section-title">Informasi Pengiriman</h4>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="label-custom">Nama Penerima</label>
                            <input type="text" name="recipient_name" 
                                   value="{{ old('recipient_name', auth()->user()->default_recipient_name ?: auth()->user()->display_name) }}"
                                   class="form-control form-control-custom" placeholder="Nama Lengkap">
                        </div>
                        <div class="col-md-6">
                            <label class="label-custom">No. HP (WhatsApp)</label>
                            <input type="text" name="phone" 
                                   value="{{ old('phone', auth()->user()->phone) }}"
                                   class="form-control form-control-custom" placeholder="08xxx">
                        </div>
                        <div class="col-md-4">
                            <label class="label-custom">Provinsi</label>
                            <input type="text" name="province" 
                                   value="{{ old('province', auth()->user()->default_province) }}"
                                   class="form-control form-control-custom">
                        </div>
                        <div class="col-md-4">
                            <label class="label-custom">Kota / Kabupaten</label>
                            <input type="text" name="city" 
                                   value="{{ old('city', auth()->user()->default_city) }}"
                                   class="form-control form-control-custom">
                        </div>
                        <div class="col-md-4">
                            <label class="label-custom">Kecamatan</label>
                            <input type="text" name="district" 
                                   value="{{ old('district', auth()->user()->default_district) }}"
                                   class="form-control form-control-custom">
                        </div>
                        <div class="col-md-4">
                            <label class="label-custom">Kode Pos</label>
                            <input type="text" name="postal_code" 
                                   value="{{ old('postal_code', auth()->user()->default_postal_code) }}"
                                   class="form-control form-control-custom">
                        </div>
                        <div class="col-12">
                            <label class="label-custom">Alamat Lengkap</label>
                            <textarea name="full_address" class="form-control form-control-custom" rows="3" placeholder="Nama Jalan, No. Rumah, Patokan">{{ old('full_address', auth()->user()->default_full_address) }}</textarea>
                        </div>
                    </div>

                    <div class="border-top my-5"></div>

                    <h4 class="section-title">Opsi Pengiriman & Pembayaran</h4>
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="label-custom">Pilih Jasa Kurir</label>
                            <select name="shipping_method" class="form-select form-control-custom" id="shippingMethod">
                                <option value="">-- Pilih Kurir --</option>
                                <option value="jne_reg">JNE REG - Rp 10.000</option>
                                <option value="jne_yes">JNE YES - Rp 20.000</option>
                                <option value="jnt">J&T - Rp 12.000</option>
                            </select>
                            <small class="text-muted mt-2 d-block" style="font-size: 10px;">*Ongkir akan otomatis ditambahkan ke total bayar.</small>
                        </div>
                        <div class="col-md-6">
                            <label class="label-custom">Metode Pembayaran</label>
                            @if($paymentMethods->count() > 0)
                                <select name="payment_method_id" class="form-select form-control-custom">
                                    <option value="">-- Pilih Metode --</option>
                                    @foreach($paymentMethods as $paymentMethod)
                                        <option value="{{ $paymentMethod->id }}" {{ old('payment_method_id') == $paymentMethod->id ? 'selected' : '' }}>
                                            {{ $paymentMethod->name }}
                                        </option>
                                    @endforeach
                                </select>
                            @else
                                <div class="alert alert-warning py-2 small border-0 rounded-3 mb-0">Metode pembayaran belum tersedia.</div>
                            @endif
                        </div>
                        <div class="col-12">
                            <label class="label-custom">Catatan Pesanan (Opsional)</label>
                            <textarea name="notes" class="form-control form-control-custom" rows="2" placeholder="Contoh: Titip di satpam atau warna cadangan">{{ old('notes') }}</textarea>
                        </div>
                    </div>

                    <div class="d-flex align-items-center gap-3 mt-5">
                        <a href="{{ route('cart.index') }}" class="btn-polman-light text-decoration-none text-center">KEMBALI</a>
                        <button type="submit" class="btn-polman-primary flex-grow-1 shadow-sm" {{ $paymentMethods->count() == 0 ? 'disabled' : '' }}>
                            {{ $paymentMethods->count() > 0 ? 'BUAT PESANAN SEKARANG' : 'ORDER BELUM TERSEDIA' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- KOLOM KANAN: RINGKASAN PESANAN -->
        <div class="col-lg-4">
            <div class="summary-card rounded-5 shadow-sm p-4">
                <h5 class="section-title mb-4" style="font-size: 18px;">Ringkasan Pesanan</h5>

                <div class="cart-items-list pe-1" style="max-height: 300px; overflow-y: auto;">
                    @php $grandTotal = 0; @endphp
                    @foreach($cart->items as $item)
                        @php
                            $subtotal = $item->variant->price * $item->quantity;
                            $grandTotal += $subtotal;
                        @endphp
                        <div class="d-flex align-items-center mb-3">
                            <img src="{{ $item->variant->product->image ? asset('storage/' . $item->variant->product->image) : asset('assets/img/no-image.png') }}" 
                                 class="product-thumb border shadow-sm me-3">
                            <div class="overflow-hidden">
                                <div class="fw-bold text-truncate small" style="color: #1a1a1a;">{{ $item->variant->product->name }}</div>
                                <div class="text-muted" style="font-size: 11px;">{{ $item->variant->name }} (x{{ $item->quantity }})</div>
                                <div class="fw-bold text-primary small">Rp {{ number_format($subtotal, 0, ',', '.') }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="border-top my-4"></div>
                
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted small fw-bold">SUBTOTAL</span>
                    <span class="value-text">Rp {{ number_format($grandTotal, 0, ',', '.') }}</span>
                </div>
                <div class="d-flex justify-content-between mb-4">
                    <span class="text-muted small fw-bold">ONGKOS KIRIM</span>
                    <span class="value-text" id="shippingCostDisplay">Rp 0</span>
                </div>
                
                <div class="d-flex justify-content-between align-items-center border-top pt-3">
                    <span class="fw-bold small">TOTAL BAYAR</span>
                    <h4 class="fw-bold text-primary mb-0" id="totalPaymentDisplay" style="letter-spacing: -1px;">
                        Rp {{ number_format($grandTotal, 0, ',', '.') }}
                    </h4>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Pastikan navbar tetap di atas ringkasan pesanan
    const navbar = document.querySelector('.navbar');
    if(navbar) navbar.style.zIndex = "1050";
</script>
@endsection