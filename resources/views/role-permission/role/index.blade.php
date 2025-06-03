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

                        <div class="card mt-3">
                            <div class="card-header">
                                <h4>Roles
                                    @can('create role')
                                        <a href="{{ url('role/create') }}" class="btn btn-primary float-end">Add
                                            Role</a>
                                    @endcan
                                </h4>
                            </div>
                            <div class="card-body">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($role as $role)
                                            <tr>
                                                <td>{{ $role->id }}</td>
                                                <td>{{ $role->name }}</td>
                                                <td>
                                                    <form action="{{ route('rl.destroy', $role->id) }}" method="POST">
                                                        @can('update role')
                                                            <a href="{{ route('rl.edit', $role->id) }}"
                                                                class="btn btn-success">Edit</a>
                                                        @endcan
                                                        @csrf
                                                        @method('DELETE')
                                                        @can('delete role')
                                                            <button type="submit" class="btn btn-danger">Delete</button>
                                                        @endcan
                                                        @can('create role')
                                                            <a href="{{ route('addPermissionToRole', $role->id) }}"
                                                                class="btn btn-warning">Add
                                                                / Edit Role Permission</a>
                                                        @endcan
                                                        @can('view role')
                                                            <a href="{{ route('rl.show', $role->id) }}"
                                                                class="btn btn-primary">Show</a>
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
