@extends('layouts.store')

@section('content')
<div class="row g-4">
    <div class="col-lg-3">
        <div class="content-card mb-4">
            <h5 class="mb-3">Cari Produk</h5>

            <form method="GET" action="{{ route('products.index') }}">
                <div class="input-group mb-3">
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        class="form-control"
                        placeholder="Search products..."
                    >
                    @if(request('category'))
                        <input type="hidden" name="category" value="{{ request('category') }}">
                    @endif
                    <button class="btn btn-primary" type="submit">Cari</button>
                </div>
            </form>
        </div>

        <div class="content-card mb-4">
            <h5 class="mb-3">Categories</h5>

            <div class="list-group list-group-flush">
                <a
                    href="{{ route('products.index', ['search' => request('search')]) }}"
                    class="list-group-item list-group-item-action d-flex justify-content-between align-items-center border-0 px-0 {{ request('category') ? '' : 'fw-bold text-primary' }}"
                >
                    Semua Produk
                    <span class="text-muted">{{ $categories->sum('products_count') }}</span>
                </a>

                @foreach($categories as $category)
                    <a
                        href="{{ route('products.index', ['category' => $category->id, 'search' => request('search')]) }}"
                        class="list-group-item list-group-item-action d-flex justify-content-between align-items-center border-0 px-0 {{ request('category') == $category->id ? 'fw-bold text-primary' : '' }}"
                    >
                        {{ $category->name }}
                        <span class="text-muted">{{ $category->products_count }}</span>
                    </a>
                @endforeach
            </div>
        </div>

        <div class="content-card">
            <h5 class="mb-3">Produk Terbaru</h5>

            @forelse($sidebarProducts as $sidebarProduct)
                <div class="d-flex gap-3 mb-3 pb-3 border-bottom">
                    <div class="bg-light border rounded-3 d-flex align-items-center justify-content-center" style="width:72px; height:72px; flex-shrink:0;">
                        <span class="text-muted small">IMG</span>
                    </div>

                    <div>
                        <div class="fw-semibold small mb-1">{{ $sidebarProduct->name }}</div>
                        <div class="text-muted small mb-1">{{ $sidebarProduct->category->name }}</div>
                        <div class="small fw-semibold">
                            @if($sidebarProduct->variants->count() > 0)
                                Rp {{ number_format($sidebarProduct->variants->min('price'), 0, ',', '.') }}
                            @else
                                -
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-muted">Belum ada produk.</div>
            @endforelse
        </div>
    </div>

    <div class="col-lg-9">
        <div class="content-card">
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-4">
                <div>
                    <div class="text-muted small mb-1">
                        Home / {{ $selectedCategory ? $selectedCategory->name : 'Store' }}
                    </div>

                    <h2 class="mb-1">
                        {{ $selectedCategory ? $selectedCategory->name : 'Store' }}
                    </h2>

                    <p class="text-muted mb-0">
                        @if(request('search'))
                            Hasil pencarian untuk: <strong>{{ request('search') }}</strong>
                        @else
                            Menampilkan produk yang tersedia di marketplace.
                        @endif
                    </p>
                </div>

                <div class="text-muted">
                    Menampilkan {{ $products->count() }} produk
                </div>
            </div>

            <div class="row g-4">
                @forelse($products as $product)
                    <div class="col-md-6 col-xl-4">
                        <div class="card h-100 border-0 shadow-sm rounded-4">
                            <div class="card-body d-flex flex-column">
                                <div class="bg-light border rounded-4 d-flex align-items-center justify-content-center mb-3" style="height: 180px;">
                                    <span class="text-muted small">Preview Produk</span>
                                </div>

                                <div class="mb-2">
                                    <span class="badge bg-light text-dark border">{{ $product->category->name }}</span>
                                </div>

                                <h5 class="card-title">{{ $product->name }}</h5>

                                <p class="card-text text-muted small">
                                    {{ \Illuminate\Support\Str::limit($product->description, 90) }}
                                </p>

                                <div class="mb-3">
                                    <div class="fw-semibold">
                                        @if($product->variants->count() > 0)
                                            Rp {{ number_format($product->variants->min('price'), 0, ',', '.') }}
                                            @if($product->variants->count() > 1)
                                                <span class="text-muted small">- mulai dari</span>
                                            @endif
                                        @else
                                            -
                                        @endif
                                    </div>
                                </div>

                                @if($product->variants->count() > 0)
                                    <div class="mb-3 d-flex flex-wrap gap-2">
                                        @foreach($product->variants->take(3) as $variant)
                                            <span class="badge bg-primary-subtle text-primary border">
                                                {{ $variant->name }}
                                            </span>
                                        @endforeach
                                    </div>
                                @endif

                                <div class="mt-auto">
                                    <a href="{{ route('products.show', $product->slug) }}" class="btn btn-primary w-100">
                                        Lihat Detail
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-secondary mb-0">
                            Produk tidak ditemukan.
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection