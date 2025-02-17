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
                    <li class="breadcrumb-item"><a href="{{ url('/') }}"><i class="bx bx-home-alt"></i></a></li>
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
            <h6 class="mb-0 text-uppercase">{{ __('messages.edit_user') }}</h6>
            <hr />
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Phone -->
                        <div class="mb-1">
                            <label for="phone" class="form-label">{{ __('messages.phone') }}</label>
                            <input class="form-control" type="text" id="phone" name="phone" value="{{ old('phone', $user->phone) }}" readonly>
                        </div>

                        <!-- Business Name -->
                        <div class="mb-1">
                            <label for="business_name" class="form-label">{{ __('messages.business_name') }}</label>
                            <input class="form-control" type="text" id="business_name" name="business_name" value="{{ old('business_name', $user->vendor?->business_name) }}" readonly>
                        </div>

                        <!-- Tax ID -->
                        <div class="mb-1">
                            <label for="tax_id" class="form-label">{{ __('messages.tax_id') }}</label>
                            <input class="form-control" type="text" id="tax_id" name="tax_id" value="{{ old('tax_id', $user->vendor?->tax_id) }}" readonly>
                        </div>

                        <!-- Location Address -->
                        <div class="mb-1">
                            <label for="location_address" class="form-label">{{ __('messages.address') }}</label>
                            <input class="form-control" type="text" id="location_address" value="{{ $user->vendor && $user->vendor->location ? $user->vendor->location->address : __('messages.na') }}" readonly>
                        </div>

                        <!-- Zone -->
                        <div class="mb-1">
                            <label for="zone" class="form-label">{{ __('messages.zone') }}</label>
                            <select class="form-select" id="zone" name="zone">
                                <option value="zone_1" {{ old('zone', $user->vendor?->zone) == 'zone_1' ? 'selected' : '' }}>Zone 1</option>
                                <option value="zone_2" {{ old('zone', $user->vendor?->zone) == 'zone_2' ? 'selected' : '' }}>Zone 2</option>
                                <option value="zone_3" {{ old('zone', $user->vendor?->zone) == 'zone_3' ? 'selected' : '' }}>Zone 3</option>
                            </select>
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
