@extends('admin.layouts.app')

@section('title', __('messages.create_discount'))

@section('content')
    <div class="container">
        <!-- Breadcrumb Navigation -->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">{{ __('messages.discounts') }}</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}"><i class="bx bx-home-alt"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ __('messages.create_discount') }}</li>
                    </ol>
                </nav>
            </div>
            <div class="ms-auto">
                <a href="{{ route('admin.discounts.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> {{ __('messages.back') }}
                </a>
            </div>
        </div>

        <!-- Discount Creation Form -->
        <div class="row">
            <div class="col-xl-9 mx-auto">
                <h6 class="mb-0 text-uppercase">{{ __('messages.create_discount') }}</h6>
                <hr />
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('admin.discounts.store') }}" method="POST">
                            @csrf
                            <div class="row">
                                <!-- Discount Percentage -->
                                <div class="col-md-6 mb-3">
                                    <label for="discount_value" class="form-label"><i class="bi bi-percent"></i> {{ __('messages.percentage') }} (%)</label>
                                    <input type="number" name="discount_value" class="form-control" min="1" max="100" required placeholder="{{ __('messages.discount_value') }}">
                                    @error('discount_value')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Start Date -->
                                <div class="col-md-3 mb-3">
                                    <label for="start_date" class="form-label"><i class="bi bi-calendar"></i> {{ __('messages.start_date') }}</label>
                                    <input type="date" name="start_date" class="form-control" required>
                                    @error('start_date')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- End Date -->
                                <div class="col-md-3 mb-3">
                                    <label for="end_date" class="form-label"><i class="bi bi-calendar"></i> {{ __('messages.end_date') }}</label>
                                    <input type="date" name="end_date" class="form-control" required>
                                    @error('end_date')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Apply Discount To -->
                            <div class="mb-3">
                                <label class="form-label"><i class="bi bi-box-seam"></i> {{ __('messages.apply_discount_to') }}</label>
                                <select name="type" id="discount_type" class="form-select select2">
                                    <option value="">{{ __('messages.select_type') }}</option>
                                    <option value="product">{{ __('messages.product') }}</option>
                                    <option value="category">{{ __('messages.category') }}</option>
                                    <option value="vendor">{{ __('messages.vendor') }}</option>
                                    <option value="zone">{{ __('messages.zone') }}</option>
                                </select>
                                @error('type')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Dynamic Selection Fields -->
                            <div id="dynamic_selection">
                                <!-- Products -->
                                <div class="mb-3 d-none" id="product_section">
                                    <label class="form-label"><i class="bi bi-cart-check"></i> {{ __('messages.select_products') }}</label>
                                    <select name="product_id" class="form-select select2">
                                        <option value="">{{ __('messages.select_products') }}</option>
                                        @foreach ($products as $product)
                                            <option value="{{ $product->id }}">{{ $product->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('product_id')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Categories -->
                                <div class="mb-3 d-none" id="category_section">
                                    <label class="form-label"><i class="bi bi-list-ul"></i> {{ __('messages.select_category') }}</label>
                                    <select name="category_id" class="form-select select2">
                                        <option value="">{{ __('messages.select_category') }}</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('category_id')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Vendors -->
                                <div class="mb-3 d-none" id="vendor_section">
                                    <label class="form-label"><i class="bi bi-shop"></i> {{ __('messages.select_vendor') }}</label>
                                    <select name="vendor_id" class="form-select select2">
                                        <option value="">{{ __('messages.select_vendor') }}</option>
                                        @foreach ($vendors as $vendor)
                                            <option value="{{ $vendor->id }}">{{ $vendor->business_name ?? $vendor->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('vendor_id')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Zones -->
                                <div class="mb-3 d-none" id="zone_section">
                                    <label class="form-label"><i class="bi bi-map"></i> {{ __('messages.select_zone') }}</label>
                                    <select name="zone_id" class="form-select select2">
                                        <option value="">{{ __('messages.select_zone') }}</option>
                                        <option value="zone_1">Zone 1</option>
                                        <option value="zone_2">Zone 2</option>
                                        <option value="zone_3">Zone 3</option>
                                    </select>
                                    @error('zone_id')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-circle"></i> {{ __('messages.create_discount') }}
                                </button>
                            </div>
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

                $('#discount_type').on('change', function () {
                    let selectedType = $(this).val();

                    // Hide all sections
                    $('#product_section, #category_section, #vendor_section, #zone_section').addClass('d-none');
                    $('select[name="product_id"], select[name="category_id"], select[name="vendor_id"], select[name="zone_id"]').val(null).trigger('change');

                    // Show the selected section
                    if (selectedType === 'product') {
                        $('#product_section').removeClass('d-none');
                    } else if (selectedType === 'category') {
                        $('#category_section').removeClass('d-none');
                    } else if (selectedType === 'vendor') {
                        $('#vendor_section').removeClass('d-none');
                    } else if (selectedType === 'zone') {
                        $('#zone_section').removeClass('d-none');
                    }
                });
            });
        </script>
    @endpush
@endsection
