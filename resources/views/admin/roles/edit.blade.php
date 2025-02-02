@extends('admin.layouts.app')

@section('content')
<div class="container">
    <!-- Breadcrumb Navigation -->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Roles</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}"><i class="bx bx-home-alt"></i></a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.roles.index') }}">Roles</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit Role</li>
                </ol>
            </nav>
        </div>
        <div class="ms-auto">
            <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary">Back</a>
        </div>
    </div>
    <!-- End Breadcrumb -->

    <!-- Role Edit Form -->
    <div class="row">
        <div class="col-xl-9 mx-auto">
            <h6 class="mb-0 text-uppercase">Edit Role</h6>
            <hr/>
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.roles.update', $role->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="name" class="form-label">Role Name</label>
                            <input class="form-control" type="text" id="name" name="name" value="{{ $role->name }}" placeholder="Enter role name" required>
                        </div>

                        <!-- Permissions Input -->
                        <div class="mb-3">
                            <label class="form-label">Permissions</label>
                            <br />
                            @foreach ($permissions as $permission)
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="permission_{{ $permission->id }}" name="permissions[]" value="{{ $permission->id }}" 
                                    @if (in_array($permission->id, $rolePermissions)) checked @endif>
                                    <label class="form-check-label" for="permission_{{ $permission->id }}">{{ $permission->name }}</label>
                                </div>
                            @endforeach
                        </div>

                        <button type="submit" class="btn btn-primary">Update Role</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
