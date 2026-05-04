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

    /* 3. Link Navbar - Efek Underline Hanya untuk Link Biasa */
    .store-navbar .nav-link {
        color: #013780 !important;
        font-weight: 600;
        font-size: 13px; 
        letter-spacing: 0.5px;
        text-transform: uppercase; 
        padding: 8px 15px !important;
        position: relative;
        transition: all 0.3s ease;
        border-radius: 8px; /* Untuk efek hover di dropdown */
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

    /* Efek Hover Khusus Dropdown (Glow halus, bukan garis) */
    .store-navbar .nav-item.dropdown .nav-link:hover {
        background-color: rgba(1, 55, 128, 0.05) !important;
        color: #013780 !important;
    }

    /* Dropdown Arrow */
    .store-navbar .dropdown-toggle::after {
        vertical-align: middle;
        margin-left: 5px;
        filter: invert(15%) sepia(61%) saturate(3505%) hue-rotate(200deg) brightness(92%) contrast(105%);
    }

    /* 4. Dropdown Menu - Dibuat Mewah */
    .dropdown-menu {
        border: none !important;
        box-shadow: 0 15px 35px rgba(0,0,0,0.1) !important;
        border-radius: 12px !important;
        padding: 10px !important;
        margin-top: 10px !important; /* Jarak dari navbar */
        border-top: 3px solid #FFD700 !important; /* Aksen emas di atas menu */
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
        padding-left: 20px !important; /* Efek geser dikit saat hover */
    }

    .dropdown-divider {
        border-top: 1px solid rgba(0, 0, 0, 0.05); /* Hitam dengan transparansi sangat tinggi */
        margin: 8px 10px; /* Memberi jarak kiri-kanan agar tidak mentok ke pinggir kotak */
        opacity: 1; /* Memastikan border-top yang mengontrol tampilannya */
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
            <span class="navbar-toggler-icon" style="filter: invert(15%) sepia(61%) saturate(3505%) hue-rotate(200deg) brightness(92%) contrast(105%);"></span>
        </button>

        <div class="collapse navbar-collapse" id="storeNavbar">
            {{-- Menu Tengah --}}
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-4 gap-lg-2">
                {{-- Dropdown Katalog Kembali ke Awal --}}
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="katalogDropdown" role="button" data-bs-toggle="dropdown">
                        Katalog
                    </a>
                    <ul class="dropdown-menu dropdown-menu-start shadow border-0 mt-2">
                        <li>
                            <a class="dropdown-item py-2 fw-bold text-primary" href="{{ route('products.index') }}">
                                LIHAT SEMUA PRODUK
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        
                        {{-- Loop Kategori (Mencatat pilihan ke checkbox array) --}}
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
            <ul class="navbar-nav align-items-lg-center gap-lg-3">
                @auth
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle store-nav-link" href="#" role="button" data-bs-toggle="dropdown">
                            {{ auth()->user()->display_name ?? auth()->user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 mt-2">
                            <li><a class="dropdown-item py-2" href="{{ route('profile.edit') }}">Profile</a></li>
                            <li><a class="dropdown-item py-2" href="{{ route('orders.index') }}">Pesanan</a></li>
                            @if(auth()->user()->role === 'admin')
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item py-2 fw-bold text-primary" href="{{ route('admin.dashboard') }}">Admin Panel</a></li>
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

                    <li class="nav-item">
                        <a class="nav-link position-relative px-2 {{ request()->routeIs('cart.index') ? 'active' : '' }}" 
                           href="{{ route('cart.index') }}">
                            <img src="{{ asset('assets/img/shopping-cart.png') }}" alt="Cart" style="height: 22px; width: auto; filter: invert(15%) sepia(61%) saturate(3505%) hue-rotate(200deg) brightness(92%) contrast(105%);">
                            @auth
                                @php
                                    $cartCount = \App\Models\CartItem::whereHas('cart', function($query) {
                                        $query->where('user_id', auth()->id());
                                    })->sum('quantity');
                                @endphp
                                @if($cartCount > 0)
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 9px; padding: 4px 6px; border: 2px solid white;">
                                        {{ $cartCount > 99 ? '99+' : $cartCount }}
                                    </span>
                                @endif
                            @endauth
                        </a>
                    </li>
                @else
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">Login</a>
                    </li>
                    <li class="nav-item ms-lg-2">
                        <a class="btn rounded-pill px-4" style="background-color: #013780; color: white; font-size: 12px; font-weight: 700;" href="{{ route('register') }}">Daftar</a>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>