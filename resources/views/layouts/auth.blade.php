<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>@yield('title', config('app.name', 'Reza Inventory'))</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/dist/css/adminlte.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/custom/inventory-adminlte.css') }}">
    @stack('page-styles')
    <style>
        .inventory-auth-page {
            min-height: 100vh;
            background:
                radial-gradient(circle at top left, rgba(13, 110, 253, 0.14), transparent 32%),
                radial-gradient(circle at bottom right, rgba(40, 167, 69, 0.18), transparent 34%),
                linear-gradient(135deg, #f4f6f9 0%, #e9eef5 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
        }

        .inventory-auth-shell {
            width: min(1100px, 100%);
            display: grid;
            grid-template-columns: minmax(0, 1.1fr) minmax(320px, 440px);
            gap: 1.5rem;
            align-items: stretch;
        }

        .inventory-auth-panel,
        .inventory-auth-card {
            border-radius: 1rem;
            overflow: hidden;
            box-shadow: 0 1rem 3rem rgba(31, 45, 61, 0.12);
        }

        .inventory-auth-panel {
            background: linear-gradient(145deg, rgba(28, 37, 54, 0.96), rgba(17, 25, 40, 0.96));
            color: #fff;
            padding: 3rem;
            position: relative;
        }

        .inventory-auth-panel::after {
            content: "";
            position: absolute;
            inset: auto -80px -80px auto;
            width: 220px;
            height: 220px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.08);
        }

        .inventory-auth-brand {
            display: inline-flex;
            align-items: center;
            gap: .75rem;
            color: inherit;
            font-weight: 700;
            letter-spacing: .02em;
            text-decoration: none;
        }

        .inventory-auth-brand img {
            width: 42px;
            height: 42px;
        }

        .inventory-auth-card {
            margin: 0;
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(8px);
        }

        .inventory-auth-links a {
            color: #6c757d;
        }

        @media (max-width: 991.98px) {
            .inventory-auth-shell {
                grid-template-columns: 1fr;
            }

            .inventory-auth-panel {
                padding: 2rem;
            }
        }
    </style>
</head>
<body class="hold-transition login-page inventory-auth-page">
    <div class="inventory-auth-shell">
        <aside class="inventory-auth-panel">
            <a href="{{ route('login') }}" class="inventory-auth-brand mb-4">
                <img src="{{ asset('static/logo-small.svg') }}" alt="{{ config('app.name') }}">
                <span>{{ config('app.name', 'Reza Inventory') }}</span>
            </a>

            <div class="mb-4">
                <span class="badge badge-light px-3 py-2 text-uppercase">{{ __('Inventory Workspace') }}</span>
            </div>

            <h1 class="display-5 font-weight-bold mb-3">
                @yield('auth_heading', __('Inventory operations, simplified.'))
            </h1>

            <p class="lead text-white-50 mb-4">
                @yield('auth_subtitle', __('Sign in to manage products, orders, quotations, purchases, and account security from one place.'))
            </p>

            <div class="row">
                <div class="col-sm-6 mb-3">
                    <div class="bg-white bg-opacity-10 rounded-lg p-3 h-100">
                        <div class="text-uppercase small text-white-50 mb-2">{{ __('Modules') }}</div>
                        <div class="font-weight-semibold">{{ __('Sales, Stock, Users') }}</div>
                    </div>
                </div>
                <div class="col-sm-6 mb-3">
                    <div class="bg-white bg-opacity-10 rounded-lg p-3 h-100">
                        <div class="text-uppercase small text-white-50 mb-2">{{ __('Access') }}</div>
                        <div class="font-weight-semibold">{{ __('Role-aware admin flow') }}</div>
                    </div>
                </div>
            </div>
        </aside>

        <div class="card inventory-auth-card">
            @yield('content')
        </div>
    </div>

    <script src="{{ asset('adminlte/plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('adminlte/dist/js/adminlte.min.js') }}"></script>
    <script src="{{ asset('adminlte/custom/inventory-adminlte.js') }}"></script>
    @stack('page-scripts')
</body>
</html>
