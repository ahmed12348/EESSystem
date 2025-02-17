@extends('admin.layouts.app')

@section('title', __('messages.show_vendor'))

@section('content')
    <div class="container">

        <!-- Breadcrumb Navigation -->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">{{ __('messages.vendors') }}</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}"><i class="bx bx-home-alt"></i></a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.vendors.index') }}">{{ __('messages.vendors') }}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ __('messages.show_vendor') }}</li>
                    </ol>
                </nav>
            </div>
            <div class="ms-auto">
                <a href="{{ route('admin.vendors.index') }}" class="btn btn-secondary">{{ __('messages.back') }}</a>
            </div>
        </div>

        <div class="row">
            <!-- Vendor Details Card -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body text-center">
                        <h5 class="card-title">{{ __('messages.vendor') }}</h5>
                        <hr>

                        <p><strong>{{ __('messages.business_name') }}:</strong> {{ $vendor->vendor->business_name ?? 'N/A' }}</p>
                        <p><strong>{{ __('messages.email') }}:</strong> {{ $vendor->email ?? 'N/A' }}</p>
                        <p><strong>{{ __('messages.tax_id') }}:</strong> {{ $vendor->vendor->tax_id ?? 'N/A' }}</p>
                        <p><strong>{{ __('messages.zone') }}:</strong> {{ $vendor->vendor->zone ?? 'N/A' }}</p>
                        <p><strong>{{ __('messages.status') }}:</strong> 
                            @if ($vendor->status == 'active')
                                <span class="badge bg-success">{{ __('messages.status') }}: Active</span>
                            @else
                                <span class="badge bg-danger">{{ __('messages.status') }}: Inactive</span>
                            @endif
                        </p>
                        <p><strong>{{ __('messages.created_at') }}:</strong> {{ $vendor->created_at->format('d M Y') }}</p>
                    </div>
                </div>
            </div>

            <!-- Vendor Orders -->
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <h5 class="mb-3">{{ __('messages.orders') }}</h5>

                        @if ($vendor->orders->count() > 0)
                            <table class="table table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th>{{ __('messages.order_id') }}</th>
                                        <th>{{ __('messages.customer') }}</th>
                                        <th>{{ __('messages.total_price') }}</th>
                                        <th>{{ __('messages.date') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($vendor->orders as $order)
                                        <tr>
                                            <td>{{ $order->id }}</td>
                                            <td>{{ $order->customer->name ?? 'N/A' }}</td>
                                            <td>${{ number_format($order->total_price, 2) }}</td>
                                            <td>{{ $order->created_at->format('d M Y') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <p class="text-muted">{{ __('messages.na') }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection  
