@extends('layouts.store')

@section('content')
<div class="container py-4">
    {{-- Form pembungkus agar checkbox yang dipilih terkirim ke rute checkout --}}
    <form action="{{ route('checkout.index') }}" method="GET" id="checkoutForm">
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="fw-bold mb-0">Keranjang Belanja</h4>
                    <span class="text-muted" style="font-size: 0.9rem;">({{ $cart->items->count() }} Produk)</span>
                </div>

                {{-- Menggunakan forelse untuk menangani kondisi keranjang kosong --}}
                @forelse($cart->items as $item)
                    @if($loop->first) {{-- Bagian "Pilih Semua" hanya muncul sekali di awal jika ada barang --}}
                    <div class="card border-0 shadow-sm rounded-4 mb-3">
                        <div class="card-body py-3">
                            <div class="form-check d-flex align-items-center">
                                <input class="form-check-input" type="checkbox" id="selectAll" checked style="width: 1.2rem; height: 1.2rem;">
                                <label class="form-check-label fw-semibold ms-2" for="selectAll" style="cursor: pointer;">Pilih Semua</label>
                            </div>
                        </div>
                    </div>
                    @endif

                    <div class="card border-0 shadow-sm rounded-4 mb-3 cart-item-row" 
                         data-id="{{ $item->id }}" 
                         data-name="{{ $item->variant->product->name }}"
                         data-price="{{ $item->variant->price }}">
                        <div class="card-body">
                            <div class="d-flex align-items-start">
                                <div class="form-check mt-md-4 me-2 me-md-3">
                                    <input class="form-check-input item-checkbox" type="checkbox" name="selected_items[]" value="{{ $item->id }}" checked style="width: 1.2rem; height: 1.2rem;">
                                </div>

                                <a href="{{ route('products.show', $item->variant->product->slug) }}" class="text-decoration-none">
                                    <div class="rounded-3 border overflow-hidden me-3 img-hover-effect" style="width: 90px; height: 90px; flex-shrink: 0;">
                                        <img src="{{ $item->variant->product->image ? asset('storage/' . $item->variant->product->image) : asset('assets/img/no-image.png') }}" 
                                             class="w-100 h-100 object-fit-cover" alt="Produk">
                                    </div>
                                </a>

                                <div class="flex-grow-1">
                                    <a href="{{ route('products.show', $item->variant->product->slug) }}" class="text-decoration-none text-dark">
                                        <h6 class="fw-bold mb-1 hover-primary">{{ $item->variant->product->name }}</h6>
                                    </a>
                                    <div class="mb-2">
                                        <span class="badge bg-light text-secondary border fw-normal" style="font-size: 0.75rem;">
                                            Variasi: {{ $item->variant->name }}
                                        </span>
                                    </div>
                                    <p class="fw-bold text-primary mb-0">Rp {{ number_format($item->variant->price, 0, ',', '.') }}</p>
                                </div>

                                <div class="text-end d-flex flex-column align-items-end justify-content-between" style="min-height: 90px;">
                                    <button type="button" class="btn btn-link text-muted p-0 hover-danger" onclick="confirmDelete('{{ $item->id }}', '{{ $item->variant->product->name }}')">
                                        <i class="bi bi-trash fs-5"></i>
                                    </button>

                                    <div class="qty-wrapper text-center">
                                        <div class="input-group input-group-sm mb-1" style="width: 110px;">
                                            <button class="btn btn-outline-primary btn-minus rounded-start-pill" type="button">-</button>
                                            <input type="number" class="form-control text-center qty-input border-primary-subtle" 
                                                   value="{{ $item->quantity }}" 
                                                   min="1" 
                                                   max="{{ $item->variant->stock }}" readonly>
                                            <button class="btn btn-outline-primary btn-plus rounded-end-pill" type="button">+</button>
                                        </div>
                                        <small class="text-muted d-block" style="font-size: 0.7rem;">Stok: <b>{{ $item->variant->stock }}</b></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty {{-- Bagian ini muncul kalau keranjang kosong --}}
                    <div class="card border-0 shadow-sm rounded-4 py-5 text-center">
                        <div class="card-body">
                            <i class="bi bi-cart-x display-1 text-muted mb-3"></i>
                            <h5>Keranjangmu masih kosong</h5>
                            <a href="{{ route('products.index') }}" class="btn btn-primary mt-2 px-4 rounded-pill">Mulai Belanja</a>
                        </div>
                    </div>
                @endforelse
            </div>

            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 sticky-top" style="top: 100px; z-index: 10;">
                    <div class="card-body">
                        <h5 class="fw-bold mb-4">Ringkasan Belanja</h5>
                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-muted">Total Harga (<span id="selectedCount">0</span> barang)</span>
                            <span id="totalPriceDisplay" class="fw-bold">Rp 0</span>
                        </div>
                        <hr class="text-muted opacity-25">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <span class="fw-bold fs-5">Total Bayar</span>
                            <h4 class="fw-bold text-primary mb-0" id="grandTotalPrice">Rp 0</h4>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 py-3 rounded-pill fw-bold shadow-sm" id="checkoutBtn" disabled>
                            Beli (<span id="btnCount">0</span>)
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

{{-- Hidden Delete Forms --}}
@foreach($cart->items as $item)
<form id="delete-form-{{ $item->id }}" action="{{ route('cart.destroy', $item->id) }}" method="POST" style="display:none;">
    @csrf
    @method('DELETE')
</form>
@endforeach

<style>
    .hover-danger:hover { color: #dc3545 !important; }
    .hover-primary:hover { color: #013780 !important; }
    .img-hover-effect:hover { opacity: 0.8; transition: 0.2s; }
    .qty-input { background-color: #fff !important; font-weight: 600; }
</style>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const rows = document.querySelectorAll('.cart-item-row');
    const selectAll = document.getElementById('selectAll');
    const itemCheckboxes = document.querySelectorAll('.item-checkbox');
    const grandTotalPriceDisplay = document.getElementById('grandTotalPrice');
    const totalPriceDisplay = document.getElementById('totalPriceDisplay');
    const selectedCountDisplay = document.getElementById('selectedCount');
    const btnCountDisplay = document.getElementById('btnCount');
    const checkoutBtn = document.getElementById('checkoutBtn');

    function updateSummary() {
        let total = 0;
        let count = 0;
        rows.forEach(row => {
            const checkbox = row.querySelector('.item-checkbox');
            const qtyInput = row.querySelector('.qty-input');
            const price = parseInt(row.dataset.price);
            const qty = parseInt(qtyInput.value);
            if (checkbox && checkbox.checked) {
                total += price * qty;
                count += 1;
            }
        });
        const formattedTotal = 'Rp ' + total.toLocaleString('id-ID');
        if(grandTotalPriceDisplay) grandTotalPriceDisplay.innerText = formattedTotal;
        if(totalPriceDisplay) totalPriceDisplay.innerText = formattedTotal;
        if(selectedCountDisplay) selectedCountDisplay.innerText = count;
        if(btnCountDisplay) btnCountDisplay.innerText = count;
        if(checkoutBtn) checkoutBtn.disabled = count === 0;
    }

    function updateQty(itemId, newQty) {
        fetch(`/cart/${itemId}`, {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ quantity: newQty })
        });
    }

    window.confirmDelete = function(id, name) {
        Swal.fire({
            title: 'Hapus Barang?',
            text: `Apakah yakin menghapus "${name}" dari keranjang?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#013780',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Iya, Hapus!',
            cancelButtonText: 'Tidak'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(`delete-form-${id}`).submit();
            }
        });
    }

    rows.forEach(row => {
        const btnMinus = row.querySelector('.btn-minus');
        const btnPlus = row.querySelector('.btn-plus');
        const qtyInput = row.querySelector('.qty-input');
        const id = row.dataset.id;
        const name = row.dataset.name;
        const maxStock = parseInt(qtyInput.getAttribute('max'));

        btnPlus.addEventListener('click', () => {
            if (parseInt(qtyInput.value) < maxStock) {
                let val = parseInt(qtyInput.value) + 1;
                qtyInput.value = val;
                updateQty(id, val);
                updateSummary();
            }
        });

        {{-- Logika diperbaiki untuk memicu notifikasi hapus saat qty mencapai 1 --}}
        btnMinus.addEventListener('click', () => {
            let currentVal = parseInt(qtyInput.value);
            if (currentVal > 1) {
                let val = currentVal - 1;
                qtyInput.value = val;
                updateQty(id, val);
                updateSummary();
            } else if (currentVal === 1) {
                window.confirmDelete(id, name);
            }
        });
    });

    if(selectAll) {
        selectAll.addEventListener('change', function() {
            itemCheckboxes.forEach(cb => cb.checked = this.checked);
            updateSummary();
        });
    }

    itemCheckboxes.forEach(cb => {
        cb.addEventListener('change', () => {
            if(selectAll) selectAll.checked = Array.from(itemCheckboxes).every(c => c.checked);
            updateSummary();
        });
    });

    updateSummary();
});
</script>
@endpush
@endsection