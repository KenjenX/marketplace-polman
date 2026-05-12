@extends('layouts.store')

@section('content')
<style>
    /* Konsistensi Tipografi & Warna Polman */
    .checkout-page { font-family: sans-serif; color: #2d3436; }
    .section-title { font-weight: 800; letter-spacing: -0.5px; color: #1a1a1a; margin-bottom: 1.5rem; }
    .label-custom { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; color: #adb5bd; margin-bottom: 6px; }
    
    /* Form Styling */
    .form-control-custom, .form-select-custom { 
        background-color: #f8f9fa !important; 
        border: none !important; 
        border-radius: 10px !important; 
        padding: 10px 15px !important; 
        font-size: 14px !important;
        font-weight: 600 !important;
    }
    .form-control-custom:focus, .form-select-custom:focus { background-color: #e9ecef !important; box-shadow: none !important; }

    /* Ringkasan Pesanan Card */
    .summary-card { border: 1px solid #f0f0f0; background: #fff; position: sticky; top: 100px; z-index: 10; }
    .product-thumb { width: 55px; height: 55px; object-fit: cover; border-radius: 8px; background: #f8f9fa; }
    
    /* Button Styling */
    .btn-polman-primary { background: #013780; color: #fff; border-radius: 50px; font-weight: 700; font-size: 13px; padding: 12px 30px; border: none; transition: 0.3s; }
    .btn-polman-primary:hover { background: #012a61; transform: translateY(-2px); color: #fff; }
    .btn-polman-light { background: #f8f9fa; color: #6c757d; border-radius: 50px; font-weight: 700; font-size: 13px; padding: 12px 25px; border: none; transition: 0.3s; }
    .btn-polman-light:hover { background: #e9ecef; color: #495057; }

    /* Utility */
    .rounded-4 { border-radius: 1.25rem !important; }
    .rounded-5 { border-radius: 2rem !important; }
</style>

<div class="container py-5 checkout-page">
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
                <h4 class="fw-bold mb-3">Informasi Pengiriman</h4>
                
                {{-- KOTAK INFORMASI ALAMAT --}}
                <div class="alert alert-info border-0 rounded-3 small mb-4" style="background-color: #e7f1ff; color: #013780;">
                    <i class="bi bi-info-circle-fill me-2"></i> 
                    Form ini otomatis terisi dengan alamat utama Anda. <strong>Mengubah alamat di sini hanya berlaku untuk pesanan ini</strong> dan tidak akan mengubah alamat default di profil Anda.
                </div>
                
                @if($errors->any())
                    <div class="alert alert-danger border-0 rounded-3">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('checkout.store') }}" method="POST">
                    @csrf
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="label-custom">Nama Penerima</label>
                            <input type="text" name="recipient_name" 
                                   value="{{ old('recipient_name', auth()->user()->default_recipient_name ?? auth()->user()->name ?? auth()->user()->company_name) }}"
                                   class="form-control form-control-custom" placeholder="Nama Lengkap" required>
                        </div>
                        <div class="col-md-6">
                            <label class="label-custom">No. HP (WhatsApp)</label>
                            <input type="text" name="phone" 
                                   value="{{ old('phone', auth()->user()->phone) }}"
                                   class="form-control form-control-custom" required>
                        </div>

                        {{-- PROVINSI --}}
                        <div class="col-md-4">
                            <label class="label-custom">Provinsi</label>
                            <select id="co_province_select" class="form-select form-select-custom shadow-none" required>
                                <option value="">Pilih Provinsi</option>
                            </select>
                            <input type="hidden" name="province" id="co_db_province_name" value="{{ old('province', auth()->user()->default_province) }}">
                            <input type="hidden" name="province_id" id="co_db_province_id" value="{{ old('province_id', auth()->user()->default_province_id) }}">
                        </div>

                        {{-- KOTA / KABUPATEN --}}
                        <div class="col-md-4">
                            <label class="label-custom">Kota / Kabupaten</label>
                            <select id="co_city_select" class="form-select form-select-custom shadow-none" required>
                                <option value="">Pilih Kota</option>
                            </select>
                            <input type="hidden" name="city" id="co_db_city_name" value="{{ old('city', auth()->user()->default_city) }}">
                            <input type="hidden" name="city_id" id="co_db_city_id" value="{{ old('city_id', auth()->user()->default_city_id) }}">
                        </div>

                        {{-- KECAMATAN --}}
                        <div class="col-md-4">
                            <label class="label-custom">Kecamatan</label>
                            <select id="co_district_select" class="form-select form-select-custom shadow-none" required>
                                <option value="">Pilih Kecamatan</option>
                            </select>
                            <input type="hidden" name="district" id="co_db_district_name" value="{{ old('district', auth()->user()->default_district) }}">
                            <input type="hidden" name="district_id" id="co_db_district_id" value="{{ old('district_id', auth()->user()->default_district_id) }}">
                        </div>

                        <div class="col-md-12">
                            <label class="label-custom">Kode Pos</label>
                            <input type="text" name="postal_code" 
                                   value="{{ old('postal_code', auth()->user()->default_postal_code) }}"
                                   class="form-control form-control-custom" placeholder="Kode Pos">
                        </div>

                        <div class="col-12">
                            <label class="label-custom">Alamat Lengkap</label>
                            <textarea name="full_address" class="form-control form-control-custom" rows="3" placeholder="Nama Jalan, No. Rumah, Patokan" required>{{ old('full_address', auth()->user()->default_full_address) }}</textarea>
                        </div>
                    </div>

                    <div class="border-top my-5"></div>

                    <h4 class="fw-bold mb-4">Opsi Pengiriman</h4>
                    <div class="mb-4">
                        <label class="label-custom">Pilih Kurir</label>
                        <select name="shipping_method" class="form-select form-select-custom shadow-none" id="shippingMethod" required>
                            <option value="">-- Pilih Jasa Pengiriman --</option>
                            <option value="jne" {{ old('shipping_method') == 'jne' ? 'selected' : '' }}>JNE (Reguler/YES)</option>
                            <option value="pos" {{ old('shipping_method') == 'pos' ? 'selected' : '' }}>POS Indonesia</option>
                            <option value="tiki" {{ old('shipping_method') == 'tiki' ? 'selected' : '' }}>TIKI</option>
                        </select>
                        <small class="text-muted mt-2 d-block">*Ongkir akan dihitung otomatis ke lokasi Anda.</small>
                    </div>

                    <hr class="my-4 opacity-25">

                    <h4 class="fw-bold mb-4">Pembayaran</h4>
                    
                    @if($paymentMethods->count() > 0)
                        <div class="mb-4">
                            <label class="label-custom">Metode Pembayaran</label>
                            <select name="payment_method_id" class="form-select form-select-custom shadow-none" required>
                                <option value="">-- Pilih Metode --</option>
                                @foreach($paymentMethods as $paymentMethod)
                                    <option value="{{ $paymentMethod->id }}" {{ old('payment_method_id') == $paymentMethod->id ? 'selected' : '' }}>
                                        {{ $paymentMethod->name }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted mt-2 d-block" style="font-size: 10px;">*Ongkir otomatis ditambahkan ke total bayar.</small>
                        </div>
                    @else
                        <div class="alert alert-warning py-2 small border-0 rounded-3 mb-4">Metode pembayaran belum tersedia.</div>
                    @endif

                    <div class="mb-4">
                        <label class="label-custom">Catatan Pesanan (Opsional)</label>
                        <textarea name="notes" class="form-control form-control-custom" rows="2" placeholder="Contoh: Titip di satpam atau varian cadangan">{{ old('notes') }}</textarea>
                    </div>

                    <div class="d-flex align-items-center gap-3 mt-5">
                        <a href="{{ route('cart.index') }}" class="btn-polman-light text-decoration-none text-center">KEMBALI</a>
                        <button type="submit" class="btn-polman-primary flex-grow-1 shadow-sm" {{ $paymentMethods->count() == 0 ? 'disabled' : '' }}>
                            {{ $paymentMethods->count() > 0 ? 'BUAT PESANAN SEKARANG' : 'ORDER BELUM TERSEDIA' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="summary-card rounded-5 shadow-sm p-4">
                <h5 class="section-title mb-4" style="font-size: 18px;">Ringkasan Pesanan</h5>

                <div class="cart-items-list pe-1" style="max-height: 300px; overflow-y: auto;">
                    @php $grandTotal = 0; @endphp
                    @foreach($cart->items as $item)
                        @php
                            $subtotal = $item->variant->price * $item->quantity;
                            $grandTotal += $subtotal;
                        @endphp
                        <div class="d-flex align-items-center mb-3">
                            <img src="{{ $item->variant->product->image ? asset('storage/' . $item->variant->product->image) : asset('assets/img/no-image.png') }}" 
                                 class="product-thumb border shadow-sm me-3">
                            <div class="overflow-hidden">
                                <div class="fw-bold text-truncate small" style="color: #1a1a1a;">{{ $item->variant->product->name }}</div>
                                <div class="text-muted" style="font-size: 11px;">{{ $item->variant->name }} (x{{ $item->quantity }})</div>
                                <div class="fw-bold text-primary small">Rp {{ number_format($subtotal, 0, ',', '.') }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="border-top my-4"></div>
                
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted small fw-bold">SUBTOTAL</span>
                    <span class="value-text">Rp {{ number_format($grandTotal, 0, ',', '.') }}</span>
                </div>
                <div class="d-flex justify-content-between mb-4">
                    <span class="text-muted">Ongkos Kirim</span>
                    <span class="fw-bold text-info italic" style="font-size: 0.85rem;">Dihitung saat checkout</span>
                </div>
                
                <div class="d-flex justify-content-between align-items-center border-top pt-3">
                    <h5 class="fw-bold mb-0">Total Belanja</h5>
                    <h4 class="fw-bold text-primary mb-0">Rp {{ number_format($grandTotal, 0, ',', '.') }}</h4>
                </div>
                <p class="text-muted small mt-2">*Belum termasuk ongkir.</p>
            </div>
        </div>
    </div>
</div>

<script>
    const navbar = document.querySelector('.navbar');
    if(navbar) navbar.style.zIndex = "1050";

    document.addEventListener('DOMContentLoaded', function () {
        const provSel = document.getElementById('co_province_select');
        const citySel = document.getElementById('co_city_select');
        const distSel = document.getElementById('co_district_select');

        // Ambil data default dari user yang login
        const initialProvName = "{{ old('province', auth()->user()->default_province) }}";
        const initialCityName = "{{ old('city', auth()->user()->default_city) }}";
        const initialDistName = "{{ old('district', auth()->user()->default_district) }}";

        // 1. Fetch Provinsi
        fetch('https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json')
            .then(res => res.json())
            .then(data => {
                provSel.innerHTML = '<option value="">Pilih Provinsi</option>';
                data.forEach(p => {
                    let opt = new Option(p.name, p.id);
                    if(p.name.toUpperCase() === String(initialProvName).toUpperCase()) opt.selected = true;
                    provSel.add(opt);
                });
                if(provSel.value) provSel.dispatchEvent(new Event('change'));
            });

        // 2. Provinsi Berubah -> Fetch Kota
        provSel.addEventListener('change', function() {
            const id = this.value;
            document.getElementById('co_db_province_id').value = id;
            document.getElementById('co_db_province_name').value = id ? this.options[this.selectedIndex].text : "";

            citySel.innerHTML = '<option value="">Memuat...</option>';
            distSel.innerHTML = '<option value="">Pilih Kecamatan</option>';

            if(id) {
                fetch(`https://www.emsifa.com/api-wilayah-indonesia/api/regencies/${id}.json`)
                    .then(res => res.json())
                    .then(data => {
                        citySel.innerHTML = '<option value="">Pilih Kota</option>';
                        data.forEach(c => {
                            let opt = new Option(c.name, c.id);
                            if(c.name.toUpperCase() === String(initialCityName).toUpperCase()) opt.selected = true;
                            citySel.add(opt);
                        });
                        if(citySel.value) citySel.dispatchEvent(new Event('change'));
                    });
            } else {
                citySel.innerHTML = '<option value="">Pilih Kota</option>';
                document.getElementById('co_db_city_id').value = "";
                document.getElementById('co_db_city_name').value = "";
            }
        });

        // 3. Kota Berubah -> Fetch Kecamatan
        citySel.addEventListener('change', function() {
            const id = this.value;
            document.getElementById('co_db_city_id').value = id;
            document.getElementById('co_db_city_name').value = id ? this.options[this.selectedIndex].text : "";

            distSel.innerHTML = '<option value="">Memuat...</option>';

            if(id) {
                fetch(`https://www.emsifa.com/api-wilayah-indonesia/api/districts/${id}.json`)
                    .then(res => res.json())
                    .then(data => {
                        distSel.innerHTML = '<option value="">Pilih Kecamatan</option>';
                        data.forEach(d => {
                            let opt = new Option(d.name, d.id);
                            if(d.name.toUpperCase() === String(initialDistName).toUpperCase()) opt.selected = true;
                            distSel.add(opt);
                        });
                    });
            } else {
                distSel.innerHTML = '<option value="">Pilih Kecamatan</option>';
                document.getElementById('co_db_district_id').value = "";
                document.getElementById('co_db_district_name').value = "";
            }
        });

        // 4. Kecamatan Berubah
        distSel.addEventListener('change', function() {
            const id = this.value;
            document.getElementById('co_db_district_id').value = id;
            document.getElementById('co_db_district_name').value = id ? this.options[this.selectedIndex].text : "";
        });
    });
</script>
@endsection