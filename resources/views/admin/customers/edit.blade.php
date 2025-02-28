@extends('admin.layouts.app')

@section('title', __('messages.edit_customer'))

@section('content')
    <div class="container">

        <!-- Breadcrumb -->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">{{ __('messages.customers') }}</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}"><i class="bx bx-home-alt"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ __('messages.edit_customer') }}</li>
                    </ol>
                </nav>
            </div>
            <div class="ms-auto">
                <a href="{{ route('admin.customers.index') }}" class="btn btn-secondary">{{ __('messages.back') }}</a>
            </div>
        </div>
        <!-- End Breadcrumb -->

        <div class="row">
            <div class="col-xl-9 mx-auto">
                <h6 class="mb-0 text-uppercase">{{ __('messages.edit_customer') }}</h6>
                <hr />
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('admin.customers.update', $customer->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <!-- Name -->
                            <div class="mb-1">
                                <label for="name" class="form-label">{{ __('messages.name') }}</label>
                                <input class="form-control" type="text" id="name" name="name"
                                    value="{{ old('name', $customer->name) }}" required>
                                @error('name') <div class="text-danger">{{ $message }}</div> @enderror
                            </div>

                            <!-- Business Name -->
                            <div class="mb-1">
                                <label for="business_name" class="form-label">{{ __('messages.business_name') }}</label>
                                <input class="form-control" type="text" id="business_name" name="business_name"
                                    value="{{ old('business_name', $customer->vendor->business_name ?? '') }}" required>
                                @error('business_name') <div class="text-danger">{{ $message }}</div> @enderror
                            </div>

                            <!-- Business Type -->
                            <div class="mb-1">
                                <label for="business_type" class="form-label">{{ __('messages.business_type') }}</label>
                                <input class="form-control" type="text" id="business_type" name="business_type"
                                    value="{{ old('business_type', $customer->vendor->business_type ?? '') }}" required>
                                @error('business_type') <div class="text-danger">{{ $message }}</div> @enderror
                            </div>

                            <!-- Phone -->
                            <div class="mb-1">
                                <label for="phone" class="form-label">{{ __('messages.phone') }}</label>
                                <input class="form-control" type="text" id="phone" name="phone"
                                    value="{{ old('phone', $customer->phone) }}" required>
                                @error('phone') <div class="text-danger">{{ $message }}</div> @enderror
                            </div>



                            <!-- Address -->
                            <div class="mb-1">
                                <label for="address" class="form-label">{{ __('messages.address') }}</label>
                                <input class="form-control" type="text" id="address" name="address"
                                    value="{{ old('address', $customer->vendor->address ?? '') }}" required>
                                @error('address') <div class="text-danger">{{ $message }}</div> @enderror
                            </div>

                            <div class="row">
                                <!-- Latitude -->
                                <div class="col-md-6 mb-1">
                                    <label for="latitude" class="form-label">{{ __('messages.latitude') }}</label>
                                    <input class="form-control" type="number" step="any" id="latitude" name="latitude"
                                        value="{{ old('latitude', $customer->vendor->latitude ?? '') }}">
                                    @error('latitude') <div class="text-danger">{{ $message }}</div> @enderror
                                </div>

                                <!-- Longitude -->
                                <div class="col-md-6 mb-1">
                                    <label for="longitude" class="form-label">{{ __('messages.longitude') }}</label>
                                    <input class="form-control" type="number" step="any" id="longitude" name="longitude"
                                        value="{{ old('longitude', $customer->vendor->longitude ?? '') }}">
                                    @error('longitude') <div class="text-danger">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <div class="mb-1">
                            <label for="city_id" class="form-label">{{ __('messages.select_city') }}</label>
                            <select class="form-select select2" id="city_id" name="city_id" required>
                                <option value="">{{ __('messages.select_city') }}</option>
                                @foreach ($cities as $city)
                                    <option value="{{ $city->id }}" {{ old('city_id', $customer->vendor?->city_id ?? '') == $city->id ? 'selected' : '' }}>
                                        {{ $city->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('city_id')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Region Dropdown -->
                        <div class="mb-1">
                            <label for="region_id" class="form-label">{{ __('messages.select_region') }}</label>
                            <select class="form-select select2" id="region_id" name="region_id">
                                <option value="">{{ __('messages.select_region') }}</option>
                                @foreach ($regions as $region)
                                    <option value="{{ $region->id }}" {{ old('region_id', $customer->vendor?->region_id ?? '') == $region->id ? 'selected' : '' }}>
                                        {{ $region->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('region_id')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                            <!-- Zone Selection -->
                            <div class="mb-1">
                                <label for="zone" class="form-label">{{ __('messages.zone') }}</label>
                                <select class="form-select" id="zone" name="zone" required>
                                    <option value="zone_1" {{ old('zone', $customer->vendor?->zone) == 'zone_1' ? 'selected' : '' }}>Zone 1</option>
                                    <option value="zone_2" {{ old('zone', $customer->vendor?->zone) == 'zone_2' ? 'selected' : '' }}>Zone 2</option>
                                    <option value="zone_3" {{ old('zone', $customer->vendor?->zone) == 'zone_3' ? 'selected' : '' }}>Zone 3</option>
                                </select>
                                @error('zone') <div class="text-danger">{{ $message }}</div> @enderror
                            </div>

                            <!-- Notes -->
                            <div class="mb-1">
                                <label for="notes" class="form-label">{{ __('messages.notes') }}</label>
                                <textarea class="form-control" id="notes" name="notes" rows="3">{{ old('notes', $customer->vendor?->notes) }}</textarea>
                                @error('notes') <div class="text-danger">{{ $message }}</div> @enderror
                            </div>

                            <!-- Submit Button -->
                            <button type="submit" class="btn btn-primary mt-2">{{ __('messages.update') }}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        $(document).ready(function() {
            $('.select2').select2();

            $('#city_id').change(function() {
                var cityId = $(this).val();
                $('#region_id').html('<option value="">{{ __("messages.loading") }}</option>');

                if (cityId) {
                    $.ajax({
                        url: '{{ route("getRegionsByCity") }}',
                        type: 'GET',
                        data: { city_id: cityId },
                        success: function(response) {
                            $('#region_id').html('<option value="">{{ __("messages.select_region") }}</option>');
                            $.each(response, function(key, value) {
                                $('#region_id').append('<option value="'+ value.id +'">'+ value.name +'</option>');
                            });
                        }
                    });
                } else {
                    $('#region_id').html('<option value="">{{ __("messages.select_region") }}</option>');
                }
            });
        });
    </script>
    @endpush

@endsection
