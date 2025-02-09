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

    <!-- Alerts -->
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <!-- User Edit Form -->
    <div class="row">
        <div class="col-xl-9 mx-auto">
            <h6 class="mb-0 text-uppercase">Edit User</h6>
            <hr />
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')


                        <!-- Phone Input -->
                        <div class="mb-1">
                            <label for="phone" class="form-label">Phone</label>
                            <input class="form-control" type="text" id="phone" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="Enter phone" readonly>
                            @error('phone')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                  

                        <!-- Business Name Input -->
                        <div class="mb-1">
                            <label for="business_name" class="form-label">Business Name</label>
                            <input class="form-control" type="text" id="business_name" name="business_name" value="{{ old('business_name', $user->vendor?->business_name) }}" readonly>
                            @error('business_name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                    

                        <!-- Tax ID Input -->
                        <div class="mb-1">
                            <label for="tax_id" class="form-label">Tax ID</label>
                            <input class="form-control" type="text" id="tax_id" name="tax_id" value="{{ old('tax_id', $user->vendor?->tax_id) }}" readonly>
                            @error('tax_id')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Location Address (Read-only) -->
                        <div class="mb-1">
                            <label for="location_address" class="form-label">Location Address</label>
                            <input class="form-control" type="text" id="location_address" value="{{ $user->vendor && $user->vendor->location ? $user->vendor->location->address : 'No location found' }}" readonly>
                        </div>

                        <!-- Business Type Input -->
                        <div class="mb-1">
                            <label for="business_type" class="form-label">Business Type</label>
                            <input class="form-control" type="text" id="business_type" name="business_type" value="{{ old('business_type', $user->vendor?->business_type) }}" readonly>
                            @error('business_type')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <!-- Zone Input -->
                        <div class="mb-1">
                            <label for="zone" class="form-label">Zone</label>
                            <select class="form-select" id="zone" name="zone" required>
                                <option value="" readonly>Select Zone</option>
                                <option value="Zone 1" {{ old('zone', $user->vendor?->zone) == 'Zone 1' ? 'selected' : '' }}>Zone 1</option>
                                <option value="Zone 2" {{ old('zone', $user->vendor?->zone) == 'Zone 2' ? 'selected' : '' }}>Zone 2</option>
                            </select>
                            @error('zone')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        {{-- <!-- Profile Picture (Photo) -->
                        <div class="mb-3">
                            <label for="photo" class="form-label">Profile Picture</label>
                            <input class="form-control" type="file" id="photo" name="photo">
                            @error('photo')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div> --}}

                        <!-- Submit Button -->
                        <button type="submit" class="btn btn-primary mt-2">Update User</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

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
