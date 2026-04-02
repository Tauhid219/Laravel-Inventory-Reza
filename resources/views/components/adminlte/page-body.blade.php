@props([
    'containerClass' => 'container-xl',
])

<div class="page-body">
    <div class="{{ $containerClass }}">
        {{ $slot }}
    </div>
</div>
