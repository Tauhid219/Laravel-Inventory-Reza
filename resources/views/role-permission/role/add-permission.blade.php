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

                        <div class="card">
                            <div class="card-header">
                                <h4 class="mb-0">Role: {{ $role->name }}
                                    <a href="{{ url('role') }}" class="btn btn-danger ms-3">Back</a>
                                </h4>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('givePermissionToRole', $role->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="mb-3">
                                        @error('permission')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                        <label for="">Permissions</label>
                                        <div class="row">
                                            @foreach ($permission as $permissions)
                                                <div class="col-md-2">
                                                    <label for="">
                                                        <input type="checkbox" name="permission[]"
                                                            value="{{ $permissions->name }}"
                                                            {{ in_array($permissions->id, $rolepermission) ? 'checked' : '' }}>
                                                        {{ $permissions->name }}
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
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
