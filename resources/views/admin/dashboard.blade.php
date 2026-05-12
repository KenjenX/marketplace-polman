@extends('layouts.admin')

@section('content')
<style>
    .icon-box {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
    }
    .text-polman { color: #013780; }
    .bg-polman-light { background-color: #e7f1ff; }
</style>

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1 fw-bold text-dark">Dashboard Admin</h2>
            <p class="text-muted mb-0">Ringkasan performa bisnis Marketplace Polman.</p>
        </div>
        <a href="{{ route('home') }}" class="btn btn-outline-primary shadow-sm rounded-3">
            <i class="bi bi-shop me-1"></i> Lihat Store
        </a>
    </div>

    {{-- KARTU RINGKASAN ATAS --}}
    <div class="row g-4 mb-4">
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100 rounded-4">
                <div class="card-body d-flex align-items-center">
                    <div class="icon-box bg-success bg-opacity-10 text-success me-3">
                        <i class="bi bi-cash-stack"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-bold text-uppercase mb-1">Total Pendapatan</div>
                        <h4 class="mb-0 fw-bold">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100 rounded-4">
                <div class="card-body d-flex align-items-center">
                    <div class="icon-box bg-warning bg-opacity-10 text-warning me-3">
                        <i class="bi bi-hourglass-split"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-bold text-uppercase mb-1">Menunggu Validasi</div>
                        <h4 class="mb-0 fw-bold">{{ $waitingValidationCount }} Pesanan</h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100 rounded-4">
                <div class="card-body d-flex align-items-center">
                    <div class="icon-box bg-polman-light text-polman me-3">
                        <i class="bi bi-box-seam"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-bold text-uppercase mb-1">Total Produk</div>
                        <h4 class="mb-0 fw-bold">{{ $productCount }} Item</h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100 rounded-4">
                <div class="card-body d-flex align-items-center">
                    <div class="icon-box bg-info bg-opacity-10 text-info me-3">
                        <i class="bi bi-people"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-bold text-uppercase mb-1">Total Pengguna</div>
                        <h4 class="mb-0 fw-bold">{{ $userCount }} Akun</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        {{-- GRAFIK PENJUALAN --}}
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white border-0 pt-4 pb-0 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0">Grafik Penjualan</h5>
                    
                    {{-- Form Filter Waktu --}}
                    <form action="{{ route('admin.dashboard') }}" method="GET" class="d-flex align-items-center">
                        <select name="filter" class="form-select form-select-sm shadow-none bg-light border-0 fw-bold text-muted" onchange="this.form.submit()" style="border-radius: 8px;">
                            <option value="harian" {{ $filter == 'harian' ? 'selected' : '' }}>7 Hari Terakhir</option>
                            <option value="mingguan" {{ $filter == 'mingguan' ? 'selected' : '' }}>4 Minggu Terakhir</option>
                            <option value="bulanan" {{ $filter == 'bulanan' ? 'selected' : '' }}>6 Bulan Terakhir</option>
                            <option value="tahunan" {{ $filter == 'tahunan' ? 'selected' : '' }}>3 Tahun Terakhir</option>
                        </select>
                    </form>
                </div>
                <div class="card-body">
                    <canvas id="salesChart" height="100"></canvas>
                </div>
            </div>
        </div>

        {{-- STOK MENIPIS --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white border-0 pt-4 pb-0">
                    <h5 class="fw-bold mb-0 text-danger"><i class="bi bi-exclamation-triangle me-2"></i>Stok Menipis</h5>
                </div>
                <div class="card-body">
                    @if($lowStockVariants->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($lowStockVariants as $variant)
                            <div class="list-group-item px-0 py-3 border-bottom-dashed">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1 small fw-bold text-dark">{{ $variant->product->name ?? 'Produk Dihapus' }}</h6>
                                        <small class="text-muted">{{ $variant->name }}</small>
                                    </div>
                                    <span class="badge bg-danger rounded-pill px-3 py-2">Sisa {{ $variant->stock }}</span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <div class="icon-box bg-success bg-opacity-10 text-success mx-auto mb-3">
                                <i class="bi bi-check-circle"></i>
                            </div>
                            <p class="text-muted small">Stok semua produk masih dalam keadaan aman.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- TABEL PESANAN TERBARU --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-header bg-white border-0 pt-4 pb-3 d-flex justify-content-between align-items-center">
            <h5 class="fw-bold mb-0">Pesanan Terbaru</h5>
            <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-light fw-bold text-primary">Lihat Semua</a>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-muted small text-uppercase">
                        <tr>
                            <th class="ps-4 py-3">ID Pesanan</th>
                            <th class="py-3">Pelanggan</th>
                            <th class="py-3">Tanggal</th>
                            <th class="py-3">Total</th>
                            <th class="py-3">Status</th>
                            <th class="pe-4 py-3 text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentOrders as $order)
                        <tr>
                            <td class="ps-4 fw-bold text-dark">{{ $order->order_code }}</td>
                            <td>{{ $order->address->recipient_name ?? 'Pelanggan' }}</td>
                            <td>{{ $order->created_at->format('d M Y, H:i') }}</td>
                            <td class="fw-bold text-primary">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                            <td>
                                @if($order->status == 'waiting_payment')
                                    <span class="badge bg-warning text-dark px-3 py-2 rounded-pill">Menunggu Pembayaran</span>
                                @elseif($order->status == 'paid')
                                    <span class="badge bg-info px-3 py-2 rounded-pill">Dibayar</span>
                                @elseif($order->status == 'shipped')
                                    <span class="badge bg-primary px-3 py-2 rounded-pill">Dikirim</span>
                                @elseif($order->status == 'completed')
                                    <span class="badge bg-success px-3 py-2 rounded-pill">Selesai</span>
                                @else
                                    <span class="badge bg-secondary px-3 py-2 rounded-pill">{{ ucfirst($order->status) }}</span>
                                @endif
                            </td>
                            <td class="pe-4 text-end">
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-outline-primary rounded-3">
                                    Detail
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">Belum ada pesanan masuk.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- SCRIPT UNTUK CHART.JS --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const ctx = document.getElementById('salesChart').getContext('2d');
        
        // Data dari Controller Laravel
        const labels = {!! json_encode($chartLabels) !!};
        const dataValues = {!! json_encode($chartData) !!};

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Pendapatan (Rp)',
                    data: dataValues,
                    borderColor: '#013780',
                    backgroundColor: 'rgba(1, 55, 128, 0.1)',
                    borderWidth: 3,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#013780',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    fill: true,
                    tension: 0.4 // Membuat kurva melengkung (smooth)
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false // Sembunyikan tulisan legend di atas
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed.y !== null) {
                                    label += new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(context.parsed.y);
                                }
                                return label;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: '#f0f0f0',
                            drawBorder: false,
                        },
                        ticks: {
                            callback: function(value, index, values) {
                                // Format angka Y axis agar ringkas (contoh: 1Jt, 500Rb)
                                if (value >= 1000000) return value / 1000000 + 'Jt';
                                if (value >= 1000) return value / 1000 + 'Rb';
                                return value;
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: false,
                            drawBorder: false,
                        }
                    }
                }
            }
        });
    });
</script>
@endsection