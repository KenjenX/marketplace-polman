@extends('layouts.admin')

@section('content')
<div class="admin-card">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">Daftar Order</h2>
            <p class="text-muted mb-0">Kelola pembayaran dan status pesanan user.</p>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode Order</th>
                    <th>User</th>
                    <th>Total</th>
                    <th>Pembayaran</th>
                    <th>Status Order</th>
                    <th>Status Bukti</th>
                    <th width="120">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                    @php
                        $orderStatusClass = match($order->status) {
                            'waiting_payment'            => 'bg-warning text-dark',
                            'waiting_receipt_validation' => 'bg-info text-dark',
                            'processing'                 => 'bg-primary',
                            'completed'                  => 'bg-success',
                            'payment_rejected', 
                            'cancelled', 
                            'expired'                    => 'bg-danger',
                            default                      => 'bg-secondary',
                        };
                    @endphp

                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $order->order_code }}</td>
                        <td>
                            <div class="fw-semibold">{{ $order->user->name }}</div>
                            <small class="text-muted">{{ $order->user->email }}</small>
                        </td>
                        <td>Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                        <td>
                            {{-- Menampilkan nama metode pembayaran --}}
                            <span class="small">{{ $order->payment_method_name ?: $order->payment_method }}</span>
                        </td>
                        <td>
                            {{-- Kolom Status Order dengan Gaya Solid --}}
                            <span class="badge {{ $orderStatusClass }} px-2 py-2" style="min-width: 100px;">
                                {{ str_replace('_', ' ', $order->status) }}
                            </span>
                        </td>
                        <td>
                            {{-- Kolom Status Bukti dengan Gaya Solid --}}
                            @if($order->payment_method_name == 'Pembayaran Online (Xendit)')
                                @if(in_array($order->status, ['processing', 'completed']))
                                    <span class="badge bg-success px-2 py-2">Terverifikasi Otomatis</span>
                                @else
                                    <span class="badge bg-warning text-dark px-2 py-2">Menunggu Pembayaran</span>
                                @endif
                            @else
                                {{-- Logika untuk Manual Transfer --}}
                                @if($order->paymentReceipt)
                                    @php
                                        $receiptStatus = $order->paymentReceipt->validation_status;
                                        $receiptBadge = match($receiptStatus) {
                                            'approved', 'accepted' => 'bg-success',
                                            'rejected'             => 'bg-danger',
                                            default                => 'bg-info text-dark', // pending
                                        };
                                    @endphp
                                    <span class="badge {{ $receiptBadge }} px-2 py-2">
                                        {{ ucfirst($receiptStatus) }}
                                    </span>
                                @else
                                    <span class="badge bg-secondary px-2 py-2">Belum Upload</span>
                                @endif
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-search me-1"></i> Detail
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">Belum ada order.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection