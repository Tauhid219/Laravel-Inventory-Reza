@extends('layouts.tabler')

@section('content')
    <div class="page-body">
        <div class="container container-xl">
            @include('role-permission.nav-links')

            <div class="container mt-5">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>Create User
                                    <a href="{{ route('user.index') }}" class="btn btn-danger float-end">Back</a>
                                </h4>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('user.store') }}" method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="">Name</label>
                                        <input type="text" name="name" class="form-control" />
                                    </div>
                                    <div class="mb-3">
                                        <label for="">Email</label>
                                        <input type="text" name="email" class="form-control" />
                                    </div>
                                    <div class="mb-3">
                                        <label for="">Password</label>
                                        <input type="text" name="password" class="form-control" />
                                    </div>
                                    <div class="mb-3">
                                        <label for="">Role</label>
                                        <select name="role[]" class="form-control">
                                            <option value="">Select Role</option>
                                            @foreach ($role as $roles)
                                                <option value="{{ $roles->name }}">{{ $roles->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <button type="submit" class="btn btn-primary">Save</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
