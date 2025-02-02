@extends('vendor.layouts.app')

@section('content')
    <div class="container">

        <meta name="csrf-token" content="{{ csrf_token() }}">

        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Product</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}"><i class="bx bx-home-alt"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">Add New Product</li>
                    </ol>
                </nav>
            </div>
            <div class="ms-auto">
                <a href="{{ route('vendor.products.index') }}" class="btn btn-secondary">Back</a>
            </div>
        </div>

        <!-- Product Creation Form -->
        <div class="row">
            <div class="col-xl-9 mx-auto">
                <h6 class="mb-0 text-uppercase">Create New Product</h6>
                <hr />
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('vendor.products.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <!-- Product Name -->
                            <div class="mb-1">
                                <label for="name" class="form-label">Product Name</label>
                                <input class="form-control" type="text" id="name" name="name"
                                    placeholder="Enter product name" required>
                                @error('name')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Description -->
                            <div class="mb-1">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control" id="description" name="description"
                                    placeholder="Enter product description" rows="4"></textarea>
                                @error('description')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Price -->
                            <div class="mb-1">
                                <label for="price" class="form-label">Price</label>
                                <input class="form-control" type="number" id="price" name="price"
                                    placeholder="Enter product price" required>
                                @error('price')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Category Selection -->
                            <div class="mb-1">
                                <label for="category_id" class="form-label">Select Category</label>
                                <select class="form-select select2" id="category_id" name="category_id">
                                    @foreach ($categories as $cat)
                                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Vendor Selection -->
                            {{-- <div class="mb-1">
                                <label for="vendor_id" class="form-label">Select Vendor</label>
                                <select class="form-select select2" id="vendor_id" name="vendor_id">
                                    @foreach ($vendors as $vendor)
                                        <option value="{{ $vendor->id }}">{{ $vendor->name }}</option>
                                    @endforeach
                                </select>
                                @error('vendor_id')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div> --}}

                            <!-- Image Upload -->
                            <div class="mb-3">
                                <label for="image" class="form-label">Product Image</label>
                                <input class="form-control" type="file" id="image" name="image">
                                @error('image')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Submit Button -->
                            <button type="submit" class="btn btn-primary">Create Product</button>
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
