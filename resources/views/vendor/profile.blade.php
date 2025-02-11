@extends('vendor.layouts.app')

@section('content')
<div class="container">
    <h3>Vendor Profile</h3>

    <div class="row">
        <!-- Vendor Profile Card -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    {{-- <img src="{{ asset('storage/' . ($vendor->user->profile_picture ?? 'images/default-avatar.png')) }}" 
                         class="rounded-circle mb-3" width="150"> --}}

                         @if(Auth::user()->profile_picture)           
                         <img src="{{ asset('storage/' . Auth::user()->profile_picture) }}" alt="Profile"  width="150" class="rounded-circle mb-3" width="">
                         @else
                             <img src="{{ asset('assets/images/default-avatar.png') }}" alt="Default Profile" class="rounded-circle mb-3" width="150">
                         @endif
                    <h5>{{ $user->name }}</h5>
                    <p class="text-muted">{{ $user->email }}</p>
                    <span class="badge bg-success">Vendor</span>

                    <hr>

                    <h6>Business Name: <strong>{{ $vendor->business_name ?? 'N/A' }}</strong></h6>
                    <h6>Business Number: <strong>{{ $vendor->tax_id ?? 'N/A' }}</strong></h6>
                    <h6>Zone: <strong>{{ $vendor->zone ?? 'N/A' }}</strong></h6>
                </div>
            </div>
        </div>

        <!-- Profile Update Form -->
        <div class="col-md-8">
            <form method="POST" action="{{ route('vendor.profile.update') }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="business_name" class="form-label">Business Name</label>
                    <input type="text" name="business_name" class="form-control" 
                           value="{{ old('business_name', $vendor->business_name) }}" required>
                </div>

                <div class="mb-3">
                    <label for="tax_id" class="form-label">Tax ID</label>
                    <input type="text" name="tax_id" class="form-control" 
                           value="{{ old('tax_id', $vendor->tax_id) }}" required>
                </div>

            

                <div class="mb-3">
                    <label for="zone" class="form-label">Zone</label>
                    <input readonly type="text" name="zone" class="form-control" 
                           value="{{ old('zone', $vendor->zone) }}">
                </div>

                <div class="mb-3">
                    <label for="profile_picture" class="form-label">Profile Picture</label>
                    <input type="file" name="profile_picture" class="form-control">
                </div>

                <button type="submit" class="btn btn-primary mb-3">Update Profile</button>
            </form>
        </div>
    </div>
</div>
@endsection
