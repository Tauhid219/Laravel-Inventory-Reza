@props([
    // TODO: WIP
])

@if (session('success'))
    <div class="alert alert-success alert-dismissible bg-white" role="alert">
        <h3 class="mb-1">Success</h3>
        <p>{{ session('success') }}</p>

        <button type="button" class="close" data-dismiss="alert" aria-label="close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger alert-dismissible bg-white" role="alert">
        <h3 class="mb-1">Oops...</h3>
        <p>{{ session('error') }}</p>

        <button type="button" class="close" data-dismiss="alert" aria-label="close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

@if ($errors->any())
    <div class="alert alert-danger alert-dismissible bg-white" role="alert">
        <h3 class="mb-1">Oops...</h3>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>

        <button type="button" class="close" data-dismiss="alert" aria-label="close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif
