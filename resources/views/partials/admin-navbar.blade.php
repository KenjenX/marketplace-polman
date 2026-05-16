<nav class="navbar navbar-expand-lg admin-navbar sticky-top">
    <div class="container">
        <a class="navbar-brand fw-bold" href="{{ route('admin.dashboard') }}">
            Admin Marketplace Polman
        </a>

        <button class="navbar-toggler bg-light" type="button" data-bs-toggle="collapse" data-bs-target="#adminNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="adminNavbar">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'fw-bold text-warning' : '' }}"
                       href="{{ route('admin.dashboard') }}">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.categories.*') ? 'fw-bold text-warning' : '' }}"
                       href="{{ route('admin.categories.index') }}">Kategori</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.products.*') || request()->routeIs('admin.variants.*') ? 'fw-bold text-warning' : '' }}"
                       href="{{ route('admin.products.index') }}">Produk</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.orders.*') ? 'fw-bold text-warning' : '' }}"
                       href="{{ route('admin.orders.index') }}">Order</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.payment-methods.*') ? 'fw-bold text-warning' : '' }}"
                       href="{{ route('admin.payment-methods.index') }}">Pembayaran</a>
                </li>
            </ul>

            <div class="d-flex align-items-center gap-3">
                {{-- Improvisasi: Ikon Notifikasi Gantikan Tombol Lihat Store --}}
                <div class="dropdown">
                    <a href="#" class="text-white position-relative p-2" id="notificationDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-bell fa-lg"></i>
                        <span id="notification-badge" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger border border-2 border-primary d-none">
                            0
                        </span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 rounded-4 py-0 overflow-hidden" style="width: 320px;" aria-labelledby="notificationDropdown">
                        <li class="p-3 border-bottom bg-light d-flex justify-content-between align-items-center">
                            <h6 class="mb-0 fw-bold">Notifikasi Pesanan</h6>
                            <span class="badge bg-primary-subtle text-primary rounded-pill small" id="notification-count-text">0 Baru</span>
                        </li>
                        <div id="notification-list" style="max-height: 350px; overflow-y: auto;">
                            {{-- Placeholder loading --}}
                            <li class="p-4 text-center text-muted small">
                                <div class="spinner-border spinner-border-sm mb-2" role="status"></div>
                                <br>Mengecek pesanan...
                            </li>
                        </div>
                        <li class="p-2 border-top text-center bg-light">
                            <a href="{{ route('admin.orders.index') }}" class="text-primary small fw-bold text-decoration-none">Lihat Semua Order</a>
                        </li>
                    </ul>
                </div>

                <div class="navbar-text text-white fw-medium ms-2">
                    {{ auth()->user()->name ?? 'Admin' }}
                </div>

                <form method="POST" action="{{ route('logout') }}" class="m-0">
                    @csrf
                    <button type="submit" class="btn btn-light btn-sm rounded-pill px-3 fw-bold text-primary">Logout</button>
                </form>
            </div>
        </div>
    </div>
</nav>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const badge = document.getElementById('notification-badge');
        const countText = document.getElementById('notification-count-text');
        const list = document.getElementById('notification-list');

        function fetchNotifications() {
            fetch("{{ route('admin.notifications.api') }}")
                .then(response => response.json())
                .then(data => {
                    // 1. Update Badge
                    if (data.count > 0) {
                        badge.innerText = data.count;
                        badge.classList.remove('d-none');
                        countText.innerText = `${data.count} Baru`;
                    } else {
                        badge.classList.add('d-none');
                        countText.innerText = `0 Baru`;
                    }

                    // 2. Update List
                    if (data.orders && data.orders.length > 0) {
                        list.innerHTML = data.orders.map(order => {
                            // Pastikan UUID ada sebelum generate link
                            const detailUrl = order.uuid ? `/admin/orders/${order.uuid}` : '#';

                            // Beri warna ikon berbeda: Hijau untuk otomatis (processing), Kuning untuk manual (waiting)
                            const iconClass = order.status === 'processing' ? 'text-success' : 'text-warning';
                            const bgClass = order.status === 'processing' ? 'bg-success-subtle' : 'bg-warning-subtle';
                            const statusLabel = order.status === 'processing' ? 'Otomatis (Xendit)' : 'Perlu Validasi';
                            
                            return `
                                <li>
                                    <a class="dropdown-item p-3 border-bottom d-flex align-items-start gap-3" href="${detailUrl}">
                                        <div class="bg-primary-subtle p-2 rounded-circle text-primary mt-1">
                                            <i class="fas fa-shopping-bag"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <p class="mb-0 small fw-bold text-dark">${order.customer || 'Pembeli'}</p>
                                                <small class="text-primary" style="font-size: 0.7rem;">${order.time}</small>
                                            </div>
                                            <p class="mb-0 text-muted small text-truncate" style="max-width: 180px;">
                                                Checkout: ${order.code}
                                            </p>
                                        </div>
                                    </a>
                                </li>
                            `;
                        }).join('');
                    } else {
                        list.innerHTML = `<li class="p-4 text-center text-muted small">Tidak ada pesanan menunggu validasi</li>`;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    list.innerHTML = `<li class="p-3 text-center text-danger small">Gagal memuat data</li>`;
                });
        }

        fetchNotifications();
        setInterval(fetchNotifications, 30000);
    });
</script>
@endpush