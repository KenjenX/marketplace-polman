<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('assets/img/logo-polman.png') }}">
    <title>{{ $title ?? 'Marketplace Polman' }}</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #eaf3fa;
            color: #1b2a41;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .store-main {
            flex: 1;
        }

        .store-navbar {
            background-color: #013780;
            border-bottom: 1px solid #d9e6f2;
        }

        .store-brand {
            font-weight: 700;
            letter-spacing: 0.5px;
            color: #0d3b66 !important;
        }

        .store-nav-link {
            font-weight: 600;
            color: #16324f !important;
            text-transform: uppercase;
            font-size: 0.9rem;
        }

        .store-nav-link.active {
            color: #0d6efd !important;
        }

        .page-section {
            padding-top: 32px;
            padding-bottom: 48px;
        }

        .content-card {
            background: #ffffff;
            border: 1px solid #dbe7f3;
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 10px 30px rgba(13, 59, 102, 0.05);
        }

        .footer-simple {
            border-top: 1px solid #d9e6f2;
            color: #5c6f82;
            font-size: 0.95rem;
        }

        .status-badge {
            font-size: 0.85rem;
            padding: 0.45rem 0.7rem;
            border-radius: 999px;
        }

        .badge-waiting-payment {
            background-color: #fff3cd;
            color: #856404;
        }

        .badge-waiting-validation {
            background-color: #cff4fc;
            color: #055160;
        }

        .badge-rejected {
            background-color: #f8d7da;
            color: #842029;
        }

        .badge-processing {
            background-color: #d1e7dd;
            color: #0f5132;
        }

        .badge-completed {
            background-color: #d4edda;
            color: #155724;
        }

        .badge-cancelled {
            background-color: #e2e3e5;
            color: #41464b;
        }
        
        .badge-expired {
            background-color: #f5c2c7;
            color: #842029;
        }

    </style>
    @stack('styles')
</head>
<body>
    @include('partials.store-navbar')

    <main class="page-section store-main">
        <div class="container">
            @include('partials.alerts')
            @yield('content')
        </div>
    </main>

    @include('partials.store-footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>