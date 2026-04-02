@php
    $isActive = fn (...$patterns) => request()->is(...$patterns);
    $menuOpen = fn (...$patterns) => $isActive(...$patterns) ? 'menu-open' : '';
    $activeClass = fn (...$patterns) => $isActive(...$patterns) ? 'active' : '';
@endphp
<aside class="main-sidebar elevation-4 {{ $theme['sidebar'] }}">
    <a href="{{ route('dashboard') }}" class="{{ $theme['logo'] }}">
        <img src="{{ asset('static/logo-small.svg') }}" alt="{{ config('app.name') }}" class="brand-image img-circle elevation-3 p-1 bg-white">
        <span class="brand-text font-weight-light">Reza Inventory</span>
    </a>

    <div class="sidebar">
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{ Avatar::create(Auth::user()->name)->toBase64() }}" class="img-circle elevation-2" alt="{{ Auth::user()->name }}">
            </div>
            <div class="info">
                <a href="{{ route('profile.edit') }}" class="d-block">{{ Auth::user()->name }}</a>
                <span class="sidebar-role-label">{{ Auth::user()->getRoleNames()->implode(', ') ?: 'User' }}</span>
            </div>
        </div>

        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item {{ $menuOpen('dashboard') }}">
                    <a href="#" class="nav-link {{ $activeClass('dashboard') }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Dashboard
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        @foreach ($themeVariants as $key => $variant)
                            <li class="nav-item">
                                <a href="{{ route('theme.switch', $key) }}" class="nav-link {{ $activeTheme === $key ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ $variant['label'] }}</p>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </li>

                <li class="nav-header">SALES</li>
                <li class="nav-item {{ $menuOpen('orders-v2*', 'orders*', 'due*') }}">
                    <a href="#" class="nav-link {{ $activeClass('orders-v2*', 'orders*', 'due*') }}">
                        <i class="nav-icon fas fa-shopping-cart"></i>
                        <p>Orders<i class="fas fa-angle-left right"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item"><a href="{{ route('ordersV2.index') }}" class="nav-link {{ $activeClass('orders-v2') }}"><i class="far fa-circle nav-icon"></i><p>All Orders</p></a></li>
                        <li class="nav-item"><a href="{{ route('ordersV2.pendingOrders') }}" class="nav-link {{ $activeClass('orders-v2/pending') }}"><i class="far fa-circle nav-icon"></i><p>Pending Orders</p></a></li>
                        <li class="nav-item"><a href="{{ route('ordersV2.completedOrders') }}" class="nav-link {{ $activeClass('orders-v2/completed') }}"><i class="far fa-circle nav-icon"></i><p>Completed Orders</p></a></li>
                        <li class="nav-item"><a href="{{ route('due.index') }}" class="nav-link {{ $activeClass('due/orders*') }}"><i class="far fa-circle nav-icon"></i><p>Due Orders</p></a></li>
                    </ul>
                </li>

                <li class="nav-item {{ $menuOpen('purchases*') }}">
                    <a href="#" class="nav-link {{ $activeClass('purchases*') }}">
                        <i class="nav-icon fas fa-truck-loading"></i>
                        <p>Purchases<i class="fas fa-angle-left right"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item"><a href="{{ route('purchases.index') }}" class="nav-link {{ $activeClass('purchases') }}"><i class="far fa-circle nav-icon"></i><p>All Purchases</p></a></li>
                        <li class="nav-item"><a href="{{ route('purchases.approvedPurchases') }}" class="nav-link {{ $activeClass('purchases/approved') }}"><i class="far fa-circle nav-icon"></i><p>Approved</p></a></li>
                        <li class="nav-item"><a href="{{ route('purchases.pendingPurchases') }}" class="nav-link {{ $activeClass('purchases/pending') }}"><i class="far fa-circle nav-icon"></i><p>Pending</p></a></li>
                        <li class="nav-item"><a href="{{ route('purchases.dailyPurchaseReport') }}" class="nav-link {{ $activeClass('purchases/report') }}"><i class="far fa-circle nav-icon"></i><p>Reports</p></a></li>
                    </ul>
                </li>

                <li class="nav-header">INVENTORY</li>
                <li class="nav-item">
                    <a href="{{ route('products.index') }}" class="nav-link {{ $activeClass('products*') }}">
                        <i class="nav-icon fas fa-boxes"></i>
                        <p>Products</p>
                    </a>
                </li>
                <li class="nav-item {{ $menuOpen('categories*', 'sub-categories*', 'units*') }}">
                    <a href="#" class="nav-link {{ $activeClass('categories*', 'sub-categories*', 'units*') }}">
                        <i class="nav-icon fas fa-layer-group"></i>
                        <p>Inventory Setup<i class="fas fa-angle-left right"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item"><a href="{{ route('categories.index') }}" class="nav-link {{ $activeClass('categories*') }}"><i class="far fa-circle nav-icon"></i><p>Categories</p></a></li>
                        <li class="nav-item"><a href="{{ route('sub-categories.index') }}" class="nav-link {{ $activeClass('sub-categories*') }}"><i class="far fa-circle nav-icon"></i><p>Sub Categories</p></a></li>
                        <li class="nav-item"><a href="{{ route('units.index') }}" class="nav-link {{ $activeClass('units*') }}"><i class="far fa-circle nav-icon"></i><p>Units</p></a></li>
                    </ul>
                </li>

                <li class="nav-header">PARTIES</li>
                <li class="nav-item"><a href="{{ route('customers.index') }}" class="nav-link {{ $activeClass('customers*') }}"><i class="nav-icon fas fa-user-friends"></i><p>Customers</p></a></li>
                <li class="nav-item"><a href="{{ route('suppliers.index') }}" class="nav-link {{ $activeClass('suppliers*') }}"><i class="nav-icon fas fa-people-carry"></i><p>Suppliers</p></a></li>

                @role('super-admin|admin')
                    <li class="nav-header">ADMINISTRATION</li>
                    @role('super-admin')
                        <li class="nav-item"><a href="{{ route('users.index') }}" class="nav-link {{ $activeClass('users*') }}"><i class="nav-icon fas fa-users-cog"></i><p>Users</p></a></li>
                    @endrole
                    <li class="nav-item"><a href="{{ route('welcome-page') }}" class="nav-link {{ $activeClass('welcome-page', 'role*', 'permission*', 'user') }}"><i class="nav-icon fas fa-user-shield"></i><p>Roles & Permissions</p></a></li>
                @endrole

                <li class="nav-header">ACCOUNT</li>
                <li class="nav-item"><a href="{{ route('profile.edit') }}" class="nav-link {{ $activeClass('profile') }}"><i class="nav-icon fas fa-id-badge"></i><p>Profile</p></a></li>
                <li class="nav-item"><a href="{{ route('profile.settings') }}" class="nav-link {{ $activeClass('profile/settings') }}"><i class="nav-icon fas fa-cogs"></i><p>Settings</p></a></li>
            </ul>
        </nav>
    </div>
</aside>
