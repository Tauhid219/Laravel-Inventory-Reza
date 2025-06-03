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
                                <h4>Users
                                    @can('create user')
                                        <a href="{{ route('user.create') }}" class="btn btn-primary float-end">Add
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
                                        @foreach ($user as $users)
                                            <tr>
                                                <td>{{ $users->id }}</td>
                                                <td>{{ $users->name }}</td>
                                                <td>{{ $users->email }}</td>
                                                <td>
                                                    @if (!empty($users->getRoleNames()))
                                                        @foreach ($users->getRoleNames() as $rolename)
                                                            <label class="badge bg-primary mx-1">{{ $rolename }}</label>
                                                        @endforeach
                                                    @endif
                                                </td>
                                                <td>
                                                    <form action="{{ route('user.destroy', $users->id) }}" method="POST">
                                                        @can('update user')
                                                            <a href="{{ route('user.edit', $users->id) }}"
                                                                class="btn btn-success">Edit</a>
                                                        @endcan
                                                        @csrf
                                                        @method('DELETE')
                                                        @can('delete user')
                                                            <button type="submit" class="btn btn-danger">Delete</button>
                                                        @endcan
                                                    </form>
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
