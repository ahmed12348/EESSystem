@extends('admin.layouts.app')

@section('title', __('messages.edit_user'))

@section('content')
<div class="container">
    <!-- Breadcrumb Navigation -->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">{{ __('messages.users') }}</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item">
                        <a href="{{ url('/') }}"><i class="bx bx-home-alt"></i></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">{{ __('messages.edit_user') }}</li>
                </ol>
            </nav>
        </div>
        <div class="ms-auto">
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">{{ __('messages.back') }}</a>
        </div>
    </div>
    <!-- End Breadcrumb -->

    <!-- User Edit Form -->
    <div class="row">
        <div class="col-xl-9 mx-auto">
            <h6 class="mb-0 text-uppercase">{{ __('messages.edit') }}</h6>
            <hr />
            <div class="card">
                <div class="card-body">
                    <!-- Display current photo as a circle inside the card -->
                    @if($user->photo)
                        <div class="text-center mb-3">
                            <img src="{{ asset('storage/' . $user->photo) }}" alt="{{ $user->name }}" class="mt-2" style="max-width: 150px; border-radius: 50%;">
                        </div>
                    @endif

                    <form action="{{ route('admin.users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Name -->
                        <div class="mb-1">
                            <label for="name" class="form-label">{{ __('messages.name') }}</label>
                            <input class="form-control" type="text" id="name" name="name" 
                                   value="{{ old('name', $user->name) }}" required>
                            @error('name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="mb-1">
                            <label for="email" class="form-label">{{ __('messages.email') }}</label>
                            <input class="form-control" type="email" id="email" name="email" 
                                   value="{{ old('email', $user->email) }}" required>
                            @error('email')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Phone -->
                        <div class="mb-1">
                            <label for="phone" class="form-label">{{ __('messages.phone') }}</label>
                            <input class="form-control" type="text" id="phone" name="phone" 
                                   value="{{ old('phone', $user->phone) }}" required>
                            @error('phone')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Password Reset -->
                        <div class="mb-1">
                            <label for="password" class="form-label">{{ __('messages.new_password') }}</label>
                            <input class="form-control" type="password" id="password" name="password" 
                                   placeholder="{{ __('messages.leave_blank_if_unchanged') }}">
                            @error('password')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Role Selection -->
                        <div class="mb-1">
                            <label for="role" class="form-label">{{ __('messages.role') }}</label>
                            <select class="form-select" id="role" name="role">
                                @foreach($roles as $role)
                                    <option value="{{ $role->name }}" {{ $user->hasRole($role->name) ? 'selected' : '' }}>
                                        {{ ucfirst($role->name) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('role')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Photo Update -->
                        <div class="mb-3">
                            <label for="photo" class="form-label">{{ __('messages.picture') }}</label>
                            <input class="form-control" type="file" id="photo" name="photo">
                            @error('photo')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="btn btn-primary">{{ __('messages.update') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
