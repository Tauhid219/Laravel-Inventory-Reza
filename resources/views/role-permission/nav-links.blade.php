<div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-4">
    <div class="btn-group">
        <a href="{{ url('role') }}" class="btn btn-{{ request()->is('role*') ? 'primary' : 'default' }}">
            {{ __('Roles') }}
        </a>
        <a href="{{ url('permission') }}" class="btn btn-{{ request()->is('permission*') ? 'primary' : 'default' }}">
            {{ __('Permissions') }}
        </a>
        <a href="{{ route('user.index') }}" class="btn btn-{{ request()->is('user*') ? 'primary' : 'default' }}">
            {{ __('Users') }}
        </a>
    </div>

    <div class="text-muted small">
        {{ __('Signed in as') }} <strong>{{ Auth::user()->name }}</strong>
        <span class="mx-2">•</span>
        {{ Auth::user()->getRoleNames()->first() ?? __('User') }}
    </div>
</div>
