@extends('layouts.store')

@section('content')
{{-- 1. Header Section --}}
<div class="position-relative py-5" style="background: linear-gradient(135deg, #013780 0%, #001d44 100%); margin-top: -24px;">
    <div class="container py-5 position-relative" style="z-index: 2;">
        <div class="row justify-content-center text-center text-white">
            <div class="col-lg-8">
                <h1 class="display-4 fw-bold mb-3">Contact Us</h1>
                <p class="lead opacity-75 mx-auto">
                    Kami siap melayani pertanyaan mengenai produk inovasi, kerjasama industri, maupun dukungan teknis lainnya bagi seluruh sivitas akademika POLMAN Bandung.
                </p>
            </div>
        </div>
    </div>
    <div class="position-absolute top-0 start-0 w-100 h-100 opacity-10" style="background-image: radial-gradient(#fff 1px, transparent 1px); background-size: 30px 30px; pointer-events: none;"></div>
</div>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-11">
            <div class="row g-0 shadow-lg border">
                
                {{-- 2. Kolom Kiri: Informasi Institusi --}}
                <div class="col-md-5 text-white p-4 p-md-5" style="background: #013780;">
                    <h3 class="fw-bold mb-4">Informasi Institusi</h3>
                    <p class="opacity-75 mb-5 small">Silakan datang langsung ke kampus atau hubungi kami melalui saluran resmi berikut.</p>
                    
                    <div class="d-flex mb-4 align-items-start">
                        {{-- PERBAIKAN: bg-white dihapus agar PNG transparan tampil sempurna --}}
                        <div class="me-3" style="width: 45px; height: 45px; flex-shrink: 0;">
                            <img src="{{ asset('assets/img/map.png') }}" alt="Map" class="w-100 h-100 object-fit-contain">
                        </div>
                        <div>
                            <h6 class="fw-bold mb-0">Alamat</h6>
                            <small class="opacity-75">Jl. Kanayakan No.21, Dago, Bandung</small>
                        </div>
                    </div>

                    <div class="d-flex mb-4 align-items-start">
                        {{-- PERBAIKAN: bg-white dihapus --}}
                        <div class="me-3" style="width: 45px; height: 45px; flex-shrink: 0;">
                            <img src="{{ asset('assets/img/email.png') }}" alt="Email" class="w-100 h-100 object-fit-contain">
                        </div>
                        <div>
                            <h6 class="fw-bold mb-0">Email Resmi</h6>
                            <small class="opacity-75">info@polman-bandung.ac.id</small>
                        </div>
                    </div>

                    <div class="d-flex mb-4 align-items-start">
                        {{-- PERBAIKAN: bg-white dihapus --}}
                        <div class="me-3" style="width: 45px; height: 45px; flex-shrink: 0;">
                            <img src="{{ asset('assets/img/phone.png') }}" alt="Phone" class="w-100 h-100 object-fit-contain">
                        </div>
                        <div>
                            <h6 class="fw-bold mb-0">Telepon</h6>
                            <small class="opacity-75">+62 22 2500241</small>
                        </div>
                    </div>

                    <hr class="my-5 opacity-25">

                    <h6 class="fw-bold mb-3 text-uppercase" style="letter-spacing: 1px;">Jam Layanan</h6>
                    <div class="d-flex justify-content-between small mb-2">
                        <span>Senin - Kamis</span>
                        <span class="fw-bold">07:30 - 16:00</span>
                    </div>
                    <div class="d-flex justify-content-between small">
                        <span>Jumat</span>
                        <span class="fw-bold">07:30 - 16:30</span>
                    </div>
                </div>

                {{-- 3. Kolom Kanan: Form Pesan --}}
                <div class="col-md-7 bg-white p-4 p-md-5">
                    <h3 class="fw-bold text-dark mb-4">Kirim Pesan</h3>
                    <form action="#" method="POST">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted">Nama Lengkap</label>
                                <input type="text" class="form-control border-0 bg-light py-2 shadow-none" placeholder="Nama Anda">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted">Email</label>
                                <input type="email" class="form-control border-0 bg-light py-2 shadow-none" placeholder="nama@email.com">
                            </div>
                            <div class="col-12">
                                <label class="form-label small fw-bold text-muted">Subjek</label>
                                <input type="text" class="form-control border-0 bg-light py-2 shadow-none" placeholder="Tujuan pesan">
                            </div>
                            <div class="col-12">
                                <label class="form-label small fw-bold text-muted">Pesan</label>
                                <textarea class="form-control border-0 bg-light shadow-none" rows="4" placeholder="Tulis pesan Anda..."></textarea>
                            </div>
                            <div class="col-12 mt-4 text-end">
                                <button type="submit" class="btn btn-primary px-5 py-3 fw-bold text-uppercase rounded-0" style="letter-spacing: 1px;">
                                    Send Message
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            {{-- 4. Map Section --}}
            <div class="mt-5 shadow-sm border border-white">
                <div class="ratio ratio-21x9">
                    <iframe 
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3961.033582498262!2d107.6186419!3d-6.8865985!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e68e6f00735870d%3A0x264870d235069772!2sPoliteknik%20Manufaktur%20Bandung!5e0!3m2!1sid!2sid!4v1714440000000!5m2!1sid!2sid" 
                        style="border:0;" allowfullscreen="" loading="lazy">
                    </iframe>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .btn, .form-control, .bg-white, .ratio {
        border-radius: 0 !important;
    }
    .form-control:focus {
        background-color: #f1f3f5 !important;
        border: 1px solid #dee2e6 !important;
    }
</style>
@endsection