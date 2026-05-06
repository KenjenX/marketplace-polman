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
                {{ $order->status }}
            </span>

            @if(in_array($order->status, ['waiting_payment', 'payment_rejected']) && $order->payment_deadline_at)
                <p class="mb-0 mt-2 text-danger">
                    <strong>Batas Pembayaran:</strong>
                    {{ $order->payment_deadline_at->format('d M Y H:i') }}
                </p>
            @endif
        </div>
    </div>
    {{-- Cek apakah status cancelled --}}
    @if($order->status == 'cancelled')
        <div class="alert alert-danger shadow-sm rounded-4 mb-4">
            <div class="d-flex align-items-center">
                <i class="bi bi-exclamation-octagon-fill fs-3 me-3"></i>
                <div>
                    <h6 class="fw-bold mb-1">Pesanan Ini Telah Dibatalkan</h6>
                    <p class="mb-0 small">Batas waktu pembayaran (1 jam) telah berakhir. Mohon <strong>TIDAK MELAKUKAN TRANSFER</strong> karena stok barang sudah dikembalikan ke sistem.</p>
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
        <div class="col-lg-7">
            <h5>Item Pesanan</h5>

            @foreach($order->items as $item)
                <div class="border rounded-4 p-3 mb-3">
                    <div class="fw-semibold">{{ $item->product_name }}</div>
                    <div class="text-muted mb-2">{{ $item->variant_name }}</div>
                    <div>Harga: Rp {{ number_format($item->price, 0, ',', '.') }}</div>
                    <div>Jumlah: {{ $item->quantity }}</div>
                    <div>Subtotal: Rp {{ number_format($item->subtotal, 0, ',', '.') }}</div>
                </div>
            @endforeach
        </div>

        <div class="col-lg-5">
            {{-- Xendit (Baris 82) --}}
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
            <div class="border rounded-4 p-3 mb-3">
                <h5>Ringkasan</h5>
                <p class="mb-1"><strong>Metode Pembayaran:</strong> {{ $order->payment_method }}</p>
                <p class="mb-0"><strong>Total:</strong> Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
            </div>

            <div class="border rounded-4 p-3 mb-3">
                <h5>Alamat Pengiriman</h5>
                <p class="mb-1">{{ $order->address->recipient_name }}</p>
                <p class="mb-1">{{ $order->address->phone }}</p>
                <p class="mb-1">{{ $order->address->province }}, {{ $order->address->city }}, {{ $order->address->district }}</p>
                <p class="mb-1">{{ $order->address->postal_code }}</p>
                <p class="mb-0">{{ $order->address->full_address }}</p>
            </div>

            <div class="border rounded-4 p-3 mb-3 bg-light">
                <h5 class="mb-3">Instruksi Pembayaran</h5>

                <p class="mb-2">Silakan lakukan transfer ke metode berikut:</p>
                <div class="mb-2"><strong>Metode:</strong> {{ $order->payment_method_name ?: $order->payment_method }}</div>

                @if($order->payment_bank_name)
                    <div class="mb-2"><strong>Bank:</strong> {{ $order->payment_bank_name }}</div>
                @endif

                @if($order->payment_account_number)
                    <div class="mb-2"><strong>No. Rekening:</strong> {{ $order->payment_account_number }}</div>
                @endif

                @if($order->payment_account_name)
                    <div class="mb-2"><strong>Atas Nama:</strong> {{ $order->payment_account_name }}</div>
                @endif

                @if($order->payment_instruction)
                    <div class="mt-3 text-muted">
                        {{ $order->payment_instruction }}
                    </div>
                @endif
            </div>

            <div class="border rounded-4 p-3">
                <h5>Bukti Pembayaran</h5>

                @if($order->paymentReceipt)
                    <p class="mb-1"><strong>Status Validasi:</strong> {{ $order->paymentReceipt->validation_status }}</p>

                    @if($order->paymentReceipt->admin_note)
                        <p class="mb-2"><strong>Catatan Admin:</strong> {{ $order->paymentReceipt->admin_note }}</p>
                    @endif

                    <a href="{{ asset('storage/' . $order->paymentReceipt->receipt_file) }}" target="_blank" class="btn btn-outline-primary btn-sm mb-3">
                        Lihat Bukti Pembayaran
                    </a>
                @endif

                @if(in_array($order->status, ['waiting_payment', 'payment_rejected']))
                    <form action="{{ route('orders.uploadReceipt', $order->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Upload Bukti Pembayaran</label>
                            <input type="file" name="receipt_file" class="form-control" accept=".jpg,.jpeg,.png">
                        </div>

                        <button type="submit" class="btn btn-primary">
                            {{ $order->status === 'payment_rejected' ? 'Upload Ulang Bukti' : 'Upload Bukti Pembayaran' }}
                        </button>
                    </form>
                @elseif($order->status === 'waiting_receipt_validation')
                    <div class="alert alert-info mb-0">
                        Bukti pembayaran sudah diupload, menunggu validasi admin.
                    </div>
                @endif
            </div>
        </div>
    </div>

    <a href="{{ route('orders.index') }}" class="btn btn-link px-0 mt-4">← Kembali ke daftar order</a>
</div>
@endsection
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const countdownElement = document.getElementById('countdown');
    
    if (countdownElement) {
        const deadlineString = countdownElement.getAttribute('data-deadline');
        // Mengonversi string format Y-m-d H:i:s menjadi objek Date
        // Kita ganti spasi dengan 'T' agar kompatibel dengan format ISO di berbagai browser
        const deadline = new Date(deadlineString.replace(/-/g, "/")).getTime();

        const x = setInterval(function() {
            const now = new Date().getTime();
            const distance = deadline - now;

            // Perhitungan waktu untuk jam, menit, dan detik
            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);

            // Tampilkan hasil di elemen id="countdown"
            countdownElement.innerHTML = 
                (hours < 10 ? "0" + hours : hours) + "j : " + 
                (minutes < 10 ? "0" + minutes : minutes) + "m : " + 
                (seconds < 10 ? "0" + seconds : seconds) + "d";

            // Jika hitung mundur selesai
            if (distance < 0) {
                clearInterval(x);
                countdownElement.innerHTML = "WAKTU HABIS";
                // Memunculkan Pop-up SweetAlert
                Swal.fire({
                    icon: 'error', // Ikon X merah
                    title: 'Pesanan Sudah Expired',
                    text: 'Transaksi tidak bisa dilakukan. Silahkan buat pesanan kembali.',
                    confirmButtonText: 'Oke, Mengerti',
                    confirmButtonColor: '#d33',
                    allowOutsideClick: false, // User tidak bisa klik luar untuk tutup
                    allowEscapeKey: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Setelah klik Oke, arahkan kembali ke halaman produk atau keranjang
                        window.location.href = "{{ route('products.index') }}";
                    }
                });
            }
        }, 1000);
    }
});
</script>