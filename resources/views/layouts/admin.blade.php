<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Admin Marketplace Polman' }}</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f7fb;
            color: #1b2a41;
        }

        .admin-navbar {
            background: linear-gradient(90deg, #0d3b66, #1d5f94);
        }

        .admin-navbar .navbar-brand,
        .admin-navbar .nav-link,
        .admin-navbar .navbar-text {
            color: #fff !important;
        }

        .admin-wrapper {
            padding-top: 32px;
            padding-bottom: 48px;
        }

        .admin-card {
            background: #fff;
            border: 1px solid #dbe7f3;
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 10px 30px rgba(13, 59, 102, 0.05);
        }

        .table thead th {
            background-color: #eef5fb;
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

        .info-box {
            background: #f8fbff;
            border: 1px solid #dbe7f3;
            border-radius: 14px;
            padding: 16px;
        }

        .section-title {
            font-size: 1rem;
            font-weight: 700;
            margin-bottom: 12px;
        }

        .badge-expired {
            background-color: #f5c2c7;
            color: #842029;
        }

    </style>
    @stack('styles')
</head>
<body>
    @include('partials.admin-navbar')

    <main class="admin-wrapper">
        <div class="container">
            @include('partials.alerts')
            @yield('content')
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>