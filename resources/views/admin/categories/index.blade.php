@extends('admin.layouts.app')

@section('title', 'Categories Dashboard')

@section('content')
    <!-- Breadcrumb -->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Categories</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.index') }}"><i class="bx bx-home-alt"></i></a></li>
                    <li class="breadcrumb-item active" aria-current="page">Categories List</li>
                </ol>
            </nav>
        </div>

        <div class="ms-auto">
            <div class="btn-group">
                <a class="btn btn-info text-white" href="{{ route('admin.categories.create') }}">
                    <i class="bi bi-plus-circle"></i> Create New Category
                </a>
            </div>
        </div>
    </div>
    <!-- End Breadcrumb -->

    @include('admin.layouts.alerts')

    <div class="card">
        <div class="card-body">
            <div class="d-flex align-items-center">
                <h5 class="mb-0">Categories</h5>
                <form class="ms-auto position-relative" method="GET" action="{{ route('admin.categories.index') }}">
                    <div class="position-absolute top-50 translate-middle-y search-icon px-3">
                        <i class="bi bi-search"></i>
                    </div>
                    <input class="form-control ps-5" type="text" name="search" placeholder="Search"
                        value="{{ request()->query('search') }}">
                </form>
            </div>

            <div class="table-responsive mt-3">
                <table class="table align-middle table-hover">
                    <thead class="table-secondary">
                        <tr>
                            <th>Name</th>
                            <th>Parent Category</th>
                            <th>Total Products</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($categories as $category)
                            <tr>
                                <td>{{ $category->name }}</td>
                                <td>{{ $category->parent ? $category->parent->name : 'No Parent' }}</td>
                                <td>{{ $category->products->count() }}</td>
                                <td>
                                    <div class="table-actions d-flex align-items-center gap-2 fs-6">

                                        {{-- Edit Button --}}
                                        <a href="{{ route('admin.categories.edit', $category->id) }}" class="text-primary"
                                            data-bs-toggle="tooltip" data-bs-placement="bottom" title="Edit">
                                            <i class="bi bi-pencil-fill"></i>
                                        </a>

                                        {{-- Delete Button --}}
                                        <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-link p-0 text-danger"
                                                data-bs-toggle="tooltip" data-bs-placement="bottom" title="Delete"
                                                onclick="return confirm('Are you sure you want to delete this category? This will delete all subcategories and products!')">
                                                <i class="bi bi-trash-fill"></i>
                                            </button>
                                        </form>

                                 

                                    </div>
                                </td>
                            </tr>

                            {{-- Display Subcategories --}}
                            @foreach ($category->subcategories as $subcategory)
                                <tr>
                                    <td> └ {{ $subcategory->name }}</td>
                                    <td>{{ $category->name }}</td>
                                    <td>{{ $subcategory->products->count() }}</td>
                                    <td>
                                        <div class="table-actions d-flex align-items-center gap-2 fs-6">

                                            {{-- Edit Button --}}
                                            <a href="{{ route('admin.categories.edit', $subcategory->id) }}" class="text-primary"
                                                data-bs-toggle="tooltip" data-bs-placement="bottom" title="Edit">
                                                <i class="bi bi-pencil-fill"></i>
                                            </a>

                                            {{-- Delete Button --}}
                                            <form action="{{ route('admin.categories.destroy', $subcategory->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-link p-0 text-danger"
                                                    data-bs-toggle="tooltip" data-bs-placement="bottom" title="Delete"
                                                    onclick="return confirm('Are you sure you want to delete this subcategory and its products?')">
                                                    <i class="bi bi-trash-fill"></i>
                                                </button>
                                            </form>

                                        </div>
                                    </td>
                                </tr>
                            @endforeach

                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-1">
                {{ $categories->links() }}
            </div>
        </div>
    </div>
@endsection
