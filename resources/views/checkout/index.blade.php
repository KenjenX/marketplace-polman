@extends('layouts.store')

@section('content')
<div class="container py-5">
    <div class="row g-4">
        <!-- Kolom Kiri: Form Checkout -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
                <h4 class="fw-bold mb-4">Informasi Pengiriman</h4>
                
                <form action="{{ route('checkout.store') }}" method="POST">
                    @csrf
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nama Penerima</label>
                            <input type="text" name="recipient_name" value="{{ old('recipient_name', auth()->user()->default_recipient_name ?: auth()->user()->display_name) }}" class="form-control bg-light border-0 py-2 shadow-none">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">No. HP</label>
                            <input type="text" name="phone" value="{{ old('phone', auth()->user()->phone) }}" class="form-control bg-light border-0 py-2 shadow-none">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Provinsi</label>
                            <input type="text" name="province" value="{{ old('province', auth()->user()->default_province) }}" class="form-control bg-light border-0 py-2 shadow-none">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Kota / Kabupaten</label>
                            <input type="text" name="city" value="{{ old('city', auth()->user()->default_city) }}" class="form-control bg-light border-0 py-2 shadow-none">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Kode Pos</label>
                            <input type="text" name="postal_code" value="{{ old('postal_code', auth()->user()->default_postal_code) }}" class="form-control bg-light border-0 py-2 shadow-none">
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold">Alamat Lengkap</label>
                            <textarea name="full_address" class="form-control bg-light border-0 shadow-none" rows="3">{{ old('full_address', auth()->user()->default_full_address) }}</textarea>
                        </div>
                    </div>

                    <hr class="my-4 opacity-25">

                    {{-- BAGIAN OPSI PENGIRIMAN (KOSONGAN) --}}
                    <h4 class="fw-bold mb-4">Opsi Pengiriman</h4>
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Pilih Kurir</label>
                        <select name="shipping_method" class="form-select bg-light border-0 py-2 shadow-none" id="shippingMethod">
                            <option value="">-- Pilih Jasa Pengiriman --</option>
                            {{-- Nanti data ongkir diambil/diisi di sini --}}
                        </select>
                        <small class="text-muted mt-2 d-block">*Silakan isi alamat lengkap untuk melihat opsi pengiriman.</small>
                    </div>

                    <hr class="my-4 opacity-25">

                    <h4 class="fw-bold mb-4">Pembayaran</h4>
                    @if($paymentMethods->count() > 0)
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Pilih Metode Pembayaran</label>
                            <select name="payment_method_id" class="form-select bg-light border-0 py-2 shadow-none">
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

        <!-- Kolom Kanan: Ringkasan Pesanan (Sticky) -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 p-4 sticky-top" style="top: 2rem;">
                <h5 class="fw-bold mb-4">Ringkasan Pesanan</h5>

                @php $grandTotal = 0; @endphp
                @foreach($cart->items as $item)
                    @php
                        $subtotal = $item->variant->price * $item->quantity;
                        $grandTotal += $subtotal;
                    @endphp
                    <div class="d-flex align-items-center mb-3">
                        <div class="rounded-3 bg-light p-1 me-3 shadow-sm" style="width: 65px; height: 65px; flex-shrink: 0;">
                            <img src="{{ $item->variant->product->image ? asset('storage/' . $item->variant->product->image) : asset('assets/img/no-image.png') }}" class="w-100 h-100 object-fit-cover rounded-2">
                        </div>
                        <div class="flex-grow-1 overflow-hidden">
                            <h6 class="mb-0 fw-bold small text-truncate">{{ $item->variant->product->name }}</h6>
                            <small class="text-muted d-block">{{ $item->variant->name }} (x{{ $item->quantity }})</small>
                            <span class="fw-bold small text-primary">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
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
                    {{-- ID ongkir ini bisa kamu update via JS nanti --}}
                    <span class="fw-bold text-dark" id="shippingCostDisplay">Rp 0</span>
                </div>
                
                <div class="d-flex justify-content-between align-items-center border-top pt-3">
                    <h5 class="fw-bold mb-0">Total Bayar</h5>
                    <h4 class="fw-bold text-primary mb-0" id="totalPaymentDisplay">Rp {{ number_format($grandTotal, 0, ',', '.') }}</h4>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .form-control:focus, .form-select:focus {
        background-color: #f1f3f5 !important;
        border: 1px solid #dee2e6 !important;
    }
    .sticky-top {
        z-index: 10;
    }
    .img-hover-effect:hover {
        transform: scale(1.05);
        transition: 0.3s;
    }
</style>
@endsection