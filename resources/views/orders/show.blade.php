@extends('layouts.store')

@section('content')
<style>
    /* Tipografi & Warna Konsisten Polman */
    .content-page { font-family: sans-serif; color: #2d3436; }
    h2, h5 { font-weight: 800; letter-spacing: -0.5px; color: #1a1a1a; }
    .label-muted { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; color: #adb5bd; }
    .value-text { font-size: 14px; font-weight: 600; color: #2d3436; }

    /* Card & Container */
    .detail-card { border: 1px solid #f0f0f0; background: #fff; transition: 0.3s; }
    .bg-light-polman { background-color: #f8f9fa; }

    /* Badge Status (Sesuai dengan Halaman Index) */
    .status-badge { font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px; padding: 6px 14px; border-radius: 50px; }
    .badge-waiting-payment { background: #fff8e1; color: #f57f17; border: 1px solid #ffe082; }
    .badge-waiting-validation { background: #e0f2f1; color: #00796b; border: 1px solid #b2dfdb; }
    .badge-processing { background: #e3f2fd; color: #1976d2; border: 1px solid #bbdefb; }
    .badge-completed { background: #e8f5e9; color: #2e7d32; border: 1px solid #c8e6c9; }
    .badge-rejected, .badge-cancelled, .badge-expired { background: #ffebee; color: #c62828; border: 1px solid #ffcdd2; }

    /* Countdown Styling */
    #countdown { font-family: monospace; font-weight: 800; }

    /* Button */
    .btn-polman-primary { background: #013780; color: #fff; border-radius: 50px; font-weight: 700; font-size: 13px; padding: 12px 24px; border: none; transition: 0.3s; }
    .btn-polman-primary:hover { background: #012a61; transform: translateY(-2px); color: #fff; }
    .btn-back { color: #013780; font-weight: 700; font-size: 12px; text-decoration: none; transition: 0.3s; }
    .btn-back:hover { color: #012a61; opacity: 0.8; }
</style>

<div class="container py-4 content-page">
    {{-- Header Section --}}
    <div class="bg-white rounded-5 p-4 shadow-sm mb-4">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <a href="{{ route('orders.index') }}" class="btn-back mb-2 d-inline-block">← KEMBALI KE DAFTAR ORDER</a>
                <h2 class="mb-1">Detail Order</h2>
                <div class="text-muted small fw-bold">{{ $order->order_code }}</div>
            </div>

            <div class="text-md-end">
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
                @endphp
                <span class="status-badge {{ $statusClass }}">
                    {{ str_replace('_', ' ', $order->status) }}
                </span>
            </div>
        </div>
    </div>

    {{-- Alert Section (Countdown / Cancelled) --}}
    @if($order->status == 'cancelled')
        <div class="alert alert-danger border-0 shadow-sm rounded-4 mb-4 p-3">
            <div class="d-flex align-items-center gap-3">
                <i class="bi bi-x-circle-fill fs-4"></i>
                <div>
                    <h6 class="fw-bold mb-1">Pesanan Dibatalkan</h6>
                    <p class="mb-0 small">Waktu pembayaran berakhir. Stok barang sudah dikembalikan ke sistem.</p>
                </div>
            </div>
        </div>
    @elseif($order->status == 'waiting_payment')
        <div class="alert alert-warning border-0 shadow-sm rounded-4 mb-4 p-3" style="background: #fff9db; color: #856404;">
            <div class="d-flex align-items-center gap-3">
                <i class="bi bi-hourglass-split fs-4"></i>
                <div class="w-100">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="fw-bold mb-0">Selesaikan Pembayaran</h6>
                        <span id="countdown" class="badge bg-danger px-3 py-2" data-deadline="{{ $order->payment_deadline_at->format('Y-m-d H:i:s') }}">-- : -- : --</span>
                    </div>
                    <p class="small mb-0 mt-1">Batas akhir: <strong>{{ $order->payment_deadline_at->format('d M Y, H:i') }} WIB</strong></p>
                </div>
            </div>
        </div>
    @endif

    <div class="row g-4">
        {{-- Sisi Kiri: Item Pesanan --}}
        <div class="col-lg-7">
            <h5 class="mb-3 px-1">Item Pesanan</h5>
            @foreach($order->items as $item)
                <div class="detail-card rounded-4 p-3 mb-3 shadow-sm">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <div class="value-text" style="font-size: 15px;">{{ $item->product_name }}</div>
                            <div class="text-muted small fw-bold">{{ $item->variant_name }}</div>
                            <div class="mt-2 small">
                                <span class="text-muted">Harga:</span> Rp {{ number_format($item->price, 0, ',', '.') }} 
                                <span class="mx-2 text-muted">|</span> 
                                <span class="text-muted">Qty:</span> {{ $item->quantity }}
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="label-muted">Subtotal</div>
                            <div class="value-text text-primary">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Sisi Kanan: Summary & Payment --}}
        <div class="col-lg-5">
            {{-- Tombol Xendit --}}
            @if($order->status == 'waiting_payment' && $order->payment_url)
                <div class="mb-4">
                    <a href="{{ $order->payment_url }}" target="_blank" class="btn btn-polman-primary w-100 shadow d-flex align-items-center justify-content-center">
                        <i class="bi bi-shield-check me-2"></i> BAYAR SEKARANG (XENDIT)
                    </a>
                    <p class="text-muted text-center mt-2" style="font-size: 11px;">Mendukung Transfer Bank, QRIS, E-Wallet, dan Retail.</p>
                </div>
            @endif

            {{-- Ringkasan & Alamat --}}
            <div class="detail-card rounded-4 p-4 mb-4 shadow-sm">
                <h5 class="mb-3" style="font-size: 16px;">Ringkasan Pesanan</h5>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted small fw-bold">METODE PEMBAYARAN</span>
                    <span class="value-text small" style="text-transform: capitalize;">{{ str_replace('_', ' ', $order->payment_method) }}</span>
                </div>
                <div class="d-flex justify-content-between border-top pt-2 mt-2">
                    <span class="fw-bold">TOTAL TAGIHAN</span>
                    <span class="value-text text-primary fs-5">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                </div>
            </div>

            <div class="detail-card rounded-4 p-4 mb-4 shadow-sm bg-light-polman">
                <h5 class="mb-3" style="font-size: 16px;">Alamat Pengiriman</h5>
                <div class="value-text mb-1">{{ $order->address->recipient_name }}</div>
                <div class="text-muted small mb-3 fw-bold">{{ $order->address->phone }}</div>
                <div class="small text-muted" style="line-height: 1.6;">
                    {{ $order->address->full_address }}<br>
                    {{ $order->address->district }}, {{ $order->address->city }}<br>
                    {{ $order->address->province }}, {{ $order->address->postal_code }}
                </div>
            </div>

            {{-- Bukti Pembayaran --}}
            <div class="detail-card rounded-4 p-4 shadow-sm">
                <h5 class="mb-3" style="font-size: 16px;">Bukti Pembayaran</h5>
                
                @if($order->paymentReceipt)
                    <div class="bg-light p-3 rounded-3 mb-3 border">
                        <div class="label-muted">Status Validasi</div>
                        <div class="value-text mb-2">{{ str_replace('_', ' ', $order->paymentReceipt->validation_status) }}</div>
                        @if($order->paymentReceipt->admin_note)
                            <div class="label-muted">Catatan Admin</div>
                            <div class="text-danger small fw-bold">{{ $order->paymentReceipt->admin_note }}</div>
                        @endif
                    </div>
                    <a href="{{ asset('storage/' . $order->paymentReceipt->receipt_file) }}" target="_blank" class="btn btn-outline-dark btn-sm w-100 rounded-pill fw-bold py-2 mb-3">LIHAT BUKTI SAAT INI</a>
                @endif

                @if(in_array($order->status, ['waiting_payment', 'payment_rejected']))
                    <form action="{{ route('orders.uploadReceipt', $order->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label class="label-muted d-block mb-2">Upload Bukti Transfer (JPG/PNG)</label>
                            <input type="file" name="receipt_file" class="form-control form-control-sm rounded-3" accept=".jpg,.jpeg,.png">
                        </div>
                        <button type="submit" class="btn btn-dark w-100 rounded-pill fw-bold py-2">
                            {{ $order->status === 'payment_rejected' ? 'UPLOAD ULANG BUKTI' : 'KIRIM BUKTI PEMBAYARAN' }}
                        </button>
                    </form>
                @elseif($order->status === 'waiting_receipt_validation')
                    <div class="alert alert-info border-0 small fw-bold text-center rounded-3 mb-0">
                        <i class="bi bi-search me-1"></i> Sedang divalidasi oleh Admin
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Script SweetAlert & Countdown Tetap Dipertahankan --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const countdownElement = document.getElementById('countdown');
    if (countdownElement) {
        const deadlineString = countdownElement.getAttribute('data-deadline');
        const deadline = new Date(deadlineString.replace(/-/g, "/")).getTime();

        const x = setInterval(function() {
            const now = new Date().getTime();
            const distance = deadline - now;

            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);

            countdownElement.innerHTML = 
                (hours < 10 ? "0" + hours : hours) + "j : " + 
                (minutes < 10 ? "0" + minutes : minutes) + "m : " + 
                (seconds < 10 ? "0" + seconds : seconds) + "d";

            if (distance < 0) {
                clearInterval(x);
                countdownElement.innerHTML = "WAKTU HABIS";
                Swal.fire({
                    icon: 'error',
                    title: 'Pesanan Kedaluwarsa',
                    text: 'Batas waktu pembayaran telah habis. Silakan buat pesanan baru.',
                    confirmButtonText: 'Oke, Mengerti',
                    confirmButtonColor: '#013780',
                    allowOutsideClick: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = "{{ route('products.index') }}";
                    }
                });
            }
        }, 1000);
    }
});
</script>

<style>
    .rounded-5 { border-radius: 2rem !important; }
    .rounded-4 { border-radius: 1.25rem !important; }
</style>
@endsection