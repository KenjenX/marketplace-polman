@extends('layouts.store')

@section('content')
{{-- 1. Header Section --}}
<div class="position-relative py-5" style="background: linear-gradient(135deg, #013780 0%, #001d44 100%); margin-top: -24px;">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-10 text-center text-white">
                <h1 class="display-4 fw-bold mb-3">Membangun Masa Depan Manufaktur</h1>
                <p class="lead opacity-75 mx-auto" style="max-width: 800px;">
                    Marketplace Politeknik Manufaktur Bandung adalah pusat inovasi yang menjembatani karya akademik seluruh sivitas akademika dengan kebutuhan industri nyata.
                </p>
            </div>
        </div>
    </div>
    <div class="position-absolute top-0 start-0 w-100 h-100 opacity-10" style="background-image: radial-gradient(#fff 1px, transparent 1px); background-size: 30px 30px; pointer-events: none;"></div>
</div>

{{-- 2. Visi & Misi --}}
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="bg-white p-4 p-md-5 border shadow-sm">
                <div class="row g-4">
                    <div class="col-md-6 border-end">
                        <h6 class="text-primary fw-bold text-uppercase mb-3" style="letter-spacing: 2px;">Visi Kami</h6>
                        <h3 class="fw-bold text-dark mb-4">Menjadi Hub Teknologi Terdepan</h3>
                        <p class="text-muted mb-0">
                            Menjadikan Marketplace POLMAN Bandung sebagai platform utama dalam mendistribusikan hasil riset, produk inovasi teknik, dan jasa manufaktur berkualitas tinggi ke seluruh penjuru Indonesia.
                        </p>
                    </div>
                    <div class="col-md-6 ps-md-4">
                        <h6 class="text-primary fw-bold text-uppercase mb-3" style="letter-spacing: 2px;">Misi Kami</h6>
                        <ul class="list-unstyled mb-0">
                            <li class="d-flex mb-3">
                                <i class="bi bi-check2 text-primary fw-bold me-3"></i>
                                <span class="text-muted small">Mendorong komersialisasi produk inovasi mahasiswa dan dosen di seluruh institusi.</span>
                            </li>
                            <li class="d-flex mb-3">
                                <i class="bi bi-check2 text-primary fw-bold me-3"></i>
                                <span class="text-muted small">Menyediakan komponen industri dan perangkat teknik yang terjamin kualitasnya.</span>
                            </li>
                            <li class="d-flex mb-0">
                                <i class="bi bi-check2 text-primary fw-bold me-3"></i>
                                <span class="text-muted small">Memberikan layanan desain manufaktur dan pengecoran logam dengan standar presisi tinggi.</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- 3. Core Values --}}
<section class="py-5 bg-light">
    <div class="container">
        <div class="row justify-content-center mb-5">
            <div class="col-lg-6 text-center">
                <h2 class="fw-bold text-dark">Nilai Utama Institusi</h2>
                <div class="mx-auto mt-2" style="width: 50px; height: 3px; background: #013780;"></div>
            </div>
        </div>
        <div class="row g-4 justify-content-center">
            <div class="col-md-4">
                <div class="card h-100 border-0 p-4 text-center shadow-sm">
                    <div class="mb-3 text-primary">
                        <i class="bi bi-cpu-fill fs-1"></i>
                    </div>
                    <h5 class="fw-bold">Inovasi Terapan</h5>
                    <p class="text-muted small mb-0">Setiap produk lahir dari riset mendalam dan praktik nyata di lingkungan workshop POLMAN Bandung.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 border-0 p-4 text-center shadow-sm">
                    <div class="mb-3 text-primary">
                        <i class="bi bi-tools fs-1"></i>
                    </div>
                    <h5 class="fw-bold">Presisi & Kualitas</h5>
                    <p class="text-muted small mb-0">Mengutamakan standar akurasi tinggi dalam setiap produk fisik maupun layanan jasa yang kami tawarkan.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 border-0 p-4 text-center shadow-sm">
                    <div class="mb-3 text-primary">
                        <i class="bi bi-mortarboard-fill fs-1"></i>
                    </div>
                    <h5 class="fw-bold">Edukatif</h5>
                    <p class="text-muted small mb-0">Berperan aktif dalam mendukung kemajuan pendidikan teknik melalui penyediaan alat penunjang yang relevan.</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- 4. Lokasi & Hubungi Kami --}}
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="row g-5 align-items-center">
                <div class="col-lg-6">
                    <h2 class="fw-bold mb-4">Lokasi Institusi</h2>
                    <p class="text-muted mb-4">
                        Kami beroperasi secara resmi di lingkungan kampus Politeknik Manufaktur Bandung. Seluruh proses transaksi dikelola untuk mendukung ekosistem pendidikan dan inovasi teknik.
                    </p>
                    <div class="d-flex mb-3">
                        <div class="text-primary me-3"><i class="bi bi-geo-alt-fill fs-4"></i></div>
                        <div>
                            <h6 class="fw-bold mb-0">Kampus POLMAN Bandung</h6>
                            <small class="text-muted">Jl. Kanayakan No.21, Dago, Coblong, Kota Bandung, Jawa Barat 40135</small>
                        </div>
                    </div>
                    <div class="d-flex">
                        <div class="text-primary me-3"><i class="bi bi-envelope-at-fill fs-4"></i></div>
                        <div>
                            <h6 class="fw-bold mb-0">Kontak Resmi</h6>
                            <small class="text-muted">info@polman-bandung.ac.id</small>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 text-center">
                    <div class="ratio ratio-4x3 shadow-lg border">
                        <img src="{{ asset('assets/img/polman.jpg') }}" class="object-fit-cover" alt="POLMAN Bandung">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .card, .btn, .shadow-sm, .form-control, img, .bg-white, .ratio {
        border-radius: 0 !important;
    }
    .container {
        max-width: 1200px;
    }
</style>
@endsection