@extends('layouts.store')

@section('content')
<div class="container-fluid p-0 overflow-hidden" style="min-height: 90vh; background-color: #ffffff;">
    <div class="row g-0 min-vh-90">
        
        {{-- SISI KIRI: SEKARANG GAMBAR (Tukar Posisi dari Login) --}}
        <div class="col-lg-6 p-3 d-none d-lg-block">
            <div class="h-100 w-100 position-relative rounded-5 shadow-lg overflow-hidden" 
                 style="background: #013780 url('{{ asset('assets/img/bengkel.jpg') }}') center/cover no-repeat;">
                
                {{-- Overlay Gradient agar lebih dramatis --}}
                <div class="position-absolute w-100 h-100" style="background: linear-gradient(to bottom, rgba(1, 55, 128, 0.2), rgba(0, 0, 0, 0.7));"></div>
                
                <div class="position-absolute bottom-0 start-0 p-5 text-white">
                    <span class="badge bg-primary mb-3 rounded-0 px-3 py-2 text-uppercase" style="letter-spacing: 2px;">Join Us</span>
                    <h2 class="display-4 fw-bold" style="font-family: serif;">Start Your Journey <br> with POLMAN.</h2>
                    <p class="opacity-75 mt-3 lead">Daftar dan mulai akses ribuan produk inovasi <br> karya sivitas akademika terbaik.</p>
                </div>
            </div>
        </div>

        {{-- SISI KANAN: SEKARANG FORM (Tukar Posisi dari Login) --}}
        <div class="col-lg-6 d-flex flex-column justify-content-center p-4 p-md-5 bg-white">
            <div class="mx-auto w-100" style="max-width: 480px;">
                
                {{-- Header Register --}}
                <div class="mb-5">
                    <h2 class="fw-bold text-dark mb-2" style="letter-spacing: -1px;">Create Account</h2>
                    <p class="text-muted">Lengkapi data di bawah untuk membuat akun baru.</p>
                </div>

                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    {{-- Pilihan Tipe Akun dengan Style Button Group agar beda --}}
                    <div class="mb-4">
                        <label class="form-label small fw-bold text-muted text-uppercase mb-3" style="letter-spacing: 1px;">Tipe Akun</label>
                        <select id="account_type" name="account_type" class="form-select form-select-lg border-0 bg-light rounded-3 shadow-none py-3" style="font-size: 15px;">
                            <option value="individual" {{ old('account_type') === 'individual' ? 'selected' : '' }}>Individu (Mahasiswa/Umum)</option>
                            <option value="company" {{ old('account_type') === 'company' ? 'selected' : '' }}>Perusahaan (Industri/Mitra)</option>
                        </select>
                    </div>

                    {{-- Field Dinamis --}}
                    <div id="individual_fields" class="mb-4">
                        <label class="form-label small fw-bold text-muted">Nama Lengkap</label>
                        <input id="name" type="text" name="name" value="{{ old('name') }}" class="form-control border-0 bg-light py-3 rounded-3 shadow-none" placeholder="Masukkan nama">
                    </div>

                    <div id="company_fields" class="d-none">
                        <div class="mb-4">
                            <label class="form-label small fw-bold text-muted">Nama Perusahaan</label>
                            <input id="company_name" type="text" name="company_name" value="{{ old('company_name') }}" class="form-control border-0 bg-light py-3 rounded-3 shadow-none" placeholder="Contoh: PT. Manufaktur Jaya">
                        </div>
                        <div class="mb-4">
                            <label class="form-label small fw-bold text-muted">Nama PIC</label>
                            <input id="contact_person" type="text" name="contact_person" value="{{ old('contact_person') }}" class="form-control border-0 bg-light py-3 rounded-3 shadow-none" placeholder="Nama penanggung jawab">
                        </div>
                    </div>

                    {{-- Kontak & Akun --}}
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label class="form-label small fw-bold text-muted">Nomor WhatsApp</label>
                            <input id="phone" type="text" name="phone" value="{{ old('phone') }}" class="form-control border-0 bg-light py-3 rounded-3 shadow-none" placeholder="08xxx">
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label small fw-bold text-muted">Alamat Email</label>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" class="form-control border-0 bg-light py-3 rounded-3 shadow-none" placeholder="email@polman.ac.id">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label class="form-label small fw-bold text-muted">Password</label>
                            <input id="password" type="password" name="password" class="form-control border-0 bg-light py-3 rounded-3 shadow-none" placeholder="••••••••">
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label small fw-bold text-muted">Konfirmasi</label>
                            <input id="password_confirmation" type="password" name="password_confirmation" class="form-control border-0 bg-light py-3 rounded-3 shadow-none" placeholder="••••••••">
                        </div>
                    </div>

                    <div class="mt-4 pt-2">
                        <button type="submit" class="btn btn-primary w-100 py-3 rounded-3 fw-bold text-uppercase shadow-sm" style="letter-spacing: 1px; background-color: #013780; border: none;">
                            Daftar Sekarang
                        </button>
                    </div>

                    <div class="text-center mt-4">
                        <p class="text-muted small">Sudah punya akun? 
                            <a href="{{ route('login') }}" class="text-primary fw-bold text-decoration-none">Masuk di sini</a>
                        </p>
                        <a href="{{ route('home') }}" class="text-muted small text-decoration-none mt-2 d-block"> Kembali ke Beranda</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const accountType = document.getElementById('account_type');
        const individualFields = document.getElementById('individual_fields');
        const companyFields = document.getElementById('company_fields');

        function toggleFields() {
            if (accountType.value === 'company') {
                individualFields.classList.add('d-none');
                companyFields.classList.remove('d-none');
            } else {
                individualFields.classList.remove('d-none');
                companyFields.classList.add('d-none');
            }
        }
        accountType.addEventListener('change', toggleFields);
        toggleFields();
    });
</script>

<style>
    .min-vh-90 { min-height: 90vh; }
    .rounded-5 { border-radius: 2.5rem !important; }
    .form-control:focus, .form-select:focus {
        background-color: #e9ecef !important;
        box-shadow: none !important;
    }
    h2, h1 { font-family: 'Playfair Display', serif; }
</style>
@endsection