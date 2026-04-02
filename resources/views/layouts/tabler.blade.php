{{-- Compatibility layout name retained so migrated views do not need a mass rename. --}}
@php
    $themeVariants = [
        'classic' => [
            'label' => 'Classic Dashboard',
            'body' => 'hold-transition sidebar-mini layout-fixed',
            'navbar' => 'navbar-white navbar-light',
            'sidebar' => 'sidebar-dark-primary',
            'logo' => 'brand-link navbar-white',
            'accent' => 'primary',
        ],
        'dark-fixed' => [
            'label' => 'Dark Fixed Dashboard',
            'body' => 'hold-transition dark-mode sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed',
            'navbar' => 'navbar-dark',
            'sidebar' => 'sidebar-dark-primary',
            'logo' => 'brand-link navbar-dark',
            'accent' => 'warning',
        ],
        'compact' => [
            'label' => 'Compact Dashboard',
            'body' => 'hold-transition sidebar-mini sidebar-collapse',
            'navbar' => 'navbar-white navbar-light',
            'sidebar' => 'sidebar-dark-primary',
            'logo' => 'brand-link navbar-white',
            'accent' => 'info',
        ],
    ];

    $activeTheme = session('adminlte_theme', 'classic');
    $theme = $themeVariants[$activeTheme] ?? $themeVariants['classic'];
    $appName = config('app.name', 'Reza Inventory');
@endphp
<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@include('layouts.adminlte.head')
<body class="{{ $theme['body'] }}">
<div class="wrapper">
    @include('layouts.adminlte.navbar', ['themeVariants' => $themeVariants, 'activeTheme' => $activeTheme, 'theme' => $theme])
    @include('layouts.adminlte.sidebar', ['themeVariants' => $themeVariants, 'activeTheme' => $activeTheme, 'theme' => $theme])

    <div class="content-wrapper">
        @include('layouts.adminlte.breadcrumbs')
        <section class="content pt-3">
            <div class="container-fluid">
                @include('layouts.adminlte.flash')
            </div>
        </section>
        @yield('content')
    </div>

    @include('layouts.adminlte.footer')
    @include('layouts.adminlte.control-sidebar')
</div>

<script src="{{ asset('adminlte/plugins/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('dist/libs/tom-select/dist/js/tom-select.complete.min.js') }}"></script>
<script src="{{ asset('adminlte/dist/js/adminlte.min.js') }}"></script>
<script src="{{ asset('adminlte/custom/inventory-adminlte.js') }}"></script>
@stack('page-libraries')
@stack('page-scripts')
@stack('scripts')
@livewireScripts
<script src="{{ asset('vendor/livewire-powergrid/powergrid.js') }}"></script>
</body>
</html>
