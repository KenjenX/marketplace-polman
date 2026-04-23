<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Detail Order Admin</title>
</head>
<body>
    <h1>Detail Order Admin</h1>

    @if(session('success'))
        <p>{{ session('success') }}</p>
    @endif

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

    <p><strong>Kode Order:</strong> {{ $order->order_code }}</p>
    <p><strong>User:</strong> {{ $order->user->name }} ({{ $order->user->email }})</p>
    <p><strong>Status Order:</strong> {{ $order->status }}</p>
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

    <h2>Bukti Pembayaran</h2>

    @if($order->paymentReceipt)
        <p><strong>Status Validasi:</strong> {{ $order->paymentReceipt->validation_status }}</p>

        @if($order->paymentReceipt->admin_note)
            <p><strong>Catatan Admin:</strong> {{ $order->paymentReceipt->admin_note }}</p>
        @endif

        <p>
            <a href="{{ asset('storage/' . $order->paymentReceipt->receipt_file) }}" target="_blank">
                Lihat Bukti Pembayaran
            </a>
        </p>

        @if($order->status === 'waiting_receipt_validation')
            <h3>Validasi Pembayaran</h3>

            <form action="{{ route('admin.orders.updatePaymentStatus', $order->id) }}" method="POST">
                @csrf
                @method('PATCH')

                <div>
                    <label>Catatan Admin</label><br>
                    <textarea name="admin_note"></textarea>
                </div>

                <br>

                <button type="submit" name="action" value="accept">Terima Pembayaran</button>
                <button type="submit" name="action" value="reject">Tolak Pembayaran</button>
            </form>
        @endif
    @else
        <p>Belum ada bukti pembayaran.</p>
    @endif

    <h2>Status Lanjutan Order</h2>

    @if($order->status === 'processing')
        <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST" style="margin-bottom: 10px;">
            @csrf
            @method('PATCH')
            <input type="hidden" name="status" value="completed">
            <button type="submit" onclick="return confirm('Selesaikan order ini?')">Selesaikan Order</button>
        </form>
    @endif

    @if(in_array($order->status, ['waiting_payment', 'payment_rejected', 'processing']))
        <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST">
            @csrf
            @method('PATCH')
            <input type="hidden" name="status" value="cancelled">
            <button type="submit" onclick="return confirm('Batalkan order ini?')">Batalkan Order</button>
        </form>
    @endif

    <br>
    <a href="{{ route('admin.orders.index') }}">Kembali ke Daftar Order</a>
</body>
</html>