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

            @if(in_array($order->status, ['waiting_payment', 'payment_rejected']) && $order->payment_deadline_at)
                <p class="mb-0 mt-2 text-danger">
                    <strong>Batas Pembayaran:</strong>
                    {{ $order->payment_deadline_at->format('d M Y H:i') }}
                </p>
            @endif
        </div>
    </div>

    {{-- Alert Status --}}
    @if($order->status == 'cancelled' || $order->status == 'expired')
        <div class="alert alert-danger shadow-sm rounded-4 mb-4">
            <div class="d-flex align-items-center">
                <i class="bi bi-exclamation-octagon-fill fs-3 me-3"></i>
                <div>
                    <h6 class="fw-bold mb-1">Pesanan Ini Telah Dibatalkan/Expired</h6>
                    <p class="mb-0 small">Batas waktu pembayaran telah berakhir. Mohon <strong>TIDAK MELAKUKAN TRANSFER</strong> karena stok barang sudah dikembalikan ke sistem.</p>
                </div>
            </div>
        </div>
    @elseif($order->status == 'waiting_payment')
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
                </div>
            </div>
        </div>
    @endif

    <div class="row g-4">
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
                    </div>
                </div>
            @endforeach
        </div>

        {{-- SISI KANAN: RINGKASAN & PEMBAYARAN --}}
        <div class="col-lg-5">
            {{-- Tombol Xendit --}}
            @if($order->status == 'waiting_payment' && $order->payment_url)
                <div class="mb-4">
                    <div class="d-grid gap-2">
                        <a href="{{ $order->payment_url }}" target="_blank" class="btn btn-primary btn-lg rounded-4 shadow">
                            <i class="bi bi-wallet2 me-2"></i> Bayar Sekarang (Via Xendit)
                        </a>
                    </div>
                    <p class="text-muted small text-center mt-2">
                        Mendukung Transfer Bank, QRIS, E-Wallet, dan Retail Outlet.
                    </p>
                </div>
            @endif

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
                @endif
            </div>
        </div>
    </div>

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

            const x = setInterval(function() {
                const now = new Date().getTime();
                const distance = expiryDate - now;

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
                    }
                });
                localStorage.setItem('alert_shipped_' + orderUuid, 'true');
            }
        @endif
    });
</script>
@endsection