@extends('layouts.store')

@section('content')
<style>
    /* Skema Desain Konsisten Polman - Sans Serif Only */
    .content-page { background: #ffffff; font-family: sans-serif; }
    .page-title { color: #1a1a1a; letter-spacing: -0.5px; font-weight: 800; }
    
    /* Order Card Modern */
    .order-item-card {
        border: 1px solid #f0f0f0;
        transition: all 0.3s ease;
        background: #fff;
    }
    .order-item-card:hover {
        border-color: #013780;
        box-shadow: 0 5px 15px rgba(1, 55, 128, 0.05);
    }

    /* Label & Value Styling */
    .label-muted {
        font-size: 10px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #adb5bd;
        margin-bottom: 2px;
    }
    .value-text {
        font-size: 13px;
        font-weight: 700;
        color: #2d3436;
    }

    /* Custom Badge Status - Proposional */
    .status-badge {
        font-size: 10px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 5px 12px;
        border-radius: 50px;
        display: inline-block;
    }
    .badge-waiting-payment { background: #fff8e1; color: #f57f17; border: 1px solid #ffe082; }
    .badge-waiting-validation { background: #e0f2f1; color: #00796b; border: 1px solid #b2dfdb; }
    .badge-processing { background: #e3f2fd; color: #1976d2; border: 1px solid #bbdefb; }
    .badge-completed { background: #e8f5e9; color: #2e7d32; border: 1px solid #c8e6c9; }
    .badge-rejected, .badge-cancelled, .badge-expired { background: #ffebee; color: #c62828; border: 1px solid #ffcdd2; }

<<<<<<< HEAD
    /* Button Styling */
    .btn-detail-polman {
        border-radius: 50px;
        font-size: 11px;
        font-weight: 800;
        padding: 8px 18px;
        border: 2px solid #013780;
        color: #013780;
        background: transparent;
        transition: 0.3s;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .btn-detail-polman:hover {
        background: #013780;
        color: #fff;
        text-decoration: none;
    }
</style>

<div class="container py-4">
    <div class="bg-white rounded-5 p-4 shadow-sm">
        <div class="d-flex justify-content-between align-items-center mb-5 mt-2 px-2">
            <div>
                <h2 class="page-title mb-1">Pesanan Saya</h2>
                <p class="text-muted mb-0" style="font-size: 13px;">Pantau status pembayaran dan penyelesaian order kamu secara real-time.</p>
=======
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
>>>>>>> c12d238a0e03d357b80b0cf4ca1c0f27b8d1ad73
            </div>
        </div>

        <div class="px-2">
            @forelse($orders as $order)
                <div class="order-item-card rounded-4 p-4 mb-3">
                    <div class="row g-3 align-items-center">
                        {{-- 1. Kode Order --}}
                        <div class="col-lg-3">
                            <div class="label-muted">Kode Order</div>
                            <div class="value-text text-primary" style="font-size: 12px;">{{ $order->order_code }}</div>
                        </div>

                        {{-- 2. Total Harga --}}
                        <div class="col-lg-2">
                            <div class="label-muted">Total Tagihan</div>
                            <div class="value-text">Rp {{ number_format($order->total_price, 0, ',', '.') }}</div>
                        </div>

                        {{-- 3. Metode Pembayaran --}}
                        <div class="col-lg-2">
                            <div class="label-muted">Metode</div>
                            <div class="value-text" style="text-transform: capitalize;">{{ str_replace('_', ' ', $order->payment_method) }}</div>
                        </div>

                        {{-- 4. Status & Deadline --}}
                        <div class="col-lg-3">
                            <div class="label-muted">Status Pesanan</div>
                            @php
                                $statusClass = match($order->status) {
                                    'waiting_payment' => 'badge-waiting-payment',
                                    'waiting_receipt_validation' => 'badge-waiting-validation',
                                    'payment_rejected' => 'badge-rejected',
                                    'processing' => 'badge-processing',
                                    'completed' => 'badge-completed',
                                    'cancelled', 'expired' => 'badge-rejected',
                                    default => 'bg-secondary text-white',
                                };
                                
                                $statusLabel = match($order->status) {
                                    'waiting_payment' => 'Menunggu Pembayaran',
                                    'waiting_receipt_validation' => 'Validasi Bukti',
                                    'payment_rejected' => 'Pembayaran Ditolak',
                                    'processing' => 'Diproses',
                                    'completed' => 'Selesai',
                                    'cancelled' => 'Dibatalkan',
                                    'expired' => 'Kedaluwarsa',
                                    default => $order->status,
                                };
                            @endphp

                            <span class="status-badge {{ $statusClass }}">
                                {{ $statusLabel }}
                            </span>

                            @if(in_array($order->status, ['waiting_payment', 'payment_rejected']) && $order->payment_deadline_at)
                                <div class="mt-2 text-danger fw-bold" style="font-size: 10px;">
                                    Batas: {{ $order->payment_deadline_at->format('d M, H:i') }}
                                </div>
                            @endif
                        </div>

                        {{-- 5. Action --}}
                        <div class="col-lg-2 text-lg-end">
                            <a href="{{ route('orders.show', $order->id) }}" class="btn btn-detail-polman">
                                Lihat Detail
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-5">
                    <p class="text-muted mb-0" style="font-size: 13px;">Belum ada riwayat pesanan.</p>
                    <a href="{{ route('products.index') }}" class="btn btn-sm btn-primary rounded-pill px-4 mt-3" style="font-size: 12px; font-weight: 700;">Mulai Belanja</a>
                </div>
            @endforelse
        </div>
    </div>
</div>

<style>
    /* Utility */
    .rounded-4 { border-radius: 1.25rem !important; }
    .rounded-5 { border-radius: 2rem !important; }
</style>
@endsection 