@extends('admin.layouts.app')

@section('title', 'Ads Dashboard')

@section('content')
    <!-- Breadcrumb -->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Ads</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.index') }}"><i class="bx bx-home-alt"></i></a></li>
                    <li class="breadcrumb-item active" aria-current="page">Ads List</li>
                </ol>
            </nav>
        </div>

        <div class="ms-auto">
            <div class="btn-group">
                @can('ads-create')
                <a class="btn btn-info text-white" href="{{ route('admin.ads.create') }}">
                    <i class="bi bi-plus-circle"></i> Add New Ad
                </a>
                @endcan
            </div>
        </div>
    </div>
    <!-- End Breadcrumb -->

    <div class="card">
        <div class="card-body">
            <div class="d-flex align-items-center justify-content-between">
                <!-- Search Form -->
                <div class="ms-auto d-flex align-items-center">
                    <form class="d-flex position-relative" method="GET" action="{{ route('admin.ads.index') }}">
                        <div class="position-absolute top-50 translate-middle-y search-icon px-3">
                            <i class="bi bi-search"></i>
                        </div>
                        <input class="form-control form-control-sm ps-5" type="text" name="search" placeholder="Search Ads" value="{{ request()->query('search') }}">
                    </form>
                </div>
            </div>

            <div class="table-responsive mt-3">
                <table id='example2' class="table align-middle table-hover">
                    <thead class="table-secondary">
                        <tr>
                            <th>ID</th>
                            <th>Image</th>
                            <th>Title</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($ads as $ad)
                            <tr>
                                <td>#{{ $ad->id }}</td>
                                <td>    
                                    @if ($ad->image)
                                        <img src="{{ asset('storage/' . $ad->image) }}" 
                                             class="img-fluid rounded shadow border p-2" 
                                             style="max-width: 50px;" 
                                             alt="Ad Image">
                                    @else 
                                        <img src="{{ asset('assets/images/default-product.png') }}" 
                                             class="img-fluid rounded shadow border p-2" 
                                             style="max-width: 50px;" 
                                             alt="Default Image">
                                    @endif 
                                </td> 
                                <td>{{ $ad->title }}</td>
                                <td>
                                    @if ($ad->status === 'approved')
                                    <span class="badge bg-success">{{ __('messages.approved') }}</span>
                                @elseif ($ad->status === 'pending')
                                    <span class="badge bg-warning">{{ __('messages.pending') }}</span>
                                @elseif ($ad->status === 'rejected')
                                    <span class="badge bg-danger">{{ __('messages.rejected') }}</span>
                                @endif
                                </td>
                                <td>
                                    <div class="table-actions d-flex align-items-center gap-2 fs-6">
                                      
                                        @can('ads-edit')
                                        <a href="{{ route('admin.ads.edit', $ad->id) }}" class="text-primary"
                                            data-bs-toggle="tooltip" data-bs-placement="bottom" title="Edit">
                                            <i class="bi bi-pencil-fill"></i>
                                        </a>
                                        @endcan

                                       
                                        @can('ads-delete')
                                        <form action="{{ route('admin.ads.destroy', $ad->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-link p-0 text-danger"
                                                data-bs-toggle="tooltip" data-bs-placement="bottom" title="Delete"
                                                onclick="return confirm('Are you sure you want to delete this ad?')">
                                                <i class="bi bi-trash-fill"></i>
                                            </button>
                                        </form>
                                        @endcan

                                    
                                        @can('ads-view')
                                        <a href="{{ route('admin.ads.show', $ad->id) }}" class="text-warning"
                                            data-bs-toggle="tooltip" data-bs-placement="bottom" title="View">
                                            <i class="bi bi-eye-fill"></i>
                                        </a>
                                        @endcan

                                        
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- Pagination -->
            <div class="mt-1">
                {{ $ads->links() }}
            </div>
        </div>
    </div>
@endsection
