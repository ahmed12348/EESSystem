@extends('admin.layouts.app')

@section('content')

<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold">{{ __('messages.expired_cart_items') }}</h4>
        <form action="{{ route('admin.cart.readd') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-warning btn-sm">
                <i class="bi bi-arrow-clockwise"></i> {{ __('messages.readd_all_expired_items') }}
            </button>
        </form>
    </div>

    @if ($expiredItems->count() > 0)
    <div class="card shadow-sm">
        <div class="card-header bg-danger text-white">
            <h5 class="mb-0">{{ __('messages.expired_order_items') }}</h5>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>{{ __('messages.product') }}</th>
                        <th>{{ __('messages.vendor') }}</th>
                        <th>{{ __('messages.price') }}</th>
                        <th>{{ __('messages.status') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($expiredItems as $item)
                    <tr>
                        <td>{{ $item->product->name }}</td>
                        <td>{{ $item->product->vendor->business_name ?? 'N/A' }}</td>
                        <td>${{ number_format($item->price, 2) }}</td>
                        <td><span class="badge bg-danger">{{ __('messages.expired') }}</span></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @else
    <p class="text-center text-muted">{{ __('messages.na') }}</p>
    @endif
</div>

@endsection
