@extends('admin.layouts.app')

@section('content')
    <div class="container">

        <meta name="csrf-token" content="{{ csrf_token() }}">

        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Orders</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}"><i class="bx bx-home-alt"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">Add New Order</li>
                    </ol>
                </nav>
            </div>
            <div class="ms-auto">
                <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">Back</a>
            </div>
        </div>

        <!-- Order Creation Form -->
        <div class="row">
            <div class="col-xl-9 mx-auto">
                <h6 class="mb-0 text-uppercase">Create New Order</h6>
                <hr />
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('admin.orders.store') }}" method="POST">
                            @csrf

                            <!-- Select Customer -->
                            <div class="mb-1">
                                <label for="customer_id" class="form-label">Select Customer</label>
                                <select class="form-select select2" id="customer_id" name="customer_id" required>
                                    <option value="">Select Customer</option>
                                    @foreach ($customers as $customer)
                                        <option value="{{ $customer->id }}">{{ $customer->vendor?->business_name }}</option>
                                    @endforeach
                                </select>
                                @error('customer_id')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Select Products (Multiple Selection) -->
                            <div class="mb-1">
                                <label for="product_ids" class="form-label">Select Products</label>
                                <select class="form-select select2" id="product_ids" name="product_ids[]" multiple required>
                                    @foreach ($products as $product)
                                        <option value="{{ $product->id }}" data-price="{{ $product->price }}" 
                                                data-vendor="{{ $product->vendor_id }}">
                                            {{ $product->name }} - {{ $product->vendor?->business_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('product_ids')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Auto-Generated Vendor (Hidden) -->
                            <input type="hidden" id="vendor_id" name="vendor_id">

                            <!-- Order Total Price -->
                            <div class="mb-1">
                                <label for="total_price" class="form-label">Total Price</label>
                                <input class="form-control" type="text" id="total_price" name="total_price" readonly>
                            </div>

                            <!-- Order Status -->
                            <div class="mb-1">
                                <label for="status" class="form-label">Order Status</label>
                                <select class="form-select" id="status" name="status" required>
                                    <option value="pending">Pending</option>
                                    <option value="completed">Completed</option>
                                    <option value="cancelled">Cancelled</option>
                                </select>
                                @error('status')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Placed At (Auto-filled) -->
                            <input type="hidden" name="placed_at" value="{{ now() }}">

                            <!-- Submit Button -->
                            <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle"></i> Create Order</button>
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

            let selectedVendor = null;

            $('#product_ids').on('change', function() {
                let totalPrice = 0;
                let vendorId = null;
                let validSelection = true;

                $('#product_ids option:selected').each(function() {
                    let productVendor = $(this).data('vendor');
                    let productPrice = parseFloat($(this).data('price'));
                    let discount = parseFloat($(this).data('discount')) || 0;

                    if (vendorId === null) {
                        vendorId = productVendor; // Set initial vendor
                    } else if (vendorId !== productVendor) {
                        validSelection = false;
                    }

                    let finalPrice = productPrice - (productPrice * discount / 100);
                    totalPrice += finalPrice;
                });

                if (!validSelection) {
                    alert('All selected products must be from the same vendor.');
                    $(this).val(null).trigger('change'); // Reset selection
                    $('#total_price').val('');
                    $('#vendor_id').val('');
                } else {
                    $('#total_price').val(totalPrice.toFixed(2));
                    $('#vendor_id').val(vendorId);
                }
            });
        });
    </script>

    @endpush
@endsection
