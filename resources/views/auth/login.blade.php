@extends('layouts.store')

@section('content')
<div class="container-fluid p-0 overflow-hidden" style="height: calc(100vh - 70px); background-color: #f8f9fa;">
    <div class="row g-0 h-100">
        
        {{-- SISI KIRI: Branding --}}
        <div class="col-lg-6 d-flex flex-column justify-content-center px-5 py-4">
            <div class="p-md-4">
                <span class="text-uppercase fw-bold small letter-spacing-2" style="color: #666; font-size: 10px;">Largest Engineering Store</span>
                <h1 class="display-5 fw-light mt-3 mb-0" style="font-family: serif; line-height: 1.1; font-size: 2.8rem;">
                    INNOVATION POWERED <br>
                    BY <span class="fw-bold text-primary">ENGINEERS</span> <br>
                    AROUND THE CAMPUS.
                </h1>

                <div class="mt-4 pt-2">
                    <p class="text-muted mb-1 small">Don't have account?</p>
                    <a href="{{ route('register') }}" class="text-dark fw-bold text-decoration-none border-bottom border-dark border-2 pb-1 small">
                        Create account &nbsp; &rarr;
                    </a>
                </div>
            </div>

            {{-- Gambar Dekoratif --}}
            <div class="px-md-4 mt-4">
                <div class="position-relative overflow-hidden shadow rounded-4" 
                     style="height: 180px; width: 100%; background: url('{{ asset('assets/img/polman.jpg') }}') center/cover no-repeat;">
                    <div class="position-absolute bottom-0 start-0 p-3 text-white bg-dark bg-opacity-25 w-100">
                        <h6 class="fw-bold mb-1">About us</h6>
                        <p class="mb-0 opacity-75" style="font-size: 11px;">Marketplace POLMAN Bandung menjembatani riset akademik dengan kebutuhan industri nyata.</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- SISI KANAN: Login Form --}}
        <div class="col-lg-6 p-3">
            <div class="h-100 w-100 position-relative rounded-5 shadow-lg overflow-hidden" style="background-color: #013780;">
                
                <img src="{{ asset('assets/img/bengkel.jpg') }}" 
                    class="position-absolute w-100 h-100" 
                    style="object-fit: cover; filter: blur(8px) brightness(0.8); transform: scale(1.1);">
                
                <div class="position-absolute w-100 h-100 d-flex align-items-center justify-content-center">
                    <div class="card border-0 shadow-lg rounded-5 p-4" 
                        style="width: 85%; max-width: 380px; background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px);">
                        
                        <div class="text-center mb-3">
                            <h6 class="fw-bold text-dark">Login to your account</h6>
                        </div>

                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label fw-bold text-muted mb-0" style="font-size: 11px;">Email</label>
                                <input type="email" name="email" value="{{ old('email') }}" required 
                                    class="form-control border-0 border-bottom rounded-0 px-0 bg-transparent shadow-none" 
                                    style="border-bottom: 2px solid #eee !important; font-size: 14px; padding-bottom: 5px;">
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold text-muted mb-0" style="font-size: 11px;">Password</label>
                                <input type="password" name="password" required 
                                    class="form-control border-0 border-bottom rounded-0 px-0 bg-transparent shadow-none" 
                                    style="border-bottom: 2px solid #eee !important; font-size: 14px; padding-bottom: 5px;">
                            </div>

                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input shadow-none" id="remember" name="remember" style="width: 13px; height: 13px;">
                                    <label class="form-check-label fw-bold" for="remember" style="font-size: 11px;">Remember me</label>
                                </div>
                                <a href="{{ route('password.request') }}" class="fw-bold text-dark text-decoration-none border-bottom border-dark" style="font-size: 11px;">Forget password?</a>
                            </div>

                            {{-- SATU-SATUNYA NOTIFIKASI: TEPAT DI ATAS TOMBOL LOGIN --}}
                            @if ($errors->any() || session('status'))
                                <div class="alert alert-danger py-2 px-3 border-0 rounded-3 mb-3" style="font-size: 11px;">
                                    <ul class="mb-0 list-unstyled">
                                        @if (session('status'))
                                            <li>{{ session('status') }}</li>
                                        @endif
                                        @foreach ($errors->all() as $error)
                                            <li><i class="bi bi-exclamation-circle-fill me-1"></i> {{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <button type="submit" class="btn btn-dark w-100 py-2 rounded-pill fw-bold text-uppercase shadow-sm" style="font-size: 13px;">
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
    .letter-spacing-2 { letter-spacing: 2px; }
    .rounded-4 { border-radius: 1.5rem !important; }
    .rounded-5 { border-radius: 2rem !important; }
    
    .form-control:focus {
        border-color: #013780 !important;
        background-color: transparent !important;
    }

    h1 {
        font-family: 'Playfair Display', serif;
    }
</style>
@endsection