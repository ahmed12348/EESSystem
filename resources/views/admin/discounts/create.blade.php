@extends('admin.layouts.app')

@section('title', 'Create Discount')

@section('content')
    <div class="card shadow-lg">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="bi bi-tags"></i> Create New Discount</h5>
            <a href="{{ route('admin.discounts.index') }}" class="btn btn-light btn-sm">
                <i class="bi bi-arrow-left"></i> Back
            </a>
        </div>

        <div class="card-body">
            <form action="{{ route('admin.discounts.store') }}" method="POST">
                @csrf
                
                <div class="row">
                    <!-- Discount Percentage -->
                    <div class="col-md-6 mb-3">
                        <label for="discount_value" class="form-label"><i class="bi bi-percent"></i> Discount Percentage (%)</label>
                        <input type="number" name="discount_value" class="form-control" min="1" max="100" required placeholder="Enter discount value">
                    </div>

                    <!-- Start Date -->
                    <div class="col-md-3 mb-3">
                        <label for="start_date" class="form-label"><i class="bi bi-calendar"></i> Start Date</label>
                        <input type="date" name="start_date" class="form-control" required>
                    </div>

                    <!-- End Date -->
                    <div class="col-md-3 mb-3">
                        <label for="end_date" class="form-label"><i class="bi bi-calendar"></i> End Date</label>
                        <input type="date" name="end_date" class="form-control" required>
                    </div>
                </div>

                <!-- Apply Discount To -->
                <div class="mb-3">
                    <label class="form-label"><i class="bi bi-box-seam"></i> Apply Discount To:</label>
                    <select name="type" id="discount_type" class="form-select">
                        <option value="">Select Type</option>
                        <option value="product">Specific Products</option>
                        <option value="category">Entire Category</option>
                        <option value="vendor">Entire Vendor</option>
                    </select>
                </div>

                <!-- Products List -->
                <div class="mb-3 d-none" id="product_section">
                    <label class="form-label"><i class="bi bi-cart-check"></i> Select Products</label>
                    <select name="products[]" class="form-select" multiple>
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}">{{ $product->name }} ({{ $product->price }}$)</option>
                        @endforeach
                    </select>
                </div>

                <!-- Categories List -->
                <div class="mb-3 d-none" id="category_section">
                    <label class="form-label"><i class="bi bi-list-ul"></i> Select Category</label>
                    <select name="category_id" class="form-select">
                        <option value="">-- Select Category --</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Vendors List -->
                <div class="mb-3 d-none" id="vendor_section">
                    <label class="form-label"><i class="bi bi-shop"></i> Select Vendor</label>
                    <select name="vendor_id" class="form-select">
                        <option value="">-- Select Vendor --</option>
                        @foreach ($vendors as $vendor)
                            <option value="{{ $vendor->id }}">{{ $vendor->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check-circle"></i> Create Discount
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Toggle Logic -->
    <script>
        document.getElementById('discount_type').addEventListener('change', function () {
            document.getElementById('product_section').classList.add('d-none');
            document.getElementById('category_section').classList.add('d-none');
            document.getElementById('vendor_section').classList.add('d-none');

            if (this.value === 'product') {
                document.getElementById('product_section').classList.remove('d-none');
            } else if (this.value === 'category') {
                document.getElementById('category_section').classList.remove('d-none');
            } else if (this.value === 'vendor') {
                document.getElementById('vendor_section').classList.remove('d-none');
            }
        });
    </script>
@endsection
