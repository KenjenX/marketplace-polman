<style>
    /* 1. Base Navbar (Menggunakan warna Biru Navy dari kode kamu) */
    .store-navbar {
        background-color: #013780 !important;
        padding: 15px 0; /* Sedikit lebih tinggi agar lega */
        border-bottom: 3px solid #FFD700; /* Garis emas identitas Polman */
    }

    /* 2. Styling Logo & Brand */
    .navbar-logo {
        height: 35px; /* Sedikit dikecilkan agar proporsional */
        width: auto;
        object-fit: contain;
    }

    .store-brand {
        color: white !important;
        font-weight: 700;
        font-size: 1.2rem; /* Sedikit dikecilkan agar sejajar menu */
        letter-spacing: -0.3px;
        text-transform: uppercase;
    }

    /* 3. Link Navbar (Menggunakan Font Modern, Putih, Tanpa Opsitas saat Aktif) */
    .store-navbar .nav-link {
        color: white !important;
        font-weight: 500;
        font-size: 13px; /* Ukuran font menu lebih kecil, ala gambar 2 */
        letter-spacing: 0.5px;
        text-transform: uppercase; /* Agar rapi semua huruf kapital */
        padding-left: 15px !important;
        padding-right: 15px !important;
        opacity: 1 !important; /* Hapus efek redup agar jelas terbaca */
        transition: 0.3s;
    }

    /* Efek hover tipis (misal garis bawah emas) */
    .store-navbar .nav-link:hover {
        color: #FFD700 !important;
    }

    /* Dropdown Arrow (Invert warna agar putih) */
    .store-navbar .dropdown-toggle::after {
        filter: invert(1);
    }

    /* 4. Dropdown Menu (Tetap Putih agar terbaca) */
    .dropdown-menu .dropdown-item {
        color: #333 !important;
        background-color: white !important;
        font-size: 13px;
        text-transform: none; /* Dropdown tidak perlu kapital semua */
    }
</style>

<nav class="navbar navbar-expand-lg store-navbar sticky-top">
    <div class="container-fluid px-lg-5">
        {{-- Sisi Kiri: Logo & Brand --}}
        <a class="navbar-brand store-brand d-flex align-items-center" href="{{ route('home') }}">
            <img src="{{ asset('assets/img/logo-polman.png') }}" alt="Logo" class="navbar-logo">
            <span class="ms-3 d-none d-sm-inline">Marketplace Polman</span>
        </a>

        {{-- Toggler Mobile (Invert agar putih) --}}
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#storeNavbar">
            <span class="navbar-toggler-icon" style="filter: invert(1);"></span>
        </button>

        <div class="collapse navbar-collapse" id="storeNavbar">
            {{-- Menu Tengah: Posisi Menu Utama di Kiri (ala Gambar 2) --}}
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-4 gap-lg-2">
                {{-- Dropdown Katalog (Ganti 'Shop') --}}
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
                        
                        {{-- Loop Kategori Terbanyak --}}
                        @foreach($topCategories as $cat)
                            <li>
                                <a class="dropdown-item py-2 d-flex justify-content-between align-items-center" 
                                href="{{ route('products.index', ['category' => $cat->slug]) }}">
                                    {{ $cat->name }}
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
                    <a class="nav-link" href="{{ route('contact') }}">Hubungi Kami</a>
                </li>
            </ul>

            {{-- Menu Kanan: User Section (ala Gambar 2) --}}
            <ul class="navbar-nav align-items-lg-center gap-lg-3">
                @auth
                    {{-- User Dropdown (Ganti 'Account') --}}
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

                    {{-- Icon Keranjang Baru Menggunakan shopping-cart.png --}}
                    <li class="nav-item">
                        <a class="nav-link position-relative px-2 {{ request()->routeIs('cart.index') ? 'active' : '' }}" 
                        href="{{ route('cart.index') }}">
                            {{-- Menggunakan file gambar shopping-cart.png --}}
                            <img src="{{ asset('assets/img/shopping-cart.png') }}" alt="Cart" style="height: 22px; width: auto; filter: brightness(0) invert(1);">
                            
                            @auth
                                @php
                                    // Hitung total quantity dari semua item di keranjang user yang login
                                    $cartCount = \App\Models\CartItem::whereHas('cart', function($query) {
                                        $query->where('user_id', auth()->id());
                                    })->sum('quantity');
                                @endphp

                                @if($cartCount > 0)
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" 
                                        style="font-size: 9px; padding: 4px 6px; border: 2px solid #013780;">
                                        {{ $cartCount > 99 ? '99+' : $cartCount }}
                                    </span>
                                @endif
                            @endauth
                        </a>
                    </li>
                @else
                    {{-- Ganti 'Login' jadi tombol minimalis --}}
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">Login</a>
                    </li>
                    <li class="nav-item ms-lg-2">
                        {{-- Tombol Register dibuat menonjol tapi tetap minimalis --}}
                        <a class="btn btn-light rounded-pill px-4" style="font-size: 12px; font-weight: 700; color: #013780;" href="{{ route('register') }}">Daftar</a>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>