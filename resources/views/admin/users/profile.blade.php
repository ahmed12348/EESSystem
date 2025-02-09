@extends('admin.layouts.app')

@section('content')
<div class="container">
    <h3>User Profile</h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="row">
        <!-- User Profile Card -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    <!-- Display profile picture -->
                    @if(Auth::user()->profile_picture)           
                        <img src="{{ asset('storage/' . Auth::user()->profile_picture) }}" alt="Profile" class="rounded-circle mb-3" width="150">
                    @else
                        <img src="{{ asset('assets/images/default-avatar.png') }}" alt="Default Profile" class="rounded-circle mb-3" width="150">
                    @endif

                    <!-- User's name and email -->
                    <h5>{{ Auth::user()->name }}</h5>
                    <p class="text-muted">{{ Auth::user()->email }}</p>

                    <span class="badge bg-success">User</span>

                    <hr>

                    <!-- Display business details -->
                    <h6>Name: <strong>{{ $user->name ?? 'N/A' }}</strong></h6>
                </div>
            </div>
        </div>

        <!-- Profile Update Form -->
        <div class="col-md-8">
            <form method="POST" action="{{ route('admin.profile.update') }}" enctype="multipart/form-data">
                @csrf

                <!-- Name Field -->
                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', Auth::user()->name) }}" >
                </div>

                <!-- Email Field (disabled for display) -->
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email', Auth::user()->email) }}" readonly>
                </div>

                <!-- Profile Picture -->
                <div class="mb-3">
                    <label for="profile_picture" class="form-label">Profile Picture</label>
                    <input type="file" name="profile_picture" class="form-control">
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-primary mb-3">Update Profile</button>
            </form>
        </div>
    </div>
</div>
@endsection
