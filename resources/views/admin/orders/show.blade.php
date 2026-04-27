@extends('layouts.admin')

@section('content')
@php
    $orderStatusClass = match($order->status) {
        'waiting_payment' => 'badge-waiting-payment',
        'waiting_receipt_validation' => 'badge-waiting-validation',
        'payment_rejected' => 'badge-rejected',
        'processing' => 'badge-processing',
        'completed' => 'badge-completed',
        'cancelled' => 'badge-cancelled',
        'expired' => 'badge-expired',
        default => 'text-bg-primary',
    };

    $receiptStatusClass = match(optional($order->paymentReceipt)->validation_status) {
        'pending' => 'badge-waiting-validation',
        'accepted' => 'badge-completed',
        'rejected' => 'badge-rejected',
        default => 'badge-cancelled',
    };
@endphp

<div class="admin-card">
    <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
        <div>
            <h2 class="mb-1">Detail Order</h2>
            <p class="text-muted mb-0">{{ $order->order_code }}</p>
        </div>

        <div class="d-flex flex-wrap gap-2">
            <span class="badge status-badge {{ $orderStatusClass }}">
                {{ $order->status }}
            </span>

            @if($order->paymentReceipt)
                <span class="badge status-badge {{ $receiptStatusClass }}">
                    bukti: {{ $order->paymentReceipt->validation_status }}
                </span>
            @endif
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="info-box mb-4">
                <div class="section-title">Informasi Pemesan</div>
                <p class="mb-1"><strong>Nama:</strong> {{ $order->user->name }}</p>
                <p class="mb-1"><strong>Email:</strong> {{ $order->user->email }}</p>
                <p class="mb-1"><strong>Nama:</strong> {{ $order->user->name }}</p>
                <p class="mb-1"><strong>Email:</strong> {{ $order->user->email }}</p>
                <p class="mb-1"><strong>Metode Pembayaran:</strong> {{ $order->payment_method_name ?: $order->payment_method }}</p>

                @if($order->payment_bank_name)
                    <p class="mb-1"><strong>Bank:</strong> {{ $order->payment_bank_name }}</p>
                @endif

                @if($order->payment_account_number)
                    <p class="mb-1"><strong>No. Rekening:</strong> {{ $order->payment_account_number }}</p>
                @endif

                @if($order->payment_account_name)
                    <p class="mb-1"><strong>Atas Nama:</strong> {{ $order->payment_account_name }}</p>
                @endif

                @if($order->payment_instruction)
                    <p class="mb-1"><strong>Instruksi:</strong> {{ $order->payment_instruction }}</p>
                @endif

                @if($order->payment_deadline_at)
                    <p class="mb-0">
                        <strong>Batas Pembayaran:</strong>
                        {{ $order->payment_deadline_at->format('d M Y H:i') }}
                    </p>
                @endif
            </div>

            <div class="info-box mb-4">
                <div class="section-title">Alamat Pengiriman</div>
                <p class="mb-1">{{ $order->address->recipient_name }}</p>
                <p class="mb-1">{{ $order->address->phone }}</p>
                <p class="mb-1">{{ $order->address->province }}, {{ $order->address->city }}, {{ $order->address->district }}</p>
                <p class="mb-1">{{ $order->address->postal_code }}</p>
                <p class="mb-0">{{ $order->address->full_address }}</p>
            </div>

            <div class="info-box">
                <div class="section-title">Item Pesanan</div>

                <div class="table-responsive">
                    <table class="table table-bordered align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Produk</th>
                                <th>Variasi</th>
                                <th>Harga</th>
                                <th>Qty</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                                <tr>
                                    <td>{{ $item->product_name }}</td>
                                    <td>{{ $item->variant_name }}</td>
                                    <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="text-end mt-3">
                    <strong>Total: Rp {{ number_format($order->total_price, 0, ',', '.') }}</strong>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="info-box mb-4">
                <div class="section-title">Bukti Pembayaran</div>

                @if($order->paymentReceipt)
                    <p class="mb-2">
                        <strong>Status Validasi:</strong>
                        <span class="badge status-badge {{ $receiptStatusClass }}">
                            {{ $order->paymentReceipt->validation_status }}
                        </span>
                    </p>

                    @if($order->paymentReceipt->admin_note)
                        <p class="mb-3">
                            <strong>Catatan Admin:</strong><br>
                            {{ $order->paymentReceipt->admin_note }}
                        </p>
                    @endif

                    <a href="{{ asset('storage/' . $order->paymentReceipt->receipt_file) }}" target="_blank" class="btn btn-outline-primary w-100">
                        Lihat Bukti Pembayaran
                    </a>
                @else
                    <p class="mb-0 text-muted">Belum ada bukti pembayaran.</p>
                @endif
            </div>

            @if($order->paymentReceipt && $order->status === 'waiting_receipt_validation')
                <div class="info-box mb-4">
                    <div class="section-title">Validasi Pembayaran</div>

                    <form action="{{ route('admin.orders.updatePaymentStatus', $order->id) }}" method="POST">
                        @csrf
                        @method('PATCH')

                        <div class="mb-3">
                            <label class="form-label">Catatan Admin</label>
                            <textarea name="admin_note" class="form-control" rows="4"></textarea>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" name="action" value="accept" class="btn btn-success">
                                Terima Pembayaran
                            </button>
                            <button type="submit" name="action" value="reject" class="btn btn-outline-danger">
                                Tolak Pembayaran
                            </button>
                        </div>
                    </form>
                </div>
            @endif

            <div class="info-box">
                <div class="section-title">Status Lanjutan Order</div>

                @if($order->status === 'processing')
                    <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST" class="mb-2">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="completed">
                        <button type="submit" class="btn btn-primary w-100" onclick="return confirm('Selesaikan order ini?')">
                            Selesaikan Order
                        </button>
                    </form>
                @endif

                @if(in_array($order->status, ['waiting_payment', 'payment_rejected', 'processing']))
                    <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="cancelled">
                        <button type="submit" class="btn btn-outline-secondary w-100" onclick="return confirm('Batalkan order ini?')">
                            Batalkan Order
                        </button>
                    </form>
                @else
                    <p class="mb-0 text-muted">Tidak ada aksi lanjutan untuk status ini.</p>
                @endif
            </div>
        </div>
    </div>

    <a href="{{ route('admin.orders.index') }}" class="btn btn-link px-0 mt-4">← Kembali ke daftar order</a>
</div>
@endsection