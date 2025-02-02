@extends('admin.layouts.app')

@section('content')
    <div class="container">

        <meta name="csrf-token" content="{{ csrf_token() }}">

        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">eCommerce</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}"><i class="bx bx-home-alt"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit Product</li>
                    </ol>
                </nav>
            </div>
            <div class="ms-auto">
                <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Back</a>
            </div>
        </div>

        <!-- Product Edit Form -->
        <div class="row">
            <div class="col-xl-9 mx-auto">
                <h6 class="mb-0 text-uppercase">Edit Product</h6>
                <hr />
                <div class="card">
                    <div class="card-body">

                        <!-- Display Product Image in a Nice Look -->
                        <div class="text-center mb-4">
                            @if ($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" 
                                     class="img-fluid rounded shadow border p-2" 
                                     style="max-width: 150px;" 
                                     alt="Product Image">
                            @else 
                                <img src="{{ asset('assets/images/default-product.png') }}" 
                                     class="img-fluid rounded shadow border p-2" 
                                     style="max-width: 150px;" 
                                     alt="Default Image">
                            @endif
                        </div>

                        <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <!-- Product Name -->
                            <div class="mb-1">
                                <label for="name" class="form-label">Product Name</label>
                                <input class="form-control" type="text" id="name" name="name"
                                    value="{{ old('name', $product->name) }}" required>
                                @error('name')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Description -->
                            <div class="mb-1">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control" id="description" name="description"
                                    rows="4">{{ old('description', $product->description) }}</textarea>
                                @error('description')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Price -->
                            <div class="mb-1">
                                <label for="price" class="form-label">Price</label>
                                <input class="form-control" type="number" id="price" name="price"
                                    value="{{ old('price', $product->price) }}" required>
                                @error('price')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Category Selection -->
                            <div class="mb-1">
                                <label for="category_id" class="form-label">Select Category</label>
                                <select class="form-select select2" id="category_id" name="category_id">
                                    @foreach ($categories as $cat)
                                        <option value="{{ $cat->id }}" {{ $product->category_id == $cat->id ? 'selected' : '' }}>
                                            {{ $cat->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Image Upload -->
                            <div class="mb-3">
                                <label for="image" class="form-label">Update Product Image</label>
                                <input class="form-control" type="file" id="image" name="image">
                                @error('image')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Submit Button -->
                            <button type="submit" class="btn btn-primary">Update Product</button>
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
