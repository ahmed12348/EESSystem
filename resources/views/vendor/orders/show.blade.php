@extends('vendor.layouts.app')

@section('title', 'View Order')

@section('content')
    <div class="container">

        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Orders</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ route('vendor.vendor_index') }}"><i class="bx bx-home-alt"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">Order Details</li>
                    </ol>
                </nav>
            </div>
            <div class="ms-auto">
                <a href="{{ route('vendor.orders.index') }}" class="btn btn-secondary">Back</a>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <h5>Order #{{ $order->id }}</h5>
                <hr>

                <p><strong>Customer:</strong> {{ $order->customer?->vendor?->name }}</p>
                <p><strong>Vendor:</strong> {{ $order->vendor?->vendor?->business_name }}</p>
                <p><strong>Status:</strong> <span class="badge bg-info">{{ ucfirst($order->status) }}</span></p>
                <p><strong>Placed At:</strong> {{ $order->placed_at->format('d M Y H:i') }}</p>

                <h6>Products:</h6>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Discount</th>
                            <th>Final Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($order->items as $item)
                            <tr>
                                <td>{{ $item->product?->name }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>${{ number_format($item->price, 2) }}</td>
                                <td>{{ $item->discount }}%</td>
                                <td>${{ number_format($item->final_price, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <h6>Total Price: ${{ number_format($order->total_price, 2) }}</h6>

                <!-- Notes Section -->
                <h6 class="mt-3">Notes</h6>
                <div class="card border p-3">
                    @if($order->notes)
                        <p>{{ $order->notes }}</p>
                    @else
                        <p class="text-muted">No notes available.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
