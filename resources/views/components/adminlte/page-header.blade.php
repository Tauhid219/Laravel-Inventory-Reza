@props([
    'title',
    'subtitle' => null,
    'containerClass' => 'container-xl',
])

<div class="page-header d-print-none">
    <div class="{{ $containerClass }}">
        <div class="row g-2 align-items-center mb-3">
            <div class="col">
                <h2 class="page-title">{{ $title }}</h2>
                @if ($subtitle)
                    <div class="text-muted mt-1">{{ $subtitle }}</div>
                @endif
            </div>

            @isset($actions)
                <div class="col-auto ms-auto d-print-none">
                    {{ $actions }}
                </div>
            @endisset
        </div>

        @isset($breadcrumbs)
            {{ $breadcrumbs }}
        @endisset
    </div>
</div>
