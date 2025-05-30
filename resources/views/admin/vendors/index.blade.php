@extends('admin.layouts.app')

@section('title', __('messages.vendors_list'))

@section('content')
<div class="container">
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">{{ __('messages.vendors') }}</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.index') }}"><i class="bx bx-home-alt"></i></a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ __('messages.vendors_list') }}</li>
                </ol>
            </nav>
        </div>
        <div class="ms-auto">
            <a class="btn btn-info text-white" href="{{ route('admin.vendors.create') }}">
                <i class="bi bi-plus-circle"></i> {{ __('messages.create_vendor') }}
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="d-flex align-items-center">
                <h5 class="mb-0">{{ __('messages.vendors') }}</h5>
                <form class="ms-auto position-relative" method="GET" action="{{ route('admin.vendors.index') }}">
                    <div class="position-absolute top-50 translate-middle-y search-icon px-3">
                        <i class="bi bi-search"></i>
                    </div>
                    <input class="form-control ps-5" type="text" name="search" placeholder="{{ __('messages.search') }}" value="{{ request()->query('search') }}">
                </form>
            </div>

            <div class="table-responsive mt-3">
                <table class="table align-middle table-hover">
                    <thead class="table-secondary">
                        <tr>
                            <th>#</th>
                            <th>{{ __('messages.business_name') }}</th>
                        
                            <th>{{ __('messages.email') }}</th>
                            <th>{{ __('messages.role') }}</th>
                            <th>{{ __('messages.status') }}</th>
                            <th>{{ __('messages.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($vendors as $index => $vendor)
                        <tr>
                            <td>{{ $index + 1 }}</td> 
                                <td>{{ $vendor->vendor->business_name ?? __('messages.no_vendor') }}</td>
                                <td>{{ $vendor->email }}</td>
                                <td>{{ optional($vendor->roles->first())->name ?? __('messages.no_role') }}</td>

                                <td>
                                    @if ($vendor->status === 'approved')
                                        <span class="badge bg-success">{{ __('messages.approved') }}</span>
                                    @elseif ($vendor->status === 'pending')
                                        <span class="badge bg-warning">{{ __('messages.pending') }}</span>
                                    @elseif ($vendor->status === 'rejected')
                                        <span class="badge bg-danger">{{ __('messages.rejected') }}</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="table-actions d-flex align-items-center gap-2 fs-6">
                                        <a href="{{ route('admin.vendors.edit', $vendor->id) }}" class="text-primary" data-bs-toggle="tooltip" title="{{ __('messages.edit') }}">
                                            <i class="bi bi-pencil-fill"></i>
                                        </a>
                                        <form action="{{ route('admin.vendors.destroy', $vendor->id) }}" method="POST" onsubmit="return confirm('{{ __('messages.delete_confirmation_vendor') }}')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-link p-0 text-danger" data-bs-toggle="tooltip" title="{{ __('messages.delete') }}">
                                                <i class="bi bi-trash-fill"></i>
                                            </button>
                                        </form>
                                        <a href="{{ route('admin.vendors.show', $vendor->id) }}" class="text-warning" data-bs-toggle="tooltip" title="{{ __('messages.show') }}">
                                            <i class="bi bi-eye-fill"></i>
                                        </a>
                                        <a href="{{ route('admin.vendors.reviews', $vendor->id) }}" class="text-info" data-bs-toggle="tooltip" title="{{ __('messages.view_reviews') }}">
                                            <i class="bi bi-star-fill"></i>
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
                {{ $vendors->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
