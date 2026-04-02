@php
    $breadcrumbContent = trim($__env->yieldContent('breadcrumbs'));
@endphp

@if ($breadcrumbContent !== '')
    <section class="content-header pb-0">
        <div class="container-fluid">
            {!! $breadcrumbContent !!}
        </div>
    </section>
@endif
