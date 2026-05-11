@extends('layouts.admin')

@section('content')
@php
    $orderStatusClass = match($order->status) {
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

    $receiptStatusClass = match(optional($order->paymentReceipt)->validation_status) {
        'pending' => 'badge-waiting-validation',
        'accepted', 'approved' => 'badge-completed',
        'rejected' => 'badge-rejected',
        default => 'badge-cancelled',
    };
@endphp

<div class="admin-card">
    <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
        <div>
            <h2 class="mb-1">Detail Order</h2>
            <p class="text-muted mb-0">{{ $order->order_code }}</p>
        </div>

        <div class="d-flex flex-wrap gap-2">
            <span class="badge status-badge {{ $orderStatusClass }}">
                {{ str_replace('_', ' ', $order->status) }}
            </span>

            {{-- Badge status bukti hanya muncul jika manual dan ada data receipt --}}
            @if(!str_contains(strtolower($order->payment_method_name), 'xendit') && $order->payment_method !== 'xendit' && $order->paymentReceipt)
                <span class="badge status-badge {{ $receiptStatusClass }}">
                    bukti: {{ $order->paymentReceipt->validation_status }}
                </span>
            @endif
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            {{-- Informasi Pemesan --}}
            <div class="info-box mb-4">
                <div class="section-title">Informasi Pemesan</div>
                <p class="mb-1"><strong>Nama:</strong> {{ $order->user->name }}</p>
                <p class="mb-1"><strong>Email:</strong> {{ $order->user->email }}</p>
                <p class="mb-1"><strong>Metode Pembayaran:</strong> {{ $order->payment_method_name ?: $order->payment_method }}</p>

                @if($order->payment_bank_name)
                    <p class="mb-1"><strong>Bank:</strong> {{ $order->payment_bank_name }}</p>
                @endif

                @if($order->payment_account_number)
                    <p class="mb-1"><strong>No. Rekening:</strong> {{ $order->payment_account_number }}</p>
                @endif

                @if($order->payment_deadline_at)
                    <p class="mb-0 text-danger">
                        <strong>Batas Pembayaran:</strong>
                        {{ $order->payment_deadline_at->format('d M Y H:i') }}
                    </p>
                @endif
            </div>

            {{-- Alamat Pengiriman --}}
            <div class="info-box mb-4">
                <div class="section-title">Alamat Pengiriman</div>
                <p class="mb-1 fw-bold">{{ $order->address->recipient_name }} ({{ $order->address->phone }})</p>
                <p class="mb-1">{{ $order->address->province ?? '-' }}, {{ $order->address->city ?? '-' }}, {{ $order->address->district ?? '-' }}</p>
                <p class="mb-1">{{ $order->address->postal_code }}</p>
                <p class="mb-0">{{ $order->address->full_address }}</p>
            </div>

            {{-- Item Pesanan --}}
            <div class="info-box">
                <div class="section-title">Item Pesanan</div>
                <div class="table-responsive">
                    <table class="table table-bordered align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Produk</th>
                                <th>Variasi</th>
                                <th>Harga</th>
                                <th>Qty</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                                <tr>
                                    <td>{{ $item->product_name }}</td>
                                    <td>{{ $item->variant_name }}</td>
                                    <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="4" class="text-end border-0">Subtotal Produk:</td>
                                <td class="border-start">Rp {{ number_format($order->total_price - ($order->shipping_cost ?? 0), 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <td colspan="4" class="text-end border-0"><strong>Ongkos Kirim ({{ $order->courier_name ?? 'Reguler' }}):</strong></td>
                                <td class="border-start">Rp {{ number_format($order->shipping_cost ?? 0, 0, ',', '.') }}</td>
                            </tr>
                            <tr class="table-light">
                                <td colspan="4" class="text-end"><strong>Total Keseluruhan:</strong></td>
                                <td><strong>Rp {{ number_format($order->total_price, 0, ',', '.') }}</strong></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            {{-- SEKSI BUKTI PEMBAYARAN --}}
            <div class="info-box mb-4 shadow-sm border-primary">
                <div class="section-title">Bukti Pembayaran</div>

                @if($order->payment_method === 'xendit' || str_contains(strtolower($order->payment_method_name), 'xendit'))
                    {{-- Tampilan Otomatis Xendit --}}
                    <div class="p-3">
                        <p class="mb-2 text-muted small"><strong>Metode:</strong> Xendit (Pembayaran Otomatis)</p>
                        <div class="alert alert-success d-flex align-items-center mb-0" style="background-color: #d1e7dd; border: none; color: #0f5132; padding: 1rem;">
                            <i class="bi bi-patch-check-fill me-2 fs-5"></i>
                            <span class="fw-bold">Pembayaran Berhasil Dikonfirmasi</span>
                        </div>
                        <div class="mt-3 p-2 bg-light rounded border small text-muted text-center">
                             <i class="bi bi-shield-lock me-1"></i> Terverifikasi otomatis oleh sistem Xendit.
                        </div>
                    </div>

                @elseif($order->paymentReceipt)
                    {{-- Logika untuk Transfer Manual --}}
                    <div class="mb-3 px-3">
                        <label class="text-muted small d-block mb-1">Status Validasi Manual:</label>
                        @php
                            $valStatus = $order->paymentReceipt->validation_status;
                            $valBadge = match($valStatus) {
                                'approved', 'accepted' => 'bg-success',
                                'rejected' => 'bg-danger',
                                default => 'bg-info text-dark',
                            };
                        @endphp
                        <span class="badge {{ $valBadge }} px-3 py-2 fs-6">{{ ucfirst($valStatus) }}</span>
                    </div>

                    <div class="px-3">
                        <a href="{{ asset('storage/' . $order->paymentReceipt->receipt_file) }}" target="_blank" class="btn btn-outline-primary w-100 mb-3">
                            <i class="bi bi-image me-1"></i> Lihat Foto Bukti
                        </a>

                        @if($order->status === 'waiting_receipt_validation' || $order->paymentReceipt->validation_status === 'pending')
                            <hr>
                            <form action="{{ route('admin.orders.updatePaymentStatus', $order->uuid) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <div class="mb-3">
                                    <label class="form-label small fw-bold">Catatan Penolakan (Opsional)</label>
                                    <textarea name="admin_note" class="form-control form-control-sm" rows="3" placeholder="Alasan jika bukti ditolak..."></textarea>
                                </div>
                                <div class="d-grid gap-2">
                                    <button type="submit" name="action" value="accept" class="btn btn-success btn-sm">
                                        <i class="bi bi-check2-circle me-1"></i> Terima Pembayaran
                                    </button>
                                    <button type="submit" name="action" value="reject" class="btn btn-outline-danger btn-sm">
                                        <i class="bi bi-x-circle me-1"></i> Tolak Pembayaran
                                    </button>
                                </div>
                            </form>
                        @endif
                    </div>
                @else
                    {{-- Muncul hanya jika manual dan belum upload --}}
                    <div class="text-center py-4 bg-light rounded-3 mx-3 mb-3">
                        <i class="bi bi-camera text-muted fs-1"></i>
                        <p class="text-muted small mt-2 mb-0">User belum mengunggah bukti transfer manual.</p>
                    </div>
                @endif
            </div>

            {{-- SEKSI AKSI ORDER & PENGIRIMAN --}}
            <div class="info-box shadow-sm">
                <div class="section-title">Aksi & Pengiriman</div>

                @if($order->status === 'processing')
                    <div class="bg-light p-3 rounded mb-3 border">
                        <h6 class="small fw-bold mb-3"><i class="bi bi-pencil-square me-1"></i> Input Informasi Resi</h6>
                        <form action="{{ route('admin.orders.update-tracking', $order->uuid) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <div class="mb-2">
                                <label class="small text-muted">Kurir</label>
                                <select name="courier_code" class="form-select form-select-sm" required>
                                    <option value="jne" {{ $order->courier_code == 'jne' ? 'selected' : '' }}>JNE</option>
                                    <option value="pos" {{ $order->courier_code == 'pos' ? 'selected' : '' }}>POS Indonesia</option>
                                    <option value="jnt" {{ $order->courier_code == 'jnt' ? 'selected' : '' }}>JNT</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="small text-muted">Nomor Resi</label>
                                <input type="text" name="tracking_number" class="form-control form-control-sm" 
                                       value="{{ $order->tracking_number }}" placeholder="Contoh: JNE12345..." required>
                            </div>
                            <button type="submit" class="btn btn-primary btn-sm w-100">
                                <i class="bi bi-save me-1"></i> Simpan & Kirim Barang
                            </button>
                        </form>
                    </div>
                @endif

                @if($order->tracking_number)
                    <div class="alert alert-info py-2 small mb-3">
                        <i class="bi bi-truck me-2"></i> <strong>Resi:</strong> {{ strtoupper($order->courier_code) }} - {{ $order->tracking_number }}
                    </div>
                @endif

                @if(in_array($order->status, ['processing', 'shipped']))
                    <form action="{{ route('admin.orders.updateStatus', $order->uuid) }}" method="POST" class="mb-2">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="completed">
                        <button type="submit" class="btn btn-success w-100 py-2 fw-bold" onclick="return confirm('Selesaikan order ini?')">
                            <i class="bi bi-box-seam me-1"></i> Selesaikan Order
                        </button>
                    </form>
                @endif

                @if(in_array($order->status, ['waiting_payment', 'payment_rejected', 'processing', 'shipped']))
                    <form action="{{ route('admin.orders.updateStatus', $order->uuid) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="cancelled">
                        <button type="submit" class="btn btn-outline-secondary btn-sm w-100" onclick="return confirm('Batalkan order ini?')">
                            Batalkan Order
                        </button>
                    </form>
                @elseif($order->status === 'completed' || $order->status === 'cancelled')
                    <div class="alert alert-light border-0 small text-center mb-0">
                        <i class="bi bi-info-circle me-1"></i> Status order ini sudah final.
                    </div>
                @endif
            </div>
        </div>
    </div>

    <a href="{{ route('admin.orders.index') }}" class="btn btn-link px-0 mt-4 text-decoration-none">← Kembali ke daftar order</a>
</div>
@endsection

{{-- CATATAN LOGIKA STATUS (DIADOPSI SESUAI PERMINTAAN) --}}
{{--
    LOGIKA 1: JIKA METODE XENDIT
        - STATUS 'processing', 'shipped', 'completed' → Auto-Verified (badge hijau)
        - STATUS 'waiting_payment' → Menunggu Bayar (badge kuning)
        - STATUS 'expired' → Kadaluarsa (badge merah)
        - STATUS lainnya → Pending (badge abu-abu)
    LOGIKA 2: JIKA TRANSFER MANUAL (ADA BUKTI)
        - validation_status 'approved', 'accepted' → Diterima (badge hijau)
        - validation_status 'rejected' → Ditolak (badge merah)
        - validation_status lainnya → Pending Validasi (badge biru)
    LOGIKA 3: TRANSFER MANUAL TAPI BELUM UPLOAD
        - Tampilkan badge "Belum Upload" (badge abu-abu)
--}}

{{-- CATATAN LOGIKA WARNA STATUS (DIADOPSI SESUAI PERMINTAAN) --}}
{{--
    'waiting_payment'           => 'bg-warning text-dark',
    'waiting_receipt_validation' => 'bg-info text-dark',
     'payment_rejected'          => 'bg-danger',
     'processing'                => 'bg-primary',
     'shipped'                   => 'bg-primary',
     'completed'                 => 'bg-success',
     'cancelled'                 => 'bg-danger',
     'expired'                   => 'bg-danger',
     default                     => 'bg-secondary',
--}}