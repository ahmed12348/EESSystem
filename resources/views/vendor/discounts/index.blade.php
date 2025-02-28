@extends('vendor.layouts.app')

@section('title', __('messages.discount_dashboard'))

@section('content')
    <!-- Breadcrumb -->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">{{ __('messages.discounts') }}</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="{{ route('vendor.vendor_index') }}"><i class="bx bx-home-alt"></i></a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ __('messages.discount_list') }}</li>
                </ol>
            </nav>
        </div>

        <div class="ms-auto">
            <div class="btn-group">
                @can('discounts-create')
                <a class="btn btn-info text-white" href="{{ route('vendor.discounts.create') }}">
                    <i class="bi bi-plus-circle"></i> {{ __('messages.add_discount') }}
                </a>
                @endcan
            </div>
        </div>
    </div>
    <!-- End Breadcrumb -->

    <div class="card">
        <div class="card-body">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <h5 class="mb-0">{{ __('messages.discounts_offers') }}</h5>
                <form class="ms-auto position-relative" method="GET" action="{{ route('vendor.discounts.index') }}">
                    <div class="position-absolute top-50 translate-middle-y search-icon px-3">
                        <i class="bi bi-search"></i>
                    </div>
                    <input class="form-control ps-5" type="text" name="search" placeholder="{{ __('messages.search') }}"
                        value="{{ request()->query('search') }}">
                </form>
            </div>

            <div class="table-responsive">
                <table class="table align-middle table-hover">
                    <thead class="table-secondary">
                        <tr>
                            <th>{{ __('messages.id') }}</th>
                            <th>{{ __('messages.percentage') }}</th>
                            <th>{{ __('messages.start_date') }}</th>
                            <th>{{ __('messages.end_date') }}</th>
                            <th>{{ __('messages.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($discounts as $discount)
                            <tr>
                                <td>#{{ $discount->id }}</td>
                                <td>{{ $discount->discount_value }}%</td>
                                <td>{{ $discount->start_date }}</td>
                                <td>{{ $discount->end_date }}</td>
                                <td>
                                    
                                    <div class="table-actions d-flex align-items-center gap-2 fs-6">
                                        @can('discounts-edit')
                                        <a href="{{ route('vendor.discounts.edit', $discount->id) }}" class="text-warning" data-bs-toggle="tooltip" title="{{ __('messages.edit') }}">
                                            <i class="bi bi-pencil-fill"></i>
                                        </a>
                                        @endcan
                                        @can('discounts-view')
                                        <a href="{{ route('vendor.discounts.show', $discount->id) }}" class="text-primary" data-bs-toggle="tooltip" title="{{ __('messages.view_details') }}">
                                            <i class="bi bi-eye-fill"></i>
                                        </a>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">{{ __('messages.no_discounts_found') }}</td>
                            </tr>
                        @endforelse
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
