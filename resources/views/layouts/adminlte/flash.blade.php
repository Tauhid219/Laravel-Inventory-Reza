@php
    $flashLevels = [
        'success' => ['class' => 'alert-success', 'icon' => 'fas fa-check-circle', 'title' => 'Success'],
        'error' => ['class' => 'alert-danger', 'icon' => 'fas fa-exclamation-triangle', 'title' => 'Error'],
        'warning' => ['class' => 'alert-warning', 'icon' => 'fas fa-exclamation-circle', 'title' => 'Warning'],
        'info' => ['class' => 'alert-info', 'icon' => 'fas fa-info-circle', 'title' => 'Info'],
    ];
    $renderedFlash = false;
    $shouldRenderDefaultFlash = trim($__env->yieldContent('use_default_flash')) !== '';
@endphp

@if ($shouldRenderDefaultFlash)
    @foreach ($flashLevels as $key => $meta)
        @if (session()->has($key))
            @php $renderedFlash = true; @endphp
            <div class="alert {{ $meta['class'] }} alert-dismissible">
                <h5 class="mb-1"><i class="{{ $meta['icon'] }} mr-2"></i>{{ $meta['title'] }}</h5>
                <p class="mb-0">{{ session($key) }}</p>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
    @endforeach

    @if ($errors->any())
        @php $renderedFlash = true; @endphp
        <div class="alert alert-danger alert-dismissible">
            <h5 class="mb-1"><i class="fas fa-ban mr-2"></i>There was a problem</h5>
            <ul class="mb-0 pl-3">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
@endif

@if (! $renderedFlash)
    @yield('flash')
@endif
