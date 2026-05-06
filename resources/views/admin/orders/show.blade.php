@extends('layouts.admin')

@section('content')
@php
    $orderStatusClass = match($order->status) {
        'waiting_payment' => 'badge-waiting-payment',
        'waiting_receipt_validation' => 'badge-waiting-validation',
        'payment_rejected' => 'badge-rejected',
        'processing' => 'badge-processing',
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

            @if($order->paymentReceipt)
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

                @if($order->payment_account_name)
                    <p class="mb-1"><strong>Atas Nama:</strong> {{ $order->payment_account_name }}</p>
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
                <p class="mb-1">{{ $order->address->province }}, {{ $order->address->city }}, {{ $order->address->district }}</p>
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
            {{-- SEKSI BUKTI PEMBAYARAN (LOGIKA PERBAIKAN) --}}
            <div class="info-box mb-4 shadow-sm border-primary">
                <div class="section-title">Bukti Pembayaran</div>

                @if($order->payment_method_name == 'Pembayaran Online (Xendit)')
                    {{-- Tampilan khusus Xendit --}}
                    <div class="mb-2">
                        <span class="small text-muted d-block">Status Validasi:</span>
                        @if(in_array($order->status, ['processing', 'completed']))
                            <div class="alert alert-success border-0 py-2 mt-2">
                                <i class="bi bi-patch-check-fill me-2"></i> Pembayaran Berhasil Dikonfirmasi
                            </div>
                        @else
                            <span class="badge bg-warning text-dark">Menunggu Konfirmasi Xendit</span>
                        @endif
                    </div>
                    <div class="alert alert-light border small mt-2">
                        Metode ini diverifikasi otomatis oleh sistem melalui Payment Gateway (Xendit).
                    </div>

                @elseif($order->paymentReceipt)
                    {{-- Tampilan khusus Manual Transfer (BCA, dll) --}}
                    <div class="mb-3">
                        <span class="small text-muted d-block mb-1">Status Validasi:</span>
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

                    @if($order->paymentReceipt->admin_note)
                        <div class="mb-3 p-2 bg-light border-start border-3 border-danger">
                            <small class="text-muted d-block">Catatan Admin sebelumnya:</small>
                            <span class="small text-danger">{{ $order->paymentReceipt->admin_note }}</span>
                        </div>
                    @endif

                    <a href="{{ asset('storage/' . $order->paymentReceipt->receipt_file) }}" 
                       target="_blank" 
                       class="btn btn-outline-primary w-100 mb-3">
                        <i class="bi bi-image me-1"></i> Lihat Bukti Pembayaran
                    </a>

                    {{-- Form Validasi Admin (Hanya jika manual dan pending) --}}
                    @if($order->status === 'waiting_receipt_validation' || $order->paymentReceipt->validation_status === 'pending')
                        <hr>
                        <form action="{{ route('admin.orders.updatePaymentStatus', $order->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <div class="mb-3">
                                <label class="form-label small fw-bold">Catatan (Opsional)</label>
                                <textarea name="admin_note" class="form-control form-control-sm" rows="3" placeholder="Alasan jika ditolak..."></textarea>
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

                @else
                    {{-- Jika manual tapi belum upload --}}
                    <div class="text-center py-4 bg-light rounded-3">
                        <i class="bi bi-camera text-muted fs-1"></i>
                        <p class="text-muted small mt-2 mb-0">User belum mengunggah bukti transfer.</p>
                    </div>
                @endif
            </div>

            {{-- Status Lanjutan Order --}}
            <div class="info-box">
                <div class="section-title">Aksi Order</div>

                @if($order->status === 'processing')
                    <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST" class="mb-2">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="completed">
                        <button type="submit" class="btn btn-primary w-100" onclick="return confirm('Selesaikan order ini?')">
                            <i class="bi bi-box-seam me-1"></i> Selesaikan Order
                        </button>
                    </form>
                @endif

                @if(in_array($order->status, ['waiting_payment', 'payment_rejected', 'processing']))
                    <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="cancelled">
                        <button type="submit" class="btn btn-outline-secondary w-100" onclick="return confirm('Batalkan order ini?')">
                            Batalkan Order
                        </button>
                    </form>
                @else
                    <div class="alert alert-light border-0 small text-center mb-0">
                        Tidak ada aksi lanjutan.
                    </div>
                @endif
            </div>
        </div>
    </div>

    <a href="{{ route('admin.orders.index') }}" class="btn btn-link px-0 mt-4 text-decoration-none">← Kembali ke daftar order</a>
</div>
@endsection