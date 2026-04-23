@extends('layouts.admin')

@section('content')
<div class="admin-card">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">Dashboard Admin</h2>
            <p class="text-muted mb-0">Ringkasan data Marketplace Polman.</p>
        </div>
        <a href="{{ route('home') }}" class="btn btn-outline-primary">Lihat Store</a>
    </div>

    <div class="row g-4">
        <div class="col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100 rounded-4">
                <div class="card-body">
                    <div class="text-muted small mb-2">Total Kategori</div>
                    <h3 class="mb-0">{{ $categoryCount }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100 rounded-4">
                <div class="card-body">
                    <div class="text-muted small mb-2">Total Produk</div>
                    <h3 class="mb-0">{{ $productCount }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100 rounded-4">
                <div class="card-body">
                    <div class="text-muted small mb-2">Total Order</div>
                    <h3 class="mb-0">{{ $orderCount }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100 rounded-4">
                <div class="card-body">
                    <div class="text-muted small mb-2">Menunggu Validasi</div>
                    <h3 class="mb-0">{{ $waitingValidationCount }}</h3>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection