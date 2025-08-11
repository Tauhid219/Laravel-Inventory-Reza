@extends('layouts.tabler')

@section('content')
    <div class="page-body">
        <div class="container-xl">
            @livewire('tables.product-by-sub-category-table', ['subCategory' => $subCategory])
        </div>
    </div>
@endsection
