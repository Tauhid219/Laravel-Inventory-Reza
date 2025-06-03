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
                                <h4>Edit User
                                    <a href="{{ route('user.index') }}" class="btn btn-danger float-end">Back</a>
                                </h4>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('user.update', $user->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="mb-3">
                                        <label for="">Name</label>
                                        <input type="text" name="name" value="{{ $user->name }}"
                                            class="form-control" />
                                        @error('name')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="">Email</label>
                                        <input type="text" name="email" readonly value="{{ $user->email }}"
                                            class="form-control" />
                                    </div>
                                    <div class="mb-3">
                                        <label for="">Password</label>
                                        <input type="text" name="password" class="form-control" />
                                        @error('password')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="">Role</label>
                                        <select name="role[]" class="form-control">
                                            <option value="">Select Role</option>
                                            @foreach ($role as $roles)
                                                <option value="{{ $roles->id }}"
                                                    {{ in_array($roles->id, $userRole) ? 'selected' : '' }}>
                                                    {{ $roles->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('role')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <button type="submit" class="btn btn-primary">Update</button>
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
