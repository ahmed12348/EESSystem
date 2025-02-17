@extends('vendor.layouts.app')

@section('title', __('messages.create_discount'))

@section('content')
    <div class="container">
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">{{ __('messages.create_discount') }}</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}"><i class="bx bx-home-alt"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ __('messages.create_discount') }}</li>
                    </ol>
                </nav>
            </div>
            <div class="ms-auto">
                <a href="{{ route('vendor.discounts.index') }}" class="btn btn-secondary">{{ __('messages.back') }}</a>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-9 mx-auto">
                <h6 class="mb-0 text-uppercase">{{ __('messages.create_discount') }}</h6>
                <hr />
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('vendor.discounts.store') }}" method="POST">
                            @csrf

                            <div class="row">
                                <!-- Discount Percentage -->
                                <div class="col-md-6 mb-3">
                                    <label for="discount_value" class="form-label"><i class="bi bi-percent"></i> {{ __('messages.discount_percentage') }}</label>
                                    <input type="number" name="discount_value" class="form-control" min="1" max="100" required placeholder="{{ __('messages.discount_percentage') }}">
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
                                <select name="product_ids[]" id="product_select" class="form-select select2" multiple required>
                                    <option value="">{{ __('messages.select_products') }}</option>
                                    @foreach ($products as $product)
                                        <option value="{{ $product->id }}">{{ $product->name }}</option>
                                    @endforeach
                                </select>
                                @error('product_ids')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-circle"></i> {{ __('messages.submit') }}
                                </button>
                            </div>
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
