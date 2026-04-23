<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Detail Order</title>
</head>
<body>
    <h1>Detail Order</h1>

    @if(session('success'))
        <p>{{ session('success') }}</p>
    @endif

    <p><strong>Kode Order:</strong> {{ $order->order_code }}</p>
    <p><strong>Status:</strong> {{ $order->status }}</p>
    <p><strong>Metode Pembayaran:</strong> {{ $order->payment_method }}</p>
    <p><strong>Total:</strong> Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>

    <h2>Alamat Pengiriman</h2>
    <p>{{ $order->address->recipient_name }}</p>
    <p>{{ $order->address->phone }}</p>
    <p>{{ $order->address->province }}, {{ $order->address->city }}, {{ $order->address->district }}</p>
    <p>{{ $order->address->postal_code }}</p>
    <p>{{ $order->address->full_address }}</p>

    <h2>Item Pesanan</h2>

    @foreach($order->items as $item)
        <div style="border:1px solid #000; padding:10px; margin-bottom:10px;">
            <p><strong>Produk:</strong> {{ $item->product_name }}</p>
            <p><strong>Variasi:</strong> {{ $item->variant_name }}</p>
            <p><strong>Harga:</strong> Rp {{ number_format($item->price, 0, ',', '.') }}</p>
            <p><strong>Jumlah:</strong> {{ $item->quantity }}</p>
            <p><strong>Subtotal:</strong> Rp {{ number_format($item->subtotal, 0, ',', '.') }}</p>
        </div>
    @endforeach

    <h2>Instruksi Pembayaran</h2>
    <p>Silakan transfer ke rekening berikut:</p>
    <p><strong>Bank:</strong> BCA</p>
    <p><strong>No. Rekening:</strong> 12345678</p>
    <p><strong>Atas Nama:</strong> Marketplace Polman</p>

    <br>
    <a href="{{ route('orders.index') }}">Kembali ke Daftar Order</a>
</body>
</html>