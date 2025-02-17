@extends('vendor.layouts.app')

@section('content')

<div class="container">

    <!-- Breadcrumb Navigation -->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">{{ __('messages.products') }}</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}"><i class="bx bx-home-alt"></i></a></li>
                    <li class="breadcrumb-item"><a href="{{ route('vendor.products.index') }}">{{ __('messages.products') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ __('messages.view_details') }}</li>
                </ol>
            </nav>
        </div>

        <div class="ms-auto">
            <!-- Back Button -->
            <a href="{{ route('vendor.products.index') }}" class="btn btn-secondary">{{ __('messages.products') }}</a>
        </div>
    </div>
    <!-- End Breadcrumb -->

    <!-- Product Details -->
    <div class="row">
        <div class="col-xl-9 mx-auto">
            <h6 class="mb-0 text-uppercase">{{ __('messages.product_details') }}</h6>
            <hr />
            <div class="card">
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">{{ __('messages.product_name') }}</label>
                        <p>{{ $product->name }}</p>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">{{ __('messages.description') }}</label>
                        <p>{{ $product->description }}</p>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">{{ __('messages.price') }} ($)</label>
                        <p>{{ $product->price }}</p>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">{{ __('messages.category') }}</label>
                        <p>{{ $product->category ? $product->category->name : __('messages.no_category_assigned') }}</p>
                    </div>

                    <!-- Product Image -->
                    <div class="mb-3">
                        <label class="form-label">{{ __('messages.product_image') }}</label>
                        @if ($product->image)
                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ __('messages.product_image') }}" class="img-fluid rounded mb-2" />
                                </div>
                            </div>
                        @else
                        <div class="col-md-3">
                            <img src="{{ asset('assets/images/default-product.png') }}" alt="{{ __('messages.product_image') }}" class="rounded-circle mb-3" width="100">
                        </div>
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </div>

</div>
@push('scripts')

@endpush

@endsection
