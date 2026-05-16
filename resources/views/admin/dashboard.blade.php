@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    {{-- Header Section --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">Dashboard Admin</h2>
            <p class="text-muted mb-0">Ringkasan data Marketplace Polman.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.export.csv') }}" class="btn btn-dark shadow-sm">
                <i class="fas fa-file-csv me-1"></i> Export ke CSV
            </a>
            <a href="{{ route('home') }}" class="btn btn-outline-primary shadow-sm">Lihat Store</a>
        </div>
    </div>

    {{-- Statistik Cards Section --}}
    <div class="row g-4 mb-4">
        <div class="col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100 rounded-4">
                <div class="card-body">
                    <div class="text-muted small mb-2">Total Kategori</div>
                    <h3 class="fw-bold mb-0">{{ $categoryCount }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100 rounded-4">
                <div class="card-body">
                    <div class="text-muted small mb-2">Total Produk</div>
                    <h3 class="fw-bold mb-0">{{ $productCount }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100 rounded-4">
                <div class="card-body">
                    <div class="text-muted small mb-2">Total Order</div>
                    <h3 class="fw-bold mb-0">{{ $orderCount }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100 rounded-4">
                <div class="card-body">
                    <div class="text-muted small mb-2">Menunggu Validasi</div>
                    <h3 class="fw-bold mb-0 text-danger">{{ $waitingValidationCount }}</h3>
                </div>
            </div>
        </div>
    </div>

    {{-- Grafik Penjualan Section --}}
    <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="fw-bold text-dark mb-0">Tren Pendapatan</h5>
            
            <div class="dropdown">
                <button class="btn btn-outline-primary dropdown-toggle rounded-pill px-3" type="button" id="filterDropdown" data-bs-toggle="dropdown">
                    <i class="fas fa-filter me-1"></i>
                    {{ $currentFilter == 'week' ? 'Data Mingguan' : ($currentFilter == 'year' ? 'Data Tahunan' : 'Data Bulanan') }}
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
                    <li><a class="dropdown-item" href="{{ route('admin.dashboard', ['filter' => 'week']) }}">Data Mingguan</a></li>
                    <li><a class="dropdown-item" href="{{ route('admin.dashboard', ['filter' => 'month']) }}">Data Bulanan</a></li>
                    <li><a class="dropdown-item" href="{{ route('admin.dashboard', ['filter' => 'year']) }}">Data Tahunan</a></li>
                </ul>
            </div>
        </div>
        
        <div style="height: 350px;">
            <canvas id="salesChart"></canvas>
        </div>
    </div>

    {{-- Recent Orders Table Section --}}
    <div class="card border-0 shadow-sm rounded-4 p-4">
        <h5 class="fw-bold text-dark mb-4">Order Terbaru</h5>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th scope="col">Kode Order</th>
                        <th scope="col">Nama Pembeli</th>
                        <th scope="col">Total Harga</th>
                        <th scope="col">Status</th>
                        <th scope="col">Tanggal Order</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentOrders as $order)
                    <tr>
                        <td class="fw-bold">
                            <a href="{{ route('admin.orders.show', $order->uuid) }}" class="text-primary text-decoration-none">
                                {{ $order->order_code ?? ('ORD-'.$order->id) }}
                            </a>
                        </td>
                        <td>{{ $order->user->name ?? 'User Tidak Ditemukan' }}</td>
                        <td>Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                        <td>
                            @if($order->status === 'waiting-validation')
                                <span class="badge bg-warning text-dark">Menunggu Validasi</span>
                            @elseif($order->status === 'rejected')
                                <span class="badge bg-danger">Ditolak</span>
                            @elseif($order->status === 'completed')
                                <span class="badge bg-success">Selesai</span>
                            @else
                                <span class="badge bg-secondary">{{ ucfirst($order->status) }}</span>
                            @endif
                        </td>
                        <td>{{ $order->created_at->format('d M Y, H:i') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const ctx = document.getElementById('salesChart').getContext('2d');
        
        const gradient = ctx.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, 'rgba(13, 59, 102, 0.2)');
        gradient.addColorStop(1, 'rgba(13, 59, 102, 0.0)');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($labels),
                datasets: [{
                    label: 'Pendapatan (Rp)',
                    data: @json($chartData),
                    borderColor: '#0d3b66',
                    backgroundColor: gradient,
                    tension: 0.4,
                    fill: true,
                    pointRadius: 4,
                    borderWidth: 3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return 'Rp ' + new Intl.NumberFormat('id-ID').format(context.parsed.y);
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + value.toLocaleString('id-ID');
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endpush