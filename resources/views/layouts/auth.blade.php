<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>{{ config('app.name') }}</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}" />
    <!-- CSS files -->
    <link href="{{ asset('dist/css/tabler.min.css') }}" rel="stylesheet" />
    <style>
        @import url('https://rsms.me/inter/inter.css');

        :root {
            --tblr-font-sans-serif: 'Inter Var', -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif;
        }

        body {
            font-feature-settings: "cv03", "cv04", "cv11";
        }
    </style>

    <!-- Custom CSS for specific page.  -->
    @stack('page-styles')
</head>

<body class=" d-flex flex-column">
    <div class="page page-center">
        <div class="container container-tight py-4">
            <div class="text-center mb-4">
                <a href="{{ url('/') }}" class="navbar-brand navbar-brand-autodark">
                    <img src="{{ asset('static/logo-small.svg') }}" width="110" height="32" alt="Tabler"
                        class="navbar-brand-image">
                    {{-- <img src="{{ asset('static/csl_logo 32x32.png') }}" width="32" height="32" alt="Project Logo"
                        class="navbar-brand-image"> --}}
                    <span class="ms-2" style="font-size: 1.5rem; font-weight: bold;">Reza Inventory</span>
                </a>
            </div>

            <!-- BEGIN: Content -->
            @yield('content')
            <!-- END: Content -->
        </div>
    </div>

    <!-- Libs JS -->
    <script src="{{ asset('dist/js/tabler.min.js') }}" defer></script>

    <!-- Custom JS for specific page.  -->
    @stack('page-scripts')
</body>

</html>
