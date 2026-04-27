@extends('layouts.store')

@section('content')
<div class="content-card">
    <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
        <div>
            <h2 class="mb-1">Detail Order</h2>
            <div class="text-muted">{{ $order->order_code }}</div>
        </div>

        <div class="text-md-end">
            @php
                $statusClass = match($order->status) {
                    'waiting_payment' => 'badge-waiting-payment',
                    'waiting_receipt_validation' => 'badge-waiting-validation',
                    'payment_rejected' => 'badge-rejected',
                    'processing' => 'badge-processing',
                    'completed' => 'badge-completed',
                    'cancelled' => 'badge-cancelled',
                    'expired' => 'badge-expired',
                    default => 'text-bg-primary',
                };
            @endphp

            <span class="badge status-badge fs-6 {{ $statusClass }}">
                {{ $order->status }}
            </span>

            @if(in_array($order->status, ['waiting_payment', 'payment_rejected']) && $order->payment_deadline_at)
                <p class="mb-0 mt-2 text-danger">
                    <strong>Batas Pembayaran:</strong>
                    {{ $order->payment_deadline_at->format('d M Y H:i') }}
                </p>
            @endif
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-7">
            <h5>Item Pesanan</h5>

            @foreach($order->items as $item)
                <div class="border rounded-4 p-3 mb-3">
                    <div class="fw-semibold">{{ $item->product_name }}</div>
                    <div class="text-muted mb-2">{{ $item->variant_name }}</div>
                    <div>Harga: Rp {{ number_format($item->price, 0, ',', '.') }}</div>
                    <div>Jumlah: {{ $item->quantity }}</div>
                    <div>Subtotal: Rp {{ number_format($item->subtotal, 0, ',', '.') }}</div>
                </div>
            @endforeach
        </div>

        <div class="col-lg-5">
            <div class="border rounded-4 p-3 mb-3">
                <h5>Ringkasan</h5>
                <p class="mb-1"><strong>Metode Pembayaran:</strong> {{ $order->payment_method }}</p>
                <p class="mb-0"><strong>Total:</strong> Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
            </div>

            <div class="border rounded-4 p-3 mb-3">
                <h5>Alamat Pengiriman</h5>
                <p class="mb-1">{{ $order->address->recipient_name }}</p>
                <p class="mb-1">{{ $order->address->phone }}</p>
                <p class="mb-1">{{ $order->address->province }}, {{ $order->address->city }}, {{ $order->address->district }}</p>
                <p class="mb-1">{{ $order->address->postal_code }}</p>
                <p class="mb-0">{{ $order->address->full_address }}</p>
            </div>

            <div class="border rounded-4 p-3 mb-3 bg-light">
                <h5 class="mb-3">Instruksi Pembayaran</h5>

                <p class="mb-2">Silakan lakukan transfer ke metode berikut:</p>
                <div class="mb-2"><strong>Metode:</strong> {{ $order->payment_method_name ?: $order->payment_method }}</div>

                @if($order->payment_bank_name)
                    <div class="mb-2"><strong>Bank:</strong> {{ $order->payment_bank_name }}</div>
                @endif

                @if($order->payment_account_number)
                    <div class="mb-2"><strong>No. Rekening:</strong> {{ $order->payment_account_number }}</div>
                @endif

                @if($order->payment_account_name)
                    <div class="mb-2"><strong>Atas Nama:</strong> {{ $order->payment_account_name }}</div>
                @endif

                @if($order->payment_instruction)
                    <div class="mt-3 text-muted">
                        {{ $order->payment_instruction }}
                    </div>
                @endif
            </div>

            <div class="border rounded-4 p-3">
                <h5>Bukti Pembayaran</h5>

                @if($order->paymentReceipt)
                    <p class="mb-1"><strong>Status Validasi:</strong> {{ $order->paymentReceipt->validation_status }}</p>

                    @if($order->paymentReceipt->admin_note)
                        <p class="mb-2"><strong>Catatan Admin:</strong> {{ $order->paymentReceipt->admin_note }}</p>
                    @endif

                    <a href="{{ asset('storage/' . $order->paymentReceipt->receipt_file) }}" target="_blank" class="btn btn-outline-primary btn-sm mb-3">
                        Lihat Bukti Pembayaran
                    </a>
                @endif

                @if(in_array($order->status, ['waiting_payment', 'payment_rejected']))
                    <form action="{{ route('orders.uploadReceipt', $order->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Upload Bukti Pembayaran</label>
                            <input type="file" name="receipt_file" class="form-control" accept=".jpg,.jpeg,.png">
                        </div>

                        <button type="submit" class="btn btn-primary">
                            {{ $order->status === 'payment_rejected' ? 'Upload Ulang Bukti' : 'Upload Bukti Pembayaran' }}
                        </button>
                    </form>
                @elseif($order->status === 'waiting_receipt_validation')
                    <div class="alert alert-info mb-0">
                        Bukti pembayaran sudah diupload, menunggu validasi admin.
                    </div>
                @endif

                @if($order->status === 'expired')
                    <div class="alert alert-danger mt-3 mb-0">
                        Order expired karena melewati batas waktu pembayaran. Stok telah dikembalikan.
                    </div>
                @endif
            </div>
        </div>
    </div>

    <a href="{{ route('orders.index') }}" class="btn btn-link px-0 mt-4">← Kembali ke daftar order</a>
</div>
@endsection