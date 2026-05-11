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
                        <span id="countdown" class="badge bg-danger fs-6 ms-1" 
                            data-deadline="{{ $order->payment_deadline_at->format('Y-m-d H:i:s') }}">
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
                <p class="mb-0 text-muted small">{{ $order->address->district }}, {{ $order->address->city }}, {{ $order->address->province }} ({{ $order->address->postal_code }})</p>
            </div>

            {{-- Instruksi Pembayaran (Hanya muncul jika bukan Xendit ATAU jika via Xendit tapi status masih waiting) --}}
            @if(!$order->payment_url || ($order->payment_url && $order->status == 'waiting_payment'))
            <div class="border rounded-4 p-3 mb-3 bg-light">
                <h5 class="mb-3">Instruksi Pembayaran</h5>
                <p class="mb-2">Metode: <strong>{{ $order->payment_method_name ?: $order->payment_method }}</strong></p>

                @if($order->payment_bank_name)
                    <div class="mb-1">Bank: <strong>{{ $order->payment_bank_name }}</strong></div>
                    <div class="mb-1">No. Rekening: <strong>{{ $order->payment_account_number }}</strong></div>
                    <div class="mb-1">Atas Nama: <strong>{{ $order->payment_account_name }}</strong></div>
                @endif

                @if($order->payment_instruction)
                    <div class="mt-3 text-muted small p-2 border-start border-3">
                        {!! nl2br(e($order->payment_instruction)) !!}
                    </div>
                @endif
            </div>
            @endif

            {{-- BUKTI PEMBAYARAN (PERBAIKAN LOGIKA SESUAI PERMINTAAN) --}}
            <div class="border rounded-4 p-3">
                <h5>Bukti Pembayaran</h5>

                @if($order->status === 'processing' || $order->status === 'completed')
                    {{-- Jika sudah lunas --}}
                    <div class="alert alert-success border-0 mb-0">
                        <i class="bi bi-check-circle-fill me-2"></i>
                        Pembayaran Berhasil Dikonfirmasi.
                    </div>
                    @if($order->paymentReceipt)
                        <a href="{{ asset('storage/' . $order->paymentReceipt->receipt_file) }}" target="_blank" class="btn btn-sm btn-outline-primary mt-2 w-100">
                            Lihat Struk
                        </a>
                    @endif

                @elseif($order->paymentReceipt && $order->status === 'waiting_receipt_validation')
                    {{-- Jika sudah upload tapi menunggu validasi admin --}}
                    <div class="alert alert-info border-0 mb-2">
                        <i class="bi bi-info-circle-fill me-2"></i>
                        Bukti sudah dikirim, menunggu validasi admin.
                    </div>
                    <a href="{{ asset('storage/' . $order->paymentReceipt->receipt_file) }}" target="_blank" class="btn btn-sm btn-outline-primary w-100">
                        Lihat Struk yang Dikirim
                    </a>

                @elseif(in_array($order->status, ['waiting_payment', 'payment_rejected']))
                    {{-- FORM UPLOAD MANUAL --}}
                    @if($order->payment_method_name == 'Pembayaran Online (Xendit)')
                        {{-- Khusus Xendit --}}
                        <div class="alert alert-warning border-0 small mb-2">
                            Menunggu pembayaran otomatis via Xendit.
                        </div>
                        <a href="{{ $order->payment_url }}" target="_blank" class="btn btn-primary btn-sm w-100">
                            Bayar via Xendit
                        </a>
                    @else
                        {{-- Khusus Transfer Manual (BCA, dll) --}}
                        @if($order->status === 'payment_rejected')
                            <div class="alert alert-danger border-0 small mb-2">
                                Bukti sebelumnya ditolak. Silakan upload ulang.
                            </div>
                        @endif

                        <form action="{{ route('orders.uploadReceipt', $order->id) }}" method="POST" enctype="multipart/form-data">
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
                @else
                    <p class="text-muted small">Tidak ada informasi pembayaran.</p>
                @endif
            </div>
        </div>
    </div>

    <a href="{{ route('orders.index') }}" class="btn btn-link px-0 mt-4">← Kembali ke daftar order</a>
</div>
@endsection

{{-- Script Countdown --}}
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
                    title: 'Pesanan Sudah Expired',
                    text: 'Transaksi tidak bisa dilakukan. Silahkan buat pesanan kembali.',
                    confirmButtonText: 'Oke, Mengerti',
                    confirmButtonColor: '#d33',
                    allowOutsideClick: false,
                    allowEscapeKey: false
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