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
                                <h4>Permissions
                                    @can('create permission')
                                        <a href="{{ url('permission/create') }}" class="btn btn-primary float-end">Add
                                            Permission</a>
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
                                        @foreach ($permission as $permissions)
                                            <tr>
                                                <td>{{ $permissions->id }}</td>
                                                <td>{{ $permissions->name }}</td>
                                                <td>
                                                    <form action="{{ route('pr.destroy', $permissions->id) }}"
                                                        method="POST">
                                                        @can('update permission')
                                                            <a href="{{ route('pr.edit', $permissions->id) }}"
                                                                class="btn btn-success">Edit</a>
                                                        @endcan
                                                        @csrf
                                                        @method('DELETE')
                                                        @can('delete permission')
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
