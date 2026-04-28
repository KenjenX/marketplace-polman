<nav class="navbar navbar-expand-lg store-navbar sticky-top">
    <div class="container">
        <a class="navbar-brand store-brand" href="{{ route('home') }}">
            Marketplace Polman
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#storeNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="storeNavbar">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link store-nav-link {{ request()->routeIs('home') ? 'active fw-bold text-primary' : '' }}"
                       href="{{ route('home') }}">
                        Home
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link store-nav-link {{ request()->routeIs('products.*') ? 'active fw-bold text-primary' : '' }}"
                       href="{{ route('products.index') }}">
                        Semua Produk
                    </a>
                </li>
            </ul>

            <ul class="navbar-nav align-items-lg-center gap-lg-2">
                <li class="nav-item">
                    <a class="nav-link store-nav-link {{ request()->routeIs('about') ? 'active fw-bold text-primary' : '' }}"
                       href="{{ route('about') }}">
                        About Us
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link store-nav-link {{ request()->routeIs('contact') ? 'active fw-bold text-primary' : '' }}"
                       href="{{ route('contact') }}">
                        Hubungi Kami
                    </a>
                </li>

                @auth
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle store-nav-link" href="#" role="button" data-bs-toggle="dropdown">
                            {{ auth()->user()->display_name ?? auth()->user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 rounded-3">
                            <li>
                                <a class="dropdown-item" href="{{ route('profile.edit') }}">Profile</a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('orders.index') }}">Pesanan</a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('cart.index') }}">Keranjang</a>
                            </li>

                            @if(auth()->user()->role === 'admin')
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('admin.dashboard') }}">Admin Panel</a>
                                </li>
                            @endif

                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item">Logout</button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @else
                    <li class="nav-item">
                        <a class="nav-link store-nav-link {{ request()->routeIs('login') ? 'active fw-bold text-primary' : '' }}"
                           href="{{ route('login') }}">
                            Login
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-primary rounded-pill px-3" href="{{ route('register') }}">Register</a>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>