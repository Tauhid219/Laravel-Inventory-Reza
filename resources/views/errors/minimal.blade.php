<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>@yield('title', __('Error')) | {{ config('app.name', 'Reza Inventory') }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/dist/css/adminlte.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/custom/inventory-adminlte.css') }}">
    @php
        $errorCode = trim((string) $__env->yieldContent('code', '500'));
        $errorCodeClass = match ($errorCode) {
            '401', '403' => 'warning',
            '404' => 'info',
            '419', '429' => 'primary',
            default => 'danger',
        };
        $errorEyebrow = match ($errorCode) {
            '401' => __('Authentication required'),
            '403' => __('Access restricted'),
            '404' => __('Page unavailable'),
            '419' => __('Session refresh needed'),
            '429' => __('Please slow down'),
            '500' => __('Application issue'),
            '503' => __('Temporary downtime'),
            default => __('Something went wrong'),
        };
    @endphp
    <style>
        .inventory-error-page {
            min-height: 100vh;
            background:
                radial-gradient(circle at top left, rgba(13, 110, 253, 0.14), transparent 30%),
                radial-gradient(circle at bottom right, rgba(40, 167, 69, 0.16), transparent 34%),
                linear-gradient(145deg, #eef2f7 0%, #dfe7f1 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
        }

        .inventory-error-shell {
            width: min(1120px, 100%);
            display: grid;
            grid-template-columns: minmax(0, 1.05fr) minmax(320px, 430px);
            gap: 1.5rem;
            align-items: stretch;
        }

        .inventory-error-panel,
        .inventory-error-card {
            border-radius: 1rem;
            overflow: hidden;
            box-shadow: 0 1rem 3rem rgba(31, 45, 61, 0.12);
        }

        .inventory-error-panel {
            position: relative;
            color: #fff;
            padding: 3rem;
            background: linear-gradient(155deg, rgba(20, 28, 45, 0.97), rgba(12, 20, 34, 0.94));
        }

        .inventory-error-panel::after {
            content: "";
            position: absolute;
            right: -90px;
            bottom: -90px;
            width: 240px;
            height: 240px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.08);
        }

        .inventory-error-brand {
            display: inline-flex;
            align-items: center;
            gap: .75rem;
            color: inherit;
            text-decoration: none;
            font-weight: 700;
            letter-spacing: .02em;
        }

        .inventory-error-brand img {
            width: 42px;
            height: 42px;
        }

        .inventory-error-code {
            font-size: clamp(4rem, 9vw, 7rem);
            line-height: .9;
            font-weight: 700;
            letter-spacing: -.04em;
            margin-bottom: 1rem;
        }

        .inventory-error-card {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(8px);
        }

        .inventory-error-actions .btn {
            min-width: 150px;
        }

        .inventory-error-tip {
            border-radius: .85rem;
            background: rgba(248, 249, 250, 0.95);
        }

        @media (max-width: 991.98px) {
            .inventory-error-shell {
                grid-template-columns: 1fr;
            }

            .inventory-error-panel {
                padding: 2rem;
            }
        }
    </style>
</head>
<body class="hold-transition inventory-error-page">
    <div class="inventory-error-shell">
        <aside class="inventory-error-panel">
            <a href="{{ auth()->check() ? route('dashboard') : route('login') }}" class="inventory-error-brand mb-4">
                <img src="{{ asset('static/logo-small.svg') }}" alt="{{ config('app.name', 'Reza Inventory') }}">
                <span>{{ config('app.name', 'Reza Inventory') }}</span>
            </a>

            <span class="badge badge-light px-3 py-2 text-uppercase mb-4">{{ $errorEyebrow }}</span>

            <div class="inventory-error-code">@yield('code')</div>

            <h1 class="display-5 font-weight-bold mb-3">
                @yield('title', __('Something went wrong'))
            </h1>

            <p class="lead text-white-50 mb-4">
                @yield('message')
            </p>

            <div class="row">
                <div class="col-sm-6 mb-3">
                    <div class="rounded-lg p-3 h-100" style="background: rgba(255, 255, 255, 0.08);">
                        <div class="text-uppercase small text-white-50 mb-2">{{ __('Workspace') }}</div>
                        <div class="font-weight-semibold">{{ __('Inventory, orders, and users remain unchanged.') }}</div>
                    </div>
                </div>
                <div class="col-sm-6 mb-3">
                    <div class="rounded-lg p-3 h-100" style="background: rgba(255, 255, 255, 0.08);">
                        <div class="text-uppercase small text-white-50 mb-2">{{ __('Next step') }}</div>
                        <div class="font-weight-semibold">{{ __('Return safely, sign in again, or retry shortly.') }}</div>
                    </div>
                </div>
            </div>
        </aside>

        <section class="card inventory-error-card">
            <div class="card-body p-4 p-lg-5">
                <div class="d-flex align-items-center justify-content-between flex-wrap mb-4">
                    <span class="badge badge-{{ $errorCodeClass }} px-3 py-2">{{ __('HTTP Status') }}: @yield('code')</span>
                    <span class="text-muted small">{{ __('Generated by Laravel exception handling') }}</span>
                </div>

                <div class="mb-4">
                    <h2 class="h4 font-weight-bold mb-3">{{ __('What happened?') }}</h2>
                    <p class="text-muted mb-0">
                        @yield('message')
                    </p>
                </div>

                <div class="inventory-error-tip p-3 p-lg-4 mb-4">
                    <h3 class="h6 text-uppercase text-muted mb-2">{{ __('Recommended action') }}</h3>
                    <p class="mb-0 text-secondary">
                        {{ __('Use the safe navigation actions below. If the issue keeps returning, review the request flow or try again after a short pause.') }}
                    </p>
                </div>

                <div class="inventory-error-actions d-flex flex-wrap gap-2">
                    <a href="{{ url()->previous() }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left mr-2"></i>{{ __('Go back') }}
                    </a>

                    @auth
                        <a href="{{ route('dashboard') }}" class="btn btn-{{ $errorCodeClass }}">
                            <i class="fas fa-home mr-2"></i>{{ __('Open dashboard') }}
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-{{ $errorCodeClass }}">
                            <i class="fas fa-sign-in-alt mr-2"></i>{{ __('Back to sign in') }}
                        </a>
                    @endauth
                </div>
            </div>
        </section>
    </div>

    <script src="{{ asset('adminlte/plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('adminlte/dist/js/adminlte.min.js') }}"></script>
    <script src="{{ asset('adminlte/custom/inventory-adminlte.js') }}"></script>
</body>
</html>
