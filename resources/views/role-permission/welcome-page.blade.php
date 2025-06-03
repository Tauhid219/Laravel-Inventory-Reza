@extends('layouts.tabler')

@section('content')
    <div class="page-body">
        <div class="container container-xl">
            @include('role-permission.nav-links')

            <div class="container mt-5">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card mt-3">
                            <div class="card-header">
                            </div>
                            <div class="card-body">
                                <h1>Welcome to Role and Permission Management using Spatie</h1>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
