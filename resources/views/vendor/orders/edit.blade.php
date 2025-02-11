@extends('vendor.layouts.app')

@section('content')
<div class="container">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Orders</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}"><i class="bx bx-home-alt"></i></a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit Order</li>
                </ol>
            </nav>
        </div>
        <div class="ms-auto">
            <a href="{{ route('vendor.orders.index') }}" class="btn btn-secondary">Back</a>
        </div>
    </div>
  
    <!-- Order Edit Form -->
    <div class="row">
        <div class="col-xl-9 mx-auto">
            <h6 class="mb-0 text-uppercase">Edit Order</h6>
            <hr />
            <div class="card">
                <div class="card-body">
                    <form id="order-form" action="{{ route('vendor.orders.update', $order->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Customer Selection -->
                        <div class="mb-1">
                            <label for="customer_id" class="form-label">Select Customer</label>
                            <select class="form-select select2" id="customer_id" name="" readonly >
                                @foreach ($customers as $customer)
                                    <option value="{{ $customer->id }}" 
                                        {{ $customer->id == $order->user_id ? 'selected' : '' }}>
                                        {{ $customer->vendor?->business_name }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="text-danger error-message" id="customer-error"></div>
                        </div>

                        {{-- <!-- Vendor Selection -->
                        <div class="mb-1">
                            <label for="vendor_id" class="form-label">Select Vendor</label>
                            <select class="form-select select2" id="vendor_id" name="vendor_id" required>
                                @foreach ($vendors as $vendor)
                                    <option value="{{ $vendor->id }}" 
                                        {{ $vendor->id == $order->vendor_id ? 'selected' : '' }}>
                                        {{ $vendor->name }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="text-danger error-message" id="vendor-error"></div>
                        </div> --}}

                        {{-- <div class="mb-1">
                            <label for="product_ids" class="form-label">Select Products</label>
                            <select class="form-select select2" id="product_ids" name="product_ids[]" multiple required>
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
                         --}}
                        <div class="p-3 border rounded bg-light mb-3">
                            <strong>Products:</strong>
                            <div class="mt-2">
                                @foreach ($products as $product)
                                    <span class="badge bg-success p-2 m-1">{{ $product->name }}</span>
                                @endforeach
                            </div>
                        </div>
                        <!-- Order Status -->
                        <div class="mb-1">
                            <label for="status" class="form-label">Order Status</label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                            <div class="text-danger error-message" id="status-error"></div>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="btn btn-primary">Update Order</button>
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

        });
    </script>
@endpush
@endsection
