@extends('layouts.store')

@section('content')
<div class="container-fluid p-0 overflow-hidden" style="height: calc(100vh - 70px); background-color: #ffffff;">
    <div class="row g-0 h-100">
        
        {{-- SISI KIRI: GAMBAR (SAMA DENGAN LOGIN) --}}
        <div class="col-lg-6 p-3 d-none d-lg-block">
            <div class="h-100 w-100 position-relative rounded-5 shadow-lg overflow-hidden" style="background-color: #013780;">
                {{-- Blur disamakan ke 8px agar tidak terlalu buram --}}
                <img src="{{ asset('assets/img/bengkel.jpg') }}" 
                     class="position-absolute w-100 h-100" 
                     style="object-fit: cover; filter: blur(8px) brightness(0.8); transform: scale(1.1);">
                
                {{-- Overlay Gradient --}}
                <div class="position-absolute w-100 h-100" style="background: rgba(1, 55, 128, 0.2);"></div>
                
                <div class="position-absolute bottom-0 start-0 p-5 text-white" style="z-index: 2;">
                    <span class="badge bg-primary mb-3 rounded-pill px-3 py-2 text-uppercase" style="letter-spacing: 2px; font-size: 10px;">Join Us</span>
                    <h2 class="display-5 fw-bold" style="font-family: serif; line-height: 1.2;">Start Your Journey <br> with POLMAN.</h2>
                    <p class="opacity-75 mt-3 lead small">Daftar dan akses produk inovasi <br> karya sivitas akademika terbaik.</p>
                </div>
            </div>
        </div>

        {{-- SISI KANAN: FORM COMPACT --}}
        <div class="col-lg-6 d-flex flex-column justify-content-center px-4 px-md-5 bg-white">
            <div class="mx-auto w-100" style="max-width: 420px;">
                
                <div class="mb-4">
                    <h3 class="fw-bold text-dark mb-1" style="letter-spacing: -1px;">Create Account</h3>
                    <p class="text-muted small">Lengkapi data untuk membuat akun baru.</p>
                </div>

                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label fw-bold text-muted text-uppercase mb-1" style="letter-spacing: 1px; font-size: 10px;">Tipe Akun</label>
                        <select id="account_type" name="account_type" class="form-select form-select-sm border-0 bg-light rounded-3 shadow-none py-2" style="font-size: 13px;">
                            <option value="individual" {{ old('account_type') === 'individual' ? 'selected' : '' }}>Individu (Mahasiswa/Umum)</option>
                            <option value="company" {{ old('account_type') === 'company' ? 'selected' : '' }}>Perusahaan (Industri/Mitra)</option>
                        </select>
                    </div>

                    <div id="individual_fields" class="mb-3">
                        <label class="form-label fw-bold text-muted mb-1" style="font-size: 11px;">Nama Lengkap</label>
                        <input id="name" type="text" name="name" value="{{ old('name') }}" class="form-control form-control-sm border-0 bg-light py-2 rounded-3 shadow-none" placeholder="Masukkan nama" style="font-size: 13px;">
                    </div>

                    <div id="company_fields" class="d-none">
                        <div class="row g-2">
                            <div class="col-6 mb-3">
                                <label class="form-label fw-bold text-muted mb-1" style="font-size: 11px;">Nama Perusahaan</label>
                                <input id="company_name" type="text" name="company_name" value="{{ old('company_name') }}" class="form-control form-control-sm border-0 bg-light py-2 rounded-3 shadow-none" placeholder="PT. Jaya" style="font-size: 13px;">
                            </div>
                            <div class="col-6 mb-3">
                                <label class="form-label fw-bold text-muted mb-1" style="font-size: 11px;">PIC</label>
                                <input id="contact_person" type="text" name="contact_person" value="{{ old('contact_person') }}" class="form-control form-control-sm border-0 bg-light py-2 rounded-3 shadow-none" placeholder="Nama PIC" style="font-size: 13px;">
                            </div>
                        </div>
                    </div>

                    <div class="row g-2">
                        <div class="col-6 mb-3">
                            <label class="form-label fw-bold text-muted mb-1" style="font-size: 11px;">WhatsApp</label>
                            <input id="phone" type="text" name="phone" value="{{ old('phone') }}" class="form-control form-control-sm border-0 bg-light py-2 rounded-3 shadow-none" placeholder="08xxx" style="font-size: 13px;">
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label fw-bold text-muted mb-1" style="font-size: 11px;">Email</label>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" class="form-control form-control-sm border-0 bg-light py-2 rounded-3 shadow-none" placeholder="user@polman.ac.id" style="font-size: 13px;">
                        </div>
                    </div>

                    <div class="row g-2">
                        <div class="col-6 mb-3">
                            <label class="form-label fw-bold text-muted mb-1" style="font-size: 11px;">Password</label>
                            <input id="password" type="password" name="password" class="form-control form-control-sm border-0 bg-light py-2 rounded-3 shadow-none" placeholder="••••••••" style="font-size: 13px;">
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label fw-bold text-muted mb-1" style="font-size: 11px;">Konfirmasi</label>
                            <input id="password_confirmation" type="password" name="password_confirmation" class="form-control form-control-sm border-0 bg-light py-2 rounded-3 shadow-none" placeholder="••••••••" style="font-size: 13px;">
                        </div>
                    </div>

                    @if ($errors->any())
                        <div class="alert alert-danger py-1 px-3 border-0 rounded-3 mb-3" style="font-size: 10px;">
                            <ul class="mb-0 list-unstyled">
                                @foreach ($errors->all() as $error)
                                    <li><i class="bi bi-exclamation-circle-fill me-1"></i> {{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="mt-2">
                        <button type="submit" class="btn btn-primary w-100 py-2 rounded-pill fw-bold text-uppercase shadow-sm" style="letter-spacing: 1px; background-color: #013780; border: none; font-size: 12px;">
                            Daftar Sekarang
                        </button>
                    </div>

                    <div class="text-center mt-3">
                        <p class="text-muted mb-0" style="font-size: 11px;">Sudah punya akun? 
                            <a href="{{ route('login') }}" class="text-primary fw-bold text-decoration-none">Masuk di sini</a>
                        </p>
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
    .rounded-5 { border-radius: 2.5rem !important; }
    .form-control:focus, .form-select:focus {
        background-color: #e2e6ea !important;
        box-shadow: none !important;
    }
    h2, h3 { font-family: 'Playfair Display', serif; }
</style>
@endsection