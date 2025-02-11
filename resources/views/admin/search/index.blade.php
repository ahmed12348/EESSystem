@extends('admin.layouts.app')

@section('content')

<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        {{-- <h4 class="fw-bold">Search Results</h4>
        <form action="{{ route('admin.search.index') }}" method="GET" class="d-flex">
            <input type="text" name="search" class="form-control form-control-sm me-2" placeholder="Search by Product">
            <button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-search"></i> Search</button>
        </form> --}}
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Product Name</th>
                        {{-- <th>Product ID</th> --}}
                        <th>Number of Search</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($searchResults as $search)
                    <tr>
                        <td>{{ $search->product->name ?? 'N/A' }}</td>
                        {{-- <td>{{ $search->product_id }}</td> --}}
                        <td>{{ $search->number }}</td>
                        <td>{{ $search->created_at->format('Y-m-d H:i') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center">No search results found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-3">
                {{ $searchResults->links() }}
            </div>
        </div>
    </div>
</div>

@endsection
