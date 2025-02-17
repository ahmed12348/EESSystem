@extends('admin.layouts.app')

@section('content')

<div class="container">

    <!-- Breadcrumb Navigation -->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Products</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}"><i class="bx bx-home-alt"></i></a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.products.index') }}">{{ __('messages.products') }}Products</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ __('messages.view_product') }}</li>
                </ol>
            </nav>
        </div>

        <div class="ms-auto">
            <!-- Back Button -->
            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">{{ __('messages.back') }}</a>
        </div>
    </div>
    <!-- End Breadcrumb -->

    <!-- Product Details -->
    <div class="row">
        <div class="col-xl-9 mx-auto">
            <h6 class="mb-0 text-uppercase">Product Details</h6>
            <hr />
            <div class="card">
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Product Name</label>
                        <p>{{ $product->name }}</p>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Product Description</label>
                        <p>{{ $product->description }}</p>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Product Price ($)</label>
                        <p>{{ $product->price }}</p>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Product Category</label>
                        <p>{{ $product->category ? $product->category->name : 'No category assigned' }}</p>
                    </div>

                    <!-- Product Image -->
                    <div class="mb-3">
                        <label class="form-label">Product Image</label>
                        @if ($product->image)
                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <img src="{{ asset('storage/' . $product->image) }}" alt="Product Image" class="img-fluid rounded mb-2" />
                                </div>
                            </div>
                        @else
                        <div class="col-md-3">
                        <img src="{{ asset('assets/images/default-product.png') }}" alt="Default Profile" class="rounded-circle mb-3" width="100">
                         </div>
                            {{-- <p>No image available for this product.</p>  --}}
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
