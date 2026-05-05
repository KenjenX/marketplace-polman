<!DOCTYPE html>
<html lang="en">
<head>
    <title>Checkout</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">

    <h3>Checkout</h3>

    <form action="{{ url('/checkout/process') }}" method="POST">
        @csrf

        {{-- ALAMAT PENGIRIMAN --}}
        <div class="card mb-3">
            <div class="card-header">Alamat Pengiriman</div>
            <div class="card-body">

                <div class="mb-3">
                    <label>Nama Penerima</label>
                    <input type="text" name="name" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>No HP</label>
                    <input type="text" name="phone" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Alamat Lengkap</label>
                    <textarea name="address" class="form-control" required></textarea>
                </div>

            </div>
        </div>

        {{-- KURIR --}}
        <div class="card mb-3">
            <div class="card-header">Pilih Kurir</div>
            <div class="card-body">

                <select name="shipping_method" class="form-select" required>
                    <option value="">-- Pilih Kurir --</option>
                    <option value="jne_reg">JNE REG - Rp 10.000</option>
                    <option value="jne_yes">JNE YES - Rp 20.000</option>
                    <option value="jnt">J&T - Rp 12.000</option>
                </select>

            </div>
        </div>

        {{-- RINGKASAN CART --}}
        <div class="card mb-3">
            <div class="card-header">Ringkasan Pesanan</div>
            <div class="card-body">

                @php
                    $cart = session('cart', []);
                    $subtotal = 0;
                @endphp

                <ul class="list-group mb-3">
                    @foreach($cart as $item)
                        @php
                            $subtotal += $item['price'] * $item['qty'];
                        @endphp
                        <li class="list-group-item d-flex justify-content-between">
                            {{ $item['name'] }} (x{{ $item['qty'] }})
                            <span>Rp {{ number_format($item['price'] * $item['qty']) }}</span>
                        </li>
                    @endforeach
                </ul>

                <h5>Subtotal: Rp {{ number_format($subtotal) }}</h5>

            </div>
        </div>

        <button class="btn btn-primary w-100">Proses Order</button>

    </form>

</div>

</body>
</html>