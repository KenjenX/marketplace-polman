@extends('layouts.store')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-11">
            <div class="row g-4">
                {{-- Sidebar Profil --}}
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm rounded-0 mb-4">
                        <div class="card-body text-center py-5">
                            <h4 class="fw-bold mb-1">{{ auth()->user()->name ?? auth()->user()->company_name }}</h4>
                            <span class="badge rounded-0 bg-primary px-3 py-2 text-uppercase mb-3" style="font-size: 10px; letter-spacing: 1px;">
                                {{ $user->account_type === 'company' ? 'Akun Perusahaan' : 'Akun Individu' }}
                            </span>
                            <p class="text-muted small mb-0">{{ auth()->user()->email }}</p>
                        </div>
                        <div class="list-group list-group-flush border-top">
                            <a href="#info-profil" class="list-group-item list-group-item-action border-0 py-3 active fw-bold" data-bs-toggle="list">
                                <i class="bi bi-person-gear me-2"></i> Informasi Akun
                            </a>
                            <a href="#alamat-default" class="list-group-item list-group-item-action border-0 py-3 fw-bold" data-bs-toggle="list">
                                <i class="bi bi-geo-alt me-2"></i> Alamat Default
                            </a>
                            <a href="#keamanan" class="list-group-item list-group-item-action border-0 py-3 fw-bold" data-bs-toggle="list">
                                <i class="bi bi-shield-lock me-2"></i> Keamanan
                            </a>
                            <a href="{{ route('orders.index') }}" class="list-group-item list-group-item-action border-0 py-3 fw-bold text-primary">
                                <i class="bi bi-bag-check me-2"></i> Riwayat Pesanan
                            </a>
                        </div>
                    </div>

                    <div class="card border-danger border-opacity-25 shadow-sm rounded-0">
                        <div class="card-body p-4">
                            <h6 class="fw-bold text-danger mb-3">Zona Berbahaya</h6>
                            <p class="text-muted small mb-4">Setelah akun dihapus, semua resource dan data akun akan ikut terhapus permanen.</p>
                            <button class="btn btn-outline-danger w-100 rounded-0 fw-bold shadow-none" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
                                Hapus Akun
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Konten Utama Form --}}
                <div class="col-md-8">
                    <div class="tab-content">
                        {{-- Tab 1: Informasi Profil --}}
                        <div class="tab-pane fade show active" id="info-profil">
                            <div class="card border-0 shadow-sm rounded-0 p-4 p-md-5">
                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <h4 class="fw-bold mb-0">Informasi Profil</h4>
                                </div>
                                
                                <form method="POST" action="{{ route('profile.update') }}">
                                    @csrf
                                    @method('PATCH')

                                    <div class="row g-3">
                                        @if($user->account_type === 'company')
                                            <div class="col-12">
                                                <label class="form-label small fw-bold text-muted">Nama Perusahaan</label>
                                                <input type="text" name="company_name" value="{{ old('company_name', $user->company_name) }}" class="form-control bg-light border-0 py-2">
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label small fw-bold text-muted">Nama PIC / Contact Person</label>
                                                <input type="text" name="contact_person" value="{{ old('contact_person', $user->contact_person) }}" class="form-control bg-light border-0 py-2">
                                            </div>
                                        @else
                                            <div class="col-12">
                                                <label class="form-label small fw-bold text-muted">Nama Lengkap</label>
                                                <input type="text" name="name" value="{{ old('name', $user->name) }}" class="form-control bg-light border-0 py-2">
                                            </div>
                                        @endif

                                        <div class="col-md-6">
                                            <label class="form-label small fw-bold text-muted">Nomor HP</label>
                                            <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="form-control bg-light border-0 py-2">
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label small fw-bold text-muted">Email</label>
                                            <input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-control bg-light border-0 py-2">
                                        </div>
                                    </div>
                                    <div class="mt-5 text-end">
                                        <button type="submit" class="btn btn-primary px-5 fw-bold rounded-0 shadow-sm">Simpan Perubahan</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        {{-- Tab 2: Alamat Default --}}
                        <div class="tab-pane fade" id="alamat-default">
                            <div class="card border-0 shadow-sm rounded-0 p-4 p-md-5">
                                <h4 class="fw-bold mb-4">Alamat Default Checkout</h4>

                                <form method="POST" action="{{ route('profile.address.update') }}">
                                    @csrf

                                    <div class="row g-3">
                                        {{-- NAMA PENERIMA DEFAULT --}}
                                        <div class="col-12">
                                            <label class="form-label small fw-bold text-muted">Nama Penerima Default <span class="text-danger">*</span></label>
                                            <input type="text" name="default_recipient_name"
                                                value="{{ old('default_recipient_name', $user->default_recipient_name ?? ($user->account_type === 'company' ? $user->company_name : $user->name)) }}"
                                                class="form-control bg-light border-0 py-2 @error('default_recipient_name') is-invalid @enderror"
                                                placeholder="Masukkan nama penerima">
                                            
                                            @error('default_recipient_name')
                                                <div class="text-danger small mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        {{-- Provinsi --}}
                                        <div class="col-md-6">
                                            <label class="form-label small fw-bold text-muted">Provinsi</label>
                                            <select id="main_province_select" class="form-select bg-light border-0 py-2 shadow-none">
                                                <option value="">Pilih Provinsi</option>
                                            </select>
                                            <input type="hidden" name="default_province" id="main_db_province_name" value="{{ old('default_province', $user->default_province) }}">
                                            <input type="hidden" name="default_province_id" id="main_db_province_id" value="{{ old('default_province_id', $user->default_province_id) }}">
                                        </div>

                                        {{-- Kota --}}
                                        <div class="col-md-6">
                                            <label class="form-label small fw-bold text-muted">Kota / Kabupaten</label>
                                            <select id="main_city_select" class="form-select bg-light border-0 py-2 shadow-none">
                                                <option value="">Pilih Kota</option>
                                            </select>
                                            <input type="hidden" name="default_city" id="main_db_city_name" value="{{ old('default_city', $user->default_city) }}">
                                            <input type="hidden" name="default_city_id" id="main_db_city_id" value="{{ old('default_city_id', $user->default_city_id) }}">
                                        </div>

                                        {{-- Kecamatan --}}
                                        <div class="col-md-6">
                                            <label class="form-label small fw-bold text-muted">Kecamatan</label>
                                            <select id="main_district_select" class="form-select bg-light border-0 py-2 shadow-none">
                                                <option value="">Pilih Kecamatan</option>
                                            </select>
                                            <input type="hidden" name="default_district" id="main_db_district_name" value="{{ old('default_district', $user->default_district) }}">
                                            <input type="hidden" name="default_district_id" id="main_db_district_id" value="{{ old('default_district_id', $user->default_district_id) }}">
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label small fw-bold text-muted">Kode Pos</label>
                                            <input type="text" name="default_postal_code"
                                                value="{{ old('default_postal_code', $user->default_postal_code) }}"
                                                class="form-control bg-light border-0 py-2">
                                        </div>

                                        <div class="col-12">
                                            <label class="form-label small fw-bold text-muted">Alamat Lengkap</label>
                                            <textarea name="default_full_address" rows="3"
                                                class="form-control bg-light border-0 py-2">{{ old('default_full_address', $user->default_full_address) }}</textarea>
                                        </div>

                                        {{-- [SEMENTARA DIMATIKAN] TITIK LOKASI PETA GOOGLE MAPS --}}
                                        {{-- 
                                        <div class="col-12 mt-4">
                                            <label class="form-label small fw-bold text-muted">Tentukan Titik Lokasi (Opsional)</label>
                                            <p class="small text-muted mb-2">Geser pin merah atau klik pada peta untuk menentukan titik koordinat lokasi pengiriman Anda.</p>
                                            
                                            <div id="map" style="height: 300px; width: 100%; border-radius: 8px; border: 1px solid #dee2e6;"></div>
                                            
                                            <div class="row g-2 mt-2">
                                                <div class="col-6">
                                                    <input type="text" name="latitude" id="latitude" class="form-control bg-light border-0 py-2 small text-muted" value="{{ old('latitude', $user->latitude) }}" placeholder="Latitude" readonly>
                                                </div>
                                                <div class="col-6">
                                                    <input type="text" name="longitude" id="longitude" class="form-control bg-light border-0 py-2 small text-muted" value="{{ old('longitude', $user->longitude) }}" placeholder="Longitude" readonly>
                                                </div>
                                            </div>
                                        </div>
                                        --}}

                                    </div>

                                    <div class="mt-5 d-flex justify-content-end align-items-center">
                                        @if (session('status-alamat') === 'alamat-updated')
                                            <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)" class="text-success small fw-bold mb-0 me-4">
                                                <i class="bi bi-check-circle me-1"></i> Data Alamat Berhasil Disimpan!
                                            </p>
                                        @endif
                                        <button type="submit" class="btn btn-primary px-5 fw-bold rounded-0 shadow-sm">
                                            Update Alamat
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        {{-- Tab 3: Keamanan --}}
                        <div class="tab-pane fade" id="keamanan">
                            <div class="card border-0 shadow-sm rounded-0 p-4 p-md-5">
                                <h4 class="fw-bold mb-2">Ganti Password</h4>
                                <p class="text-muted small mb-4">Gunakan password yang panjang dan aman.</p>

                                <form method="POST" action="{{ route('password.update') }}">
                                    @csrf
                                    @method('PUT')
                                    <div class="mb-4">
                                        <label class="form-label small fw-bold text-muted">Password Saat Ini</label>
                                        <input type="password" name="current_password" class="form-control bg-light border-0 py-2 shadow-none">
                                        @if($errors->updatePassword->get('current_password'))
                                            <div class="text-danger small mt-1">{{ $errors->updatePassword->first('current_password') }}</div>
                                        @endif
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label small fw-bold text-muted">Password Baru</label>
                                        <input type="password" name="password" class="form-control bg-light border-0 py-2 shadow-none">
                                        @if($errors->updatePassword->get('password'))
                                            <div class="text-danger small mt-1">{{ $errors->updatePassword->first('password') }}</div>
                                        @endif
                                    </div>
                                    <div class="mb-5">
                                        <label class="form-label small fw-bold text-muted">Konfirmasi Password Baru</label>
                                        <input type="password" name="password_confirmation" class="form-control bg-light border-0 py-2 shadow-none">
                                    </div>
                                    <button type="submit" class="btn btn-primary px-5 fw-bold rounded-0 shadow-sm">Update Password</button>
                                    @if (session('status') === 'password-updated')
                                        <div class="text-success small mt-3 fw-bold"><i class="bi bi-check-circle me-1"></i> Password berhasil diperbarui.</div>
                                    @endif
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal Hapus Akun --}}
<div class="modal fade" id="deleteAccountModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-0">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-danger">Konfirmasi Hapus Akun</h5>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('profile.destroy') }}">
                @csrf
                @method('DELETE')
                <div class="modal-body py-4">
                    <p class="text-muted">Apakah Anda yakin ingin menghapus akun permanen? Masukkan password untuk mengonfirmasi tindakan ini.</p>
                    <input type="password" name="password" class="form-control bg-light border-0 py-2 shadow-none" placeholder="Masukkan Password Anda">
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light rounded-0 border px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger rounded-0 px-4 fw-bold shadow-sm">Ya, Hapus Permanen</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    /* Styling khusus Profile POLMAN */
    .list-group-item.active {
        background-color: transparent !important;
        color: #013780 !important;
        border-left: 4px solid #013780 !important;
    }
    .form-control:focus, .form-select:focus {
        background-color: #f1f3f5 !important;
        border: 1px solid #dee2e6 !important;
    }
    .btn, .card, .form-control, .form-select, .modal-content, .list-group-item {
        border-radius: 0 !important;
    }
</style>

{{-- Script SweetAlert --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // ----------------------------------------------------
    // NOTIFIKASI POPUP ERROR & SUKSES (SWEETALERT)
    // ----------------------------------------------------
    
    // Notifikasi untuk Ganti Password (Gagal)
    @if ($errors->updatePassword->any())
        Swal.fire({
            icon: 'error',
            title: 'Ganti Password Gagal',
            text: '{{ $errors->updatePassword->first() }}',
            confirmButtonColor: '#013780',
        });
        // Pindah ke tab keamanan
        const securityTab = document.querySelector('a[href="#keamanan"]');
        bootstrap.Tab.getOrCreateInstance(securityTab).show();
    @endif

    // Notifikasi untuk Ganti Password (Berhasil)
    @if (session('status') === 'password-updated')
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: 'Password Anda telah berhasil diperbarui.',
            showConfirmButton: false,
            timer: 2000
        });
        const securityTab = document.querySelector('a[href="#keamanan"]');
        bootstrap.Tab.getOrCreateInstance(securityTab).show();
    @endif

    // Notifikasi Error Profil/Alamat Umum
    @if ($errors->any() && !$errors->updatePassword->any())
        Swal.fire({
            icon: 'error',
            title: 'Gagal Menyimpan',
            text: 'Terdapat kolom yang wajib diisi atau format yang salah. Silakan periksa kembali form Anda.',
            confirmButtonColor: '#013780',
        });
    @endif

    // Notifikasi Berhasil Profil/Alamat
    @if (session('status-alamat') === 'alamat-updated' || session('success-profil'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: 'Data Anda telah berhasil diperbarui.',
            showConfirmButton: false,
            timer: 2000
        });
    @endif

    // ----------------------------------------------------
    // LOGIKA DROPDOWN WILAYAH ALAMAT
    // ----------------------------------------------------
    const provSel = document.getElementById('main_province_select');
    const citySel = document.getElementById('main_city_select');
    const distSel = document.getElementById('main_district_select');

    const initialProvName = "{{ $user->default_province }}";
    const initialCityName = "{{ $user->default_city }}";
    const initialDistName = "{{ $user->default_district }}";

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
        document.getElementById('main_db_province_id').value = id;
        document.getElementById('main_db_province_name').value = id ? this.options[this.selectedIndex].text : "";

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
            document.getElementById('main_db_city_id').value = "";
            document.getElementById('main_db_city_name').value = "";
        }
    });

    // 3. Kota Berubah -> Fetch Kecamatan
    citySel.addEventListener('change', function() {
        const id = this.value;
        document.getElementById('main_db_city_id').value = id;
        document.getElementById('main_db_city_name').value = id ? this.options[this.selectedIndex].text : "";

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
            document.getElementById('main_db_district_id').value = "";
            document.getElementById('main_db_district_name').value = "";
        }
    });

    // 4. Kecamatan Berubah
    distSel.addEventListener('change', function() {
        const id = this.value;
        document.getElementById('main_db_district_id').value = id;
        document.getElementById('main_db_district_name').value = id ? this.options[this.selectedIndex].text : "";
    });
});
</script>

{{-- [SEMENTARA DIMATIKAN] SCRIPT GOOGLE MAPS API --}}
{{-- 
<script>
    let map, marker;
    
    function initMap() {
        const savedLat = parseFloat("{{ $user->latitude ?? -6.914744 }}");
        const savedLng = parseFloat("{{ $user->longitude ?? 107.609810 }}");
        const myLatLng = { lat: savedLat, lng: savedLng };

        map = new google.maps.Map(document.getElementById("map"), {
            zoom: 15,
            center: myLatLng,
        });

        marker = new google.maps.Marker({
            position: myLatLng,
            map: map,
            draggable: true,
            title: "Geser pin ke lokasi Anda"
        });

        marker.addListener("dragend", (event) => {
            updateInputs(event.latLng);
        });

        map.addListener("click", (event) => {
            marker.setPosition(event.latLng);
            updateInputs(event.latLng);
        });
    }

    function updateInputs(latLng) {
        document.getElementById("latitude").value = latLng.lat();
        document.getElementById("longitude").value = latLng.lng();
    }
</script>

<script async defer src="https://maps.googleapis.com/maps/api/js?key=API_KEY_DISINI&callback=initMap"></script>
--}}

@endsection