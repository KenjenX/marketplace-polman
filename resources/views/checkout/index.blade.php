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
                            <label class="form-label fw-semibold">
                                Provinsi
                            </label>

                            <input
                                type="text"
                                class="form-control bg-light border-0 py-2 shadow-none"
                                value="{{ $userAddress->province ?? auth()->user()->default_province }}"
                                readonly
                            >

                            <input
                                type="hidden"
                                name="province"
                                value="{{ $userAddress->province ?? auth()->user()->default_province }}"
                            >
                        </div>

                        {{-- KOTA / KABUPATEN --}}
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">
                                Kota / Kabupaten
                            </label>

                            <input
                                type="text"
                                class="form-control bg-light border-0 py-2 shadow-none"
                                value="{{ $userAddress->city ?? auth()->user()->default_city }}"
                                readonly
                            >

                            <input
                                type="hidden"
                                name="city_id"
                                value="55"
                            >

                            <input
                                type="hidden"
                                name="city"
                                value="{{ $userAddress->city ?? auth()->user()->default_city }}"
                            >
                        </div>

                        {{-- KECAMATAN --}}
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">
                                Kecamatan
                            </label>

                            <input
                                type="text"
                                class="form-control bg-light border-0 py-2 shadow-none"
                                value="{{ $userAddress->district ?? auth()->user()->default_district }}"
                                readonly
                            >

                            <input
                                type="hidden"
                                name="district"
                                value="{{ $userAddress->district ?? auth()->user()->default_district }}"
                            >
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold">Alamat Lengkap</label>
                            <textarea name="full_address"
                                      class="form-control bg-light border-0 shadow-none" rows="3" required>{{ old('full_address', auth()->user()->default_full_address) }}</textarea>
                        </div>
                    </div>

                    <hr class="my-4 opacity-25">

                    <h4 class="fw-bold mb-4">Opsi Pengiriman</h4>
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Pilih Kurir</label>
                        <select name="shipping_method" class="form-select bg-light border-0 py-2 shadow-none" id="shippingMethod" required>
                            <option value="">-- Pilih Jasa Pengiriman --</option>
                            <option value="jne" {{ old('shipping_method') == 'jne' ? 'selected' : '' }}>JNE (Reguler/YES)</option>
                            <option value="pos" {{ old('shipping_method') == 'pos' ? 'selected' : '' }}>POS Indonesia</option>
                            <option value="tiki" {{ old('shipping_method') == 'tiki' ? 'selected' : '' }}>TIKI</option>
                        </select>
                        <small class="text-muted mt-2 d-block">*Ongkir akan dihitung otomatis dari Bandung ke lokasi Anda.</small>
                    </div>

                    <hr class="my-4 opacity-25">

                    <h4 class="fw-bold mb-4">Pembayaran</h4>
                    @if($paymentMethods->count() > 0)
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Pilih Metode Pembayaran</label>
                            <select name="payment_method_id" class="form-select bg-light border-0 py-2 shadow-none" required>
                                <option value="">-- Pilih Metode --</option>
                                @foreach($paymentMethods as $paymentMethod)
                                    <option value="{{ $paymentMethod->id }}" {{ old('payment_method_id') == $paymentMethod->id ? 'selected' : '' }}>
                                        {{ $paymentMethod->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @else
                        <div class="alert alert-warning border-0 rounded-3">
                            <i class="bi bi-exclamation-triangle me-2"></i> Metode pembayaran belum tersedia. Hubungi admin.
                        </div>
                    @endif

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Catatan Pesanan (Opsional)</label>
                        <textarea name="notes" class="form-control bg-light border-0 shadow-none" rows="2" placeholder="Contoh: Titip di satpam">{{ old('notes') }}</textarea>
                    </div>

                    <div class="d-flex gap-2 mt-5">
                        <a href="{{ route('cart.index') }}" class="btn btn-light px-4 fw-semibold text-muted rounded-pill">Kembali</a>
                        @if($paymentMethods->count() > 0)
                            <button type="submit" class="btn btn-primary px-5 fw-bold rounded-pill">Buat Pesanan Sekarang</button>
                        @else
                            <button type="button" class="btn btn-secondary px-4 fw-bold rounded-pill" disabled>Order Belum Tersedia</button>
                        @endif
                    </div>
                </form>
            </div>
        </div>

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
                    <span class="fw-bold text-info italic" style="font-size: 0.85rem;">Dihitung saat proses...</span>
                </div>
                
                <div class="d-flex justify-content-between align-items-center border-top pt-3">
                    <h5 class="fw-bold mb-0">Total Harga Barang</h5>
                    <h4 class="fw-bold text-primary mb-0">Rp {{ number_format($grandTotal, 0, ',', '.') }}</h4>
                </div>
                <p class="text-muted small mt-2">*Belum termasuk biaya pengiriman.</p>
            </div>
        </div>
    </div>
</div>

<style>
    .navbar { z-index: 1050 !important; }
    .form-control:focus, .form-select:focus {
        background-color: #f1f3f5 !important;
        border: 1px solid #dee2e6 !important;
    }
</style>
@endsection