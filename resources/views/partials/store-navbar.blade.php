<style>
    /* 1. Base Navbar (Background Putih Transparan) */
    .store-navbar {
        background-color: rgba(255, 255, 255, 0.95) !important;
        backdrop-filter: blur(8px);
        padding: 15px 0;
        border-bottom: 2px solid #f0f0f0;
    }

    /* 2. Styling Logo & Brand */
    .navbar-logo {
        height: 35px;
        width: auto;
        object-fit: contain;
    }

    .store-brand {
        color: #013780 !important;
        font-weight: 700;
        font-size: 1.2rem;
        letter-spacing: -0.3px;
        text-transform: uppercase;
    }

    /* 3. Link Navbar */
    .store-navbar .nav-link {
        color: #013780 !important;
        font-weight: 600;
        font-size: 13px;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        padding: 8px 15px !important;
        position: relative;
        transition: all 0.3s ease;
        border-radius: 8px;
    }

    /* Garis bawah emas hanya untuk yang BUKAN dropdown */
    .store-navbar .nav-item:not(.dropdown) .nav-link::after {
        content: '';
        position: absolute;
        width: 0;
        height: 2px;
        bottom: 5px;
        left: 15px;
        background-color: #FFD700;
        transition: width 0.3s ease;
    }

    .store-navbar .nav-item:not(.dropdown) .nav-link:hover::after {
        width: calc(100% - 30px);
    }

    /* Efek Hover Khusus Dropdown User & Notifikasi */
    .store-navbar .nav-item.dropdown .nav-link:hover,
    .notification-bell-link:hover {
        background-color: rgba(1, 55, 128, 0.05) !important;
        color: #013780 !important;
    }

    /* 4. Dropdown Menu Utama (Katalog & User) */
    .dropdown-menu {
        border: none !important;
        box-shadow: 0 15px 35px rgba(0,0,0,0.1) !important;
        border-radius: 12px !important;
        padding: 10px !important;
        margin-top: 10px !important;
        border-top: 3px solid #FFD700 !important;
    }

    .dropdown-menu .dropdown-item {
        color: #013780 !important;
        background-color: white !important;
        font-size: 13px;
        border-radius: 8px;
        padding: 10px 15px !important;
        transition: all 0.2s ease;
        font-weight: 500;
    }
    
    .dropdown-menu .dropdown-item:hover {
        background-color: #f0f7ff !important;
        color: #013780 !important;
        padding-left: 20px !important;
    }

    /* 5. NOTIFICATION SPECIFIC STYLES */
    .notification-pulse {
        font-size: 9px;
        padding: 4px 6px;
        border: 2px solid white;
        animation: pulse-red 2s infinite;
    }

    @keyframes pulse-red {
        0% { box-shadow: 0 0 0 0 rgba(220, 53, 69, 0.7); }
        70% { box-shadow: 0 0 0 10px rgba(220, 53, 69, 0); }
        100% { box-shadow: 0 0 0 0 rgba(220, 53, 69, 0); }
    }

    .notification-dropdown-menu {
        width: 350px !important; /* Lebar ditambah sedikit agar teks tidak terlalu sesak */
    }

    .icon-circle {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    /* Helper for Filter Icon Cart agar konsisten dengan bell */
    .icon-filter-navy {
        filter: invert(15%) sepia(61%) saturate(3505%) hue-rotate(200deg) brightness(92%) contrast(105%);
    }

    /* Menghilangkan panah dropdown pada notifikasi */
    .no-caret::after {
        display: none !important;
    }

    /* Scrollbar halus untuk dropdown notifikasi */
    .notification-scroll::-webkit-scrollbar {
        width: 4px;
    }
    .notification-scroll::-webkit-scrollbar-track {
        background: #f1f1f1;
    }
    .notification-scroll::-webkit-scrollbar-thumb {
        background: #013780;
        border-radius: 10px;
    }
</style>

<nav class="navbar navbar-expand-lg store-navbar sticky-top">
    <div class="container-fluid px-lg-5">
        {{-- Sisi Kiri: Logo & Brand --}}
        <a class="navbar-brand store-brand d-flex align-items-center" href="{{ route('home') }}">
            <img src="{{ asset('assets/img/logo-polman.png') }}" alt="Logo" class="navbar-logo">
            <span class="ms-3 d-none d-sm-inline">Marketplace Polman</span>
        </a>

        {{-- Toggler Mobile --}}
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#storeNavbar">
            <span class="navbar-toggler-icon icon-filter-navy"></span>
        </button>

        <div class="collapse navbar-collapse" id="storeNavbar">
            {{-- Menu Tengah --}}
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-4 gap-lg-2">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="katalogDropdown" role="button" data-bs-toggle="dropdown">
                        Katalog
                    </a>
                    <ul class="dropdown-menu dropdown-menu-start">
                        <li>
                            <a class="dropdown-item py-2 fw-bold text-primary text-uppercase" href="{{ route('products.index') }}">
                                Lihat Semua Produk
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        @foreach($topCategories as $cat)
                            <li>
                                <a class="dropdown-item py-2 d-flex justify-content-between align-items-center"
                                   href="{{ route('products.index', ['categories' => [$cat->slug]]) }}">
                                   <span>{{ $cat->name }}</span>
                                   <span class="badge rounded-pill bg-light text-muted border ms-2" style="font-size: 10px;">
                                       {{ $cat->products_count }}
                                   </span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('about') }}">About Us</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('contact') }}">Contact Us</a>
                </li>
            </ul>

            {{-- Menu Kanan: User Section --}}
            <ul class="navbar-nav align-items-lg-center gap-lg-1">
                @auth
                    {{-- 1. Ikon Cart --}}
                    <li class="nav-item">
                        <a class="nav-link position-relative px-3 {{ request()->routeIs('cart.index') ? 'active' : '' }}" href="{{ route('cart.index') }}">
                            <img src="{{ asset('assets/img/shopping-cart.png') }}" alt="Cart" class="icon-filter-navy" style="height: 22px; width: auto;">
                            @php
                                $cartCount = \App\Models\CartItem::whereHas('cart', function($query) {
                                    $query->where('user_id', auth()->id());
                                })->sum('quantity');
                            @endphp
                            @if($cartCount > 0)
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 9px; padding: 4px 6px; border: 2px solid white; margin-top: 5px; margin-left: -5px;">
                                    {{ $cartCount > 99 ? '99+' : $cartCount }}
                                </span>
                            @endif
                        </a>
                    </li>

                    {{-- 2. Ikon Notifikasi (Bell Dinamis) --}}
                    <li class="nav-item dropdown">
                        <a class="nav-link position-relative px-3 no-caret" href="#" id="notificationDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-bell fs-5" style="color: #013780;"></i>
                            @php $unreadCount = auth()->user()->unreadNotifications->count(); @endphp
                            @if($unreadCount > 0)
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger notification-pulse" style="margin-top: 5px; margin-left: -5px;">
                                    {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                                </span>
                            @endif
                        </a>
                        
                        <ul class="dropdown-menu dropdown-menu-end notification-dropdown-menu" aria-labelledby="notificationDropdown">
                            <li class="dropdown-header d-flex justify-content-between align-items-center border-bottom mb-2 pb-2">
                                <span class="fw-bold text-dark">Notifikasi</span>
                                @if($unreadCount > 0)
                                    <form action="{{ route('notifications.markAllRead') }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-link btn-sm p-0 text-decoration-none small" style="font-size: 12px; vertical-align: baseline;">
                                            Tandai dibaca
                                        </button>
                                    </form>
                                @endif
                            </li>
                            
                            <div class="notification-scroll" style="max-height: 350px; overflow-y: auto;">
                                @forelse(auth()->user()->notifications as $notification)
                                    <li>
                                        <a class="dropdown-item d-flex align-items-start py-3 {{ $notification->read_at ? 'opacity-75' : 'bg-light-subtle fw-bold' }}"
                                           href="{{ $notification->data['url'] ?? '#' }}">
                                            <div class="icon-circle bg-{{ $notification->data['type'] ?? 'primary' }}-subtle text-{{ $notification->data['type'] ?? 'primary' }} me-3">
                                                <i class="bi {{ $notification->data['icon'] ?? 'bi-bell' }} fs-5"></i>
                                            </div>
                                            <div style="white-space: normal; flex: 1;">
                                                <p class="mb-0 small">{{ $notification->data['title'] }}</p>
                                                <small class="text-muted d-block" style="font-weight: 400; line-height: 1.4;">
                                                    {{ $notification->data['message'] }}
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
                                    <li class="p-5 text-center">
                                        <i class="bi bi-bell-slash text-muted fs-1 d-block mb-2"></i>
                                        <small class="text-muted">Tidak ada notifikasi untuk Anda</small>
                                    </li>
                                @endforelse
                            </div>
                            
                            @if(auth()->user()->notifications->count() > 0)
                                <li><hr class="dropdown-divider m-0"></li>
                                <li>
                                    <a class="dropdown-item text-center small text-primary fw-bold py-2" href="{{ route('orders.index') }}">
                                        Cek Riwayat Pesanan
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>

                    {{-- 3. Dropdown User --}}
                    <li class="nav-item dropdown ms-lg-2">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            {{ auth()->user()->display_name ?? auth()->user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item py-2" href="{{ route('profile.edit') }}">Profile</a></li>
                            <li><a class="dropdown-item py-2" href="{{ route('orders.index') }}">Pesanan Saya</a></li>
                            @if(auth()->check() && trim(auth()->user()->role) === 'admin')
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item py-2 fw-bold text-primary text-uppercase" href="{{ route('admin.dashboard') }}">Admin Panel</a></li>
                            @endif
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item py-2 text-danger">Logout</button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @else
                    {{-- Guest Links --}}
                    <li class="nav-item">
                        <a class="nav-link text-uppercase" href="{{ route('login') }}">Login</a>
                    </li>
                    <li class="nav-item ms-lg-2">
                        <a class="btn rounded-pill px-4" style="background-color: #013780; color: white; font-size: 12px; font-weight: 700; text-transform: uppercase;" href="{{ route('register') }}">Daftar</a>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>