@extends('layouts.store')

@section('content')
<div class="container-fluid p-0 overflow-hidden" style="min-height: 90vh; background-color: #f8f9fa;">
    <div class="row g-0 min-vh-90">
        
        {{-- SISI KIRI: Basa-basi & Branding Marketplace POLMAN --}}
        <div class="col-lg-6 d-flex flex-column justify-content-center p-5 p-md-5">
            <div class="mb-auto p-md-4">
                <span class="text-uppercase fw-bold small letter-spacing-2" style="color: #666;">Largest Engineering Store</span>
                <h1 class="display-3 fw-light mt-4 mb-0" style="font-family: serif; line-height: 1.1;">
                    INNOVATION POWERED <br>
                    BY <span class="fw-bold text-primary">ENGINEERS</span> <br>
                    AROUND THE CAMPUS.
                </h1>

                <div class="mt-5 pt-4">
                    <p class="text-muted mb-2">Don't have account?</p>
                    <a href="{{ route('register') }}" class="text-dark fw-bold text-decoration-none border-bottom border-dark border-2 pb-1">
                        Create account &nbsp; &rarr;
                    </a>
                </div>
            </div>

            {{-- Gambar Dekoratif About Us --}}
            <div class="p-md-4 mt-5">
                <div class="position-relative overflow-hidden shadow-lg rounded-5" 
                     style="height: 300px; width: 100%; background: url('{{ asset('assets/img/polman.jpg') }}') center/cover no-repeat;">
                    <div class="position-absolute bottom-0 start-0 p-4 text-white bg-dark bg-opacity-25 w-100">
                        <h5 class="fw-bold mb-1">About us</h5>
                        <p class="small mb-0 opacity-75">Marketplace POLMAN Bandung menjembatani riset akademik dengan kebutuhan industri nyata.</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- SISI KANAN: Login Form dengan Latar Bengkel HD --}}
        <div class="col-lg-6 p-3">
        <div class="h-100 w-100 position-relative rounded-5 shadow-lg overflow-hidden" style="background-color: #013780;">
            
            {{-- Gambar Bengkel dengan Filter Blur --}}
            <img src="{{ asset('assets/img/bengkel.jpg') }}" 
                class="position-absolute w-100 h-100" 
                style="object-fit: cover; filter: blur(8px) brightness(0.8); transform: scale(1.1);">
            
            {{-- Overlay Login Form --}}
            <div class="position-absolute w-100 h-100 d-flex align-items-center justify-content-center">
                <div class="card border-0 shadow-lg rounded-5 p-4 p-md-5" 
                    style="width: 90%; max-width: 420px; background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px);">
                    
                    <div class="text-center mb-4">
                        <h5 class="fw-bold text-dark">Login to your account</h5>
                    </div>

                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="mb-4">
                            <label class="form-label small fw-bold text-muted mb-1">Email</label>
                            <input type="email" name="email" value="{{ old('email') }}" required 
                                class="form-control border-0 border-bottom rounded-0 px-0 bg-transparent shadow-none" 
                                style="border-bottom: 2px solid #eee !important;">
                        </div>

                        <div class="mb-4">
                            <label class="form-label small fw-bold text-muted mb-1">Password</label>
                            <input type="password" name="password" required 
                                class="form-control border-0 border-bottom rounded-0 px-0 bg-transparent shadow-none" 
                                style="border-bottom: 2px solid #eee !important;">
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-5">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input shadow-none" id="remember" name="remember">
                                <label class="form-check-label small fw-bold" for="remember">Remember me</label>
                            </div>
                            <a href="{{ route('password.request') }}" class="small text-dark fw-bold text-decoration-none border-bottom border-dark">Forget password?</a>
                        </div>

                        <button type="submit" class="btn btn-dark w-100 py-3 rounded-pill fw-bold text-uppercase shadow">
                            Login
                        </button>
                    </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .letter-spacing-1 { letter-spacing: 1px; }
    .letter-spacing-2 { letter-spacing: 2px; }
    .min-vh-90 { min-height: 90vh; }
    .rounded-5 { border-radius: 2.5rem !important; }
    
    /* Perbaikan Visual Fokus Input */
    .form-control:focus {
        border-color: #013780 !important;
        background-color: transparent !important;
    }

    /* Memastikan Font Serif untuk Heading agar Mirip Referensi */
    h1, .display-5 {
        font-family: 'Playfair Display', serif;
    }
</style>
@endsection