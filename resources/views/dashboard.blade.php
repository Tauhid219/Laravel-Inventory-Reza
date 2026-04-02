@php
    $activeTheme = session('adminlte_theme', 'classic');
    $dashboardView = match ($activeTheme) {
        'dark-fixed' => 'dashboard.variants.dark-fixed',
        'compact' => 'dashboard.variants.compact',
        default => 'dashboard.variants.classic',
    };
    $dashboardMeta = match ($activeTheme) {
        'dark-fixed' => [
            'title' => 'Dark Fixed Dashboard',
            'subtitle' => 'AdminLTE v2 inspired fixed shell with stronger contrast for long operator sessions.',
            'breadcrumb' => 'Dark Dashboard',
        ],
        'compact' => [
            'title' => 'Compact Dashboard',
            'subtitle' => 'AdminLTE v3 inspired compact mode with a quicker overview footprint.',
            'breadcrumb' => 'Compact Dashboard',
        ],
        default => [
            'title' => 'Classic Dashboard',
            'subtitle' => 'AdminLTE v1 inspired overview for daily inventory operations.',
            'breadcrumb' => 'Dashboard',
        ],
    };

    $completionRate = $orders > 0 ? round(($completedOrders / $orders) * 100) : 0;
    $inventoryFamilies = $categories + $subCategories;
@endphp

@extends('layouts.tabler')

@section('title', 'Dashboard')

@section('content')
    <x-adminlte.page-header :title="$dashboardMeta['title']" :subtitle="$dashboardMeta['subtitle']" container-class="container-fluid">
        <x-slot:breadcrumbs>
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active">{{ $dashboardMeta['breadcrumb'] }}</li>
            </ol>
        </x-slot:breadcrumbs>

        @if ($activeTheme === 'compact')
            <x-slot:actions>
                <div class="btn-group">
                    <a href="{{ route('ordersV2.create') }}" class="btn btn-primary">New Order</a>
                    <a href="{{ route('products.index') }}" class="btn btn-default">Browse Inventory</a>
                </div>
            </x-slot:actions>
        @endif
    </x-adminlte.page-header>

    <x-adminlte.page-body container-class="container-fluid">
        @include($dashboardView, [
            'completionRate' => $completionRate,
            'inventoryFamilies' => $inventoryFamilies,
        ])
    </x-adminlte.page-body>
@endsection
