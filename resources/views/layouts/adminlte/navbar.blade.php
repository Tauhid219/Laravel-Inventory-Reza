<nav class="main-header navbar navbar-expand {{ $theme['navbar'] }}">
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button">
                <i class="fas fa-bars"></i>
            </a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="{{ route('dashboard') }}" class="nav-link">Dashboard</a>
        </li>
        <li class="nav-item d-none d-md-inline-block">
            <span class="nav-link text-muted">{{ $themeVariants[$activeTheme]['label'] }}</span>
        </li>
    </ul>

    <ul class="navbar-nav ml-auto align-items-center">
        <li class="nav-item dropdown mr-2">
            <a class="nav-link" data-toggle="dropdown" href="#" role="button" aria-expanded="false">
                <i class="fas fa-palette mr-1"></i>
                <span class="d-none d-md-inline">Theme</span>
            </a>
            <div class="dropdown-menu dropdown-menu-right">
                @foreach ($themeVariants as $key => $variant)
                    <a href="{{ route('theme.switch', $key) }}"
                       class="dropdown-item d-flex align-items-center justify-content-between {{ $activeTheme === $key ? 'active' : '' }}">
                        <span>{{ $variant['label'] }}</span>
                        @if ($activeTheme === $key)
                            <i class="fas fa-check text-success"></i>
                        @endif
                    </a>
                @endforeach
            </div>
        </li>

        <li class="nav-item">
            <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                <i class="fas fa-expand-arrows-alt"></i>
            </a>
        </li>

        <li class="nav-item dropdown user-menu">
            <a href="#" class="nav-link dropdown-toggle d-flex align-items-center" data-toggle="dropdown">
                <img src="{{ Avatar::create(Auth::user()->name)->toBase64() }}"
                     class="user-image img-circle elevation-2"
                     alt="{{ Auth::user()->name }}">
                <span class="d-none d-md-inline ml-2">{{ Auth::user()->name }}</span>
            </a>
            <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <li class="user-header bg-{{ $theme['accent'] }}">
                    <img src="{{ Avatar::create(Auth::user()->name)->toBase64() }}"
                         class="img-circle elevation-2"
                         alt="{{ Auth::user()->name }}">
                    <p>
                        {{ Auth::user()->name }}
                        <small>{{ Auth::user()->getRoleNames()->implode(', ') ?: 'User' }}</small>
                    </p>
                </li>
                <li class="user-footer">
                    <a href="{{ route('profile.edit') }}" class="btn btn-default btn-flat">Profile</a>
                    <form action="{{ route('logout') }}" method="POST" class="d-inline float-right">
                        @csrf
                        <button type="submit" class="btn btn-default btn-flat">Logout</button>
                    </form>
                </li>
            </ul>
        </li>
    </ul>
</nav>
