<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Daftar Order Admin</title>
</head>
<body>
    <h1>Daftar Order</h1>

    @if(session('success'))
        <p>{{ session('success') }}</p>
    @endif

    @if(session('error'))
        <p>{{ session('error') }}</p>
    @endif

    @forelse($orders as $order)
        <div style="border:1px solid #000; padding:10px; margin-bottom:10px;">
            <p><strong>Kode Order:</strong> {{ $order->order_code }}</p>
            <p><strong>User:</strong> {{ $order->user->name }} ({{ $order->user->email }})</p>
            <p><strong>Total:</strong> Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
            <p><strong>Metode Pembayaran:</strong> {{ $order->payment_method }}</p>
            <p><strong>Status Order:</strong> {{ $order->status }}</p>

            @if($order->paymentReceipt)
                <p><strong>Status Bukti:</strong> {{ $order->paymentReceipt->validation_status }}</p>
            @else
                <p><strong>Status Bukti:</strong> Belum upload</p>
            @endif

            <a href="{{ route('admin.orders.show', $order->id) }}">Lihat Detail</a>
        </div>
    @empty
        <p>Belum ada order.</p>
    @endforelse
</body>
</html>