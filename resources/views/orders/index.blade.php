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

                <div class="col-lg-2">
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
                                'shipped' => 'badge-processing',
                                'completed' => 'badge-completed',
                                'cancelled' => 'badge-cancelled',
                                'expired' => 'badge-expired',
                                default => 'text-bg-primary',
                            };
                        @endphp

                        <span class="badge status-badge {{ $statusClass }}">
                            {{ str_replace('_', ' ', $order->status) }}
                        </span>

                        @if(in_array($order->status, ['waiting_payment', 'payment_rejected']) && $order->payment_deadline_at)
                            <div class="small text-muted mt-1" style="font-size: 0.7rem;">
                                Batas: {{ $order->payment_deadline_at->format('d M H:i') }}
                            </div>
                        @endif
                    </div>
                </div>

                {{-- KOLOM AKSI: Penempatan tombol Lacak Pesanan --}}
               <div class="col-lg-3 text-lg-end">
                    <div class="d-flex flex-column flex-lg-row gap-2 justify-content-lg-end">
                        @if($order->tracking_number)
                            {{-- PERBAIKAN: Gunakan uuid --}}
                            <a href="{{ route('orders.track', $order->uuid) }}" class="btn btn-sm btn-info text-white">
                                <i class="bi bi-truck me-1"></i> Lacak Pesanan
                            </a>
                        @endif

                        {{-- PERBAIKAN: Gunakan uuid --}}
                        <a href="{{ route('orders.show', $order->uuid) }}" class="btn btn-outline-primary btn-sm">
                            Lihat Detail
                        </a>
                    </div>
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