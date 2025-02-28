@extends('admin.layouts.app')

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
                <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                    {{ __('messages.back') }}
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-9 mx-auto">
                <div class="card shadow-lg border-0">
                    <div class="card-body p-4">
                        <h4 class="mb-4 text-center">{{ __('messages.edit_product') }}</h4>

                        <!-- Product Image Preview -->
                        <div class="text-center mb-4">
                            <label class="form-label fw-bold d-block">{{ __('messages.product_image') }}</label>
                            <div>
                                @if ($product->image)
                                    <img id="imagePreview" src="{{ asset('storage/' . $product->image) }}" 
                                         class="img-fluid border rounded shadow mb-3" 
                                         style="max-width: 120px;">
                                @else 
                                    <img src="{{ asset('assets/images/default-product.png') }}" 
                                         class="img-fluid rounded shadow border p-2" 
                                         style="max-width: 120px;" 
                                         alt="{{ __('messages.image') }}">
                                @endif 
                            </div>
                        </div>

                        <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <!-- Product Name -->
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label fw-bold">{{ __('messages.product_name') }}</label>
                                    <input class="form-control" type="text" id="name" name="name"
                                        value="{{ old('name', $product->name) }}" required>
                                    @error('name') <div class="text-danger">{{ $message }}</div> @enderror
                                </div>

                                <!-- Price -->
                                <div class="col-md-6 mb-3">
                                    <label for="price" class="form-label fw-bold">{{ __('messages.price') }} ($)</label>
                                    <input class="form-control" type="text" id="price" name="price"
                                        value="{{ old('price', $product->price) }}" required>
                                    @error('price') <div class="text-danger">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <!-- Description -->
                            <div class="mb-3">
                                <label for="description" class="form-label fw-bold">{{ __('messages.description') }}</label>
                                <textarea class="form-control" id="description" name="description"
                                    rows="3">{{ old('description', $product->description) }}</textarea>
                                @error('description') <div class="text-danger">{{ $message }}</div> @enderror
                            </div>

                            <div class="row">
                                <!-- Items -->
                                <div class="col-md-4 mb-3">
                                    <label for="items" class="form-label fw-bold">{{ __('messages.items') }}</label>
                                    <input class="form-control" type="number" id="items" name="items"
                                        value="{{ old('items', $product->items) }}">
                                    @error('items') <div class="text-danger">{{ $message }}</div> @enderror
                                </div>

                                <!-- Color -->
                                <div class="col-md-4 mb-3">
                                    <label for="color" class="form-label fw-bold">{{ __('messages.color') }}</label>
                                    <input class="form-control" type="text" id="color" name="color"
                                        value="{{ old('color', $product->color) }}">
                                    @error('color') <div class="text-danger">{{ $message }}</div> @enderror
                                </div>

                                <!-- Shape -->
                                <div class="col-md-4 mb-3">
                                    <label for="shape" class="form-label fw-bold">{{ __('messages.shape') }}</label>
                                    <input class="form-control" type="text" id="shape" name="shape"
                                        value="{{ old('shape', $product->shape) }}">
                                    @error('shape') <div class="text-danger">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <!-- New Fields -->
                            <div class="row">
                                <!-- Min Order Quantity -->
                                <div class="col-md-4 mb-3">
                                    <label for="min_order_quantity" class="form-label fw-bold">{{ __('messages.min_order_quantity') }}</label>
                                    <input class="form-control" type="number" id="min_order_quantity" name="min_order_quantity"
                                        value="{{ old('min_order_quantity', $product->min_order_quantity) }}">
                                    @error('min_order_quantity') <div class="text-danger">{{ $message }}</div> @enderror
                                </div>

                                <!-- Max Order Quantity -->
                                <div class="col-md-4 mb-3">
                                    <label for="max_order_quantity" class="form-label fw-bold">{{ __('messages.max_order_quantity') }}</label>
                                    <input class="form-control" type="number" id="max_order_quantity" name="max_order_quantity"
                                        value="{{ old('max_order_quantity', $product->max_order_quantity) }}">
                                    @error('max_order_quantity') <div class="text-danger">{{ $message }}</div> @enderror
                                </div>

                                <!-- Notes -->
                                <div class="col-md-4 mb-3">
                                    <label for="notes" class="form-label fw-bold">{{ __('messages.notes') }}</label>
                                    <textarea class="form-control" id="notes" name="notes"
                                        rows="2">{{ old('notes', $product->notes) }}</textarea>
                                    @error('notes') <div class="text-danger">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <!-- Category Selection -->
                            <div class="mb-3">
                                <label for="category_id" class="form-label fw-bold">{{ __('messages.select_category') }}</label>
                                <select class="form-select select2" id="category_id" name="category_id">
                                    @foreach ($categories as $cat)
                                        <option value="{{ $cat->id }}" {{ $product->category_id == $cat->id ? 'selected' : '' }}>
                                            {{ $cat->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id') <div class="text-danger">{{ $message }}</div> @enderror
                            </div>

                            <div class="mb-3">
                                <label for="vendor_id" class="form-label fw-bold">{{ __('messages.vendor') }}</label>
                                <select class="form-select select2" id="vendor_id" name="vendor_id" required>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}" {{ $product->vendor_id == $user->id ? 'selected' : '' }}>
                                            {{ $user->vendor?->business_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('vendor_id') <div class="text-danger">{{ $message }}</div> @enderror
                            </div>

                            <!-- Image Upload -->
                            <div class="mb-3">
                                <label for="image" class="form-label fw-bold">{{ __('messages.product_image') }}</label>
                                <input class="form-control" type="file" id="image" name="image">
                                @error('image') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <!-- Submit Button -->
                       
                                <button type="submit" class="btn btn-primary  mt-3">{{ __('messages.update') }}</button>
                         
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
