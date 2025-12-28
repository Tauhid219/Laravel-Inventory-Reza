@extends('layouts.tabler')

@section('content')
    <div class="page-body">
        <div class="container container-xl">
            @include('role-permission.nav-links')

            <div class="container mt-5">
                <div class="row">
                    <div class="col-md-12">

                        @if (session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif

                        <div class="card mt-3">
                            <div class="card-header">
                                <h4 class="mb-0">Users
                                    @can('create user')
                                        <a href="{{ route('user.create') }}" class="btn btn-primary ms-3">Add
                                            User</a>
                                    @endcan
                                </h4>
                            </div>
                            <div class="card-body">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Roles</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($user as $singleUser)
                                            <tr>
                                                <td>{{ $singleUser->id }}</td>
                                                <td>{{ $singleUser->name }}</td>
                                                <td>{{ $singleUser->email }}</td>
                                                <td>
                                                    @foreach ($singleUser->getRoleNames() as $rolename)
                                                        <span class="badge bg-primary text-white">{{ $rolename }}</span>
                                                    @endforeach
                                                </td>
                                                <td>
                                                    <a href="{{ route('user.edit', $singleUser->id) }}"
                                                        class="btn btn-sm btn-success">Edit</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
