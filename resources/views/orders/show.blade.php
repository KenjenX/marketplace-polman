@extends('layouts.store')

@section('content')
<style>
    /* Tipografi & Warna Konsisten Polman */
    .content-page { font-family: sans-serif; color: #2d3436; }
    h2, h5 { font-weight: 800; letter-spacing: -0.5px; color: #1a1a1a; }
    .label-muted { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; color: #adb5bd; }
    .value-text { font-size: 14px; font-weight: 600; color: #2d3436; }

<<<<<<< HEAD
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
=======
        <div class="text-md-end">
            @php
                $statusClass = match($order->status) {
                    'waiting_payment' => 'badge-waiting-payment',
                    'waiting_receipt_validation' => 'badge-waiting-validation',
                    'payment_rejected' => 'badge-rejected',
                    'processing' => 'badge-processing',
                    'shipped' => 'bg-info',
                    'completed' => 'badge-completed',
                    'cancelled' => 'badge-cancelled',
                    'expired' => 'badge-expired',
                    default => 'text-bg-primary',
                };
            @endphp

            <span class="badge status-badge fs-6 {{ $statusClass }}">
                {{ str_replace('_', ' ', $order->status) }}
            </span>
>>>>>>> c12d238a0e03d357b80b0cf4ca1c0f27b8d1ad73

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

<<<<<<< HEAD
    {{-- Alert Section (Countdown / Cancelled) --}}
    @if($order->status == 'cancelled')
        <div class="alert alert-danger border-0 shadow-sm rounded-4 mb-4 p-3">
            <div class="d-flex align-items-center gap-3">
                <i class="bi bi-x-circle-fill fs-4"></i>
                <div>
                    <h6 class="fw-bold mb-1">Pesanan Dibatalkan</h6>
                    <p class="mb-0 small">Waktu pembayaran berakhir. Stok barang sudah dikembalikan ke sistem.</p>
=======
    {{-- Alert Status --}}
    @if($order->status == 'cancelled' || $order->status == 'expired')
        <div class="alert alert-danger shadow-sm rounded-4 mb-4">
            <div class="d-flex align-items-center">
                <i class="bi bi-exclamation-octagon-fill fs-3 me-3"></i>
                <div>
                    <h6 class="fw-bold mb-1">Pesanan Ini Telah Dibatalkan/Expired</h6>
                    <p class="mb-0 small">Batas waktu pembayaran telah berakhir. Mohon <strong>TIDAK MELAKUKAN TRANSFER</strong> karena stok barang sudah dikembalikan ke sistem.</p>
>>>>>>> c12d238a0e03d357b80b0cf4ca1c0f27b8d1ad73
                </div>
            </div>
        </div>
    @elseif($order->status == 'waiting_payment')
<<<<<<< HEAD
        <div class="alert alert-warning border-0 shadow-sm rounded-4 mb-4 p-3" style="background: #fff9db; color: #856404;">
            <div class="d-flex align-items-center gap-3">
                <i class="bi bi-hourglass-split fs-4"></i>
                <div class="w-100">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="fw-bold mb-0">Selesaikan Pembayaran</h6>
                        <span id="countdown" class="badge bg-danger px-3 py-2" data-deadline="{{ $order->payment_deadline_at->format('Y-m-d H:i:s') }}">-- : -- : --</span>
                    </div>
                    <p class="small mb-0 mt-1">Batas akhir: <strong>{{ $order->payment_deadline_at->format('d M Y, H:i') }} WIB</strong></p>
=======
        <div class="alert alert-warning shadow-sm rounded-4 mb-4 border-0">
            <div class="d-flex align-items-center">
                <i class="bi bi-clock-history fs-3 me-3"></i>
                <div>
                    <h6 class="fw-bold mb-1">Segera Selesaikan Pembayaran</h6>
                    <p class="small mb-0">
                        Sisa waktu pembayaran Anda:
                        <span id="countdown" class="badge bg-danger fs-6 ms-1">
                            -- : -- : --
                        </span>
                    </p>
                    <small class="text-muted">Sebelum: {{ $order->payment_deadline_at->format('d M Y, H:i') }} WIB</small>
>>>>>>> c12d238a0e03d357b80b0cf4ca1c0f27b8d1ad73
                </div>
            </div>
        </div>
    @endif

    <div class="row g-4">
<<<<<<< HEAD
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
=======
        {{-- SISI KIRI: ITEM PESANAN --}}
        <div class="col-lg-7">
            <h5>Item Pesanan</h5>
            @foreach($order->items as $item)
                <div class="border rounded-4 p-3 mb-3">
                    <div class="fw-semibold">{{ $item->product_name }}</div>
                    <div class="text-muted mb-2">{{ $item->variant_name }}</div>
                    <div class="d-flex justify-content-between">
                        <span>Harga: Rp {{ number_format($item->price, 0, ',', '.') }} x {{ $item->quantity }}</span>
                        <span class="fw-bold">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
>>>>>>> c12d238a0e03d357b80b0cf4ca1c0f27b8d1ad73
                    </div>
                </div>
            @endforeach
        </div>

<<<<<<< HEAD
        {{-- Sisi Kanan: Summary & Payment --}}
=======
        {{-- SISI KANAN: RINGKASAN & PEMBAYARAN --}}
>>>>>>> c12d238a0e03d357b80b0cf4ca1c0f27b8d1ad73
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

<<<<<<< HEAD
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
=======
            {{-- Ringkasan Harga --}}
            <div class="border rounded-4 p-3 mb-3">
                <h5>Ringkasan</h5>
                <div class="d-flex justify-content-between mb-1">
                    <span>Subtotal Produk:</span>
                    <span>Rp {{ number_format($order->total_price - ($order->shipping_cost ?? 0), 0, ',', '.') }}</span>
                </div>
                <div class="d-flex justify-content-between mb-1">
                    <span>Ongkos Kirim ({{ $order->courier_name ?? 'Reguler' }}):</span>
                    <span>Rp {{ number_format($order->shipping_cost ?? 0, 0, ',', '.') }}</span>
                </div>
                <hr>
                <div class="d-flex justify-content-between fw-bold fs-5">
                    <span>Total:</span>
                    <span class="text-primary">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                </div>
            </div>

            {{-- Alamat Pengiriman --}}
            <div class="border rounded-4 p-3 mb-3">
                <h5>Alamat Pengiriman</h5>
                <p class="mb-1 fw-bold">{{ $order->address->recipient_name }}</p>
                <p class="mb-1">{{ $order->address->phone }}</p>
                <p class="mb-1">{{ $order->address->full_address }}</p>
                <p class="mb-0 text-muted small">{{ $order->address->district ?? '-' }}, {{ $order->address->city ?? '-' }}, {{ $order->address->province ?? '-' }} ({{ $order->address->postal_code }})</p>
            </div>

            {{-- INFORMASI PEMBAYARAN --}}
            <div class="border rounded-4 p-3 bg-white shadow-sm">
                <h5 class="mb-3">Informasi Pembayaran</h5>
                <p class="mb-2"><strong>Metode:</strong> {{ $order->payment_method_name ?: $order->payment_method }}</p>

                @if($order->payment_method_name == 'Pembayaran Online (Xendit)')
                    <div class="mt-3">
                        @if(in_array($order->status, ['processing', 'shipped', 'completed']))
                            <div class="alert alert-success border-0 py-2 mb-1">
                                <i class="bi bi-patch-check-fill me-2"></i> Pembayaran Berhasil Dikonfirmasi
                            </div>
                            <small class="text-muted">Diverifikasi otomatis oleh sistem Xendit.</small>
                        @elseif($order->status == 'waiting_payment')
                            <div class="alert alert-warning border-0 py-2 text-dark mb-1">
                                <i class="bi bi-clock-history me-2"></i> Menunggu Pembayaran
                            </div>
                            <a href="{{ $order->payment_url }}" target="_blank" class="btn btn-outline-primary btn-sm w-100 mt-2">
                                Link Pembayaran Xendit
                            </a>
                        @elseif($order->status == 'cancelled' || $order->status == 'expired')
                            <div class="alert alert-secondary border-0 py-2 mb-0">
                                <i class="bi bi-x-circle me-2"></i> Transaksi Dibatalkan
                            </div>
                        @endif
                    </div>
                @else
                    {{-- MANUAL TRANSFER LOGIC --}}
                    @if(in_array($order->status, ['processing', 'shipped', 'completed']))
                        <div class="alert alert-success border-0 py-2 mb-1">
                            <i class="bi bi-patch-check-fill me-2"></i> Pembayaran Berhasil Dikonfirmasi
                        </div>
                        @if($order->paymentReceipt)
                            <a href="{{ asset('storage/' . $order->paymentReceipt->receipt_file) }}" target="_blank" class="text-decoration-none small">
                                <i class="bi bi-image me-1"></i> Lihat bukti transfer Anda
                            </a>
                        @endif
                    @elseif($order->status === 'waiting_receipt_validation')
                        <div class="alert alert-info border-0 py-2 mb-1">
                            <i class="bi bi-hourglass-split me-2"></i> Menunggu Validasi Admin
                        </div>
                        <small class="text-muted">Bukti transfer Anda sedang diperiksa.</small>
                    @elseif(in_array($order->status, ['waiting_payment', 'payment_rejected']))
                        @if($order->status === 'payment_rejected')
                            <div class="alert alert-danger border-0 py-2 mb-2 small">
                                <i class="bi bi-x-octagon me-2"></i> Bukti sebelumnya ditolak. Mohon upload ulang.
                            </div>
                        @endif
                        
                        {{-- PERBAIKAN DI SINI: Gunakan uuid dan pastikan route name sesuai --}}
                        <form action="{{ route('orders.upload_receipt', $order->uuid) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label small fw-bold">Upload Struk Transfer (JPG/PNG)</label>
                                <input type="file" name="receipt_file" class="form-control form-control-sm @error('receipt_file') is-invalid @enderror" accept="image/*" required>
                                @error('receipt_file')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary btn-sm w-100">
                                <i class="bi bi-cloud-upload me-1"></i> Kirim Bukti Pembayaran
                            </button>
                        </form>
                    @endif
>>>>>>> c12d238a0e03d357b80b0cf4ca1c0f27b8d1ad73
                @endif
            </div>
        </div>
    </div>
<<<<<<< HEAD
</div>

{{-- Script SweetAlert & Countdown Tetap Dipertahankan --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const countdownElement = document.getElementById('countdown');
    if (countdownElement) {
        const deadlineString = countdownElement.getAttribute('data-deadline');
        const deadline = new Date(deadlineString.replace(/-/g, "/")).getTime();
=======

    <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
        <a href="{{ route('orders.index') }}" class="btn btn-primary rounded-4 shadow-sm px-4">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke daftar order
        </a>

        @if(in_array($order->status, ['shipped', 'completed']))
            {{-- Gunakan uuid --}}
            <a href="{{ route('orders.track', $order->uuid) }}" class="btn btn-primary rounded-4 shadow-sm px-4">
                <i class="bi bi-geo-alt me-2"></i> Lacak Pesanan
            </a>
        @endif
    </div>
</div>

{{-- SCRIPT SECTION --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        
        // 1. LOGIKA COUNTDOWN
        @if($order->status == 'waiting_payment' && $order->payment_deadline_at)
            const expiryDate = new Date("{{ $order->payment_deadline_at->format('Y-m-d H:i:s') }}").getTime();
            const countdownElement = document.getElementById("countdown");
>>>>>>> c12d238a0e03d357b80b0cf4ca1c0f27b8d1ad73

            const x = setInterval(function() {
                const now = new Date().getTime();
                const distance = expiryDate - now;

<<<<<<< HEAD
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
=======
                if (distance < 0) {
                    clearInterval(x);
                    countdownElement.innerHTML = "EXPIRED";
                    location.reload();
                    return;
                }

                const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                countdownElement.innerHTML = 
                    (hours < 10 ? "0" + hours : hours) + " : " + 
                    (minutes < 10 ? "0" + minutes : minutes) + " : " + 
                    (seconds < 10 ? "0" + seconds : seconds);
            }, 1000);
        @endif

        // 2. LOGIKA SWEETALERT SHIPPED
        @if($order->status === 'shipped')
            const orderUuid = "{{ $order->uuid }}";
            const hasShownAlert = localStorage.getItem('alert_shipped_' + orderUuid);

            if (!hasShownAlert) {
                Swal.fire({
                    icon: 'info',
                    title: 'Pesanan Dalam Perjalanan',
                    text: 'Pesananmu sudah diserahkan ke kurir dengan nomor resi: {{ $order->tracking_number }}. Mau lacak sekarang?',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Lacak!',
                    cancelButtonText: 'Nanti Saja',
                    confirmButtonColor: '#0d6efd'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = "{{ route('orders.track', $order->uuid) }}";
>>>>>>> c12d238a0e03d357b80b0cf4ca1c0f27b8d1ad73
                    }
                });
                localStorage.setItem('alert_shipped_' + orderUuid, 'true');
            }
<<<<<<< HEAD
        }, 1000);
    }
});
</script>

<style>
    .rounded-5 { border-radius: 2rem !important; }
    .rounded-4 { border-radius: 1.25rem !important; }
</style>
=======
        @endif
    });
</script>
>>>>>>> c12d238a0e03d357b80b0cf4ca1c0f27b8d1ad73
@endsection