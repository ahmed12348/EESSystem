@extends('admin.layouts.app')

@section('title', __('messages.vendor_reviews'))

@section('content')
<div class="container">
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">{{ __('messages.vendor_reviews') }}</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.index') }}"><i class="bx bx-home-alt"></i></a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.vendors.index') }}">{{ __('messages.vendors') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ __('messages.vendor_reviews') }}</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <h5 class="mb-3">{{ __('messages.reviews_list') }}</h5>
            <div class="table-responsive">
                <table class="table align-middle table-hover">
                    <thead class="table-secondary">
                        <tr>
                            <th>#</th>
                            <th>{{ __('messages.customer') }}</th>
                            <th>{{ __('messages.rating') }}</th>
                            <th>{{ __('messages.comment') }}</th>
                            <th>{{ __('messages.status') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($reviews as $key => $review)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $review->user->name ?? __('messages.na') }}</td>
                                <td>
                                    <span class="text-warning">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <i class="bi {{ $i <= $review->rating ? 'bi-star-fill' : 'bi-star' }}"></i>
                                        @endfor
                                    </span>
                                </td>
                                <td>{{ $review->comment ?? __('messages.no_comment') }}</td>
                                <td>
                                    <span class="badge bg-{{ $review->status === 'approved' ? 'success' : ($review->status === 'pending' ? 'warning' : 'danger') }}">
                                        {{ ucfirst($review->status) }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-3">
                {{ $reviews->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
