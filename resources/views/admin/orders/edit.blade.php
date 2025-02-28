@extends('admin.layouts.app')

@section('content')
<div class="container">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">{{ __('messages.orders') }}</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}"><i class="bx bx-home-alt"></i></a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ __('messages.edit_order') }}</li>
                </ol>
            </nav>
        </div>
        <div class="ms-auto">
            <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">{{ __('messages.back') }}</a>
        </div>
    </div>
  
    <!-- Order Edit Form -->
    <div class="row">
        <div class="col-xl-9 mx-auto">
            <h6 class="mb-0 text-uppercase">{{ __('messages.edit_order') }}</h6>
            <hr />
            <div class="card">
                <div class="card-body">
                    <form id="order-form" action="{{ route('admin.orders.update', $order->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Customer Selection -->
                        <div class="mb-1">
                            <label for="customer_id" class="form-label">{{ __('messages.customer') }}</label>
                            <select class="form-select select2" id="customer_id" name="customer_id" required>
                                @foreach ($customers as $customer)
                                    <option value="{{ $customer->id }}" 
                                        {{ $customer->id == $order->user_id ? 'selected' : '' }}>
                                        {{ $customer->vendor?->business_name }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="text-danger error-message" id="customer-error"></div>
                        </div>

                        <!-- Vendor Selection -->
                        <div class="mb-1">
                            <label for="vendor_id" class="form-label">{{ __('messages.vendor') }}</label>
                            <select class="form-select select2" id="vendor_id" name="vendor_id" required>
                                @foreach ($vendors as $vendor)
                                    <option value="{{ $vendor->id }}" 
                                        {{ $vendor->id == $order->vendor_id ? 'selected' : '' }}>
                                        {{ $vendor->name }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="text-danger error-message" id="vendor-error"></div>
                        </div>

                        <!-- Select Products (Multiple Selection) -->
                        <div class="mb-1">
                            <label for="product_ids" class="form-label">{{ __('messages.select_products') }}</label>
                            <select readonly class="form-select select2" id="product_ids" name="product_ids[]" multiple required>
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}" data-price="{{ $product->price }}" 
                                            data-vendor="{{ $product->vendor_id }}"
                                            {{ in_array($product->id, $selectedProducts) ? 'selected' : '' }}>
                                        {{ $product->name }} - {{ $product->vendor?->business_name }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="text-danger error-message" id="product-error"></div>
                        </div>

                        <!-- Order Status -->
                        <div class="mb-1">
                            <label for="status" class="form-label">{{ __('messages.order_status') }}</label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>{{ __('messages.pending') }}</option>
                                <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>{{ __('messages.completed') }}</option>
                                <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>{{ __('messages.cancelled') }}</option>
                            </select>
                            <div class="text-danger error-message" id="status-error"></div>
                        </div>

                        <div class="mb-1">
                            <label for="notes" class="form-label">{{ __('messages.notes') }}</label>
                            <textarea class="form-control" id="notes" name="notes" rows="3">{{ old('notes', $order->notes) }}</textarea>
                            <div class="text-danger error-message" id="notes-error"></div>
                        </div>
                        <!-- Submit Button -->
                        <button type="submit" class="btn btn-primary">{{ __('messages.update_order') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


@push('scripts')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.select2').select2();

            // Product Vendor Validation
            $('#product_ids').on('change', function() {
                let selectedProducts = $(this).find(':selected');
                let selectedVendors = [];

                selectedProducts.each(function() {
                    let vendorId = $(this).data('vendor');
                    if (!selectedVendors.includes(vendorId)) {
                        selectedVendors.push(vendorId);
                    }
                });

                if (selectedVendors.length > 1) {
                    alert('You can only select products from one vendor.');
                    $(this).val([]).trigger('change'); // Reset selection
                }
            });

            // Form Validation before submission
            $('#order-form').on('submit', function(event) {
                let isValid = true;

                // Reset error messages
                $('.error-message').text('');

                // Validate Customer
                let customer = $('#customer_id').val();
                if (!customer) {
                    $('#customer-error').text('Customer is required.');
                    isValid = false;
                }

                // Validate Vendor
                let vendor = $('#vendor_id').val();
                if (!vendor) {
                    $('#vendor-error').text('Vendor is required.');
                    isValid = false;
                }

                // Validate Products
                let products = $('#product_ids').val();
                if (!products || products.length === 0) {
                    $('#product-error').text('At least one product must be selected.');
                    isValid = false;
                }

                // Validate Status
                let status = $('#status').val();
                if (!status) {
                    $('#status-error').text('Order status is required.');
                    isValid = false;
                }

                if (!isValid) {
                    event.preventDefault(); // Stop form submission if validation fails
                }
            });
        });
    </script>
@endpush
@endsection
