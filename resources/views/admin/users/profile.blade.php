@extends('admin.layouts.app')

@section('title', __('messages.user_profile'))

@section('content')
<div class="container">
    <h3>{{ __('messages.user_profile') }}</h3>

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

                    <span class="badge bg-success">{{ __('messages.user') }}</span>

                    <hr>

                    <!-- Display business details -->
                    <h6>{{ __('messages.name') }}: <strong>{{ Auth::user()->name ?? __('messages.na') }}</strong></h6>
                </div>
            </div>
        </div>

        <!-- Profile Update Form -->
        <div class="col-md-8">
            <form method="POST" action="{{ route('admin.profile.update') }}" enctype="multipart/form-data">
                @csrf

                <!-- Name Field -->
                <div class="mb-3">
                    <label for="name" class="form-label">{{ __('messages.name') }}</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', Auth::user()->name) }}">
                </div>

                <!-- Email Field (disabled for display) -->
                <div class="mb-3">
                    <label for="email" class="form-label">{{ __('messages.email') }}</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email', Auth::user()->email) }}" readonly>
                </div>

                <!-- Profile Picture -->
                <div class="mb-3">
                    <label for="profile_picture" class="form-label">{{ __('messages.profile_picture') }}</label>
                    <input type="file" name="profile_picture" class="form-control">
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-primary mb-3">{{ __('messages.update_profile') }}</button>
            </form>
        </div>
    </div>
</div>
@endsection

