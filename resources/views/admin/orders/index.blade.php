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
                            'waiting_payment'           => 'bg-warning text-dark',
                            'waiting_receipt_validation' => 'bg-info text-dark',
                            'processing'                => 'bg-primary',
                            'shipped'                   => 'bg-primary',
                            'completed'                 => 'bg-success',
                            'payment_rejected'          => 'bg-danger',
                            'cancelled'                 => 'bg-danger',
                            'expired'                   => 'bg-danger',
                            default                     => 'bg-secondary',
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
                            <span class="small">{{ $order->payment_method_name ?: $order->payment_method }}</span>
                        </td>
                        <td>
                            <span class="badge {{ $orderStatusClass }} px-2 py-2" style="min-width: 100px;">
                                {{ str_replace('_', ' ', $order->status) }}
                            </span>
                        </td>
                        <td>
                            {{-- LOGIKA 1: JIKA XENDIT --}}
                            @if($order->payment_method === 'xendit' || str_contains(strtolower($order->payment_method_name), 'xendit'))
                                @if(in_array($order->status, ['processing', 'shipped', 'completed']))
                                    <span class="badge bg-success">
                                        <i class="bi bi-patch-check-fill me-1"></i> Terkonfirmasi
                                    </span>
                                @elseif($order->status == 'waiting_payment')
                                    <span class="badge bg-warning text-dark">Menunggu Bayar</span>
                                @elseif($order->status == 'expired')
                                    <span class="badge bg-danger">Kadaluarsa</span>
                                @else
                                    <span class="badge bg-secondary text-white">Otomatis</span>
                                @endif
                                
                            {{-- LOGIKA 2: JIKA TRANSFER MANUAL (ADA BUKTI) --}}
                            @elseif($order->paymentReceipt)
                                @php
                                    $valStatus = $order->paymentReceipt->validation_status;
                                    $valBadge = match($valStatus) {
                                        'approved', 'accepted' => 'bg-success',
                                        'rejected' => 'bg-danger',
                                        default => 'bg-info text-dark',
                                    };
                                @endphp
                                <span class="badge {{ $valBadge }}">{{ ucfirst($valStatus) }}</span>
                            
                            {{-- LOGIKA 3: TRANSFER MANUAL TAPI BELUM UPLOAD --}}
                            @else
                                <span class="badge bg-light text-muted border">Belum Upload</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.orders.show', $order->uuid) }}" class="btn btn-outline-primary btn-sm w-100">
                                <i class="bi bi-search me-1"></i> Detail
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-5">
                            <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                            Belum ada pesanan masuk.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection