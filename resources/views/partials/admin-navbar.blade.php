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
            </ul>

            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('home') }}" class="btn btn-outline-light btn-sm">Lihat Store</a>

                <div class="navbar-text">
                    {{ auth()->user()->name ?? 'Admin' }}
                </div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-light btn-sm">Logout</button>
                </form>
            </div>
        </div>
    </div>
</nav>