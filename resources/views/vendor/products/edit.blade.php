@extends('vendor.layouts.app')

@section('content')
    <div class="container">

        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">{{ __('messages.products') }}</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}"><i class="bx bx-home-alt"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ __('messages.edit_product') }}</li>
                    </ol>
                </nav>
            </div>
            <div class="ms-auto">
                <a href="{{ route('vendor.products.index') }}" class="btn btn-secondary">{{ __('messages.back') }}</a>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-9 mx-auto">
                <div class="card shadow">
                    <div class="card-body">
                        <h5 class="mb-3">{{ __('messages.edit_product') }}</h5>

                        <!-- Image Preview (Above All Inputs) -->
                        <div class="text-center mb-4">
                            <label class="form-label d-block">{{ __('messages.product_image') }}</label>
                            <div>
                                @if ($product->image)
                                    <img id="imagePreview" src="{{ asset('storage/' . $product->image) }}" 
                                         class="img-fluid border rounded shadow mb-3" 
                                         style="max-width: 100px;">
                                @else
                                    <img id="imagePreview" class="img-fluid border rounded shadow mb-3" 
                                         style="max-width: 100px; display: none;">
                                @endif
                            </div>
                        </div>

                        <!-- Product Form -->
                        <form action="{{ route('vendor.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <!-- Product Name -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="name" class="form-label">{{ __('messages.product_name') }}</label>
                                        <input class="form-control" type="text" id="name" name="name"
                                            value="{{ old('name', $product->name) }}" required>
                                        @error('name') <div class="text-danger">{{ $message }}</div> @enderror
                                    </div>
                                </div>

                                <!-- Price -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="price" class="form-label">{{ __('messages.price') }} ($)</label>
                                        <input class="form-control" type="text" id="price" name="price"
                                            value="{{ old('price', $product->price) }}" required>
                                        @error('price') <div class="text-danger">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Description -->
                            <div class="mb-3">
                                <label for="description" class="form-label">{{ __('messages.description') }}</label>
                                <textarea class="form-control" id="description" name="description"
                                    rows="4">{{ old('description', $product->description) }}</textarea>
                                @error('description') <div class="text-danger">{{ $message }}</div> @enderror
                            </div>

                            <div class="row">
                                <!-- Items -->
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="items" class="form-label">{{ __('messages.items') }}</label>
                                        <input class="form-control" type="number" id="items" name="items"
                                            value="{{ old('items', $product->items) }}">
                                        @error('items') <div class="text-danger">{{ $message }}</div> @enderror
                                    </div>
                                </div>

                                <!-- Color -->
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="color" class="form-label">{{ __('messages.color') }}</label>
                                        <input class="form-control" type="text" id="color" name="color"
                                            value="{{ old('color', $product->color) }}">
                                        @error('color') <div class="text-danger">{{ $message }}</div> @enderror
                                    </div>
                                </div>

                                <!-- Shape -->
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="shape" class="form-label">{{ __('messages.shape') }}</label>
                                        <input class="form-control" type="text" id="shape" name="shape"
                                            value="{{ old('shape', $product->shape) }}">
                                        @error('shape') <div class="text-danger">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Category Selection -->
                            <div class="mb-3">
                                <label for="category_id" class="form-label">{{ __('messages.select_category') }}</label>
                                <select class="form-select select2" id="category_id" name="category_id">
                                    <option value="">{{ __('messages.select_category_placeholder') }}</option>
                                    @foreach ($categories as $cat)
                                        <option value="{{ $cat->id }}" {{ $product->category_id == $cat->id ? 'selected' : '' }}>
                                            {{ $cat->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id') <div class="text-danger">{{ $message }}</div> @enderror
                            </div>
                            
                            <!-- Image Upload -->
                            <div class="mb-3">
                                <label for="image" class="form-label">{{ __('messages.image') }}</label>
                                <input class="form-control" type="file" id="image" name="image" onchange="previewImage(event)">
                                @error('image') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <!-- Submit Button -->
                            <button type="submit" class="btn btn-primary mt-3">{{ __('messages.update') }}</button>
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
                $('.select2').select2({
                    width: '100%',
                    placeholder: "{{ __('messages.select_category') }}",
                    allowClear: true
                });
            });
        </script>
    @endpush
@endsection
