<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Checkout</title>
</head>
<body>
    <h1>Checkout</h1>

    @if(session('error'))
        <p>{{ session('error') }}</p>
    @endif

    @if($errors->any())
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <h2>Ringkasan Pesanan</h2>

    @php
        $grandTotal = 0;
    @endphp

    @foreach($cart->items as $item)
        @php
            $subtotal = $item->variant->price * $item->quantity;
            $grandTotal += $subtotal;
        @endphp

        <div style="border:1px solid #000; padding:10px; margin-bottom:10px;">
            <p><strong>Produk:</strong> {{ $item->variant->product->name }}</p>
            <p><strong>Variasi:</strong> {{ $item->variant->name }}</p>
            <p><strong>Harga:</strong> Rp {{ number_format($item->variant->price, 0, ',', '.') }}</p>
            <p><strong>Jumlah:</strong> {{ $item->quantity }}</p>
            <p><strong>Subtotal:</strong> Rp {{ number_format($subtotal, 0, ',', '.') }}</p>
        </div>
    @endforeach

    <h3>Total: Rp {{ number_format($grandTotal, 0, ',', '.') }}</h3>

    <hr>

    <form action="{{ route('checkout.store') }}" method="POST">
        @csrf

        <h2>Alamat Pengiriman</h2>

        <div>
            <label>Nama Penerima</label><br>
            <input type="text" name="recipient_name" value="{{ old('recipient_name') }}">
        </div>

        <br>

        <div>
            <label>No. HP</label><br>
            <input type="text" name="phone" value="{{ old('phone') }}">
        </div>

        <br>

        <div>
            <label>Provinsi</label><br>
            <input type="text" name="province" value="{{ old('province') }}">
        </div>

        <br>

        <div>
            <label>Kota / Kabupaten</label><br>
            <input type="text" name="city" value="{{ old('city') }}">
        </div>

        <br>

        <div>
            <label>Kecamatan</label><br>
            <input type="text" name="district" value="{{ old('district') }}">
        </div>

        <br>

        <div>
            <label>Kode Pos</label><br>
            <input type="text" name="postal_code" value="{{ old('postal_code') }}">
        </div>

        <br>

        <div>
            <label>Alamat Lengkap</label><br>
            <textarea name="full_address">{{ old('full_address') }}</textarea>
        </div>

        <br>

        <h2>Metode Pembayaran</h2>

        <div>
            <select name="payment_method">
                <option value="bank_transfer" {{ old('payment_method') == 'bank_transfer' ? 'selected' : '' }}>
                    Transfer Bank
                </option>
            </select>
        </div>

        <br>

        <div>
            <label>Catatan</label><br>
            <textarea name="notes">{{ old('notes') }}</textarea>
        </div>

        <br>

        <button type="submit">Buat Order</button>
    </form>

    <br>
    <a href="{{ route('cart.index') }}">Kembali ke Keranjang</a>
</body>
</html>