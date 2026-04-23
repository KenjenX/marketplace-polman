<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Pesanan Saya</title>
</head>
<body>
    <h1>Pesanan Saya</h1>

    @if(session('success'))
        <p>{{ session('success') }}</p>
    @endif

    @forelse($orders as $order)
        <div style="border:1px solid #000; padding:10px; margin-bottom:10px;">
            <p><strong>Kode Order:</strong> {{ $order->order_code }}</p>
            <p><strong>Total:</strong> Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
            <p><strong>Metode Pembayaran:</strong> {{ $order->payment_method }}</p>
            <p><strong>Status:</strong> {{ $order->status }}</p>
            <a href="{{ route('orders.show', $order->id) }}">Lihat Detail</a>
        </div>
    @empty
        <p>Belum ada order.</p>
    @endforelse

    <a href="{{ route('products.index') }}">Kembali Belanja</a>
</body>
</html>