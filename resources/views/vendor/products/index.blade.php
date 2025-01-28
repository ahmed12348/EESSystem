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

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="d-flex align-items-center">
                <h5 class="mb-0">Products</h5>
                <form class="ms-auto position-relative" method="GET" action="{{ route('admin.products.index') }}">
                    <div class="position-absolute top-50 translate-middle-y search-icon px-3">
                        <i class="bi bi-search"></i>
                    </div>
                    <input class="form-control ps-5" type="text" name="search" placeholder="Search Products"
                        value="{{ request()->query('search') }}">
                </form>
            </div>
        
            <div class="table-responsive mt-3">
                <table class="table align-middle table-hover">
                    <thead class="table-secondary">
                        <tr>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Vendor</th>
                            <th>Price</th>
                            <th>Status</th>  
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($products as $product)
                            <tr>
                                <td>{{ $product->name }}</td>
                                <td>{{ $product->category->name ?? 'Uncategorized' }}</td>
                                <td>{{ $product->vendor->business_name ?? 'Admin' }}</td>
                                <td>${{ number_format($product->price, 2) }}</td>
                                <td>
                                    @if ($product->status == 'inactive')
                                        <span class="badge bg-danger">Inactive</span>
                                    @else
                                        <span class="badge bg-success">Active</span>
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

                                        <a href="{{ route('admin.products.show', $product->id) }}" class="text-warning"
                                            data-bs-toggle="tooltip" data-bs-placement="bottom" title="View">
                                            <i class="bi bi-eye-fill"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="mt-1">
                {{-- {{ $products->links() }} --}}
            </div>
        </div>
    </div>
@endsection
