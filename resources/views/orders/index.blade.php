@extends('layouts.store')

@section('content')
{{-- Pembungkus luar agar Content Card berada di tengah dan ukurannya ramping (tidak memenuhi bodi) --}}
<div class="row justify-content-center">
    <div class="col-xl-9 col-lg-11">
        
        <div class="content-card">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="mb-1">Pesanan Saya</h2>
                    <p class="text-muted mb-0">Pantau status pembayaran dan penyelesaian order kamu.</p>
                </div>
            </div>

            @forelse($orders as $order)
                <div class="border rounded-4 p-3 mb-3 bg-white shadow-sm">
                    {{-- BARIS UTAMA --}}
                    <div class="row g-3 align-items-center">
                        
                        {{-- Kiri: Kumpulan info dibuat padat rapat --}}
                        <div class="col-xl-8 col-lg-7">
                            <div class="d-flex flex-wrap gap-4 gap-md-4">
                                <div style="min-width: 140px;">
                                    <div class="text-muted small">Kode Order</div>
                                    <div class="fw-semibold text-primary" style="font-size: 0.95rem;">{{ $order->order_code }}</div>
                                </div>

                                <div style="min-width: 110px;">
                                    <div class="text-muted small">Total</div>
                                    <div class="fw-semibold" style="font-size: 0.95rem;">Rp {{ number_format($order->total_price, 0, ',', '.') }}</div>
                                </div>

                                <div style="min-width: 130px;">
                                    <div class="text-muted small">Metode Pembayaran</div>
                                    <div class="text-secondary text-capitalize small mt-1">{{ str_replace('_', ' ', $order->payment_method) }}</div>
                                </div>

                                <div>
                                    <div class="text-muted small">Status</div>
                                    <div class="mt-1">
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
                            </div>
                        </div>

                        {{-- Kanan: Dua Tombol dikunci agar SELALU SATU GARIS (Bersebelahan) --}}
                        <div class="col-xl-4 col-lg-5 text-lg-end ms-auto">
                            <div class="d-flex flex-row gap-2 justify-content-start justify-content-lg-end align-items-center">
                                @if($order->tracking_number)
                                    <a href="{{ route('orders.track', $order->uuid) }}" class="btn btn-sm btn-info text-white text-nowrap">
                                        <i class="bi bi-truck me-1"></i> Lacak Pesanan
                                    </a>
                                @endif
                                <a href="{{ route('orders.show', $order->uuid) }}" class="btn btn-outline-primary btn-sm text-nowrap">
                                    Lihat Detail
                                </a>
                            </div>
                        </div>
                    </div>

                    {{-- BARIS BAWAH: Daftar Produk --}}
                    <div class="row mt-3 pt-3 border-top">
                        <div class="col-12">
                            <div class="text-muted small mb-1 fw-semibold">Produk yang Dibeli:</div>
                            <ul class="list-unstyled mb-0" style="font-size: 13px;">
                                @foreach($order->items as $item)
                                    <li class="mb-1 text-dark d-flex align-items-center">
                                        <i class="bi bi-box-seam text-secondary me-2" style="font-size: 14px;"></i> 
                                        <span>
                                            {{ $item->product_name ?? ($item->product->name ?? 'Produk Tidak Diketahui') }} 
                                            <strong class="text-muted ms-1">x{{ $item->quantity }}</strong>
                                        </span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @empty
                <div class="alert alert-secondary mb-0">
                    Belum ada order.
                </div>
            @endforelse
        </div>

    </div>
</div>
@endsection