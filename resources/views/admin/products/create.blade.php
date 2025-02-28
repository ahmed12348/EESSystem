@extends('admin.layouts.app')

@section('content')
    <div class="container">

        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">{{ __('messages.products') }}</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}"><i class="bx bx-home-alt"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ __('messages.add_product') }}</li>
                    </ol>
                </nav>
            </div>
            <div class="ms-auto">
                <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">{{ __('messages.back') }}</a>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-9 mx-auto">
                <div class="card shadow">
                    <div class="card-body">
                        <h5 class="mb-3">{{ __('messages.create_product') }}</h5>

                        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="row">
                                <!-- Product Name -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="name" class="form-label">{{ __('messages.product_name') }}</label>
                                        <input class="form-control" type="text" id="name" name="name"
                                            placeholder="{{ __('messages.enter_product_name') }}" required>
                                        @error('name') <div class="text-danger">{{ $message }}</div> @enderror
                                    </div>
                                </div>

                                <!-- Price -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="price" class="form-label">{{ __('messages.price') }} ($)</label>
                                        <input class="form-control" type="text" id="price" name="price"
                                            placeholder="{{ __('messages.enter_price') }}" required>
                                        @error('price') <div class="text-danger">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Description -->
                            <div class="mb-3">
                                <label for="description" class="form-label">{{ __('messages.description') }}</label>
                                <textarea class="form-control" id="description" name="description"
                                    placeholder="{{ __('messages.enter_description') }}" rows="4"></textarea>
                                @error('description') <div class="text-danger">{{ $message }}</div> @enderror
                            </div>

                            <div class="row">
                                <!-- Items -->
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="items" class="form-label">{{ __('messages.items') }}</label>
                                        <input class="form-control" type="number" id="items" name="items"
                                            placeholder="{{ __('messages.enter_items') }}">
                                        @error('items') <div class="text-danger">{{ $message }}</div> @enderror
                                    </div>
                                </div>

                                <!-- Color -->
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="color" class="form-label">{{ __('messages.color') }}</label>
                                        <input class="form-control" type="text" id="color" name="color"
                                            placeholder="{{ __('messages.enter_color') }}">
                                        @error('color') <div class="text-danger">{{ $message }}</div> @enderror
                                    </div>
                                </div>

                                <!-- Shape -->
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="shape" class="form-label">{{ __('messages.shape') }}</label>
                                        <input class="form-control" type="text" id="shape" name="shape"
                                            placeholder="{{ __('messages.enter_shape') }}">
                                        @error('shape') <div class="text-danger">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- New Fields -->
                            <div class="row">
                                <!-- Min Order Quantity -->
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="min_order_quantity" class="form-label">{{ __('messages.min_order_quantity') }}</label>
                                        <input class="form-control" type="number" id="min_order_quantity" name="min_order_quantity"
                                            placeholder="{{ __('messages.min_order_quantity') }}">
                                        @error('min_order_quantity') <div class="text-danger">{{ $message }}</div> @enderror
                                    </div>
                                </div>

                                <!-- Max Order Quantity -->
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="max_order_quantity" class="form-label">{{ __('messages.max_order_quantity') }}</label>
                                        <input class="form-control" type="number" id="max_order_quantity" name="max_order_quantity"
                                            placeholder="{{ __('messages.max_order_quantity') }}">
                                        @error('max_order_quantity') <div class="text-danger">{{ $message }}</div> @enderror
                                    </div>
                                </div>

                                <!-- Notes -->
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="notes" class="form-label">{{ __('messages.notes') }}</label>
                                        <textarea class="form-control" id="notes" name="notes"
                                            placeholder="{{ __('messages.notes') }}" rows="2"></textarea>
                                        @error('notes') <div class="text-danger">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Category Selection -->
                            <div class="mb-3">
                                <label for="category_id" class="form-label">{{ __('messages.select_category') }}</label>
                                <select class="form-select select2" id="category_ids" name="category_id">
                                    @foreach ($categories as $cat)
                                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                                @error('category_id') <div class="text-danger">{{ $message }}</div> @enderror
                            </div>

                            <div class="mb-3">
                                <label for="vendor_id" class="form-label">{{ __('messages.vendor') }}</label>
                                <select class="form-select select2" id="vendor_id" name="vendor_id" required>
                                    @foreach ($vendors as $vendor)
                                        <option value="{{ $vendor->id }}">{{ $vendor->vendor?->business_name }}</option>
                                    @endforeach
                                </select>
                                @error('vendor_id') <div class="text-danger">{{ $message }}</div> @enderror
                            </div>

                            <!-- Image Upload -->
                            <div class="mb-3">
                                <label for="image" class="form-label">{{ __('messages.product_image') }}</label>
                                <input class="form-control" type="file" id="image" name="image">
                                @error('image') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <!-- Submit Button -->
                            <button type="submit" class="btn btn-primary mt-3">{{ __('messages.create_product') }}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
