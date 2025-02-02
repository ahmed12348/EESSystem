@extends('admin.layouts.app')

@section('title', 'Products Dashboard')

@section('content')
    <!-- Breadcrumb -->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Products</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.index') }}"><i class="bx bx-home-alt"></i></a></li>
                    <li class="breadcrumb-item active" aria-current="page">Product List</li>
                </ol>
            </nav>
        </div>

        <div class="ms-auto">
            <div class="btn-group">
                <a class="btn btn-info text-white" href="{{ route('admin.products.create') }}">
                    <i class="bi bi-plus-circle"></i> Add New Product
                </a>
            </div>
        </div>
    </div>
    <!-- End Breadcrumb -->

    @include('admin.layouts.alerts')

    <div class="card">
        <div class="card-body">
            <div class="d-flex align-items-center justify-content-between">
                <!-- Import Form and Export Button -->
                <div class="d-flex align-items-center">
                    <!-- Import Form -->
                    <form action="{{ route('admin.products.import') }}" method="POST" enctype="multipart/form-data" class="d-flex align-items-center me-2">
                        @csrf
                        <input type="file" name="file" class="form-control form-control-sm" required>
                        <button type="submit" class="btn btn-secondary btn-sm ms-2">Import</button>
                    </form>
                
                    <!-- Export Sample Button (for downloading the header template) -->
                    <a href="{{ route('admin.products.export.sample') }}" class="btn btn-light btn-sm ms-2">Sample</a>
                </div>
                
                
                <!-- Search Form -->
                <div class="ms-auto d-flex align-items-center">
                    <form class="d-flex position-relative" method="GET" action="{{ route('admin.products.index') }}">
                        <div class="position-absolute top-50 translate-middle-y search-icon px-3">
                            <i class="bi bi-search"></i>
                        </div>
                        <input class="form-control form-control-sm ps-5" type="text" name="search" placeholder="Search Products" value="{{ request()->query('search') }}">
                    </form>
                </div>
            </div>

            <div class="table-responsive mt-3">
                <table id='example2' class="table align-middle table-hover">
                    <thead class="table-secondary">
                        <tr>
                            <th>ID</th>
                            <th>Image</th>
                            <th>Product Name</th>
                            <th>Vendor</th>
                            <th>Status</th>  
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($products as $product)
                            <tr>
                                <td>#{{ $product->id }}</td>
                                <td>    
                                    @if ($product->image)
                                        <img src="{{ asset('storage/' . $product->image) }}" 
                                             class="img-fluid rounded shadow border p-2" 
                                             style="max-width: 50px;" 
                                             alt="Product Image">
                                    @else 
                                        <img src="{{ asset('assets/images/default-product.png') }}" 
                                             class="img-fluid rounded shadow border p-2" 
                                             style="max-width: 50px;" 
                                             alt="Default Image">
                                    @endif 
                                </td> 
                                <td>{{ $product->name }}</td>
                                <td>{{ $product->vendor->business_name ?? 'N/A' }}</td>
                                <td>
                                    @if ($product->approved)
                                        <span class="badge bg-success">Approved</span>
                                    @elseif(!$product->approved)
                                        <span class="badge bg-danger">Not Approved</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="table-actions d-flex align-items-center gap-2 fs-6">
                                        <a href="{{ route('admin.products.edit', $product->id) }}" class="text-primary"
                                            data-bs-toggle="tooltip" data-bs-placement="bottom" title="Edit">
                                            <i class="bi bi-pencil-fill"></i>
                                        </a>

                                        <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-link p-0 text-danger"
                                                data-bs-toggle="tooltip" data-bs-placement="bottom" title="Delete"
                                                onclick="return confirm('Are you sure you want to delete this product?')">
                                                <i class="bi bi-trash-fill"></i>
                                            </button>
                                        </form>

                                        {{-- <a href="{{ route('admin.products.show', $product->id) }}" class="text-warning"
                                            data-bs-toggle="tooltip" data-bs-placement="bottom" title="View">
                                            <i class="bi bi-eye-fill"></i>
                                        </a> --}}

                                        @if (!$product->approved)
                                        <form action="{{ route('admin.products.approve', $product->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-link p-0 text-success"
                                                data-bs-toggle="tooltip" data-bs-placement="bottom" title="Approve "
                                                onclick="return confirm('Are you sure you want to approve this Product?')">
                                                <i class="bi bi-check-circle-fill"></i> 
                                            </button>
                                        </form>
                                        @elseif ($product->approved)
                                            <form action="{{ route('admin.products.reject', $product->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-link p-0 text-danger"
                                                    data-bs-toggle="tooltip" data-bs-placement="bottom" title="Reject Product"
                                                    onclick="return confirm('Are you sure you want to reject this product?')">
                                                    <i class="bi bi-x-circle-fill"></i> 
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="mt-1">
                {{ $products->links() }}
            </div>
        </div>
    </div>
@endsection

@push('scripts')
@endpush
