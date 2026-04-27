@extends('layouts.store')

@section('content')
<div class="row g-4">
    <div class="col-lg-5">
        <div class="content-card">
            <h3 class="mb-3">Ringkasan Pesanan</h3>

            @php
                $grandTotal = 0;
            @endphp

            @foreach($cart->items as $item)
                @php
                    $subtotal = $item->variant->price * $item->quantity;
                    $grandTotal += $subtotal;
                @endphp

                <div class="border-bottom pb-3 mb-3">
                    <div class="fw-semibold">{{ $item->variant->product->name }}</div>
                    <div class="text-muted small">{{ $item->variant->name }}</div>
                    <div>Qty: {{ $item->quantity }}</div>
                    <div>Rp {{ number_format($subtotal, 0, ',', '.') }}</div>
                </div>
            @endforeach

            <h4 class="mb-0">Total: Rp {{ number_format($grandTotal, 0, ',', '.') }}</h4>
        </div>
    </div>

    <div class="col-lg-7">
        <div class="content-card">
            <h3 class="mb-4">Form Checkout</h3>

            <form action="{{ route('checkout.store') }}" method="POST">
                @csrf

                <h5 class="mb-3">Alamat Pengiriman</h5>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Nama Penerima</label>
                        <input type="text" name="recipient_name" value="{{ old('recipient_name') }}" class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">No. HP</label>
                        <input type="text" name="phone" value="{{ old('phone') }}" class="form-control">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Provinsi</label>
                        <input type="text" name="province" value="{{ old('province') }}" class="form-control">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Kota / Kabupaten</label>
                        <input type="text" name="city" value="{{ old('city') }}" class="form-control">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Kecamatan</label>
                        <input type="text" name="district" value="{{ old('district') }}" class="form-control">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Kode Pos</label>
                        <input type="text" name="postal_code" value="{{ old('postal_code') }}" class="form-control">
                    </div>

                    <div class="col-12">
                        <label class="form-label">Alamat Lengkap</label>
                        <textarea name="full_address" class="form-control" rows="4">{{ old('full_address') }}</textarea>
                    </div>
                </div>

                <hr class="my-4">

                <h5 class="mb-3">Pembayaran</h5>

                @if($paymentMethods->count() > 0)
                    <div class="mb-3">
                        <label class="form-label">Metode Pembayaran</label>
                        <select name="payment_method_id" class="form-select">
                            <option value="">-- Pilih Metode Pembayaran --</option>
                            @foreach($paymentMethods as $paymentMethod)
                                <option value="{{ $paymentMethod->id }}" {{ old('payment_method_id') == $paymentMethod->id ? 'selected' : '' }}>
                                    {{ $paymentMethod->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mt-3">
                        @foreach($paymentMethods as $paymentMethod)
                            <div class="border rounded-3 p-3 mb-2">
                                <div class="fw-semibold">{{ $paymentMethod->name }}</div>
                                <div><strong>Bank:</strong> {{ $paymentMethod->bank_name }}</div>
                                <div><strong>No. Rekening:</strong> {{ $paymentMethod->account_number }}</div>
                                <div><strong>Atas Nama:</strong> {{ $paymentMethod->account_name }}</div>

                                @if($paymentMethod->instruction)
                                    <div class="text-muted mt-2">{{ $paymentMethod->instruction }}</div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="alert alert-warning">
                        Saat ini belum ada metode pembayaran aktif. Silakan hubungi admin atau coba lagi nanti.
                    </div>
                @endif

                <div class="mb-3">
                    <label class="form-label">Catatan</label>
                    <textarea name="notes" class="form-control" rows="3">{{ old('notes') }}</textarea>
                </div>

                <div class="d-flex gap-2">
                    <a href="{{ route('cart.index') }}" class="btn btn-outline-secondary">Kembali ke Keranjang</a>
                    @if($paymentMethods->count() > 0)
                        <button type="submit" class="btn btn-primary">Buat Order</button>
                    @else
                        <button type="button" class="btn btn-secondary" disabled>Metode Pembayaran Belum Tersedia</button>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>
@endsection