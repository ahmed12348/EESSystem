@extends('admin.layouts.app')

@section('content')
<div class="container">
    <!-- Breadcrumb Navigation -->

    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Users</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}"><i class="bx bx-home-alt"></i></a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit User</li>
                </ol>
            </nav>
        </div>
        <div class="ms-auto">
            <!-- Back Button -->
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Back</a>
        </div>
    </div>
    <!-- End Breadcrumb -->

    <!-- User Edit Form -->
    <div class="row">
        <div class="col-xl-9 mx-auto">
            <h6 class="mb-0 text-uppercase">Edit User</h6>
            <hr/>
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <!-- Name Input -->
                        <div class="mb-1">
                            <label for="name" class="form-label">Name</label>
                            <input class="form-control" type="text" id="name" name="name" placeholder="Enter user name" value="{{ old('name', $user->name) }}" required>
                            @error('name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-1">
                            <label for="phone" class="form-label">Phone</label>
                            <input class="form-control" type="text" id="phone" name="phone" value="{{ old('name', $user->phone) }}"
                                placeholder="Enter phone" required>
                            @error('phone')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <!-- Email Input -->
                        <div class="mb-1">
                            <label for="email" class="form-label">Email</label>
                            <input class="form-control" type="email" id="email" name="email" placeholder="Enter user email" value="{{ old('email', $user->email) }}" required>
                            @error('email')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Password Input (Optional) -->
                        <div class="mb-1">
                            <label for="password" class="form-label">Password (Leave blank to keep current password)</label>
                            <input class="form-control" type="password" id="password" name="password" placeholder="Enter new password">
                            @error('password')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Select Role Input with Select2 -->
                        <div class="mb-1">
                            <label for="role" class="form-label">Select Role</label>
                            <select class="form-select select2" id="role" name="role">
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}" {{ $user->roles[0]?->id == $role->id ? 'selected' : '' }}>{{$role->name   }}</option>
                                @endforeach
                            </select>
                            @error('role')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>



                        <!-- File Input for Profile Picture -->
                        <div class="mb-3">
                            <label for="profile_picture" class="form-label">Profile Picture</label>
                            <input class="form-control" type="file" id="profile_picture" name="profile_picture">
                            @error('profile_picture')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>


                        <!-- Submit Button -->
                        <button type="submit" class="btn btn-primary">Update User</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- End User Edit Form -->
</div>

<!-- Scripts to add Select2 functionality -->
@push('scripts')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.select2').select2();
        });
    </script>
@endpush

@endsection
