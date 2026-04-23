@extends('layouts.store')

@section('content')
<div class="content-card">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">Pesanan Saya</h2>
            <p class="text-muted mb-0">Pantau status pembayaran dan penyelesaian order kamu.</p>
        </div>
    </div>

    @forelse($orders as $order)
        <div class="border rounded-4 p-3 mb-3">
            <div class="row g-3 align-items-center">
                <div class="col-lg-3">
                    <div class="text-muted small">Kode Order</div>
                    <div class="fw-semibold">{{ $order->order_code }}</div>
                </div>

                <div class="col-lg-2">
                    <div class="text-muted small">Total</div>
                    <div class="fw-semibold">Rp {{ number_format($order->total_price, 0, ',', '.') }}</div>
                </div>

                <div class="col-lg-3">
                    <div class="text-muted small">Metode Pembayaran</div>
                    <div>{{ $order->payment_method }}</div>
                </div>

                <div class="col-lg-2">
                    <div class="text-muted small">Status</div>
                    <div>
                        @php
                            $statusClass = match($order->status) {
                                'waiting_payment' => 'badge-waiting-payment',
                                'waiting_receipt_validation' => 'badge-waiting-validation',
                                'payment_rejected' => 'badge-rejected',
                                'processing' => 'badge-processing',
                                'completed' => 'badge-completed',
                                'cancelled' => 'badge-cancelled',
                                default => 'text-bg-primary',
                            };
                        @endphp

                        <span class="badge status-badge {{ $statusClass }}">
                            {{ $order->status }}
                        </span>
                    </div>
                </div>

                <div class="col-lg-2 text-lg-end">
                    <a href="{{ route('orders.show', $order->id) }}" class="btn btn-outline-primary btn-sm">
                        Lihat Detail
                    </a>
                </div>
            </div>
        </div>
    @empty
        <div class="alert alert-secondary mb-0">
            Belum ada order.
        </div>
    @endforelse
</div>
@endsection