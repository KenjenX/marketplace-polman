@extends('layouts.admin')

@section('content')
<div class="admin-card">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">Daftar Order</h2>
            <p class="text-muted mb-0">Kelola pembayaran dan status pesanan user.</p>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode Order</th>
                    <th>User</th>
                    <th>Total</th>
                    <th>Pembayaran</th>
                    <th>Status Order</th>
                    <th>Status Bukti</th>
                    <th width="120">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                    @php
                        $orderStatusClass = match($order->status) {
                            'waiting_payment' => 'badge-waiting-payment',
                            'waiting_receipt_validation' => 'badge-waiting-validation',
                            'payment_rejected' => 'badge-rejected',
                            'processing' => 'badge-processing',
                            'completed' => 'badge-completed',
                            'cancelled' => 'badge-cancelled',
                            default => 'text-bg-primary',
                        };

                        $receiptStatusClass = match(optional($order->paymentReceipt)->validation_status) {
                            'pending' => 'badge-waiting-validation',
                            'accepted' => 'badge-completed',
                            'rejected' => 'badge-rejected',
                            default => 'badge-cancelled',
                        };
                    @endphp

                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $order->order_code }}</td>
                        <td>
                            <div class="fw-semibold">{{ $order->user->name }}</div>
                            <small class="text-muted">{{ $order->user->email }}</small>
                        </td>
                        <td>Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                        <td>{{ $order->payment_method }}</td>
                        <td>
                            <span class="badge status-badge {{ $orderStatusClass }}">
                                {{ $order->status }}
                            </span>
                        </td>
                        <td>
                            @if($order->paymentReceipt)
                                <span class="badge status-badge {{ $receiptStatusClass }}">
                                    {{ $order->paymentReceipt->validation_status }}
                                </span>
                            @else
                                <span class="badge status-badge badge-cancelled">
                                    belum upload
                                </span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-outline-primary btn-sm">
                                Detail
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted">Belum ada order.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection