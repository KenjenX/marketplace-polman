@extends('layouts.store')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-5">
        <div class="content-card">
            <div class="text-center mb-4">
                <h2 class="mb-1">Login</h2>
                <p class="text-muted mb-0">Masuk ke akun Marketplace Polman</p>
            </div>

            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus class="form-control">
                </div>

                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input id="password" type="password" name="password" required class="form-control">
                </div>

                <div class="form-check mb-3">
                    <input id="remember_me" type="checkbox" class="form-check-input" name="remember">
                    <label class="form-check-label" for="remember_me">Remember me</label>
                </div>

                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <div class="d-flex gap-3">
                        <a href="{{ route('home') }}" class="btn btn-outline-secondary">Kembali ke Home</a>

                        @if (Route::has('password.request'))
                            <a class="btn btn-link px-0" href="{{ route('password.request') }}">
                                Forgot password?
                            </a>
                        @endif
                    </div>

                    <button type="submit" class="btn btn-primary">Log In</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection