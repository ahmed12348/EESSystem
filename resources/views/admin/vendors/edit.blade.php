@extends('admin.layouts.app')

@section('title', __('messages.edit_vendor'))

@section('content')
    <div class="container">
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">{{ __('messages.vendors') }}</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}"><i class="bx bx-home-alt"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ __('messages.edit_vendor') }}</li>
                    </ol>
                </nav>
            </div>
            <div class="ms-auto">
                <!-- Back Button -->
                <a href="{{ route('admin.vendors.index') }}" class="btn btn-secondary">{{ __('messages.back') }}</a>
            </div>
        </div>

        <!-- User Edit Form -->
        <div class="row">
            <div class="col-xl-9 mx-auto">
                <h6 class="mb-0 text-uppercase">{{ __('messages.edit_vendor') }}</h6>
                <hr/>
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('admin.vendors.update', $vendor->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <!-- Email -->
                            <div class="mb-3">
                                <label for="email" class="form-label">{{ __('messages.email') }}</label>
                                <input type="text" class="form-control" id="email" name="email" 
                                    value="{{ old('email', $vendor->email ?? '') }}" readonly>
                                @error('email')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Business Name -->
                            <div class="mb-3">
                                <label for="business_name" class="form-label">{{ __('messages.business_name') }}</label>
                                <input type="text" class="form-control" id="business_name" name="business_name" 
                                    value="{{ old('business_name', $vendor->vendor->business_name ?? '') }}" readonly>
                                @error('business_name')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Tax Number -->
                            <div class="mb-3">
                                <label for="tax_id" class="form-label">{{ __('messages.tax_id') }}</label>
                                <input type="text" class="form-control" id="tax_id" name="tax_id" 
                                    value="{{ old('tax_id', $vendor->vendor?->tax_id) }}" readonly>
                                @error('tax_id')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Zone Selection -->
                            <div class="mb-3">
                                <label for="zone" class="form-label">{{ __('messages.zone') }}</label>
                                <select class="form-select" id="zone" name="zone" required>
                                    <option value="zone_1" {{ old('zone', $vendor->vendor?->zone) == 'zone_1' ? 'selected' : '' }}>Zone 1</option>
                                    <option value="zone_2" {{ old('zone', $vendor->vendor?->zone) == 'zone_2' ? 'selected' : '' }}>Zone 2</option>
                                    <option value="zone_3" {{ old('zone', $vendor->vendor?->zone) == 'zone_3' ? 'selected' : '' }}>Zone 3</option>
                                </select>
                                @error('zone')
                                    <div class="text-danger">{{ $message }}</div>
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
