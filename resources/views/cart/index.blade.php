<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Keranjang Belanja</title>
</head>
<body>
    <h1>Keranjang Belanja</h1>

    @if(session('success'))
        <p>{{ session('success') }}</p>
    @endif

    @if(session('error'))
        <p>{{ session('error') }}</p>
    @endif

    <a href="{{ route('products.index') }}">Lanjut Belanja</a>

    <br><br>

    @php
        $grandTotal = 0;
    @endphp

    @forelse($cart->items as $item)
        @php
            $subtotal = $item->variant->price * $item->quantity;
            $grandTotal += $subtotal;
        @endphp

        <div style="border:1px solid #000; padding:10px; margin-bottom:10px;">
            <h3>{{ $item->variant->product->name }}</h3>
            <p><strong>Variasi:</strong> {{ $item->variant->name }}</p>
            <p><strong>Spesifikasi:</strong> {{ $item->variant->specification }}</p>
            <p><strong>Harga:</strong> Rp {{ number_format($item->variant->price, 0, ',', '.') }}</p>
            <p><strong>Stok tersedia:</strong> {{ $item->variant->stock }}</p>
            <p><strong>Subtotal:</strong> Rp {{ number_format($subtotal, 0, ',', '.') }}</p>

            <form action="{{ route('cart.update', $item->id) }}" method="POST">
                @csrf
                @method('PATCH')

                <label>Quantity</label>
                <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" max="{{ $item->variant->stock }}">
                <button type="submit">Update</button>
            </form>

            <br>

            <form action="{{ route('cart.destroy', $item->id) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" onclick="return confirm('Hapus item ini dari keranjang?')">Hapus</button>
            </form>
        </div>
    @empty
        <p>Keranjang masih kosong.</p>
    @endforelse

    <hr>
    <h2>Total: Rp {{ number_format($grandTotal, 0, ',', '.') }}</h2>

    @if($cart->items->count() > 0)
    <a href="{{ route('checkout.index') }}">Lanjut ke Checkout</a>
    @endif

</body>
</html>