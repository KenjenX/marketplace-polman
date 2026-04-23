@extends('layouts.store')

@section('content')
<div class="content-card">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">Keranjang Belanja</h2>
            <p class="text-muted mb-0">Periksa kembali produk sebelum checkout.</p>
        </div>
        <a href="{{ route('products.index') }}" class="btn btn-outline-primary">Lanjut Belanja</a>
    </div>

    @php
        $grandTotal = 0;
    @endphp

    @forelse($cart->items as $item)
        @php
            $subtotal = $item->variant->price * $item->quantity;
            $grandTotal += $subtotal;
        @endphp

        <div class="border rounded-4 p-3 mb-3">
            <div class="row g-3 align-items-center">
                <div class="col-lg-5">
                    <h5 class="mb-1">{{ $item->variant->product->name }}</h5>
                    <p class="mb-1"><strong>Variasi:</strong> {{ $item->variant->name }}</p>
                    <p class="mb-1 text-muted">{{ $item->variant->specification }}</p>
                    <p class="mb-1">Harga: Rp {{ number_format($item->variant->price, 0, ',', '.') }}</p>
                    <small class="text-muted">Stok tersedia: {{ $item->variant->stock }}</small>
                </div>

                <div class="col-lg-3">
                    <form action="{{ route('cart.update', $item->id) }}" method="POST" class="d-flex gap-2">
                        @csrf
                        @method('PATCH')
                        <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" max="{{ $item->variant->stock }}" class="form-control">
                        <button type="submit" class="btn btn-outline-primary">Update</button>
                    </form>
                </div>

                <div class="col-lg-2 text-lg-center">
                    <div class="fw-semibold">Rp {{ number_format($subtotal, 0, ',', '.') }}</div>
                </div>

                <div class="col-lg-2 text-lg-end">
                    <form action="{{ route('cart.destroy', $item->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Hapus item ini dari keranjang?')">
                            Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @empty
        <div class="alert alert-secondary mb-0">
            Keranjang masih kosong.
        </div>
    @endforelse

    <hr>

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
        <h4 class="mb-0">Total: Rp {{ number_format($grandTotal, 0, ',', '.') }}</h4>

        @if($cart->items->count() > 0)
            <a href="{{ route('checkout.index') }}" class="btn btn-primary btn-lg">
                Lanjut ke Checkout
            </a>
        @endif
    </div>
</div>
@endsection