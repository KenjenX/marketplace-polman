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
                       href="{{ route('admin.dashboard') }}">
                        Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.categories.*') ? 'fw-bold text-warning' : '' }}"
                       href="{{ route('admin.categories.index') }}">
                        Kategori
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.products.*') || request()->routeIs('admin.variants.*') ? 'fw-bold text-warning' : '' }}"
                       href="{{ route('admin.products.index') }}">
                        Produk
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.orders.*') ? 'fw-bold text-warning' : '' }}"
                       href="{{ route('admin.orders.index') }}">
                        Order
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.payment-methods.*') ? 'fw-bold text-warning' : '' }}"
                       href="{{ route('admin.payment-methods.index') }}">
                        Pembayaran
                    </a>
                </li>
            </ul>

            <div class="d-flex align-items-center gap-3">
                
                {{-- IKON NOTIFIKASI ADMIN --}}
                <div class="nav-item dropdown">
                    <a class="nav-link position-relative px-2" href="#" id="adminNotificationDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-bell fs-5 text-light"></i>
                        
                        @php 
                            // FILTER: Hanya hitung notifikasi yang 'for_admin' => true
                            $unreadCount = auth()->user()->unreadNotifications()
                                           ->where('data->for_admin', true)
                                           ->count(); 
                            
                            // Ambil list notifikasinya juga difilter
                            $adminNotifications = auth()->user()->notifications()
                                                  ->where('data->for_admin', true)
                                                  ->latest()
                                                  ->get();
                        @endphp

                        @if($unreadCount > 0)
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger notification-pulse" style="margin-top: 5px; margin-left: -5px;">
                                {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                            </span>
                        @endif
                    </a>
                    
                    <ul class="dropdown-menu dropdown-menu-end shadow-sm" aria-labelledby="adminNotificationDropdown" style="width: 320px; border-radius: 12px;">
                        <li class="dropdown-header d-flex justify-content-between align-items-center border-bottom mb-2 pb-2">
                            <span class="fw-bold text-dark">Notifikasi Pesanan</span>
                            @if($unreadCount > 0)
                                <form action="{{ route('notifications.markAllRead') }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-link btn-sm p-0 text-decoration-none small" style="font-size: 12px;">
                                        Tandai dibaca
                                    </button>
                                </form>
                            @endif
                        </li>
                        
                        <div class="notification-scroll" style="max-height: 350px; overflow-y: auto;">
                            @forelse($adminNotifications as $notification)
                                <li>
                                    <a class="dropdown-item d-flex align-items-start py-3 {{ $notification->read_at ? 'opacity-75' : 'bg-light-subtle fw-bold' }}" 
                                       href="{{ $notification->data['url'] ?? route('admin.orders.index') }}">
                                        <div class="icon-circle bg-{{ $notification->data['type'] ?? 'primary' }}-subtle text-{{ $notification->data['type'] ?? 'primary' }} me-3 rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                            <i class="bi {{ $notification->data['icon'] ?? 'bi-bag-check' }} fs-5"></i>
                                        </div>
                                        <div style="white-space: normal; flex: 1;">
                                            <p class="mb-0 small fw-bold">{{ $notification->data['title'] ?? 'Pesanan Baru!' }}</p>
                                            <small class="text-muted d-block" style="font-weight: 400; line-height: 1.4;">
                                                {{ $notification->data['message'] ?? 'Ada pesanan masuk yang menunggu konfirmasi Anda.' }}
                                            </small>
                                            <small class="text-primary mt-1 d-block" style="font-size: 10px;">
                                                {{ $notification->created_at->diffForHumans() }}
                                            </small>
                                        </div>
                                        @if(!$notification->read_at)
                                            <span class="ms-2 badge bg-primary rounded-circle p-1" style="width: 6px; height: 6px;"> </span>
                                        @endif
                                    </a>
                                </li>
                            @empty
                                <li class="p-4 text-center">
                                    <i class="bi bi-bell-slash text-muted fs-2 d-block mb-2"></i>
                                    <small class="text-muted">Belum ada pesanan baru</small>
                                </li>
                            @endforelse
                        </div>
                        
                        @if($adminNotifications->count() > 0)
                            <li><hr class="dropdown-divider m-0"></li>
                            <li>
                                <a class="dropdown-item text-center small text-primary fw-bold py-2" href="{{ route('admin.orders.index') }}">
                                    Lihat Semua Pesanan
                                </a>
                            </li>
                        @endif
                    </ul>
                </div>

                <a href="{{ route('home') }}" class="btn btn-outline-light btn-sm">Lihat Store</a>

                <div class="navbar-text text-light">
                    {{ auth()->user()->name ?? 'Admin' }}
                </div>

                <form method="POST" action="{{ route('logout') }}" class="m-0">
                    @csrf
                    <button type="submit" class="btn btn-light btn-sm">Logout</button>
                </form>
            </div>
        </div>
    </div>
</nav>