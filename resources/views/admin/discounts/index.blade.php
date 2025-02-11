@extends('admin.layouts.app')

@section('title', 'Discounts Dashboard')

@section('content')
    <!-- Breadcrumb -->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Discounts</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.index') }}"><i class="bx bx-home-alt"></i></a></li>
                    <li class="breadcrumb-item active" aria-current="page">Discount List</li>
                </ol>
            </nav>
        </div>

        <div class="ms-auto">
            <div class="btn-group">
                <a class="btn btn-info text-white" href="{{ route('admin.discounts.create') }}">
                    <i class="bi bi-plus-circle"></i> Add Discount
                </a>
            </div>
        </div>
    </div>
    <!-- End Breadcrumb -->

    <div class="card">
        <div class="card-body">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <h5 class="mb-0">Discounts & Offers</h5>
                <form class="ms-auto position-relative" method="GET" action="{{ route('admin.categories.index') }}">
                    <div class="position-absolute top-50 translate-middle-y search-icon px-3">
                        <i class="bi bi-search"></i>
                    </div>
                    <input class="form-control ps-5" type="text" name="search" placeholder="Search"
                        value="{{ request()->query('search') }}">
                </form>
            </div>

            <div class="table-responsive">
                <table class="table align-middle table-hover">
                    <thead class="table-secondary">
                        <tr>
                            <th>ID</th>
                            {{-- <th>Discount Code</th> --}}
                            <th>Percentage</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($discounts as $discount)
                            <tr>
                                <td>#{{ $discount->id }}</td>
                                {{-- <td>{{ $discount->code }}</td> --}}
                                <td>{{ $discount->discount_value }}%</td>
                                <td>{{ $discount->start_date }}</td>
                                <td>{{ $discount->end_date }}</td>
                                <td>
                                    <div class="table-actions d-flex align-items-center gap-2 fs-6">
                                        <a href="{{ route('admin.discounts.edit', $discount->id) }}" class="text-warning" data-bs-toggle="tooltip" title="Edit">
                                            <i class="bi bi-pencil-fill"></i>
                                        </a>
                                        {{-- <form action="{{ route('admin.discounts.destroy', $discount->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-link p-0 text-danger" data-bs-toggle="tooltip" title="Delete"
                                                onclick="return confirm('Are you sure you want to delete this discount?')">
                                                <i class="bi bi-trash-fill"></i>
                                            </button>
                                        </form> --}}
                                        <a href="{{ route('admin.discounts.show', $discount->id) }}" class="text-primary" data-bs-toggle="tooltip" title="View Details">
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
            <div class="mt-2">
                {{ $discounts->links() }}
            </div>
        </div>
    </div>
@endsection
