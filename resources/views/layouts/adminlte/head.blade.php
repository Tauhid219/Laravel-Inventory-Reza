<head>
    @php
        $inventoryAdminlteCss = 'adminlte/custom/inventory-adminlte.css';
        $inventoryAdminlteCssVersion = @filemtime(public_path($inventoryAdminlteCss)) ?: time();
    @endphp
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>@yield('title', $appName)</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/dist/css/adminlte.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/livewire-powergrid/bootstrap5.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/livewire-powergrid/powergrid.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/livewire-powergrid/tom-select.css') }}">
    <link rel="stylesheet" href="{{ asset($inventoryAdminlteCss) }}?v={{ $inventoryAdminlteCssVersion }}">
    @includeIf('livewire-powergrid::assets.styles')
    @stack('page-styles')
    @stack('styles')
    @livewireStyles
</head>
