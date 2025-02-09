@extends('admin.layouts.app')

@section('title', 'Vendors Dashboard')

@section('content')
    <!-- Breadcrumb -->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Vendors</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.index') }}"><i class="bx bx-home-alt"></i></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Vendors List</li>
                </ol>
            </nav>
        </div>

        <div class="ms-auto">
            <div class="btn-group">
                {{-- <a class="btn btn-info text-white" href="{{ route('admin.vendors.create') }}">
                    <i class="bi bi-plus-circle"></i> Create New Vendor
                </a> --}}
            </div>
        </div>
    </div>
    <!-- End Breadcrumb -->

    
    <div class="card">
        <div class="card-body">

            <div class="d-flex align-items-center">
                <h5 class="mb-0">Vendors</h5>
                <form class="ms-auto position-relative" method="GET" action="{{ route('admin.vendors.index') }}">
                    <div class="position-absolute top-50 translate-middle-y search-icon px-3">
                        <i class="bi bi-search"></i>
                    </div>
                    <input class="form-control ps-5" type="text" name="search" placeholder="Search"
                        value="{{ request()->query('search') }}">
                </form>
            </div>

            <div class="table-responsive mt-3">
                <table class="table align-middle">
                    <thead class="table-secondary">
                        <tr>
                            <th>ID</th>
                            <th>Business Name</th>
                            <th>Phone Number</th>
                            <th>Zone</th>
                            <th>Status</th>
        
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($vendors as $vendor)
                            <tr>
                                <td>{{ $vendor->id }}</td>
                                <td>
                                @if ($vendor->hasRole('vendor'))  <!-- Check if user is a vendor -->
                                {{ $vendor->vendor ? $vendor->vendor->business_name : 'N/A' }} <!-- Safe Access -->
                                @else
                                    N/A
                                @endif
                                </td>
                                {{-- <td>{{ $vendor->vendor->business_name }}</td> --}}
                                <td>{{ $vendor->phone }}</td>
                                <td>{{ $vendor->vendor?->zone ?? 'N/A' }}</td>
                                <td>
                                    @if ($vendor->status == 'active')
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-danger">Inactive</span>
                                    @endif
                                </td>

                                <td>
                                    <div class="table-actions d-flex align-items-center gap-3 fs-6">
                                        <a href="{{ route('admin.vendors.edit', $vendor->id) }}" class="text-primary"
                                            data-bs-toggle="tooltip" data-bs-placement="bottom" title="Edit">
                                            <i class="bi bi-pencil-fill"></i>
                                        </a>

                                        <form action="{{ route('admin.vendors.destroy', $vendor->id) }}" method="POST"
                                            style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-link p-0 text-danger"
                                                data-bs-toggle="tooltip" data-bs-placement="bottom" title="Delete"
                                                onclick="return confirm('Are you sure you want to delete this vendor?')">
                                                <i class="bi bi-trash-fill"></i>
                                            </button>
                                        </form>
{{--                                         
                                        <a href="{{ route('admin.vendors.show', $vendor->id) }}" class="text-warning"
                                            data-bs-toggle="tooltip" data-bs-placement="bottom" title="View">
                                            <i class="bi bi-eye-fill"></i>
                                        </a> --}}


                                        @if ($vendor->status == 'inactive')  
                                            <form action="{{ route('admin.vendors.approve', $vendor->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-link p-0 text-success"
                                                    data-bs-toggle="tooltip" data-bs-placement="bottom" title="Approve Vendor"
                                                    onclick="return confirm('Are you sure you want to approve this vendor?')">
                                                    <i class="bi bi-check-circle-fill"></i> 
                                                </button>
                                            </form>
                                         @elseif ($vendor->status == 'active')
                                            <form action="{{ route('admin.vendors.reject', $vendor->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-link p-0 text-danger"
                                                    data-bs-toggle="tooltip" data-bs-placement="bottom" title="Reject Vendor"
                                                    onclick="return confirm('Are you sure you want to reject this vendor?')">
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

        </div>
    </div>
@endsection
